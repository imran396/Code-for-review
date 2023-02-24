<?php
/**
 * SAM-5716: Extract auction bidder validation and building logic from "Add New Bidder" form
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AddNewBidderForm\Validate;

use AuctionBidder;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Billing\CreditCard\Validate\CreditCardValidatorAwareTrait;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Constants;
use Sam\Core\Email\Validate\EmailAddressChecker;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\View\Admin\Form\AddNewBidderForm\Dto\AddNewBidderDto;
use Sam\View\Admin\Form\AddNewBidderForm\Dto\BidderAddressDto;

/**
 * Class AddNewBidderFormValidator
 * @package Sam\View\Admin\Form\AddNewBidderForm\Validate
 */
class AddNewBidderFormValidator extends CustomizableClass
{
    use AuctionBidderCheckerAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use CreditCardValidatorAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use UserLoaderAwareTrait;

    public const ERR_BIDDER_NUMBER_EXISTENCE = 1;
    public const ERR_BILLING_ADDRESS_ZIP_INVALID = 2;
    public const ERR_CC_EXP_DATE_INVALID = 3;
    public const ERR_CC_NUMBER_INVALID = 4;
    public const ERR_EMAIL_INVALID = 5;
    public const ERR_EMAIL_REQUIRED = 6;
    public const ERR_FLAGGED_USER = 7;
    public const ERR_RESELLER_ID_REQUIRED = 8;
    public const ERR_SHIPPING_ADDRESS_ZIP_INVALID = 9;

    protected const ERROR_MESSAGES = [
        self::ERR_BIDDER_NUMBER_EXISTENCE => 'Bidder number already exist',
        self::ERR_BILLING_ADDRESS_ZIP_INVALID => 'Wrong ZIP',
        self::ERR_CC_EXP_DATE_INVALID => 'Invalid CC Exp Date {MMYY}',
        self::ERR_CC_NUMBER_INVALID => 'Unsupported card type or invalid card number',
        self::ERR_EMAIL_INVALID => 'This value is not a valid email address',
        self::ERR_EMAIL_REQUIRED => 'Required',
        self::ERR_FLAGGED_USER => 'User has been flagged',
        self::ERR_RESELLER_ID_REQUIRED => 'Required',
        self::ERR_SHIPPING_ADDRESS_ZIP_INVALID => 'Wrong ZIP',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AddNewBidderDto $dto
     * @return bool
     */
    public function validate(AddNewBidderDto $dto): bool
    {
        $this->getResultStatusCollector()->construct(static::ERROR_MESSAGES);
        $this->checkBidderNumberExistence($dto, self::ERR_BIDDER_NUMBER_EXISTENCE);
        $this->checkRequired($dto->email, self::ERR_EMAIL_REQUIRED);
        $this->checkEmail($dto->email, self::ERR_EMAIL_INVALID);
        if ($dto->isAssigningExistingUser()) {
            $this->checkFlaggedUser($dto->email, self::ERR_FLAGGED_USER);
        }
        $this->checkCountryZip($dto->shippingAddress, self::ERR_SHIPPING_ADDRESS_ZIP_INVALID);
        $this->checkCountryZip($dto->billingAddress, self::ERR_BILLING_ADDRESS_ZIP_INVALID);
        if ($dto->reseller) {
            $this->checkRequired($dto->resellerId, self::ERR_RESELLER_ID_REQUIRED);
        }
        $this->checkCcNumber($dto->ccNumber, self::ERR_CC_NUMBER_INVALID);
        $this->checkCcExpDate($dto->ccExpDate, self::ERR_CC_EXP_DATE_INVALID);
        return !$this->hasErrors();
    }

    /**
     * @param AddNewBidderDto $dto
     * @param int $errorCode
     */
    private function checkBidderNumberExistence(AddNewBidderDto $dto, int $errorCode): void
    {
        if ($dto->email) {
            $auctionBidder = $this->loadAuctionBidderByEmail($dto->email, $dto->auctionId);
            $skipAuctionBidderIds = (array)($auctionBidder->Id ?? null);
            $isBidderNoExist = $this
                ->getAuctionBidderChecker()
                ->existBidderNo($dto->bidderNum, $dto->auctionId, [], $skipAuctionBidderIds);
            if ($isBidderNoExist) {
                $this->getResultStatusCollector()->addError($errorCode);
            }
        }
    }

    /**
     * Return AuctionBidder record for entered email
     * @param string $email
     * @param int $auctionId
     * @return AuctionBidder|null
     */
    private function loadAuctionBidderByEmail(string $email, int $auctionId): ?AuctionBidder
    {
        $user = $this->getUserLoader()->loadByEmail($email, true);
        return $user
            ? $this->getAuctionBidderLoader()->load($user->Id, $auctionId, true)
            : null;
    }

    /**
     * @param mixed $value
     * @param int $errorCode
     */
    private function checkRequired(mixed $value, int $errorCode): void
    {
        if ((string)$value === '') {
            $this->getResultStatusCollector()->addError($errorCode);
        }
    }

    /**
     * Is valid email
     *
     * @param string $email
     * @param int $errorCode
     */
    private function checkEmail(string $email, int $errorCode): void
    {
        if (!$email) {
            return;
        }
        $isEmail = EmailAddressChecker::new()->isEmail($email);
        if (!$isEmail) {
            $this->getResultStatusCollector()->addError($errorCode);
        }
    }

    /**
     * @param string $email
     * @param int $errorCode
     */
    private function checkFlaggedUser(string $email, int $errorCode): void
    {
        if (!$email) {
            return;
        }

        $user = $this->getUserLoader()->loadByEmail($email);
        if ($user && $user->Flag !== Constants\User::FLAG_NONE) {
            $this->getResultStatusCollector()->addError(
                $errorCode,
                sprintf(
                    'User "%s" has been flagged as "%s"!',
                    $user->Username,
                    UserPureRenderer::new()->makeFlag($user->Flag)
                )
            );
        }
    }

    /**
     * @param string $ccNumber
     * @param int $errorCode
     */
    private function checkCcNumber(string $ccNumber, int $errorCode): void
    {
        if (!$ccNumber) {
            return;
        }
        $isValid = $this->getCreditCardValidator()->validateNumber($ccNumber);
        if (!$isValid) {
            $this->getResultStatusCollector()->addError($errorCode);
        }
    }

    /**
     * @param string $ccExpDate
     * @param int $errorCode
     */
    private function checkCcExpDate(string $ccExpDate, int $errorCode): void
    {
        if (!$ccExpDate) {
            return;
        }
        if (!preg_match('/^(0[1-9]|1[0-2])\d{2}$/', $ccExpDate)) {
            $this->getResultStatusCollector()->addError($errorCode);
        }
    }

    /**
     * @param BidderAddressDto $addressDto
     * @param int $errorCode
     */
    private function checkCountryZip(BidderAddressDto $addressDto, int $errorCode): void
    {
        $addressChecker = AddressChecker::new();
        if (
            $addressDto->zip
            && $addressChecker->isUsa($addressDto->country)
            && !$this->isValidUsaZip($addressDto->zip)
        ) {
            $this->getResultStatusCollector()->addError($errorCode);
        }
    }

    /**
     * @param string $zip
     * @return bool
     */
    private function isValidUsaZip(string $zip): bool
    {
        return strlen($zip) === 5 && is_numeric($zip);
    }

    /**
     * @return ResultStatus[]
     */
    public function errorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }
}
