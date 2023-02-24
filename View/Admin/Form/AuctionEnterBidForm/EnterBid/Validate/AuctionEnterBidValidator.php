<?php
/**
 * SAM-6181 : Refactor for Admin>Auction>Enter bids - Move input validation logic to separate class and implement unit test
 * https://bidpath.atlassian.net/browse/SAM-6181
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01/25/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionEnterBidForm\EnterBid\Validate;

use Sam\Core\Constants\Admin\AuctionEnterBidFormConstants;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\View\Admin\Form\AuctionEnterBidForm\EnterBid\AuctionEnterBidConstants;
use Sam\View\Admin\Form\AuctionEnterBidForm\EnterBid\Internal\Validate\BaseValidationHelper;
use Sam\View\Admin\Form\AuctionEnterBidForm\EnterBid\Internal\Validate\PlaceBidValidationHelper;
use Sam\View\Admin\Form\AuctionEnterBidForm\EnterBid\Internal\Validate\SellLotValidationHelper;

/**
 * Class AuctionEnterBidValidator
 * @package Sam\View\Admin\Form\AuctionEnterBidForm\Validate
 */
class AuctionEnterBidValidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use ResultStatusCollectorAwareTrait;

    protected PlaceBidValidationHelper|SellLotValidationHelper|null $validationHelper = null;

    // --- Incoming values ---
    protected AuctionEnterBidInputDto $dto;
    protected int $auctionId;
    protected int $actionType;

    // --Constructor-- //

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionEnterBidInputDto $dto
     * @param int $auctionId
     * @param int $actionType
     * @return $this
     */
    public function constructor(
        AuctionEnterBidInputDto $dto,
        int $auctionId,
        int $actionType
    ): static {
        $this->initResultStatusCollector();
        $this->actionType = $actionType;
        $this->dto = $dto;
        $this->auctionId = $auctionId;
        return $this;
    }

    // --- Main command method ---

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $isValid = true;
        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            if (!$this->getValidationHelper()->validateLotFullNumber()) {
                $isValid = false;
            }
        } else {
            if (!$this->getValidationHelper()->validateLotNumber()) {
                $isValid = false;
            }
        }
        // Checking auction lot existence for valid lot number
        if (
            $isValid
            && !$this->getValidationHelper()->validateAuctionLotExistence()
        ) {
            $isValid = false;
        }
        // Checking listing only for existing lot number
        if (
            $isValid
            && !$this->getValidationHelper()->validateIsListingOnly()
        ) {
            $isValid = false;
        }
        // Checking lot already sold or not when lot number exists and not listing only
        if (
            $isValid
            && !$this->getValidationHelper()->validateLotAlreadySold()
        ) {
            $isValid = false;
        }

        if (!$this->getValidationHelper()->validateBidAmount()) {
            $isValid = false;
        }

        if (!$this->getValidationHelper()->validateBidderNumber()) {
            $isValid = false;
        }
        return $isValid;
    }

    /**
     * @return bool
     */
    public function validateTimedItem(): bool
    {
        return $this->getValidationHelper()->validateTimedItem();
    }

    // --- Read results ---

    /**
     * @return string
     */
    public function lotNoErrorMessage(): string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(AuctionEnterBidConstants::LOT_ERRORS);
    }

    /**
     * @return string
     */
    public function bidAmountErrorMessage(): string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(AuctionEnterBidConstants::BID_AMOUNT_ERRORS);
    }

    /**
     * @return string
     */
    public function bidderNoErrorMessage(): string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(AuctionEnterBidConstants::BIDDER_NO_ERRORS);
    }

    /**
     * @return string
     */
    public function notStartedErrorMessage(): string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes([AuctionEnterBidConstants::ERR_NOT_STARTED]);
    }

    /**
     * @return string
     */
    public function notForBiddingErrorMessage(): string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes([AuctionEnterBidConstants::ERR_NOT_FOR_BIDDING]);
    }

    /**
     * @return string
     */
    public function generalErrorMessage(): string
    {
        return $this->actionType === AuctionEnterBidFormConstants::AT_SUBMIT_BID
            ? AuctionEnterBidConstants::FAILED_PLACE_BID
            : AuctionEnterBidConstants::FAILED_SELL_LOT;
    }

    /**
     * @return string
     */
    public function lotNoInputFieldName(): string
    {
        return $this->cfg()->get('core->lot->lotNo->concatenated') ? 'txtLotFullNum' : 'txtLotNum';
    }

    // --- Internal logic ---

    /**
     * @return bool
     */
    public function hasLotNoError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(AuctionEnterBidConstants::LOT_ERRORS);
    }

    /**
     * @return bool
     */
    public function hasBidAmountError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(AuctionEnterBidConstants::BID_AMOUNT_ERRORS);
    }

    /**
     * @return bool
     */
    public function hasBidderNoError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(AuctionEnterBidConstants::BIDDER_NO_ERRORS);
    }

    /**
     * @return bool
     */
    public function hasNotStartedError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(AuctionEnterBidConstants::ERR_NOT_STARTED);
    }

    /**
     * @return bool
     */
    public function hasNotForBiddingError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(AuctionEnterBidConstants::ERR_NOT_FOR_BIDDING);
    }

    /**
     * @return PlaceBidValidationHelper|SellLotValidationHelper
     */
    protected function getValidationHelper(): PlaceBidValidationHelper|SellLotValidationHelper
    {
        if ($this->validationHelper === null) {
            $this->validationHelper = $this->actionType === AuctionEnterBidFormConstants::AT_SUBMIT_BID
                ? PlaceBidValidationHelper::new()->construct($this->getResultStatusCollector(), $this->dto, $this->auctionId)
                : SellLotValidationHelper::new()->construct($this->getResultStatusCollector(), $this->dto, $this->auctionId);
        }
        return $this->validationHelper;
    }

    /**
     * Need for unit test.
     * @param PlaceBidValidationHelper|SellLotValidationHelper $validationHelper
     */
    public function setValidationHelper(PlaceBidValidationHelper|SellLotValidationHelper $validationHelper): void
    {
        $this->validationHelper = $validationHelper;
    }

    /**
     * Initialize ResultStatusCollector
     */
    protected function initResultStatusCollector(): void
    {
        // ResultStatusCollector default error messages for error codes
        $errorMessages = [
            AuctionEnterBidConstants::ERR_FULL_LOT_NO => '',
            AuctionEnterBidConstants::ERR_LOT_NO_REQUIRED => 'Required',
            AuctionEnterBidConstants::ERR_BID_AMOUNT_REQUIRED => 'Required',
            AuctionEnterBidConstants::ERR_BIDDER_NO_REQUIRED => 'Required',
            AuctionEnterBidConstants::ERR_LOT_NO_NUMERIC => 'Should be numeric value',
            AuctionEnterBidConstants::ERR_BID_AMOUNT_NUMERIC => 'Should be numeric value',
            AuctionEnterBidConstants::ERR_NOT_STARTED => 'This item is not open for bidding yet',
            AuctionEnterBidConstants::ERR_NOT_FOR_BIDDING => 'Sorry this lot item is not for bidding',
            AuctionEnterBidConstants::ERR_LISTING_ONLY => 'This lot is listing only lot!',
            AuctionEnterBidConstants::ERR_LOT_NOT_EXIST => 'This lot does not exist in the selected sale.',
            AuctionEnterBidConstants::ERR_INVALID_USER => 'Invalid bidder number provided!',
            AuctionEnterBidConstants::ERR_ALREADY_SOLD => 'This lot has already been sold.',
            AuctionEnterBidConstants::ERR_ABOVE_ZERO => 'Should be greater than zero.',
            AuctionEnterBidConstants::ERR_INVALID => 'Invalid bid',
            AuctionEnterBidConstants::ERR_BIDDER_NO_ALPHA_NUMERIC => 'Only letters, numbers and underscore are allowed',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
    }
}
