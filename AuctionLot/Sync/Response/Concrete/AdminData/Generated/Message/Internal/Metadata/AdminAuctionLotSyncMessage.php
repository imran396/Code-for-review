<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: admin_auction_lot_sync_message.proto

namespace Sam\AuctionLot\Sync\Response\Concrete\AdminData\Generated\Message\Internal\Metadata;

class AdminAuctionLotSyncMessage
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        $pool->internalAddGeneratedFile(
            '
�
$admin_auction_lot_sync_message.protosam.auction_lot.admin_sync"�
AdminDataResponseA
auction_totals (2).sam.auction_lot.admin_sync.AuctionTotals]
auction_lot_items (2B.sam.auction_lot.admin_sync.AdminDataResponse.AuctionLotItemsEntryb
AuctionLotItemsEntry
key (9
value (2*.sam.auction_lot.admin_sync.AuctionLotData:8"�
AuctionTotals
	total_bid (
total_hammer_price (#
total_hammer_price_internet (
total_high_estimate (
total_low_estimate (
total_max_bid (
total_reserve (
total_starting_bid (
total_views	 ("�
AuctionLotData

asking_bid (
auction_status (
	bid_count (

lot_status (
lot_view_count (
seconds_before (
seconds_left (
reserve_not_met (H �
current_bid	 (H�
current_max_bid
 (H�
hammer_price (H�
reserve_price (H�
auction_type (	
end_date (	
last_bid_date (	;
high_bidder (2&.sam.auction_lot.admin_sync.HighBidder2
winner (2".sam.auction_lot.admin_sync.WinnerB
_reserve_not_metB
_current_bidB
_current_max_bidB
_hammer_priceB
_reserve_price"�

HighBidder
user_id (
company (	
email (	

first_name (	
house (	
	last_name (	
username (	"6
Winner
company (	
email (	
info (	B��ASam\\AuctionLot\\Sync\\Response\\Concrete\\AdminData\\Generated\\Message�SSam\\AuctionLot\\Sync\\Response\\Concrete\\AdminData\\Generated\\Message\\Internal\\Metadatabproto3'
        , true);

        static::$is_initialized = true;
    }
}

