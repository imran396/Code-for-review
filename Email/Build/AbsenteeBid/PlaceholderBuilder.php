<?php
/**
 * SAM-5018 : Refactor Email_Template to sub classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Apr 1, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Build\AbsenteeBid;

use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Email\Build\PlaceholdersAbstractBuilder;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class PlaceholdersBuilder
 * @package Sam\Email\Build\Test
 */
class PlaceholderBuilder extends PlaceholdersAbstractBuilder
{
    use AuctionRendererAwareTrait;
    use CurrencyLoaderAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function build(): array
    {
        $lotItem = $this->getDataProvider()->getLotItem();
        $auctionLot = $this->getDataProvider()->getAuctionLot();
        $auction = $this->getDataProvider()->getAuction();
        $absenteeBid = $this->getDataProvider()->getAbsenteeBid();
        $user = $this->getDataProvider()->getUser();

        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($user->Id, true);
        $imageUrl = $this->buildLotImageUrl($lotItem->Id, $auction->Id, $lotItem->AccountId);

        $placeholders = [
            'first_name' => $userInfo->FirstName,
            'last_name' => $userInfo->LastName,
            'username' => $user->Username,
            'bidder_number' => $this->getBidderInfo($user->Id, $auction->Id),
            'lot_url' => $this->buildLotDetailsUrl($lotItem->Id, $auction->Id, $lotItem->AccountId),
            'lot_id' => $lotItem->Id,
            'auction_id' => $auction->Id,
            'sale_no' => $this->getAuctionRenderer()->renderSaleNo($auction),
            'lot_name' => $this->getLotRenderer()->makeName($lotItem->Name, $auction->TestAuction),
            'lot_number' => $this->getLotRenderer()->renderLotNo($auctionLot),
            'image_url' => $imageUrl,
            'sale_name' => $this->getAuctionRenderer()->renderName($auction),
            'absentee_bid_amount' => $this->getNumberFormatter()->formatMoney($absenteeBid->MaxBid),
            'currency' => $this->getCurrencyLoader()->detectDefaultSign($auction->Id),
        ];
        $placeholders = array_merge($placeholders, $this->getDefaultPlaceholders());
        return $placeholders;
    }
}
