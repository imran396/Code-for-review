<?php
/**
 * SAM-5412: Validations at controller layer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/24/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Lot\Validate;

use Sam\Application\Controller\Responsive\Lot\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Application\Controller\Responsive\Lot\Validate\LotControllerValidationResult as Result;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;

/**
 * Class LotControllerValidator
 * @package Sam\Application\Controller\Responsive\Lot
 */
class LotControllerValidator extends CustomizableClass
{
    use DataProviderCreateTrait;
    use OptionalsTrait;
    use ResultStatusCollectorAwareTrait;

    // --- Incoming values ---

    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool

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
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    // --- Main method ---

    /**
     * Validate/Check if Auction ID exists, and not archived or deleted
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @return Result
     */
    public function validate(?int $lotItemId, ?int $auctionId): Result
    {
        $result = Result::new()->construct();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $dataProvider = $this->createDataProvider();

        /**
         * Check lot availability by LotItemLoader default filtering
         */
        $lotItem = $dataProvider->loadLotItem($lotItemId, $isReadOnlyDb);
        if (!$lotItem) {
            return $result->addError(Result::ERR_LOT_ITEM_NOT_FOUND);
        }

        /**
         * Do not perform auction validation, when auction id is absent
         */
        if (!$auctionId) {
            return $result->addSuccess(Result::OK_AUCTION_ID_IS_ABSENT);
        }

        /**
         * Validate auction with passed id
         */
        $result = $this->validateAuction($auctionId, $result);
        if ($result->hasError()) {
            return $result;
        }

        /**
         * Check auction lot availability by AuctionLotLoader default filtering
         */
        $auctionLot = $dataProvider->loadAuctionLotItem($lotItemId, $auctionId, $isReadOnlyDb);
        if (!$auctionLot) {
            return $result->addError(Result::ERR_AUCTION_LOT_NOT_FOUND);
        }

        return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
    }

    /**
     * Validate auction with passed id
     * @param int $auctionId
     * @param Result $result
     * @return Result
     */
    protected function validateAuction(int $auctionId, Result $result): Result
    {
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $dataProvider = $this->createDataProvider();

        /**
         * Check auction availability according AuctionLoader filtering
         */
        $auction = $dataProvider->loadAuction($auctionId, $isReadOnlyDb);
        if (!$auction) {
            return $result->addError(Result::ERR_AUCTION_NOT_FOUND);
        }

        $isAllowed = $dataProvider->isAllowedForAuction($auction);
        if (!$isAllowed) {
            return $result->addError(Result::ERR_DOMAIN_AUCTION_VISIBILITY);
        }

        return $result;
    }

    // --- Internal logic ---

    /**
     * @param array $optionals
     */
    public function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_READ_ONLY_DB] = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $this->setOptionals($optionals);
    }
}
