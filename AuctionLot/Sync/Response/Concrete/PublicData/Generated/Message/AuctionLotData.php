<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: auction_lot_sync_message.proto

namespace Sam\AuctionLot\Sync\Response\Concrete\PublicData\Generated\Message;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>sam.auction_lot.sync.AuctionLotData</code>
 */
class AuctionLotData extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string auction_type = 1;</code>
     */
    protected $auction_type = '';
    /**
     * Generated from protobuf field <code>optional double buy_now_amount = 2;</code>
     */
    protected $buy_now_amount = null;
    /**
     * Generated from protobuf field <code>bool closed = 3;</code>
     */
    protected $closed = false;
    /**
     * Generated from protobuf field <code>optional double hammer_price = 5;</code>
     */
    protected $hammer_price = null;
    /**
     * Generated from protobuf field <code>optional double current_bid = 6;</code>
     */
    protected $current_bid = null;
    /**
     * Generated from protobuf field <code>optional double starting_bid = 7;</code>
     */
    protected $starting_bid = null;
    /**
     * Generated from protobuf field <code>optional double asking_bid = 8;</code>
     */
    protected $asking_bid = null;
    /**
     * Generated from protobuf field <code>optional double bulk_master_asking_bid = 9;</code>
     */
    protected $bulk_master_asking_bid = null;
    /**
     * Generated from protobuf field <code>int32 seconds_left = 10;</code>
     */
    protected $seconds_left = 0;
    /**
     * Generated from protobuf field <code>int32 seconds_before = 11;</code>
     */
    protected $seconds_before = 0;
    /**
     * Generated from protobuf field <code>int32 auction_id = 12;</code>
     */
    protected $auction_id = 0;
    /**
     * Generated from protobuf field <code>int32 lot_item_id = 13;</code>
     */
    protected $lot_item_id = 0;
    /**
     * Generated from protobuf field <code>bool auction_reverse = 14;</code>
     */
    protected $auction_reverse = false;
    /**
     * Generated from protobuf field <code>bool lot_changes = 15;</code>
     */
    protected $lot_changes = false;
    /**
     * Generated from protobuf field <code>map<int32, int32> auction_lot_changes_timestamps = 16;</code>
     */
    private $auction_lot_changes_timestamps;
    /**
     * Generated from protobuf field <code>optional int32 bid_count = 17;</code>
     */
    protected $bid_count = null;
    /**
     * Generated from protobuf field <code>optional double absentee_bid = 18;</code>
     */
    protected $absentee_bid = null;
    /**
     * Generated from protobuf field <code>int32 lot_status = 19;</code>
     */
    protected $lot_status = 0;
    /**
     * Generated from protobuf field <code>int32 auction_status = 20;</code>
     */
    protected $auction_status = 0;
    /**
     * Generated from protobuf field <code>string currency_sign = 21;</code>
     */
    protected $currency_sign = '';
    /**
     * Generated from protobuf field <code>double quantity = 22;</code>
     */
    protected $quantity = 0.0;
    /**
     * Generated from protobuf field <code>bool quantity_x_money = 23;</code>
     */
    protected $quantity_x_money = false;
    /**
     * Generated from protobuf field <code>string winning_bidder_info = 24;</code>
     */
    protected $winning_bidder_info = '';
    /**
     * Generated from protobuf field <code>optional bool is_high_bidder = 25;</code>
     */
    protected $is_high_bidder = null;
    /**
     * Generated from protobuf field <code>optional bool next_bid_button = 26;</code>
     */
    protected $next_bid_button = null;
    /**
     * Generated from protobuf field <code>optional bool rtb_lot_active = 27;</code>
     */
    protected $rtb_lot_active = null;
    /**
     * Generated from protobuf field <code>optional double reserve_price = 28;</code>
     */
    protected $reserve_price = null;
    /**
     * Generated from protobuf field <code>optional bool reserve_not_met = 29;</code>
     */
    protected $reserve_not_met = null;
    /**
     * Generated from protobuf field <code>double user_max_bid = 30;</code>
     */
    protected $user_max_bid = 0.0;
    /**
     * Generated from protobuf field <code>string rev = 31;</code>
     */
    protected $rev = '';
    /**
     * Generated from protobuf field <code>string rel = 32;</code>
     */
    protected $rel = '';
    /**
     * Generated from protobuf field <code>bool auction_lot_listing_only = 33;</code>
     */
    protected $auction_lot_listing_only = false;
    /**
     * Generated from protobuf field <code>bool auction_listing_only = 34;</code>
     */
    protected $auction_listing_only = false;
    /**
     * Generated from protobuf field <code>bool notify_absentee_bidders = 35;</code>
     */
    protected $notify_absentee_bidders = false;
    /**
     * Generated from protobuf field <code>string absentee_bids_display = 36;</code>
     */
    protected $absentee_bids_display = '';
    /**
     * Generated from protobuf field <code>.sam.auction_lot.sync.HybridCountdown hybrid_countdown = 37;</code>
     */
    protected $hybrid_countdown = null;
    /**
     * Generated from protobuf field <code>int32 quantity_scale = 38;</code>
     */
    protected $quantity_scale = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $auction_type
     *     @type float $buy_now_amount
     *     @type bool $closed
     *     @type float $hammer_price
     *     @type float $current_bid
     *     @type float $starting_bid
     *     @type float $asking_bid
     *     @type float $bulk_master_asking_bid
     *     @type int $seconds_left
     *     @type int $seconds_before
     *     @type int $auction_id
     *     @type int $lot_item_id
     *     @type bool $auction_reverse
     *     @type bool $lot_changes
     *     @type array|\Google\Protobuf\Internal\MapField $auction_lot_changes_timestamps
     *     @type int $bid_count
     *     @type float $absentee_bid
     *     @type int $lot_status
     *     @type int $auction_status
     *     @type string $currency_sign
     *     @type float $quantity
     *     @type bool $quantity_x_money
     *     @type string $winning_bidder_info
     *     @type bool $is_high_bidder
     *     @type bool $next_bid_button
     *     @type bool $rtb_lot_active
     *     @type float $reserve_price
     *     @type bool $reserve_not_met
     *     @type float $user_max_bid
     *     @type string $rev
     *     @type string $rel
     *     @type bool $auction_lot_listing_only
     *     @type bool $auction_listing_only
     *     @type bool $notify_absentee_bidders
     *     @type string $absentee_bids_display
     *     @type \Sam\AuctionLot\Sync\Response\Concrete\PublicData\Generated\Message\HybridCountdown $hybrid_countdown
     *     @type int $quantity_scale
     * }
     */
    public function __construct($data = NULL) {
        \Sam\AuctionLot\Sync\Response\Concrete\PublicData\Generated\Message\Internal\Metadata\AuctionLotSyncMessage::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string auction_type = 1;</code>
     * @return string
     */
    public function getAuctionType()
    {
        return $this->auction_type;
    }

    /**
     * Generated from protobuf field <code>string auction_type = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setAuctionType($var)
    {
        GPBUtil::checkString($var, True);
        $this->auction_type = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional double buy_now_amount = 2;</code>
     * @return float
     */
    public function getBuyNowAmount()
    {
        return isset($this->buy_now_amount) ? $this->buy_now_amount : 0.0;
    }

    public function hasBuyNowAmount()
    {
        return isset($this->buy_now_amount);
    }

    public function clearBuyNowAmount()
    {
        unset($this->buy_now_amount);
    }

    /**
     * Generated from protobuf field <code>optional double buy_now_amount = 2;</code>
     * @param float $var
     * @return $this
     */
    public function setBuyNowAmount($var)
    {
        GPBUtil::checkDouble($var);
        $this->buy_now_amount = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bool closed = 3;</code>
     * @return bool
     */
    public function getClosed()
    {
        return $this->closed;
    }

    /**
     * Generated from protobuf field <code>bool closed = 3;</code>
     * @param bool $var
     * @return $this
     */
    public function setClosed($var)
    {
        GPBUtil::checkBool($var);
        $this->closed = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional double hammer_price = 5;</code>
     * @return float
     */
    public function getHammerPrice()
    {
        return isset($this->hammer_price) ? $this->hammer_price : 0.0;
    }

    public function hasHammerPrice()
    {
        return isset($this->hammer_price);
    }

    public function clearHammerPrice()
    {
        unset($this->hammer_price);
    }

    /**
     * Generated from protobuf field <code>optional double hammer_price = 5;</code>
     * @param float $var
     * @return $this
     */
    public function setHammerPrice($var)
    {
        GPBUtil::checkDouble($var);
        $this->hammer_price = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional double current_bid = 6;</code>
     * @return float
     */
    public function getCurrentBid()
    {
        return isset($this->current_bid) ? $this->current_bid : 0.0;
    }

    public function hasCurrentBid()
    {
        return isset($this->current_bid);
    }

    public function clearCurrentBid()
    {
        unset($this->current_bid);
    }

    /**
     * Generated from protobuf field <code>optional double current_bid = 6;</code>
     * @param float $var
     * @return $this
     */
    public function setCurrentBid($var)
    {
        GPBUtil::checkDouble($var);
        $this->current_bid = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional double starting_bid = 7;</code>
     * @return float
     */
    public function getStartingBid()
    {
        return isset($this->starting_bid) ? $this->starting_bid : 0.0;
    }

    public function hasStartingBid()
    {
        return isset($this->starting_bid);
    }

    public function clearStartingBid()
    {
        unset($this->starting_bid);
    }

    /**
     * Generated from protobuf field <code>optional double starting_bid = 7;</code>
     * @param float $var
     * @return $this
     */
    public function setStartingBid($var)
    {
        GPBUtil::checkDouble($var);
        $this->starting_bid = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional double asking_bid = 8;</code>
     * @return float
     */
    public function getAskingBid()
    {
        return isset($this->asking_bid) ? $this->asking_bid : 0.0;
    }

    public function hasAskingBid()
    {
        return isset($this->asking_bid);
    }

    public function clearAskingBid()
    {
        unset($this->asking_bid);
    }

    /**
     * Generated from protobuf field <code>optional double asking_bid = 8;</code>
     * @param float $var
     * @return $this
     */
    public function setAskingBid($var)
    {
        GPBUtil::checkDouble($var);
        $this->asking_bid = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional double bulk_master_asking_bid = 9;</code>
     * @return float
     */
    public function getBulkMasterAskingBid()
    {
        return isset($this->bulk_master_asking_bid) ? $this->bulk_master_asking_bid : 0.0;
    }

    public function hasBulkMasterAskingBid()
    {
        return isset($this->bulk_master_asking_bid);
    }

    public function clearBulkMasterAskingBid()
    {
        unset($this->bulk_master_asking_bid);
    }

    /**
     * Generated from protobuf field <code>optional double bulk_master_asking_bid = 9;</code>
     * @param float $var
     * @return $this
     */
    public function setBulkMasterAskingBid($var)
    {
        GPBUtil::checkDouble($var);
        $this->bulk_master_asking_bid = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int32 seconds_left = 10;</code>
     * @return int
     */
    public function getSecondsLeft()
    {
        return $this->seconds_left;
    }

    /**
     * Generated from protobuf field <code>int32 seconds_left = 10;</code>
     * @param int $var
     * @return $this
     */
    public function setSecondsLeft($var)
    {
        GPBUtil::checkInt32($var);
        $this->seconds_left = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int32 seconds_before = 11;</code>
     * @return int
     */
    public function getSecondsBefore()
    {
        return $this->seconds_before;
    }

    /**
     * Generated from protobuf field <code>int32 seconds_before = 11;</code>
     * @param int $var
     * @return $this
     */
    public function setSecondsBefore($var)
    {
        GPBUtil::checkInt32($var);
        $this->seconds_before = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int32 auction_id = 12;</code>
     * @return int
     */
    public function getAuctionId()
    {
        return $this->auction_id;
    }

    /**
     * Generated from protobuf field <code>int32 auction_id = 12;</code>
     * @param int $var
     * @return $this
     */
    public function setAuctionId($var)
    {
        GPBUtil::checkInt32($var);
        $this->auction_id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int32 lot_item_id = 13;</code>
     * @return int
     */
    public function getLotItemId()
    {
        return $this->lot_item_id;
    }

    /**
     * Generated from protobuf field <code>int32 lot_item_id = 13;</code>
     * @param int $var
     * @return $this
     */
    public function setLotItemId($var)
    {
        GPBUtil::checkInt32($var);
        $this->lot_item_id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bool auction_reverse = 14;</code>
     * @return bool
     */
    public function getAuctionReverse()
    {
        return $this->auction_reverse;
    }

    /**
     * Generated from protobuf field <code>bool auction_reverse = 14;</code>
     * @param bool $var
     * @return $this
     */
    public function setAuctionReverse($var)
    {
        GPBUtil::checkBool($var);
        $this->auction_reverse = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bool lot_changes = 15;</code>
     * @return bool
     */
    public function getLotChanges()
    {
        return $this->lot_changes;
    }

    /**
     * Generated from protobuf field <code>bool lot_changes = 15;</code>
     * @param bool $var
     * @return $this
     */
    public function setLotChanges($var)
    {
        GPBUtil::checkBool($var);
        $this->lot_changes = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>map<int32, int32> auction_lot_changes_timestamps = 16;</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getAuctionLotChangesTimestamps()
    {
        return $this->auction_lot_changes_timestamps;
    }

    /**
     * Generated from protobuf field <code>map<int32, int32> auction_lot_changes_timestamps = 16;</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setAuctionLotChangesTimestamps($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::INT32, \Google\Protobuf\Internal\GPBType::INT32);
        $this->auction_lot_changes_timestamps = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional int32 bid_count = 17;</code>
     * @return int
     */
    public function getBidCount()
    {
        return isset($this->bid_count) ? $this->bid_count : 0;
    }

    public function hasBidCount()
    {
        return isset($this->bid_count);
    }

    public function clearBidCount()
    {
        unset($this->bid_count);
    }

    /**
     * Generated from protobuf field <code>optional int32 bid_count = 17;</code>
     * @param int $var
     * @return $this
     */
    public function setBidCount($var)
    {
        GPBUtil::checkInt32($var);
        $this->bid_count = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional double absentee_bid = 18;</code>
     * @return float
     */
    public function getAbsenteeBid()
    {
        return isset($this->absentee_bid) ? $this->absentee_bid : 0.0;
    }

    public function hasAbsenteeBid()
    {
        return isset($this->absentee_bid);
    }

    public function clearAbsenteeBid()
    {
        unset($this->absentee_bid);
    }

    /**
     * Generated from protobuf field <code>optional double absentee_bid = 18;</code>
     * @param float $var
     * @return $this
     */
    public function setAbsenteeBid($var)
    {
        GPBUtil::checkDouble($var);
        $this->absentee_bid = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int32 lot_status = 19;</code>
     * @return int
     */
    public function getLotStatus()
    {
        return $this->lot_status;
    }

    /**
     * Generated from protobuf field <code>int32 lot_status = 19;</code>
     * @param int $var
     * @return $this
     */
    public function setLotStatus($var)
    {
        GPBUtil::checkInt32($var);
        $this->lot_status = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int32 auction_status = 20;</code>
     * @return int
     */
    public function getAuctionStatus()
    {
        return $this->auction_status;
    }

    /**
     * Generated from protobuf field <code>int32 auction_status = 20;</code>
     * @param int $var
     * @return $this
     */
    public function setAuctionStatus($var)
    {
        GPBUtil::checkInt32($var);
        $this->auction_status = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string currency_sign = 21;</code>
     * @return string
     */
    public function getCurrencySign()
    {
        return $this->currency_sign;
    }

    /**
     * Generated from protobuf field <code>string currency_sign = 21;</code>
     * @param string $var
     * @return $this
     */
    public function setCurrencySign($var)
    {
        GPBUtil::checkString($var, True);
        $this->currency_sign = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>double quantity = 22;</code>
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Generated from protobuf field <code>double quantity = 22;</code>
     * @param float $var
     * @return $this
     */
    public function setQuantity($var)
    {
        GPBUtil::checkDouble($var);
        $this->quantity = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bool quantity_x_money = 23;</code>
     * @return bool
     */
    public function getQuantityXMoney()
    {
        return $this->quantity_x_money;
    }

    /**
     * Generated from protobuf field <code>bool quantity_x_money = 23;</code>
     * @param bool $var
     * @return $this
     */
    public function setQuantityXMoney($var)
    {
        GPBUtil::checkBool($var);
        $this->quantity_x_money = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string winning_bidder_info = 24;</code>
     * @return string
     */
    public function getWinningBidderInfo()
    {
        return $this->winning_bidder_info;
    }

    /**
     * Generated from protobuf field <code>string winning_bidder_info = 24;</code>
     * @param string $var
     * @return $this
     */
    public function setWinningBidderInfo($var)
    {
        GPBUtil::checkString($var, True);
        $this->winning_bidder_info = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional bool is_high_bidder = 25;</code>
     * @return bool
     */
    public function getIsHighBidder()
    {
        return isset($this->is_high_bidder) ? $this->is_high_bidder : false;
    }

    public function hasIsHighBidder()
    {
        return isset($this->is_high_bidder);
    }

    public function clearIsHighBidder()
    {
        unset($this->is_high_bidder);
    }

    /**
     * Generated from protobuf field <code>optional bool is_high_bidder = 25;</code>
     * @param bool $var
     * @return $this
     */
    public function setIsHighBidder($var)
    {
        GPBUtil::checkBool($var);
        $this->is_high_bidder = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional bool next_bid_button = 26;</code>
     * @return bool
     */
    public function getNextBidButton()
    {
        return isset($this->next_bid_button) ? $this->next_bid_button : false;
    }

    public function hasNextBidButton()
    {
        return isset($this->next_bid_button);
    }

    public function clearNextBidButton()
    {
        unset($this->next_bid_button);
    }

    /**
     * Generated from protobuf field <code>optional bool next_bid_button = 26;</code>
     * @param bool $var
     * @return $this
     */
    public function setNextBidButton($var)
    {
        GPBUtil::checkBool($var);
        $this->next_bid_button = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional bool rtb_lot_active = 27;</code>
     * @return bool
     */
    public function getRtbLotActive()
    {
        return isset($this->rtb_lot_active) ? $this->rtb_lot_active : false;
    }

    public function hasRtbLotActive()
    {
        return isset($this->rtb_lot_active);
    }

    public function clearRtbLotActive()
    {
        unset($this->rtb_lot_active);
    }

    /**
     * Generated from protobuf field <code>optional bool rtb_lot_active = 27;</code>
     * @param bool $var
     * @return $this
     */
    public function setRtbLotActive($var)
    {
        GPBUtil::checkBool($var);
        $this->rtb_lot_active = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional double reserve_price = 28;</code>
     * @return float
     */
    public function getReservePrice()
    {
        return isset($this->reserve_price) ? $this->reserve_price : 0.0;
    }

    public function hasReservePrice()
    {
        return isset($this->reserve_price);
    }

    public function clearReservePrice()
    {
        unset($this->reserve_price);
    }

    /**
     * Generated from protobuf field <code>optional double reserve_price = 28;</code>
     * @param float $var
     * @return $this
     */
    public function setReservePrice($var)
    {
        GPBUtil::checkDouble($var);
        $this->reserve_price = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional bool reserve_not_met = 29;</code>
     * @return bool
     */
    public function getReserveNotMet()
    {
        return isset($this->reserve_not_met) ? $this->reserve_not_met : false;
    }

    public function hasReserveNotMet()
    {
        return isset($this->reserve_not_met);
    }

    public function clearReserveNotMet()
    {
        unset($this->reserve_not_met);
    }

    /**
     * Generated from protobuf field <code>optional bool reserve_not_met = 29;</code>
     * @param bool $var
     * @return $this
     */
    public function setReserveNotMet($var)
    {
        GPBUtil::checkBool($var);
        $this->reserve_not_met = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>double user_max_bid = 30;</code>
     * @return float
     */
    public function getUserMaxBid()
    {
        return $this->user_max_bid;
    }

    /**
     * Generated from protobuf field <code>double user_max_bid = 30;</code>
     * @param float $var
     * @return $this
     */
    public function setUserMaxBid($var)
    {
        GPBUtil::checkDouble($var);
        $this->user_max_bid = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string rev = 31;</code>
     * @return string
     */
    public function getRev()
    {
        return $this->rev;
    }

    /**
     * Generated from protobuf field <code>string rev = 31;</code>
     * @param string $var
     * @return $this
     */
    public function setRev($var)
    {
        GPBUtil::checkString($var, True);
        $this->rev = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string rel = 32;</code>
     * @return string
     */
    public function getRel()
    {
        return $this->rel;
    }

    /**
     * Generated from protobuf field <code>string rel = 32;</code>
     * @param string $var
     * @return $this
     */
    public function setRel($var)
    {
        GPBUtil::checkString($var, True);
        $this->rel = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bool auction_lot_listing_only = 33;</code>
     * @return bool
     */
    public function getAuctionLotListingOnly()
    {
        return $this->auction_lot_listing_only;
    }

    /**
     * Generated from protobuf field <code>bool auction_lot_listing_only = 33;</code>
     * @param bool $var
     * @return $this
     */
    public function setAuctionLotListingOnly($var)
    {
        GPBUtil::checkBool($var);
        $this->auction_lot_listing_only = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bool auction_listing_only = 34;</code>
     * @return bool
     */
    public function getAuctionListingOnly()
    {
        return $this->auction_listing_only;
    }

    /**
     * Generated from protobuf field <code>bool auction_listing_only = 34;</code>
     * @param bool $var
     * @return $this
     */
    public function setAuctionListingOnly($var)
    {
        GPBUtil::checkBool($var);
        $this->auction_listing_only = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bool notify_absentee_bidders = 35;</code>
     * @return bool
     */
    public function getNotifyAbsenteeBidders()
    {
        return $this->notify_absentee_bidders;
    }

    /**
     * Generated from protobuf field <code>bool notify_absentee_bidders = 35;</code>
     * @param bool $var
     * @return $this
     */
    public function setNotifyAbsenteeBidders($var)
    {
        GPBUtil::checkBool($var);
        $this->notify_absentee_bidders = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string absentee_bids_display = 36;</code>
     * @return string
     */
    public function getAbsenteeBidsDisplay()
    {
        return $this->absentee_bids_display;
    }

    /**
     * Generated from protobuf field <code>string absentee_bids_display = 36;</code>
     * @param string $var
     * @return $this
     */
    public function setAbsenteeBidsDisplay($var)
    {
        GPBUtil::checkString($var, True);
        $this->absentee_bids_display = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.sam.auction_lot.sync.HybridCountdown hybrid_countdown = 37;</code>
     * @return \Sam\AuctionLot\Sync\Response\Concrete\PublicData\Generated\Message\HybridCountdown|null
     */
    public function getHybridCountdown()
    {
        return $this->hybrid_countdown;
    }

    public function hasHybridCountdown()
    {
        return isset($this->hybrid_countdown);
    }

    public function clearHybridCountdown()
    {
        unset($this->hybrid_countdown);
    }

    /**
     * Generated from protobuf field <code>.sam.auction_lot.sync.HybridCountdown hybrid_countdown = 37;</code>
     * @param \Sam\AuctionLot\Sync\Response\Concrete\PublicData\Generated\Message\HybridCountdown $var
     * @return $this
     */
    public function setHybridCountdown($var)
    {
        GPBUtil::checkMessage($var, \Sam\AuctionLot\Sync\Response\Concrete\PublicData\Generated\Message\HybridCountdown::class);
        $this->hybrid_countdown = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int32 quantity_scale = 38;</code>
     * @return int
     */
    public function getQuantityScale()
    {
        return $this->quantity_scale;
    }

    /**
     * Generated from protobuf field <code>int32 quantity_scale = 38;</code>
     * @param int $var
     * @return $this
     */
    public function setQuantityScale($var)
    {
        GPBUtil::checkInt32($var);
        $this->quantity_scale = $var;

        return $this;
    }

}

