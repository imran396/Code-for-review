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

namespace Sam\Invoice\Legacy\Generate\Item\Single\Internal\InvoiceItem\Internal\Load;

use InvoiceItem;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoader;
use Sam\BuyersPremium\Calculate\BuyersPremiumCalculator;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Create\EntityFactory;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Calculate\Basic\LegacyInvoiceCalculator;
use Sam\Invoice\Legacy\Calculate\SaleTax\LegacyInvoiceApplicableSaleTaxResult;
use Sam\Invoice\Legacy\Generate\Item\Single\Internal\InvoiceItem\Internal\LotName\LotNameBuilder;

/**
 * Class DataProvider
 * @package Sam\Invoice\Legacy\Generate\Item\Single\Internal\Load
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

    public function detectApplicableSalesTax(
        int $winnerUserId,
        int $lotItemId,
        ?int $auctionId,
        bool $isReadOnlyDb = false
    ): LegacyInvoiceApplicableSaleTaxResult {
        return LegacyInvoiceCalculator::new()->detectApplicableSalesTax($winnerUserId, $lotItemId, $auctionId, $isReadOnlyDb);
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
}
