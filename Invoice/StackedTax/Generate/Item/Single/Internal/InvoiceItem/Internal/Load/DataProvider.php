<?php
/**
 * SAM-9721: Refactor and implement unit test for single invoice producer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceItem\Internal\Load;

use AuctionLotItem;
use InvoiceItem;
use Sam\AuctionLot\Load\AuctionLotLoader;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoader;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoader;
use Sam\BuyersPremium\Calculate\BuyersPremiumCalculator;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Create\EntityFactory;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceItem\Internal\LotName\LotNameBuilder;
use Sam\Invoice\StackedTax\TaxSchema\Detect\StackedTaxInvoiceTaxSchemaDetector;
use Sam\Tax\StackedTax\Calculate\StackedTaxCalculationResult;
use Sam\Tax\StackedTax\Calculate\StackedTaxCalculator;
use TaxSchema;

/**
 * Class DataProvider
 * @package Sam\Invoice\StackedTax\Generate\Item\Single\Internal\Load
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

    public function newInvoiceItem(): InvoiceItem
    {
        return EntityFactory::new()->invoiceItem();
    }

    public function buildLotName(string $lotName, string $lotDescription, int $accountId): string
    {
        return LotNameBuilder::new()->build($lotName, $lotDescription, $accountId);
    }

    public function loadAuctionLot(int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?AuctionLotItem
    {
        return AuctionLotLoader::new()->load($lotItemId, $auctionId, $isReadOnlyDb);
    }

    public function calcBuyersPremium(
        int $lotItemId,
        ?int $auctionId,
        ?int $winningUserId,
        ?int $accountId = null,
        ?float $newHp = null
    ): float {
        return BuyersPremiumCalculator::new()->calculate($lotItemId, $auctionId, $winningUserId, $accountId, $newHp);
    }

    public function loadPostAucImportPremium(?int $userId, ?int $auctionId, bool $isReadOnlyDb = false): ?float
    {
        $row = AuctionBidderLoader::new()->loadSelected(['aub.post_auc_import_premium'], $userId, $auctionId, $isReadOnlyDb);
        return Cast::toFloat($row['post_auc_import_premium'] ?? null);
    }

    public function detectHpTaxSchema(int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?TaxSchema
    {
        return StackedTaxInvoiceTaxSchemaDetector::new()->detectForHp($lotItemId, $auctionId, $isReadOnlyDb);
    }

    public function detectBpTaxSchema(int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?TaxSchema
    {
        return StackedTaxInvoiceTaxSchemaDetector::new()->detectForBp($lotItemId, $auctionId, $isReadOnlyDb);
    }

    public function calcHpTax(float $hp, TaxSchema $taxSchema, bool $isReadOnlyDb = false): StackedTaxCalculationResult
    {
        return StackedTaxCalculator::new()->calculate($hp, $taxSchema, $isReadOnlyDb);
    }

    public function calcBpTax(float $bp, TaxSchema $taxSchema, bool $isReadOnlyDb = false): StackedTaxCalculationResult
    {
        return StackedTaxCalculator::new()->calculate($bp, $taxSchema, $isReadOnlyDb);
    }

    public function loadAuctionLotQuantityScale(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): int
    {
        return LotQuantityScaleLoader::new()->loadAuctionLotQuantityScale($lotItemId, $auctionId, $isReadOnlyDb);
    }

    public function loadLotItemQuantityScale(int $lotItemId, bool $isReadOnlyDb = false): int
    {
        return LotQuantityScaleLoader::new()->loadLotItemQuantityScale($lotItemId, $isReadOnlyDb);
    }
}
