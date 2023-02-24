<?php
/**
 * SAM-11061: Stacked Tax. Invoice Management pages. Implement Stacked Tax Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\TaxSchema\Detect;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\TaxSchema\Detect\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\StackedTax\TaxSchema\Detect\StackedTaxInvoiceTaxSchemaDetectionResult as Result;
use TaxSchema;

/**
 * Class StackedTaxInvoiceTaxSchemaDetector
 * @package Sam\Invoice\StackedTax\TaxSchema\Detect
 */
class StackedTaxInvoiceTaxSchemaDetector extends CustomizableClass
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

    public function detectForHp(int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?TaxSchema
    {
        $result = $this->detectResultForHp($lotItemId, $auctionId, $isReadOnlyDb);
        $logData = [
                'li' => $lotItemId,
                'a' => $auctionId,
            ] + $result->logData();
        log_debug("Search of Tax Schema for Hammer Price - " . $result->statusMessage() . composeSuffix($logData));
        return $result->taxSchema;
    }

    public function detectResultForHp(int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): Result
    {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();

        $auctionLotHpTaxSchemaId = $dataProvider->loadAuctionLotLevelHpTaxSchemaId($lotItemId, $auctionId, $isReadOnlyDb);
        if ($auctionLotHpTaxSchemaId) {
            $auctionLotHpTaxSchema = $dataProvider->loadTaxSchema($auctionLotHpTaxSchemaId, $isReadOnlyDb);
            if ($auctionLotHpTaxSchema) {
                return $result->addSuccess(Result::OK_AUCTION_LOT_LEVEL, $auctionLotHpTaxSchema);
            }
        }

        $lotItemHpTaxSchemaId = $dataProvider->loadLotItemLevelHpTaxSchemaId($lotItemId, $isReadOnlyDb);
        if ($lotItemHpTaxSchemaId) {
            $lotItemHpTaxSchema = $dataProvider->loadTaxSchema($lotItemHpTaxSchemaId, $isReadOnlyDb);
            if ($lotItemHpTaxSchema) {
                return $result->addSuccess(Result::OK_LOT_ITEM_LEVEL, $lotItemHpTaxSchema);
            }
        }

        $auctionHpTaxSchemaId = $dataProvider->loadAuctionLevelHpTaxSchemaId($auctionId, $isReadOnlyDb);
        if ($auctionHpTaxSchemaId) {
            $auctionHpTaxSchema = $dataProvider->loadTaxSchema($auctionHpTaxSchemaId, $isReadOnlyDb);
            if ($auctionHpTaxSchema) {
                return $result->addSuccess(Result::OK_AUCTION_LEVEL, $auctionHpTaxSchema);
            }
        }

        $lotAccountId = $dataProvider->loadLotAccountId($lotItemId, $isReadOnlyDb);
        $lotAccountHpTaxSchemaId = $dataProvider->loadAccountLevelHpTaxSchemaId($lotAccountId);
        if ($lotAccountHpTaxSchemaId) {
            $lotAccountHpTaxSchema = $dataProvider->loadTaxSchema($lotAccountHpTaxSchemaId, $isReadOnlyDb);
            if ($lotAccountHpTaxSchema) {
                return $result->addSuccess(Result::OK_LOT_ACCOUNT_LEVEL, $lotAccountHpTaxSchema);
            }
        }

        return $result->addError(Result::ERR_NOT_FOUND);
    }


    public function detectForBp(int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?TaxSchema
    {
        $result = $this->detectResultForBp($lotItemId, $auctionId, $isReadOnlyDb);
        $logData = [
                'li' => $lotItemId,
                'a' => $auctionId,
            ] + $result->logData();
        log_debug("Search of Tax Schema for Buyer's Premium - " . $result->statusMessage() . composeSuffix($logData));
        return $result->taxSchema;
    }

    public function detectResultForBp(int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): Result
    {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();

        $auctionLotBpTaxSchemaId = $dataProvider->loadAuctionLotLevelBpTaxSchemaId($lotItemId, $auctionId, $isReadOnlyDb);
        if ($auctionLotBpTaxSchemaId) {
            $auctionLotBpTaxSchema = $dataProvider->loadTaxSchema($auctionLotBpTaxSchemaId, $isReadOnlyDb);
            if ($auctionLotBpTaxSchema) {
                return $result->addSuccess(Result::OK_AUCTION_LOT_LEVEL, $auctionLotBpTaxSchema);
            }
        }

        $lotItemBpTaxSchemaId = $dataProvider->loadLotItemLevelBpTaxSchemaId($lotItemId, $isReadOnlyDb);
        if ($lotItemBpTaxSchemaId) {
            $lotItemBpTaxSchema = $dataProvider->loadTaxSchema($lotItemBpTaxSchemaId, $isReadOnlyDb);
            if ($lotItemBpTaxSchema) {
                return $result->addSuccess(Result::OK_LOT_ITEM_LEVEL, $lotItemBpTaxSchema);
            }
        }

        $auctionBpTaxSchemaId = $dataProvider->loadAuctionLevelBpTaxSchemaId($auctionId, $isReadOnlyDb);
        if ($auctionBpTaxSchemaId) {
            $auctionBpTaxSchema = $dataProvider->loadTaxSchema($auctionBpTaxSchemaId, $isReadOnlyDb);
            if ($auctionBpTaxSchema) {
                return $result->addSuccess(Result::OK_AUCTION_LEVEL, $auctionBpTaxSchema);
            }
        }

        $lotAccountId = $dataProvider->loadLotAccountId($lotItemId, $isReadOnlyDb);
        $lotAccountBpTaxSchemaId = $dataProvider->loadAccountLevelBpTaxSchemaId($lotAccountId);
        if ($lotAccountBpTaxSchemaId) {
            $lotAccountBpTaxSchema = $dataProvider->loadTaxSchema($lotAccountBpTaxSchemaId, $isReadOnlyDb);
            if ($lotAccountBpTaxSchema) {
                return $result->addSuccess(Result::OK_LOT_ACCOUNT_LEVEL, $lotAccountBpTaxSchema);
            }
        }

        return $result->addError(Result::ERR_NOT_FOUND);
    }

    public function detectForServices(int $invoiceId, bool $isReadOnlyDb = false): ?TaxSchema
    {
        $result = $this->detectResultForServices($invoiceId, $isReadOnlyDb);
        $logData = [
                'i' => $invoiceId,
            ] + $result->logData();
        log_debug("Search of Tax Schema for Services - " . $result->statusMessage() . composeSuffix($logData));
        return $result->taxSchema;
    }

    public function detectResultForServices(int $invoiceId, bool $isReadOnlyDb = false): Result
    {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();

        $invoiceAuctionIds = $dataProvider->loadInvoiceAuctionIds($invoiceId, $isReadOnlyDb);
        if (count($invoiceAuctionIds) === 1) {
            $auctionServicesTaxSchemaId = $dataProvider->loadAuctionLevelServicesTaxSchemaId(array_shift($invoiceAuctionIds), $isReadOnlyDb);
            $auctionServicesTaxSchema = $dataProvider->loadTaxSchema($auctionServicesTaxSchemaId, $isReadOnlyDb);
            if ($auctionServicesTaxSchema) {
                return $result->addSuccess(Result::OK_AUCTION_LEVEL, $auctionServicesTaxSchema);
            }
        }

        $invoiceAccountId = $dataProvider->loadInvoiceAccountId($invoiceId, $isReadOnlyDb);
        $invoiceAccountServicesTaxSchemaId = $dataProvider->loadAccountLevelServicesTaxSchemaId($invoiceAccountId);
        $invoiceAccountServicesTaxSchema = $dataProvider->loadTaxSchema($invoiceAccountServicesTaxSchemaId, $isReadOnlyDb);
        if ($invoiceAccountServicesTaxSchema) {
            return $result->addSuccess(Result::OK_LOT_ACCOUNT_LEVEL, $invoiceAccountServicesTaxSchema);
        }

        return $result->addError(Result::ERR_NOT_FOUND);
    }
}
