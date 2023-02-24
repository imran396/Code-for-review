<?php
/**
 * SAM-8705: Apply DTO's for loaded data at Home Dashboard page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\HomeDashboardForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class HomeDashboardAuctionDto
 * @package Sam\View\Admin\Form\HomeDashboardForm\Load
 */
class HomeDashboardAuctionDto extends CustomizableClass
{
    public readonly int $accountId;
    public readonly string $auctionEndDate;
    public readonly int $auctionStatusId;
    public readonly string $auctionType;
    public readonly int $bidders;
    public readonly int $biddersApproved;
    public readonly int $biddersBidding;
    public readonly int $biddersWinning;
    public readonly int $bids;
    public readonly string $endDate;
    public readonly int $eventType;
    public readonly int $id;
    public readonly int $lotsAboveHighEstimate;
    public readonly int $lotsSold;
    public readonly int $lotsWithBids;
    public readonly int $maxBidCount;
    public readonly string $name;
    public readonly int $saleNum;
    public readonly string $saleNumExt;
    public readonly string $startClosingDate;
    public readonly string $timezoneLocation;
    public readonly float $totalBid;
    public readonly float $totalBuyersPremium;
    public readonly float $totalCommission;
    public readonly float $totalCommissionSettled;
    public readonly float $totalFees;
    public readonly float $totalHammerPrice;
    public readonly float $totalHammerPriceInternet;
    public readonly float $totalHighEstimate;
    public readonly int $totalLots;
    public readonly float $totalLowEstimate;
    public readonly float $totalPaidBuyersPremium;
    public readonly float $totalPaidFees;
    public readonly float $totalPaidHammerPrice;
    public readonly float $totalPaidTax;
    public readonly float $totalReserveMet;
    public readonly float $totalSettlementFee;
    public readonly float $totalSettlementFeeSettled;
    public readonly int $totalViews;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $accountId
     * @param string $auctionEndDate
     * @param int $auctionStatusId
     * @param string $auctionType
     * @param int $bidders
     * @param int $biddersApproved
     * @param int $biddersBidding
     * @param int $biddersWinning
     * @param int $bids
     * @param string $endDate
     * @param int $eventType
     * @param int $id
     * @param int $lotsAboveHighEstimate
     * @param int $lotsSold
     * @param int $lotsWithBids
     * @param int $maxBidCount
     * @param string $name
     * @param int $saleNum
     * @param string $saleNumExt
     * @param string $startClosingDate
     * @param string $timezoneLocation
     * @param float $totalBid
     * @param float $totalBuyersPremium
     * @param float $totalCommission
     * @param float $totalCommissionSettled
     * @param float $totalFees
     * @param float $totalHammerPrice
     * @param float $totalHammerPriceInternet
     * @param float $totalHighEstimate
     * @param int $totalLots
     * @param float $totalLowEstimate
     * @param float $totalPaidBuyersPremium
     * @param float $totalPaidFees
     * @param float $totalPaidHammerPrice
     * @param float $totalPaidTax
     * @param float $totalReserveMet
     * @param float $totalSettlementFee
     * @param float $totalSettlementFeeSettled
     * @param int $totalViews
     * @return $this
     */
    public function construct(
        int $accountId,
        string $auctionEndDate,
        int $auctionStatusId,
        string $auctionType,
        int $bidders,
        int $biddersApproved,
        int $biddersBidding,
        int $biddersWinning,
        int $bids,
        string $endDate,
        int $eventType,
        int $id,
        int $lotsAboveHighEstimate,
        int $lotsSold,
        int $lotsWithBids,
        int $maxBidCount,
        string $name,
        int $saleNum,
        string $saleNumExt,
        string $startClosingDate,
        string $timezoneLocation,
        float $totalBid,
        float $totalBuyersPremium,
        float $totalCommission,
        float $totalCommissionSettled,
        float $totalFees,
        float $totalHammerPrice,
        float $totalHammerPriceInternet,
        float $totalHighEstimate,
        int $totalLots,
        float $totalLowEstimate,
        float $totalPaidBuyersPremium,
        float $totalPaidFees,
        float $totalPaidHammerPrice,
        float $totalPaidTax,
        float $totalReserveMet,
        float $totalSettlementFee,
        float $totalSettlementFeeSettled,
        int $totalViews
    ): static {
        $this->accountId = $accountId;
        $this->auctionEndDate = $auctionEndDate;
        $this->auctionStatusId = $auctionStatusId;
        $this->auctionType = $auctionType;
        $this->bidders = $bidders;
        $this->biddersApproved = $biddersApproved;
        $this->biddersBidding = $biddersBidding;
        $this->biddersWinning = $biddersWinning;
        $this->bids = $bids;
        $this->endDate = $endDate;
        $this->eventType = $eventType;
        $this->id = $id;
        $this->lotsAboveHighEstimate = $lotsAboveHighEstimate;
        $this->lotsSold = $lotsSold;
        $this->lotsWithBids = $lotsWithBids;
        $this->maxBidCount = $maxBidCount;
        $this->name = $name;
        $this->saleNum = $saleNum;
        $this->saleNumExt = $saleNumExt;
        $this->startClosingDate = $startClosingDate;
        $this->timezoneLocation = $timezoneLocation;
        $this->totalBid = $totalBid;
        $this->totalBuyersPremium = $totalBuyersPremium;
        $this->totalCommission = $totalCommission;
        $this->totalCommissionSettled = $totalCommissionSettled;
        $this->totalFees = $totalFees;
        $this->totalHammerPrice = $totalHammerPrice;
        $this->totalHammerPriceInternet = $totalHammerPriceInternet;
        $this->totalHighEstimate = $totalHighEstimate;
        $this->totalLots = $totalLots;
        $this->totalLowEstimate = $totalLowEstimate;
        $this->totalPaidBuyersPremium = $totalPaidBuyersPremium;
        $this->totalPaidFees = $totalPaidFees;
        $this->totalPaidHammerPrice = $totalPaidHammerPrice;
        $this->totalPaidTax = $totalPaidTax;
        $this->totalReserveMet = $totalReserveMet;
        $this->totalSettlementFee = $totalSettlementFee;
        $this->totalSettlementFeeSettled = $totalSettlementFeeSettled;
        $this->totalViews = $totalViews;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        // only for active auctions
        $auctionEndDate = isset($row['auction_end_date']) ? (string)$row['auction_end_date'] : '';
        $auctionStatusId = isset($row['auction_status_id']) ? (int)$row['auction_status_id'] : 0;
        $eventType = isset($row['event_type']) ? (int)$row['event_type'] : 0;
        $startClosingDate = isset($row['start_closing_date']) ? (string)$row['start_closing_date'] : '';

        return $this->construct(
            (int)$row['account_id'],
            $auctionEndDate,
            $auctionStatusId,
            (string)$row['auction_type'],
            (int)['bidders'],
            (int)$row['bidders_approved'],
            (int)$row['bidders_bidding'],
            (int)$row['bidders_winning'],
            (int)$row['bids'],
            (string)$row['end_date'],
            $eventType,
            (int)$row['id'],
            (int)$row['lots_above_high_estimate'],
            (int)$row['lots_sold'],
            (int)$row['lots_with_bids'],
            (int)$row['max_bid_count'],
            (string)$row['name'],
            (int)$row['sale_num'],
            (string)$row['sale_num_ext'],
            $startClosingDate,
            (string)$row['timezone_location'],
            (float)$row['total_bid'],
            (float)$row['total_buyers_premium'],
            (float)$row['total_commission'],
            (float)$row['total_commission_settled'],
            (float)$row['total_fees'],
            (float)$row['total_hammer_price'],
            (float)$row['total_hammer_price_internet'],
            (float)$row['total_high_estimate'],
            (int)$row['total_lots'],
            (float)$row['total_low_estimate'],
            (float)$row['total_paid_buyers_premium'],
            (float)$row['total_paid_fees'],
            (float)$row['total_paid_hammer_price'],
            (float)$row['total_paid_tax'],
            (float)$row['total_reserve_met'],
            (float)$row['total_settlement_fee'],
            (float)$row['total_settlement_fee_settled'],
            (int)$row['total_views']
        );
    }
}
