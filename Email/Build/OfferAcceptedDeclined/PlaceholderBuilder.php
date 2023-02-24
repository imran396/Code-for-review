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

namespace Sam\Email\Build\OfferAcceptedDeclined;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Register\AuctionRegistrationManagerAwareTrait;
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
    use AuctionLoaderAwareTrait;
    use AuctionRegistrationManagerAwareTrait;
    use AuctionRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use LotRendererAwareTrait;
    use CurrencyLoaderAwareTrait;

    public const AVAILABLE_PLACEHOLDERS = [
        'first_name',
        'last_name',
        'offer_amount',
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
        $auctionLot = $this->getDataConverter()->getAuctionLot();
        $auctionId = $auctionLot->AuctionId;
        $offerAmount = $this->getDataConverter()->getOfferAmount();

        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($user->Id, true);
        $imageUrl = $this->buildLotImageUrl($lotItem->Id, $auctionId, $lotItem->AccountId);
        $auction = $this->getAuctionLoader()->load($auctionId, true);
        if (!$auction) {
            log_error("Available auction not found" . composeSuffix(['a' => $auctionId]));
            return [];
        }

        $placeholders = [
            'first_name' => $userInfo->FirstName,
            'last_name' => $userInfo->LastName,
            'offer_amount' => $this->getNumberFormatter()->formatMoney($offerAmount),
            'lot_number' => $this->getLotRenderer()->renderLotNo($auctionLot),
            'lot_name' => $this->getLotRenderer()->makeName($lotItem->Name, $auction->TestAuction),
            'lot_url' => $this->buildLotDetailsUrl($lotItem->Id, $auctionId, $lotItem->AccountId),
            'lot_id' => $lotItem->Id,
            'image_url' => $imageUrl,
            'auction_id' => $auction->Id,
            'sale_no' => $this->getAuctionRenderer()->renderSaleNo($auction),
            'currency' => $this->getCurrencyLoader()->detectDefaultSign($auctionId),
        ];
        $placeholders = array_merge($placeholders, $this->getDefaultPlaceholders());
        return $placeholders;
    }
}
