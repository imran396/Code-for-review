<?php
/**
 * SAM-6181 : Refactor for Admin>Auction>Enter bids - Move input validation logic to separate class and implement unit test
 * https://bidpath.atlassian.net/browse/SAM-6181
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01/25/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionEnterBidForm\EnterBid;

/**
 * Class AuctionEnterBidConstants
 * @package Sam\View\Admin\Form\AuctionEnterBidForm\EnterBid
 */
class AuctionEnterBidConstants
{
    // LOT ERRORS
    public const ERR_FULL_LOT_NO = 1;
    public const ERR_LOT_NO_REQUIRED = 2;
    public const ERR_LOT_NO_NUMERIC = 3;
    public const ERR_LOT_NOT_EXIST = 4;
    public const ERR_LISTING_ONLY = 5;
    public const ERR_ALREADY_SOLD = 6;
    public const LOT_ERRORS = [
        self::ERR_FULL_LOT_NO,
        self::ERR_LOT_NO_REQUIRED,
        self::ERR_LOT_NO_NUMERIC,
        self::ERR_LOT_NOT_EXIST,
        self::ERR_LISTING_ONLY,
        self::ERR_ALREADY_SOLD
    ];

    // BID AMOUNT ERRORS
    public const ERR_BID_AMOUNT_REQUIRED = 7;
    public const ERR_BID_AMOUNT_NUMERIC = 8;
    public const ERR_INVALID = 9;
    public const ERR_ABOVE_ZERO = 10;
    public const BID_AMOUNT_ERRORS = [
        self::ERR_BID_AMOUNT_REQUIRED,
        self::ERR_BID_AMOUNT_NUMERIC,
        self::ERR_INVALID,
        self::ERR_ABOVE_ZERO
    ];

    // BIDDER NO ERRORS
    public const ERR_BIDDER_NO_REQUIRED = 11;
    public const ERR_BIDDER_NO_ALPHA_NUMERIC = 12;
    public const ERR_INVALID_USER = 13;
    public const BIDDER_NO_ERRORS = [
        self::ERR_BIDDER_NO_REQUIRED,
        self::ERR_BIDDER_NO_ALPHA_NUMERIC,
        self::ERR_INVALID_USER
    ];

    // GENERAL ERRORS
    public const ERR_NOT_STARTED = 14;
    public const ERR_NOT_FOR_BIDDING = 15;
    public const FAILED_PLACE_BID = 'Place bid failed. Please check fields below for details';
    public const FAILED_SELL_LOT = 'Sell lot failed. Please check fields below for details';
}
