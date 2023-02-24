<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/3/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Currency;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;

/**
 * Class InvoiceCurrencyDetector
 * @package Sam\Invoice\Common\Currency
 */
class InvoiceCurrencyDetector extends CustomizableClass
{
    use CurrencyLoaderAwareTrait;
    use InvoiceItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Returns the currency sign
     *
     * @param int $invoiceId invoice.id
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function detectSign(int $invoiceId, bool $isReadOnlyDb = false): string
    {
        $invoiceItem = $this->createInvoiceItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterInvoiceId($invoiceId)
            // ->joinAccountFilterActive(true)
            // ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            // ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            // ->joinLotItemFilterActive(true)
            ->joinInvoiceFilterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses)
            // ->joinUserWinningBidderFilterUserStatusId(Constants\User::US_ACTIVE)
            ->loadEntity();
        $auctionId = $invoiceItem->AuctionId ?? null;
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign($auctionId);
        return $currencySign;
    }
    //
    // /**
    //  * Returns the currency sign and ex rate
    //  *
    //  * @param int $invoiceId invoice.id
    //  * @return array
    //  */
    // public function detectCurrencyAndExRate($invoiceId, bool $isReadOnlyDb = false)
    // {
    //     $currencySign = $this->detectSign($invoiceId, $isReadOnlyDb);
    //
    //     if ($currencySign != $this->getCurrencyLoader()->detectDefaultSign(null, $isReadOnlyDb)) {
    //         $exRate = (float)$this->getCurrencyLoader()->loadExRateBySign($currencySign, $isReadOnlyDb);
    //     } else {
    //         $exRate = 1;
    //     }
    //
    //     return [$currencySign, $exRate];
    // }
}
