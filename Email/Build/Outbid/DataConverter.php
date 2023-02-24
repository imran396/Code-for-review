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

use BidTransaction;
use InvalidArgumentException;
use LotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Email\Build\DataConverterAbstract;
use UnexpectedValueException;
use User;

/**
 * Class DataConverter
 * @package Sam\Email\Build\BidderInfo
 */
class DataConverter extends DataConverterAbstract
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param $data
     */
    public function convert($data): void
    {
        [$user, $looserBidAmount, $winnerBid, $lotItem] = $data;

        if (!$user instanceof User) {
            throw new InvalidArgumentException('Not an instance of User given');
        }

        if (!$winnerBid instanceof BidTransaction) {
            throw new InvalidArgumentException('Not an instance of BidTransaction given');
        }

        if (!$lotItem instanceof LotItem) {
            throw new InvalidArgumentException('Not an instance of LotItem given');
        }

        $auctionLot = $this->getAuctionLotLoader()->load($winnerBid->LotItemId, $winnerBid->AuctionId, true);
        if (!$auctionLot) {
            log_error(
                "Available auction lot not found for Outbid email"
                . composeSuffix(['li' => $winnerBid->LotItemId, 'a' => $winnerBid->AuctionId])
            );
            throw new UnexpectedValueException('Available auction lot not found for Outbid email');
        }
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            log_error(
                "Available auction not found for Outbid email"
                . composeSuffix(['a' => $auctionLot->AuctionId])
            );
            throw new UnexpectedValueException('Available auction not found for Outbid email');
        }

        $this->setAuctionLot($auctionLot);
        $this->setAuction($auction);
        $this->setUser($user);
        $this->setBidTransaction($winnerBid);
        $this->setLotItem($lotItem);
        $this->setBidAmount($looserBidAmount);
    }
}
