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

namespace Sam\Invoice\StackedTax\TaxSchema\Detect\Internal\Load;

use Sam\Auction\Load\AuctionLoader;
use Sam\AuctionLot\Load\AuctionLotLoader;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceAuctionLoader;
use Sam\Invoice\Common\Load\InvoiceLoader;
use Sam\Lot\Load\LotItemLoader;
use Sam\Settings\SettingsManager;
use Sam\Tax\StackedTax\Schema\Load\TaxSchemaLoader;
use TaxSchema;

/**
 * Class DataProvider
 * @package Sam\Invoice\StackedTax\TaxSchema\Detect\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Load HP Tax Schema Id ---

    public function loadAuctionLotLevelHpTaxSchemaId(int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        return AuctionLotLoader::new()->load($lotItemId, $auctionId, $isReadOnlyDb)->HpTaxSchemaId ?? null;
    }

    public function loadLotItemLevelHpTaxSchemaId(int $lotItemId, bool $isReadOnlyDb = false): ?int
    {
        return LotItemLoader::new()->load($lotItemId, $isReadOnlyDb)->HpTaxSchemaId ?? null;
    }

    public function loadAuctionLevelHpTaxSchemaId(?int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        return AuctionLoader::new()->load($auctionId, $isReadOnlyDb)->HpTaxSchemaId ?? null;
    }

    public function loadAccountLevelHpTaxSchemaId(?int $accountId): ?int
    {
        if (!$accountId) {
            return null;
        }
        return SettingsManager::new()->get(Constants\Setting::INVOICE_HP_TAX_SCHEMA_ID, $accountId);
    }

    // --- Load BP Tax Schema Id ---

    public function loadAuctionLotLevelBpTaxSchemaId(int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        return AuctionLotLoader::new()->load($lotItemId, $auctionId, $isReadOnlyDb)->BpTaxSchemaId ?? null;
    }

    public function loadLotItemLevelBpTaxSchemaId(int $lotItemId, bool $isReadOnlyDb = false): ?int
    {
        return LotItemLoader::new()->load($lotItemId, $isReadOnlyDb)->BpTaxSchemaId ?? null;
    }

    public function loadAuctionLevelBpTaxSchemaId(?int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        return AuctionLoader::new()->load($auctionId, $isReadOnlyDb)->BpTaxSchemaId ?? null;
    }

    public function loadAccountLevelBpTaxSchemaId(?int $accountId): ?int
    {
        if (!$accountId) {
            return null;
        }
        return SettingsManager::new()->get(Constants\Setting::INVOICE_BP_TAX_SCHEMA_ID, $accountId);
    }

    // --- Load Services Tax Schema Id ---

    public function loadAuctionLevelServicesTaxSchemaId(?int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        return AuctionLoader::new()->load($auctionId, $isReadOnlyDb)->ServicesTaxSchemaId ?? null;
    }

    public function loadAccountLevelServicesTaxSchemaId(?int $accountId): ?int
    {
        if (!$accountId) {
            return null;
        }
        return SettingsManager::new()->get(Constants\Setting::INVOICE_SERVICES_TAX_SCHEMA_ID, $accountId);
    }

    // ---

    public function loadTaxSchema(?int $taxSchemaId, bool $isReadOnlyDb = false): ?TaxSchema
    {
        return TaxSchemaLoader::new()->load($taxSchemaId, $isReadOnlyDb);
    }

    public function loadLotAccountId(int $lotItemId, bool $isReadOnlyDb = false): ?int
    {
        return LotItemLoader::new()->load($lotItemId, $isReadOnlyDb)->AccountId ?? null;
    }

    public function loadInvoiceAccountId(int $invoiceId, bool $isReadOnlyDb = false): ?int
    {
        return InvoiceLoader::new()->load($invoiceId, $isReadOnlyDb)?->AccountId;
    }

    /**
     * @return int[]
     */
    public function loadInvoiceAuctionIds(int $invoiceId, bool $isReadOnlyDb = false): array
    {
        $rows = InvoiceAuctionLoader::new()->loadSelectedByInvoiceId(['auction_id'], $invoiceId, $isReadOnlyDb);
        return array_map(static fn(array $row) => (int)$row['auction_id'], $rows);
    }
}
