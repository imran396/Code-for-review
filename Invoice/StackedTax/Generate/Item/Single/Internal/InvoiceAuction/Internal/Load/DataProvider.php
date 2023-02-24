<?php
/**
 * SAM-11061: Stacked Tax. Invoice Management pages. Implement Stacked Tax Invoice Generation logic
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

namespace Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceAuction\Internal\Load;

use Auction;
use InvoiceAuction;
use Sam\Auction\Load\AuctionLoader;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoader;
use Sam\Core\Entity\Create\EntityFactory;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceAuctionLoader;
use Sam\Timezone\Load\TimezoneLoader;

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

    public function newInvoiceAuction(): InvoiceAuction
    {
        return EntityFactory::new()->invoiceAuction();
    }

    public function loadAuction(?int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return AuctionLoader::new()
            ->clear()
            ->load($auctionId, $isReadOnlyDb);
    }

    public function loadInvoiceAuction(int $invoiceId, int $auctionId, bool $isReadOnlyDb = false): ?InvoiceAuction
    {
        return InvoiceAuctionLoader::new()->load($invoiceId, $auctionId, $isReadOnlyDb);
    }

    public function loadTimezoneLocation(?int $timezoneId, bool $isReadOnlyDb = false): string
    {
        $row = TimezoneLoader::new()->loadSelected(['location'], $timezoneId, $isReadOnlyDb);
        return $row['location'] ?? '';
    }

    public function loadAuctionBidderNumPadded(int $userId, ?int $auctionId, bool $isReadOnlyDb = false): string
    {
        $row = AuctionBidderLoader::new()->loadSelected(['aub.bidder_num'], $userId, $auctionId, $isReadOnlyDb);
        return $row['bidder_num'] ?? '';
    }
}
