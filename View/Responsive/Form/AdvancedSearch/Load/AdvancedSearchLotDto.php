<?php

namespace Sam\View\Responsive\Form\AdvancedSearch\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AdvancedSearchLotDto
 * @package Sam\View\Responsive\Form\AdvancedSearch\Load
 */
class AdvancedSearchLotDto extends CustomizableClass
{
    public string $absenteeBidsDisplay = '';
    public bool $isAccountBuyNowSelectQuantityEnabled = false;
    public int $lotAccountId = 0;
    public int $auctionLotId = 0;
    public bool $isAllowForceBid = false;
    public string $buyNowRestriction = '';
    public bool $isBuyNowUnsold = false;
    public bool $isConditionalSales = false;
    public bool $isConfirmTimedBid = false;
    public bool $isInlineBidConfirm = false;
    public ?float $askingBid = null;
    public string $auctionStartClosingDateIso = '';
    public string $auctionTimezoneLocation = '';
    public string $auctionEndRegisterDateIso = '';
    public int $auctionId = 0;
    public bool $isAuctionListing = false;
    public string $auctionName = '';
    public bool $isAuctionReverse = false;
    public string $auctionSeoUrl = '';
    public string $auctionStartRegisterDateIso = '';
    public int $auctionStatusId = 0;
    public bool $isAuctionTest = false;
    public string $auctionType = '';
    public int $bestOffer = 0;
    public int $bidCount = 0;
    public bool $isBidderTermsAreAgreed = false;
    public bool $isBiddingPaused = false;
    public float $bulkMasterAskingBid = 0.;
    public int $bulkMasterId = 0;
    public float $buyAmount = 0.;
    public string $changes = '';
    public int $consignorId = 0;
    public int $currency = 0;
    public ?float $currentBid = null;
    public int $currentBidId = 0;
    public int $currentBidderId = 0;
    public float $currentMaxBid = 0.;
    public float $currentTransactionBid = 0.;
    public string $dateAssignmentStrategy = '';
    public string $endPrebiddingDateIso = '';
    public int $eventType = 0;
    public int $extendTime = 0;
    public ?float $hammerPrice = null;
    public float $highEstimate = 0.;
    public int $lotItemId = 0;
    public int $imageId = 0;
    public bool $isBulkMaster = false;
    public int $itemNum = 0;
    public string $itemNumExt = '';
    public bool $isLotListing = false;
    public bool $lotBuyNowSelectQuantityEnabled = false;
    public string $lotDescription = '';
    public string $lotEndDateIso = '';
    public string $lotName = '';
    public ?int $lotNum = null;
    public string $lotNumExt = '';
    public string $lotNumPrefix = '';
    public string $lotSeoUrl = '';
    public string $lotStartDateIso = '';
    public int $lotStartGapTime = 0;
    public int $lotStatusId = 0;
    public string $lotTimezoneLocation = '';
    public float $lowEstimate = 0.;
    public ?float $maxBid = null;
    public bool $isNextBidButton = false;
    public bool $isNoBidding = false;
    public bool $isNotifyAbsenteeBidders = false;
    public ?int $orBid = null;
    public int $orderNum = 0;
    public ?float $quantity = null;
    public int $quantityScale = 0;
    public bool $isQuantityXMoney = false;
    public bool $isReserveMetNotice = false;
    public bool $isReserveNotMetNotice = false;
    public float $reservePrice = 0.;
    public bool $isRequireLotChangeConfirmation = false;
    public int $rtbCurrentLotId = 0;
    public int $rtbLotActive = 0;
    public string $rtbLotEndDateIso = '';
    public string $rtbPauseDateIso = '';
    public ?int $saleNum = null;
    public string $saleNumExt = '';
    public int $secondsBefore = 0;
    public int $secondsLeft = 0;
    public string $startBiddingDate = '';
    public float $startingBidNormalized = 0.;
    public string $termsAndConditions = '';
    public int $watchlistId = 0;
    public int $winnerUserId = 0;
    public int $winningAuctionId = 0;
    public array $customFields = [];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $row
     * @param array $customFields
     * @return static
     */
    public function fromDbRow(array $row, array $customFields): static
    {
        foreach ($customFields as $customField) {
            $this->customFields[$customField] = $row[$customField] ?? '';
        }

        $this->absenteeBidsDisplay = (string)$row['absentee_bids_display'];
        $this->askingBid = Cast::toFloat($row['asking_bid']);
        $this->auctionEndRegisterDateIso = (string)$row['auction_end_register_date'];
        $this->auctionId = (int)$row['auction_id'];
        $this->auctionLotId = (int)$row['alid'];
        $this->auctionName = (string)$row['auction_name'];
        $this->auctionSeoUrl = (string)$row['auction_seo_url'];
        $this->auctionStartClosingDateIso = (string)$row['auction_start_closing_date'];
        $this->auctionStartRegisterDateIso = (string)$row['auction_start_register_date'];
        $this->auctionStatusId = (int)$row['auction_status_id'];
        $this->auctionTimezoneLocation = (string)$row['auc_tz_location'];
        $this->auctionType = (string)$row['auction_type'];
        $this->bestOffer = (int)$row['best_offer'];
        $this->bidCount = (int)$row['bid_count'];
        $this->bulkMasterAskingBid = (float)$row['bulk_master_asking_bid'];
        $this->bulkMasterId = (int)$row['bulk_master_id'];
        $this->buyAmount = (float)$row['buy_amount'];
        $this->buyNowRestriction = (string)$row['ap_buy_now_restriction'];
        $this->changes = (string)$row['changes'];
        $this->consignorId = (int)$row['consignor_id'];
        $this->currency = (int)$row['currency'];
        $this->currentBid = Cast::toFloat($row['current_bid']);
        $this->currentBidId = (int)$row['current_bid_id'];
        $this->currentBidderId = (int)$row['current_bidder_id'];
        $this->currentMaxBid = (float)$row['current_max_bid'];
        $this->currentTransactionBid = (float)$row['current_transaction_bid'];
        $this->dateAssignmentStrategy = (string)$row['date_assignment_strategy'];
        $this->endPrebiddingDateIso = (string)$row['end_prebidding_date'];
        $this->eventType = (int)$row['event_type'];
        $this->extendTime = (int)$row['extend_time'];
        $this->hammerPrice = Cast::toFloat($row['hammer_price']);
        $this->highEstimate = (float)$row['high_estimate'];
        $this->imageId = (int)$row['img_id'];
        $this->isAccountBuyNowSelectQuantityEnabled = (bool)$row['account_buy_now_select_quantity_enabled'];
        $this->isAllowForceBid = (bool)$row['allow_force_bid'];
        $this->isAuctionListing = (bool)$row['auction_listing'];
        $this->isAuctionReverse = (bool)$row['auction_reverse'];
        $this->isAuctionTest = (bool)$row['auction_test'];
        $this->isBidderTermsAreAgreed = (bool)$row['bidder_terms_are_agreed'];
        $this->isBiddingPaused = (bool)$row['bidding_paused'];
        $this->isBulkMaster = (bool)$row['is_bulk_master'];
        $this->isBuyNowUnsold = (bool)$row['ap_buy_now_unsold'];
        $this->isConditionalSales = (bool)$row['ap_conditional_sales'];
        $this->isConfirmTimedBid = (bool)$row['ap_confirm_timed_bid'];
        $this->isInlineBidConfirm = (bool)$row['ap_inline_bid_confirm'];
        $this->isLotListing = (bool)$row['listing'];
        $this->isNextBidButton = (bool)$row['next_bid_button'];
        $this->isNoBidding = (bool)$row['no_bidding'];
        $this->isNotifyAbsenteeBidders = (bool)$row['notify_absentee_bidders'];
        $this->isQuantityXMoney = (bool)$row['qty_x_money'];
        $this->isRequireLotChangeConfirmation = (bool)$row['rlcc'];
        $this->isReserveMetNotice = (bool)$row['reserve_met_notice'];
        $this->isReserveNotMetNotice = (bool)$row['reserve_not_met_notice'];
        $this->itemNum = (int)$row['item_num'];
        $this->itemNumExt = (string)$row['item_num_ext'];
        $this->lotAccountId = (int)$row['account_id'];
        $this->lotBuyNowSelectQuantityEnabled = (bool)$row['lot_buy_now_select_quantity_enabled'];
        $this->lotDescription = (string)$row['lot_desc'];
        $this->lotEndDateIso = (string)$row['lot_en_dt'];
        $this->lotItemId = (int)$row['id'];
        $this->lotName = (string)$row['lot_name'];
        $this->lotNum = Cast::toInt($row['lot_num']);
        $this->lotNumExt = (string)$row['lot_num_ext'];
        $this->lotNumPrefix = (string)$row['lot_num_prefix'];
        $this->lotSeoUrl = (string)$row['lot_seo_url'];
        $this->lotStartDateIso = (string)$row['lot_st_dt'];
        $this->lotStartGapTime = (int)$row['lot_start_gap_time'];
        $this->lotStatusId = (int)$row['lot_status_id'];
        $this->lotTimezoneLocation = (string)$row['lot_tz_location'];
        $this->lowEstimate = (float)$row['low_estimate'];
        $this->maxBid = (float)$row['max_bid'];
        $this->orBid = Cast::toInt($row['or_bid']);
        $this->orderNum = (int)$row['order_num'];
        $this->quantity = Cast::toFloat($row['qty']);
        $this->quantityScale = (int)$row['qty_scale'];
        $this->reservePrice = (float)$row['reserve_price'];
        $this->rtbCurrentLotId = (int)$row['rtb_current_lot_id'];
        $this->rtbLotActive = (int)$row['rtb_lot_active'];
        $this->rtbLotEndDateIso = (string)$row['rtb_lot_end_date'];
        $this->rtbPauseDateIso = (string)$row['rtb_pause_date'];
        $this->saleNum = Cast::toInt($row['sale_num']);
        $this->saleNumExt = (string)$row['sale_num_ext'];
        $this->secondsBefore = (int)$row['seconds_before'];
        $this->secondsLeft = (int)$row['seconds_left'];
        $this->startBiddingDate = (string)$row['start_bidding_date'];
        $this->startingBidNormalized = (float)$row['starting_bid_normalized'];
        $this->termsAndConditions = (string)$row['terms_and_conditions'];
        $this->watchlistId = (int)$row['watchlist_id'];
        $this->winnerUserId = (int)$row['winner_user_id'];
        $this->winningAuctionId = (int)$row['winning_auction_id'];
        return $this;
    }
}
