<?php

namespace Sam\Core\Constants;

/**
 * Class PageTracking
 * @package Sam\Core\Constants
 */
class PageTracking
{
    public const SIGNUP = 'signup'; // Signup ga=signup
    public const BID_PLACED = 'bid_placed_'; // Bid placed ga=bid_placed_<number>
    public const PAYMENT = 'payment_made'; // Payment=payment_made
    public const BUY_NOW = 'buy_now_completed_'; // Buy now ga=buy_now_completed_<item-number>
    public const OFFER = 'offer_submitted_'; // Offer ga=offer_submitted_<item-number>
    public const SALE_REGISTRATION = 'auction_registration_';
}
