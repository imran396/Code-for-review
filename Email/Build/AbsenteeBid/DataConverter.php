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


use AbsenteeBid;
use InvalidArgumentException;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Email\Build\DataConverterAbstract;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use UnexpectedValueException;

/**
 * Class DataConverter
 * @package Sam\Email\Build\BidderInfo
 */
class DataConverter extends DataConverterAbstract
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use UserLoaderAwareTrait;
    use LotItemLoaderAwareTrait;

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
        [$absenteeBid] = $data;
        if (!$absenteeBid instanceof AbsenteeBid) {
            throw new InvalidArgumentException('Object must be instance of AbsenteeBid');
        }
        $this->setAbsenteeBid($absenteeBid);
        $user = $this->getUserLoader()->load($absenteeBid->UserId, true);
        if (!$user) {
            log_error(
                "Available user not found for Absentee Bid email"
                . composeSuffix(['u' => $absenteeBid->UserId, 'ab' => $absenteeBid->Id])
            );
            throw new UnexpectedValueException('Available user not found for Absentee Bid email');
        }

        $lotItem = $this->getLotItemLoader()->load($absenteeBid->LotItemId, true);
        if (!$lotItem) {
            log_error(
                "Available lot item not found for Absentee Bid email"
                . composeSuffix(['li' => $absenteeBid->LotItemId, 'ab' => $absenteeBid->Id])
            );
            throw new UnexpectedValueException('Available lot item not found for  Absentee Bid email');
        }

        $auction = $this->getAuctionLoader()->load($absenteeBid->AuctionId, true);
        if (!$auction) {
            log_error("Available auction not found for  Absentee Bid email" . composeSuffix(['a' => $absenteeBid->AuctionId, 'ab' => $absenteeBid->Id]));
            throw new UnexpectedValueException('Available auction not found for  Absentee Bid email');
        }

        $auctionLot = $this->getAuctionLotLoader()->load($absenteeBid->LotItemId, $absenteeBid->AuctionId, true);
        if (!$auctionLot) {
            log_error(
                "Available auctionLot not found for Absentee Bid email"
                . composeSuffix(['li' => $absenteeBid->LotItemId, 'a' => $absenteeBid->AuctionId, 'ab' => $absenteeBid->Id])
            );
            throw new UnexpectedValueException('Available auction lot not found for  Absentee Bid email');
        }

        $this->setUser($user);
        $this->setAuctionLot($auctionLot);
        $this->setAuction($auction);
        $this->setLotItem($lotItem);
    }
}
