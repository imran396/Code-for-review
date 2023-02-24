<?php
/**
 * SAM-5096 : #2 Extract COUNTER_OFFER, OFFER_ACCEPTED, OFFER_DECLINED, OFFER_SUBMITTED, COUNTER_DECLINED, COUNTER_ACCEPT
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Jun 1, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Build\OfferSubmitted;

use Sam\Application\Url\Build\Config\AuctionLot\AdminLotEditUrlConfig;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Email\Build\PlaceholdersAbstractBuilder;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class PlaceholderBuilder
 * @package Sam\Email\Build\OfferSubmitted
 */
class PlaceholderBuilder extends PlaceholdersAbstractBuilder
{
    use AuctionLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use CurrencyLoaderAwareTrait;
    use NumberFormatterAwareTrait;
    use LotRendererAwareTrait;

    /**
     * @var array
     */
    public const AVAILABLE_PLACEHOLDERS = [
        'bidder_username',
        'bidder_first_name',
        'bidder_last_name',
        'lot_name',
        'lot_number',
        'lot_url',
        'lot_link_admin',
        'lot_id',
        'image_url',
        'auction_id',
        'sale_no',
        'offer_amount',
        'bidder_phone',
        'bidder_email',
        'currency'
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
        $auctionLot = $this->getDataConverter()->getAuctionLot();
        $lotItem = $this->getDataConverter()->getLotItem();
        $auctionId = $auctionLot->AuctionId;
        $offerAmount = $this->getDataConverter()->getOfferAmount();

        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($user->Id, true);
        $imageUrl = $this->buildLotImageUrl($lotItem->Id, $auctionId, $lotItem->AccountId);

        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId, true);
        if (!$auction) {
            log_error("Available auction not found" . composeSuffix(['a' => $auctionId]));
            return [];
        }
        $auctionLotEditUrl = $this->getUrlBuilder()->build(
            AdminLotEditUrlConfig::new()->forWeb($lotItem->Id, $auctionLot->AuctionId)
        );

        $placeholders = [
            'offer_amount' => $this->getNumberFormatter()->formatMoney($offerAmount),
            'lot_number' => $this->getLotRenderer()->renderLotNo($auctionLot),
            'lot_name' => $this->getLotRenderer()->makeName($lotItem->Name, $auction->TestAuction),
            'lot_url' => $this->buildLotDetailsUrl($lotItem->Id, $auctionLot->AuctionId, $lotItem->AccountId),
            'lot_link_admin' => $auctionLotEditUrl,
            'lot_id' => $lotItem->Id,
            'image_url' => $imageUrl,
            'auction_id' => $auction->Id,
            'sale_no' => $this->getAuctionRenderer()->renderSaleNo($auction),
            'bidder_username' => $user->Username,
            'bidder_first_name' => $userInfo->FirstName,
            'bidder_last_name' => $userInfo->LastName,
            'bidder_phone' => $userInfo->Phone,
            'bidder_email' => $user->Email,
            'currency' => $this->getCurrencyLoader()->detectDefaultSign($auctionLot->AuctionId),
        ];
        return array_merge($placeholders, $this->getDefaultPlaceholders());
    }
}
