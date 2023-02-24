<?php
/**
 * SAM-4502 : Email template modules
 * https://bidpath.atlassian.net/browse/SAM-4502
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/17/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class EmailKey
 * @package Sam\Core\constants
 */
class EmailKey
{
    // MAIN ACCOUNT ONLY
    public const ACCOUNT_REG = 'ACCOUNT_REG';
    public const RESET_PASS = 'RESET_PASS';
    public const EMAIL_VERIFICATION = 'EMAIL_VERIFICATION';
    public const FORGOT_USER = 'FORGOT_USER';
    public const BIDDER_INFO = 'BIDDER_INFO';
    public const SEND_A_FRIEND = 'SEND_A_FRIEND';
    public const MY_SEARCH = 'MY_SEARCH';

    // MAIN AND PORTAL
    public const AUCTION_REG = 'AUCTION_REG';
    public const AUCTION_APP = 'AUCTION_APP';
    public const INVOICE = 'INVOICE';
    public const COUNTER_OFFER = 'COUNTER_OFFER';
    public const OUTBID = 'OUTBID';
    public const ITEM_WON_LIVE = 'ITEM_WON_LIVE';
    public const ITEM_WON_TIMED = 'ITEM_WON_TIMED';
    public const PAYMENT_CONF = 'PAYMENT_CONFIRM';
    public const SETTLEMENT = 'SETTLEMENT';
    public const COUNTER_ACCEPT = 'COUNTEROFFER_ACCEPTED_TO_ADM';
    public const COUNTER_DECLINED = 'COUNTEROFFER_DECLINED_TO_ADM';
    public const OFFER_ACCEPTED = 'OFFER_ACCEPTED';
    public const OFFER_DECLINED = 'OFFER_DECINED';
    public const BUY_NOW_ADMIN = 'BUY_NOW_NOTIFICATIONS_TO_BOTH';
    public const ABSENTEE_BID = 'ABSENTEE_BID';
    public const WINNER_BID = 'WINNERBID';
    public const OFFER_SUBMITTED = 'OFFER_SUBMITTED';
    public const SECOND_CHANCE_OFFER = 'SECOND_CHANCE_OFFER';
    public const ABSENTEE_OUTBID = 'ABSENTEE_BID_OUTBID';
    public const CONSIGNOR_RESERVE_PRICE_CHANGED = 'CONSIGNOR_RESERVE_PRICE_CHANGED';
    public const AUTH_SUCCESS = 'PRE_AUTH_SUCCESS';
    public const AUTH_FAILED = 'PRE_AUTH_FAILED';
    public const BUYER_ACCEPT_AND_SELL = 'BUYER_ACCEPT_AND_SELL';
    public const SELLER_ACCEPT_AND_SELL = 'SELLER_ACCEPT_AND_SELL';
    public const ABSENTEE_BID_CONSIGNOR_NOTIFICATION = 'ABSENTEE_BID_CONSIGNOR_NOTIFICATION';
    public const WINNER_BID_CONSIGNOR_NOTIFICATION = 'WINNERBID_CONSIGNOR_NOTIFICATION';
    public const TIMED_BID_CONSIGNOR_NOTIFICATION = 'TIMED_BID_CONSIGNOR_NOTIFICATION';
    public const ABSENTEE_AND_TIMED_BID_LOT_MODIFICATION_NOTIFICATION = 'ABSENTEE_AND_TIMED_BID_LOT_MODIFICATION_NOTIFICATION';
    public const BUY_NOW_CONFIRM_LIVE = 'BUY_NOW_NOTIFICATIONS_LIVE';
    public const BUY_NOW_CONFIRM_LIVE_SELLER = 'BUY_NOW_NOTIFICATIONS_LIVE_SELLER';
    public const BUY_NOW_ADMIN_REVERSE = 'BUY_NOW_NOTIFICATIONS_TO_BOTH_REVERSE';
    public const WINNER_BID_REVERSE = 'WINNERBID_REVERSE';
    public const WINNING_BID_NOTIFICATION_SENT_CONSIGNOR = 'WINNING_BID_NOTIFICATION_SENT_CONSIGNOR';
    public const DOWNLOAD_DOCUMENT_CUSTOM_FIELD = 'DOWNNLOAD_DOCUMENT_CUSTOM_FIELD';
    public const SETTLEMENT_PAYMENT_CONF = 'SETTLEMENT_PAYMENT_CONFIRM';
    public const REGISTRATION_REMINDER = 'REGISTRATION_REMINDER';
    public const ACCOUNT_ADMIN_REGISTRATION_EMAIL = 'ACCOUNT_ADMIN_REGISTRATION_EMAIL';
    public const CONSIGNOR_REPORT = 'CONSIGNOR_REPORT';
    public const CONSIGNOR_REPORT_2 = 'CONSIGNOR_REPORT_2';
    public const PICKUP_REMINDER = 'PICKUP_REMINDER';
    public const PAYMENT_REMINDER = 'PAYMENT_REMINDER';
    public const RESELLER_CERT_RENEWAL = 'RESELLER_CERT_RENEWAL';
    public const ACCOUNT_ADMIN_TMP_PASS_GENERATION = 'ACCOUNT_ADMIN_TMP_PASS_GENERATION';
    public const SEND_TEST = 'TEST'; //THIS IS FOR TEST SENDING OF TEMPLATE

    /** @var string[] */
    public static array $mainAccountOnlyKeys = [
        self::BIDDER_INFO,
        self::SEND_A_FRIEND,
        self::MY_SEARCH,
        self::ACCOUNT_ADMIN_REGISTRATION_EMAIL,
        self::ACCOUNT_ADMIN_TMP_PASS_GENERATION,
    ];

    /** @var string[] */
    public static array $noSupportKeys = [
        self::OFFER_SUBMITTED,
        self::COUNTER_DECLINED,
        self::COUNTER_ACCEPT,
        self::ABSENTEE_BID_CONSIGNOR_NOTIFICATION,
        self::WINNER_BID_CONSIGNOR_NOTIFICATION,
        self::TIMED_BID_CONSIGNOR_NOTIFICATION,
    ];

    /** @var string[] */
    public static array $noAuctionSupportKeys = [
        self::ACCOUNT_REG,
        self::BIDDER_INFO,
        //Email_Template::CounterAccept,
        self::ITEM_WON_LIVE,
        self::RESET_PASS,
        self::PAYMENT_CONF,
        //Email_Template::WinnerBid,
        self::EMAIL_VERIFICATION,
        self::FORGOT_USER,
        self::MY_SEARCH,
        self::ABSENTEE_BID_CONSIGNOR_NOTIFICATION,
        self::WINNER_BID_CONSIGNOR_NOTIFICATION,
        self::TIMED_BID_CONSIGNOR_NOTIFICATION,
        self::DOWNLOAD_DOCUMENT_CUSTOM_FIELD,
        self::ACCOUNT_ADMIN_REGISTRATION_EMAIL,
    ];

    /** @var string[] */
    public static array $consignorKeys = [
        self::ABSENTEE_BID_CONSIGNOR_NOTIFICATION,
        self::WINNER_BID_CONSIGNOR_NOTIFICATION,
        self::TIMED_BID_CONSIGNOR_NOTIFICATION,
    ];
}
