<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\PublicData\Dto;

use DateTime;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Auction lot data to sync the frontend catalog page
 *
 * Class SyncAuctionLotDto
 * @package Sam\AuctionLot\Sync\Response\Concrete\PublicData\Dto
 */
class SyncAuctionLotDto extends CustomizableClass
{
    public readonly int $auctionId;
    public readonly int $auctionLotItemOrderNum;
    public readonly string $auctionStartClosingDateIso;
    public readonly bool $auctionReverse;
    public readonly string $auctionType;
    public readonly int $auctionCurrencyId;
    public readonly float $askingBid;
    public readonly int $auctionLotId;
    public readonly int $auctionStatus;
    public readonly string $auctionTimezoneLocation;
    public readonly string $absenteeBidsDisplay;
    public readonly int $bidCount;
    public readonly string $bidderNum;
    public readonly ?float $buyAmount;
    public readonly ?string $buyNowRestriction;
    public readonly ?float $bulkMasterAskingBid;
    public readonly ?float $currentBid;
    public readonly int $currentBidderId;
    public readonly int $consignorUserId;
    public readonly ?float $currentMaxBid;
    public readonly ?float $currentTransactionBid;
    public readonly string $currencySign;
    public readonly ?DateTime $endDate;
    public readonly int $extendTime;
    public readonly ?float $hammerPrice;
    public readonly bool $isBiddingPaused;
    public readonly bool $isAuctionLotListingOnly;
    public readonly bool $isAuctionListingOnly;
    public readonly bool $isReserveNotMet;
    public readonly bool $isReserveMet;
    public readonly bool $isNoBidding;
    public readonly bool $isBuyNowUnsold;
    public readonly bool $isRtbLotActive;
    public readonly bool $isNextBidButton;
    public readonly bool $isQuantityXMoney;
    public readonly int $lotItemId;
    public readonly string $lotBiddingInfoAccess;
    public readonly string $lotWinningBidAccess;
    public readonly int $lotStatus;
    public readonly bool $lotChanges;
    public readonly int $lotChangesTimestamp;
    public readonly int $lotStartGapTime;
    public readonly bool $notifyAbsenteeBidders;
    public readonly float $quantity;
    public readonly int $quantityScale;
    public readonly ?float $reservePrice;
    public readonly int $rtbCurrentLotItemId;
    public readonly string $rtbLotEndDateIso;
    public readonly string $rtbPauseDateIso;
    public readonly ?float $startingBid;
    public readonly ?DateTime $startDate;
    public readonly int $secondsBefore;
    public readonly int $secondsLeft;
    public readonly int $userFlag;
    public readonly int $userAccountFlag;
    public readonly ?float $userMaxBid;
    public readonly int $winnerUserId;
    public readonly string $winningBidderInfo;

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
        $this->absenteeBidsDisplay = (string)$row['absentee_bids_display'];
        $this->askingBid = (float)$row['asking_bid'];
        $this->auctionCurrencyId = (int)$row['auction_currency_id'];
        $this->auctionId = (int)$row['auction_id'];
        $this->auctionLotId = (int)$row['alid'];
        $this->auctionLotItemOrderNum = (int)$row['order_num'];
        $this->auctionReverse = (bool)$row['auction_reverse'];
        $this->auctionStartClosingDateIso = (string)$row['auction_start_closing_date'];
        $this->auctionStatus = (int)$row['auction_status_id'];
        $this->auctionTimezoneLocation = (string)$row['auc_tz_location'];
        $this->auctionType = (string)$row['auction_type'];
        $this->bidCount = (int)$row['bid_count'];
        $this->bidderNum = (string)$row['bidder_num'];
        $this->bulkMasterAskingBid = Cast::toFloat($row['bulk_master_asking_bid']);
        $this->buyAmount = Cast::toFloat($row['buy_amount']);
        $this->buyNowRestriction = Cast::toString($row['ap_buy_now_restriction'], Constants\SettingRtb::BUY_NOW_LIVE_RESTRICTIONS);
        $this->consignorUserId = (int)$row['consignor_id'];
        $this->currencySign = (string)$row['str_currency'];
        $this->currentBid = Cast::toFloat($row['current_bid']);
        $this->currentBidderId = (int)$row['current_bidder_id'];
        $this->currentMaxBid = Cast::toFloat($row['current_max_bid']);
        $this->currentTransactionBid = Cast::toFloat($row['current_transaction_bid']);
        $this->endDate = $row['lot_en_dt'] ? new DateTime($row['lot_en_dt']) : null;
        $this->extendTime = (int)$row['extend_time'];
        $this->hammerPrice = Cast::toFloat($row['hammer_price']);
        $this->isAuctionListingOnly = (bool)$row['auction_listing_only'];
        $this->isAuctionLotListingOnly = (bool)$row['listing_only'];
        $this->isBiddingPaused = (bool)$row['bidding_paused'];
        $this->isBuyNowUnsold = (bool)$row['ap_buy_now_unsold'];
        $this->isNextBidButton = (bool)$row['next_bid_button'];
        $this->isNoBidding = (bool)$row['no_bidding'];
        $this->isQuantityXMoney = (bool)$row['qty_x_money'];
        $this->isReserveMet = (bool)$row['reserve_met_notice'];
        $this->isReserveNotMet = (bool)$row['reserve_not_met_notice'];
        $this->isRtbLotActive = (bool)$row['rtb_lot_active'];
        $this->lotBiddingInfoAccess = (string)$row['lot_bidding_info_access'];
        $this->lotWinningBidAccess = (string)$row['lot_winning_bid_access'];
        $this->lotChanges = (bool)$row['changes'];
        $this->lotChangesTimestamp = (int)$row['changes_timestamp'];
        $this->lotItemId = (int)$row['id'];
        $this->lotStartGapTime = (int)$row['lot_start_gap_time'];
        $this->lotStatus = (int)$row['lot_status_id'];
        $this->notifyAbsenteeBidders = (bool)$row['notify_absentee_bidders'];
        $this->quantity = (float)$row['qty'];
        $this->quantityScale = (int)$row['qty_scale'];
        $this->reservePrice = Cast::toFloat($row['reserve_price']);
        $this->rtbCurrentLotItemId = (int)$row['rtb_current_lot_id'];
        $this->rtbLotEndDateIso = (string)$row['rtb_lot_end_date'];
        $this->rtbPauseDateIso = (string)$row['rtb_pause_date'];
        $this->secondsBefore = (int)$row['seconds_before'];
        $this->secondsLeft = (int)$row['seconds_left'];
        $this->startDate = $row['lot_st_dt'] ? new DateTime($row['lot_st_dt']) : null;
        $this->startingBid = Cast::toFloat($row['starting_bid_normalized']);
        $this->userAccountFlag = (int)$row['user_account_flag'];
        $this->userFlag = (int)$row['user_flag'];
        $this->userMaxBid = Cast::toFloat($row['max_bid']);
        $this->winnerUserId = (int)$row['winning_bidder_id'];
        $this->winningBidderInfo = (string)$row['wbinfo'];
        return $this;
    }
}
