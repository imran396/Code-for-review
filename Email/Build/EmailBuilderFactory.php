<?php
/** @noinspection PhpDuplicateSwitchCaseBodyInspection */

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

namespace Sam\Email\Build;

use Exception;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class EmailBuilderFactory
 * @package Sam\Email
 */
class EmailBuilderFactory extends CustomizableClass
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $emailKey
     * @return EmailBuilderAbstract|null
     * @throws Exception
     */
    public function getEmailBuilderByKey(string $emailKey): ?EmailBuilderAbstract
    {
        $builder = null;
        switch ($emailKey) {
            case Constants\EmailKey::ABSENTEE_AND_TIMED_BID_LOT_MODIFICATION_NOTIFICATION:
                break;
            case Constants\EmailKey::ABSENTEE_BID_CONSIGNOR_NOTIFICATION:
                break;
            case Constants\EmailKey::ABSENTEE_BID:
                $builder = AbsenteeBid\Builder::new();
                break;
            case Constants\EmailKey::ABSENTEE_OUTBID:
                break;
            case Constants\EmailKey::ACCOUNT_ADMIN_REGISTRATION_EMAIL:
                break;
            case Constants\EmailKey::ACCOUNT_ADMIN_TMP_PASS_GENERATION:
                break;
            case Constants\EmailKey::ACCOUNT_REG:
                break;
            case Constants\EmailKey::AUCTION_APP:
                break;
            case Constants\EmailKey::AUCTION_REG:
                break;
            case Constants\EmailKey::AUTH_FAILED:
                break;
            case Constants\EmailKey::AUTH_SUCCESS:
                break;
            case Constants\EmailKey::BIDDER_INFO:
                break;
            case Constants\EmailKey::BUY_NOW_ADMIN_REVERSE:
                break;
            case Constants\EmailKey::BUY_NOW_ADMIN:
                break;
            case Constants\EmailKey::BUY_NOW_CONFIRM_LIVE_SELLER:
                break;
            case Constants\EmailKey::BUY_NOW_CONFIRM_LIVE:
                break;
            case Constants\EmailKey::BUYER_ACCEPT_AND_SELL:
                break;
            case Constants\EmailKey::CONSIGNOR_REPORT_2:
                break;
            case Constants\EmailKey::CONSIGNOR_REPORT:    // SAM-1521
                break;
            case Constants\EmailKey::CONSIGNOR_RESERVE_PRICE_CHANGED:
                break;
            case Constants\EmailKey::COUNTER_ACCEPT:
                break;
            case Constants\EmailKey::COUNTER_DECLINED:
                break;
            case Constants\EmailKey::COUNTER_OFFER:
                break;
            case Constants\EmailKey::DOWNLOAD_DOCUMENT_CUSTOM_FIELD:
                break;
            case Constants\EmailKey::EMAIL_VERIFICATION:
                break;
            case Constants\EmailKey::FORGOT_USER:
                break;
            case Constants\EmailKey::INVOICE:
                break;
            case Constants\EmailKey::ITEM_WON_LIVE:
                break;
            case Constants\EmailKey::ITEM_WON_TIMED:
                break;
            case Constants\EmailKey::MY_SEARCH:
                break;
            case Constants\EmailKey::OFFER_ACCEPTED:
                break;
            case Constants\EmailKey::OFFER_DECLINED:
                break;
            case Constants\EmailKey::OFFER_SUBMITTED:
                break;
            case Constants\EmailKey::OUTBID:
                break;
            case Constants\EmailKey::PAYMENT_CONF:
                break;
            case Constants\EmailKey::PAYMENT_REMINDER:
                break;
            case Constants\EmailKey::PICKUP_REMINDER:
                break;
            case Constants\EmailKey::REGISTRATION_REMINDER:
                break;
            case Constants\EmailKey::RESELLER_CERT_RENEWAL:
                break;
            case Constants\EmailKey::RESET_PASS:
                break;
            case Constants\EmailKey::SECOND_CHANCE_OFFER:
                break;
            case Constants\EmailKey::SELLER_ACCEPT_AND_SELL:
                break;
            case Constants\EmailKey::SEND_A_FRIEND:
                break;
            case Constants\EmailKey::SEND_TEST:
                $builder = Test\HeaderBuilder::new();
                break;
            case Constants\EmailKey::SETTLEMENT_PAYMENT_CONF:
                break;
            case Constants\EmailKey::SETTLEMENT:
                break;
            case Constants\EmailKey::TIMED_BID_CONSIGNOR_NOTIFICATION:
                break;
            case Constants\EmailKey::WINNER_BID_CONSIGNOR_NOTIFICATION:
                break;
            case Constants\EmailKey::WINNER_BID_REVERSE:
                break;
            case Constants\EmailKey::WINNER_BID:
                break;
            case Constants\EmailKey::WINNING_BID_NOTIFICATION_SENT_CONSIGNOR:
                break;
            default:
                throw new Exception("EmailKey is required");
        }
        return $builder;
    }
}
