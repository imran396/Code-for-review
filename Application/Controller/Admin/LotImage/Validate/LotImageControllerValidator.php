<?php
/**
 * SAM-5412: Validations at controller layer
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/01/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\LotImage\Validate;

use Auction;
use Sam\Account\Validate\MultipleTenantAccountSimpleChecker;
use Sam\Application\Controller\Admin\LotImage\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Application\Controller\Admin\LotImage\Validate\LotImageControllerValidationResult as Result;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Constants;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Class LotImageControllerValidator
 * @package Sam\Application\Controller\Admin\LotImage\Validate
 */
class LotImageControllerValidator extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;
    use OptionalsTrait;
    use DataProviderCreateTrait;

    // --- Input values ---

    public const OP_AVAILABLE_AUCTION_STATUSES = 'availableAuctionStatuses'; // int[]
    public const OP_IS_MULTIPLE_TENANT = OptionalKeyConstants::KEY_IS_MULTIPLE_TENANT; // bool
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_MAIN_ACCOUNT_ID = OptionalKeyConstants::KEY_MAIN_ACCOUNT_ID; // int

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    // --- Main method ---

    /**
     * Validate/Check if Auction exists, and user has privileges to edit lot images
     * @param int|null $auctionId
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @return Result
     */
    public function validate(?int $auctionId, ?int $editorUserId, int $systemAccountId): Result
    {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);

        // check editor user privilege for lot image operations
        if (!$dataProvider->hasPrivilegeForManageLotImage($editorUserId, $isReadOnlyDb)) {
            return $result->addError(Result::ERR_LOT_IMAGE_ACCESS_DENIED);
        }

        // check auction existence
        $auction = $dataProvider->loadAuction($auctionId);
        if (!$auction) {
            return $result->addError(Result::ERR_INCORRECT_AUCTION_ID);
        }

        // check auction availability (we should allow everything except deleted)
        if (!$this->isAuctionStatusAvailable($auction)) {
            return $result->addError(Result::ERR_UNAVAILABLE_AUCTION);
        }

        // check auction account existence
        if (!$dataProvider->isAccountAvailable($auction->AccountId, $isReadOnlyDb)) {
            return $result->addError(Result::ERR_AUCTION_ACCOUNT_NOT_FOUND);
        }

        // check access on portal account
        if (
            $auction->AccountId !== $systemAccountId
            && !$this->isMainSystemAccount($systemAccountId)
        ) {
            return $result->addError(Result::ERR_AUCTION_AND_PORTAL_ACCOUNTS_NOT_MATCH);
        }

        return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
    }

    // --- Internal logic ---

    /**
     * @param Auction $auction
     * @return bool
     */
    protected function isAuctionStatusAvailable(Auction $auction): bool
    {
        $availableStatuses = (array)$this->fetchOptional(self::OP_AVAILABLE_AUCTION_STATUSES);
        return in_array($auction->AuctionStatusId, $availableStatuses, true);
    }

    /**
     * @param int $systemAccountId
     * @return bool
     */
    protected function isMainSystemAccount(int $systemAccountId): bool
    {
        return MultipleTenantAccountSimpleChecker::new()->isMainAccount(
            $systemAccountId,
            (bool)$this->fetchOptional(self::OP_IS_MULTIPLE_TENANT),
            (int)$this->fetchOptional(self::OP_MAIN_ACCOUNT_ID)
        );
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_AVAILABLE_AUCTION_STATUSES] = $optionals[self::OP_AVAILABLE_AUCTION_STATUSES]
            ?? Constants\Auction::$notDeletedAuctionStatuses;
        $optionals[self::OP_IS_READ_ONLY_DB] = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $optionals[self::OP_IS_MULTIPLE_TENANT] = $optionals[self::OP_IS_MULTIPLE_TENANT]
            ?? static function (): bool {
                return (bool)ConfigRepository::getInstance()->get('core->portal->enabled');
            };
        $optionals[self::OP_MAIN_ACCOUNT_ID] = $optionals[self::OP_MAIN_ACCOUNT_ID]
            ?? static function (): int {
                return (int)ConfigRepository::getInstance()->get('core->portal->mainAccountId');
            };
        $this->setOptionals($optionals);
    }

}
