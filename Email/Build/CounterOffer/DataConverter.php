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

namespace Sam\Email\Build\CounterOffer;

use AuctionLotItem;
use InvalidArgumentException;
use LotItem;
use Sam\Email\Build\DataConverterAbstract;
use User;

/**
 * Class DataConverter
 * @package Sam\Email\Build\BidderInfo
 */
class DataConverter extends DataConverterAbstract
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param $data
     * @return void
     */
    public function convert($data): void
    {
        [$user, $lotItem, $auctionLot, $initialOfferAmount, $counterOfferAmount] = $data;

        if (!$user instanceof User) {
            throw new InvalidArgumentException('Not an instance of User given');
        }
        $this->setUser($user);

        if (!$auctionLot instanceof AuctionLotItem) {
            throw new InvalidArgumentException('Not an instance of AuctionLotItem given');
        }

        $this->setAuctionLot($auctionLot);

        if (!$lotItem instanceof LotItem) {
            throw new InvalidArgumentException('Not an instance of LotItem given');
        }
        $this->setLotItem($lotItem);
        $this->setInitialOfferAmount($initialOfferAmount);
        $this->setCounterOfferAmount($counterOfferAmount);
    }
}
