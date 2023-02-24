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

namespace Sam\Email\Build\Outbid;

use Sam\Auction\Register\AuctionRegistrationManagerAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidding\AskingBid\AskingBidDetectorCreateTrait;
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
    use AskingBidDetectorCreateTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionRegistrationManagerAwareTrait;
    use AuctionRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use LotRendererAwareTrait;
    use CurrencyLoaderAwareTrait;

    public const AVAILABLE_PLACEHOLDERS = [
        'first_name',
        'last_name',
        'user_bid_amount',
        'new_current_bid_amount',
        'new_asking_bid_amount',
        'lot_number',
        'lot_name',
        'lot_url',
        'lot_id',
        'image_url',
        'auction_id',
        'sale_no',
        'currency',
    ];

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
        $user = $this->getDataConverter()->getUser();
        $lotItem = $this->getDataConverter()->getLotItem();
        $winnerBid = $this->getDataConverter()->getBidTransaction();
        $looserBidAmount = $this->getDataConverter()->getBidAmount();
        $auctionLot = $this->getDataConverter()->getAuctionLot();
        $auction = $this->getDataConverter()->getAuction();
        $auctionId = $auctionLot->AuctionId;

        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($user->Id, true);
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $askingBid = $this->createAskingBidDetector()
            ->detectAskingBid($winnerBid->LotItemId, $winnerBid->AuctionId, $winnerBid->Bid);
        $imageUrl = $this->buildLotImageUrl($lotItem->Id, $auctionId, $lotItem->AccountId);

        $placeholders = [
            'first_name' => $userInfo->FirstName,
            'last_name' => $userInfo->LastName,
            'user_bid_amount' => $this->getNumberFormatter()->formatMoney($looserBidAmount),
            'new_current_bid_amount' => $this->getNumberFormatter()->formatMoney($winnerBid->Bid),
            'new_asking_bid_amount' => $this->getNumberFormatter()->formatMoney($askingBid),
            'lot_number' => $lotNo,
            'lot_name' => $this->getLotRenderer()->makeName($lotItem->Name, $auction->TestAuction),
            'lot_url' => $this->buildLotDetailsUrl($lotItem->Id, $auctionId, $lotItem->AccountId),
            'image_url' => $imageUrl,
            'lot_id' => $lotItem->Id,
            'auction_id' => $auction->Id,
            'sale_no' => $this->getAuctionRenderer()->renderSaleNo($auction),
            'currency' => $this->getCurrencyLoader()->detectDefaultSign($auctionId),
        ];
        $placeholders = array_merge($placeholders, $this->getDefaultPlaceholders());
        return $placeholders;
    }
}
