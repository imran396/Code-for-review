<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\AdminData\Dto;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Auction lot data to sync the admin auction list page
 *
 * Class SyncAuctionLotDto
 * @package Sam\AuctionLot\Sync\Response\Concrete\AdminData\Dto
 */
class SyncAdminAuctionLotDto extends CustomizableClass
{
    /** @var int */
    public int $accountId;
    /** @var float */
    public float $askingBid;
    /** @var int */
    public int $auctionId;
    /** @var int */
    public int $auctionLotId;
    /** @var string */
    public string $auctionStartClosingDateIso;
    /** @var int */
    public int $auctionStatusId;
    /** @var string|null */
    public ?string $auctionTimezoneLocation;
    /** @var string */
    public string $auctionType;
    /** @var int */
    public int $bidCount;
    /** @var float|null */
    public ?float $currentBid;
    /** @var string */
    public string $currentBidDateIso;
    /** @var float */
    public float $currentMaxBid;
    /** @var float|null */
    public ?float $hammerPrice;
    /** @var string */
    public string $highBidderCompany;
    /** @var string */
    public string $highBidderEmail;
    /** @var string */
    public string $highBidderFirstName;
    /** @var string */
    public string $highBidderHouse;
    /** @var string */
    public string $highBidderLastName;
    /** @var int */
    public int $highBidderUserId;
    /** @var string */
    public string $highBidderUsername;
    /** @var bool */
    public bool $isAuctionReverse;
    /** @var bool */
    public bool $isExtendAll;
    /** @var bool */
    public bool $isReserveMet;
    /** @var bool */
    public bool $isReserveNotMet;
    /** @var string|null */
    public ?string $lotEndDateIso;
    /** @var int */
    public int $extendTime;
    /** @var int */
    public int $lotItemId;
    /** @var int */
    public int $lotStartGapTime;
    /** @var int */
    public int $lotStatusId;
    /** @var string|null */
    public ?string $lotTimezoneLocation;
    /** @var int */
    public int $lotViewCount;
    /** @var int */
    public int $lotsPerInterval;
    /** @var int */
    public int $orderNum;
    /** @var float|null */
    public ?float $reservePrice;
    /** @var int */
    public int $rtbCurrentLotItemId;
    /** @var string */
    public string $rtbLotEndDateIso;
    /** @var string */
    public string $rtbPauseDateIso;
    /** @var int */
    public int $secondsBefore;
    /** @var int */
    public int $secondsLeft;
    /** @var int */
    public int $staggerClosing;
    /** @var string */
    public string $winnerBidderNum;
    /** @var string */
    public string $winnerCompany;
    /** @var string */
    public string $winnerEmail;
    /** @var string */
    public string $winnerUsername;
    /** @var int */
    public int $winningAuctionId;
    /** @var int */
    public int $winningBidderId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $row
     * @return static
     */
    public function fromDbRow(array $row): static
    {
        $this->accountId = (int)$row['account_id'];
        $this->askingBid = (float)$row['asking_bid'];
        $this->auctionId = (int)$row['auction_id'];
        $this->auctionLotId = (int)$row['alid'];
        $this->auctionStartClosingDateIso = (string)$row['auction_start_closing_date'];
        $this->auctionStatusId = (int)$row['auction_status_id'];
        $this->auctionTimezoneLocation = $row['auc_tz_location'];
        $this->auctionType = (string)$row['auction_type'];
        $this->bidCount = (int)$row['bid_count'];
        $this->currentBid = Cast::toFloat($row['current_bid']);
        $this->currentBidDateIso = (string)$row['current_bid_placed'];
        $this->currentMaxBid = (float)$row['current_max_bid'];
        $this->extendTime = (int)$row['extend_time'];
        $this->hammerPrice = Cast::toFloat($row['hammer_price']);
        $this->highBidderCompany = (string)$row['high_bidder_company'];
        $this->highBidderEmail = (string)$row['high_bidder_email'];
        $this->highBidderFirstName = (string)$row['first_name'];
        $this->highBidderHouse = (string)$row['high_bidder_house'];
        $this->highBidderLastName = (string)$row['last_name'];
        $this->highBidderUserId = (int)$row['high_bidder_user_id'];
        $this->highBidderUsername = (string)$row['high_bidder_username'];
        $this->isAuctionReverse = (bool)$row['auction_reverse'];
        $this->isExtendAll = (bool)$row['extend_all'];
        $this->isReserveMet = (bool)$row['reserve_met_notice'];
        $this->isReserveNotMet = (bool)$row['reserve_not_met_notice'];
        $this->lotEndDateIso = $row['lot_en_dt'];
        $this->lotItemId = (int)$row['id'];
        $this->lotStartGapTime = (int)$row['lot_start_gap_time'];
        $this->lotStatusId = (int)$row['lot_status_id'];
        $this->lotTimezoneLocation = $row['lot_tz_location'];
        $this->lotViewCount = (int)$row['view_count'];
        $this->lotsPerInterval = (int)$row['lots_per_interval'];
        $this->orderNum = (int)$row['order_num'];
        $this->reservePrice = Cast::toFloat($row['reserve_price']);
        $this->rtbCurrentLotItemId = (int)$row['rtb_current_lot_id'];
        $this->rtbLotEndDateIso = (string)$row['rtb_lot_end_date'];
        $this->rtbPauseDateIso = (string)$row['rtb_pause_date'];
        $this->secondsBefore = (int)$row['seconds_before'];
        $this->secondsLeft = (int)$row['seconds_left'];
        $this->staggerClosing = (int)$row['stagger_closing'];
        $this->winnerBidderNum = (string)$row['winner_bidder_num'];
        $this->winnerCompany = (string)$row['winner_company'];
        $this->winnerEmail = (string)$row['winner_email'];
        $this->winnerUsername = (string)$row['winner_username'];
        $this->winningAuctionId = (int)$row['winning_auction_id'];
        $this->winningBidderId = (int)$row['winner_user_id'];
        return $this;
    }
}
