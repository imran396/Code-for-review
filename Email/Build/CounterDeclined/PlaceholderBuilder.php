<?php
/**
 * SAM-5096 : #2 Extract COUNTER_OFFER, OFFER_ACCEPTED, OFFER_DECLINED, OFFER_SUBMITTED, COUNTER_DECLINED, COUNTER_ACCEPT
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Jun 2, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Build\CounterDeclined;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Email\Build\PlaceholdersAbstractBuilder;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class PlaceholderBuilder
 * @package Sam\Email\Build\CounterDeclined
 */
class PlaceholderBuilder extends PlaceholdersAbstractBuilder
{
    use AuctionLoaderAwareTrait;
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
        $user = $this->getDataConverter()->getUser();
        $lotItem = $this->getDataConverter()->getLotItem();
        $auctionLot = $this->getDataConverter()->getAuctionLot();
        $auctionId = $auctionLot->AuctionId;
        $initialOfferAmount = $this->getDataConverter()->getInitialOfferAmount();
        $counterOfferAmount = $this->getDataConverter()->getCounterOfferAmount();

        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($user->Id, true);
        $imageUrl = $this->buildLotImageUrl($lotItem->Id, $auctionId, $lotItem->AccountId);
        $auction = $this->getAuctionLoader()->load($auctionId, true);
        if (!$auction) {
            $message = "Available auction not found, when building placeholders for email"
                . composeSuffix(
                    [
                        'email' => Constants\EmailKey::COUNTER_OFFER,
                        'a' => $auctionId,
                    ]
                );
            log_error($message);
            return [];
        }

        $placeholders = [
            'first_name' => $userInfo->FirstName,
            'last_name' => $userInfo->LastName,
            'initial_offer_amount' => $this->getNumberFormatter()->formatMoney($initialOfferAmount),
            'counter_offer_amount' => $this->getNumberFormatter()->formatMoney($counterOfferAmount),
            'lot_number' => $this->getLotRenderer()->renderLotNo($auctionLot),
            'lot_name' => $this->getLotRenderer()->makeName($lotItem->Name, $auction->TestAuction),
            'lot_url' => $this->buildLotDetailsUrl($lotItem->Id, $auctionId, $lotItem->AccountId),
            'lot_id' => $lotItem->Id,
            'image_url' => $imageUrl,
            'auction_id' => $auction->Id,
            'sale_no' => $this->getAuctionRenderer()->renderSaleNo($auction),
            'currency' => $this->getCurrencyLoader()->detectDefaultSign($auctionId),
        ];
        return array_merge($placeholders, $this->getDefaultPlaceholders());
    }
}
