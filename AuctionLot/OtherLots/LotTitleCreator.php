<?php
/**
 * Create a title for a lot for "Other lots"  Carousel
 *
 * @see https://bidpath.atlassian.net/browse/SAM-3506
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 25, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\OtherLots;

use AuctionLotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;

/**
 * Class LotTitleCreator
 */
class LotTitleCreator extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param $auctionLot
     * @return string
     */
    public function getTitle(AuctionLotItem $auctionLot): string
    {
        $title = '';
        $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId, true);
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId, true);
        if (
            $lotItem
            && $auction
            && mb_check_encoding($lotItem->Name, 'UTF-8')
        ) {
            $name = $this->getLotRenderer()->makeName($lotItem->Name, $auction->TestAuction);
            $title = htmlspecialchars($name, ENT_QUOTES);
        }
        return $title;
    }
}
