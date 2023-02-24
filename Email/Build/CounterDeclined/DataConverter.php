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

use AuctionLotItem;
use InvalidArgumentException;
use LotItem;
use Sam\Email\Build\DataConverterAbstract;
use User;

/**
 * Class DataConverter
 * @package Sam\Email\Build\CounterDeclined
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
