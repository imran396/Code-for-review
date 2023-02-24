<?php
/**
 * Check if tax schema is assigned to entities.
 *
 * SAM-11972: Stacked Tax. Geo Type field at Tax Schema is still editable even when Tax Schema assigned to different entity(lot,auction,account,location)
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 12, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Validate\Assignment;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Tax\StackedTax\Schema\Validate\Assignment\Internal\Load\DataProviderCreateTrait;
use TaxSchema;

/**
 * Class TaxSchemaAssignmentChecker
 * @package Sam\Tax\StackedTax\Schema\Validate\Assign
 */
class TaxSchemaAssignmentChecker extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isAssignedTaxSchemaToAnyEntity(TaxSchema $taxSchema, bool $isReadOnlyDb = false): bool
    {
        return $this->isAssignedToAnyEntity(
            $taxSchema->Id,
            $taxSchema->AmountSource,
            $taxSchema->AccountId,
            $isReadOnlyDb
        );
    }

    public function isAssignedToAnyEntity(
        int $taxSchemaId,
        int $amountSource,
        int $accountId,
        bool $isReadOnlyDb = false
    ): bool {
        $dataProvider = $this->createDataProvider();
        $logData = ['ts' => $taxSchemaId, 'as' => $amountSource, 'acc' => $accountId];

        $isAssignedToAccount = $this->isAssignedToAccount($taxSchemaId, $amountSource, $accountId);
        if ($isAssignedToAccount) {
            log_trace('Tax schema is assigned to account' . composeSuffix($logData));
            return true;
        }

        $isAssignedToAuctions = $dataProvider->isAssignedToAuctions($taxSchemaId, $amountSource, $accountId, $isReadOnlyDb);
        if ($isAssignedToAuctions) {
            log_trace('Tax schema is assigned to auctions' . composeSuffix($logData));
            return true;
        }

        $isAssignedToAuctionLotItems = $dataProvider->isAssignedToAuctionLotItems($taxSchemaId, $amountSource, $accountId, $isReadOnlyDb);
        if ($isAssignedToAuctionLotItems) {
            log_trace('Tax schema is assigned to auction lot items' . composeSuffix($logData));
            return true;
        }

        $isAssignedToLotItems = $dataProvider->isAssignedToLotItems($taxSchemaId, $amountSource, $accountId, $isReadOnlyDb);
        if ($isAssignedToLotItems) {
            log_trace('Tax schema is assigned to lot items' . composeSuffix($logData));
            return true;
        }

        return false;
    }

    protected function isAssignedToAccount(int $taxSchemaId, int $amountSource, int $accountId): bool
    {
        $dataProvider = $this->createDataProvider();
        if (
            $amountSource === Constants\StackedTax::AS_HAMMER_PRICE
            && $taxSchemaId === $dataProvider->loadHpTaxSchemaForAccount($accountId)
        ) {
            return true;
        }

        if (
            $amountSource === Constants\StackedTax::AS_BUYERS_PREMIUM
            && $taxSchemaId === $dataProvider->loadBpTaxSchemaForAccount($accountId)
        ) {
            return true;
        }

        return false;
    }
}
