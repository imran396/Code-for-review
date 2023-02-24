<?php
/**
 * SAM-5018 : Refactor Email_Template to sub classes
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           May 30, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Template;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;


/**
 * Class EmailTemplatePlaceholderProvider
 * @package Sam\Email\Build\Template
 */
class EmailTemplatePlaceholderProvider extends CustomizableClass
{
    use LotCustomFieldLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $emailKey
     * @return array|string[]
     */
    public function getPlaceholders(string $emailKey): array
    {
        return match ($emailKey) {
            Constants\EmailKey::FORGOT_USER => \Sam\Email\Build\AccountRegistration\PlaceholderBuilder::AVAILABLE_PLACEHOLDERS_FORGOT_USERNAME,
            Constants\EmailKey::ACCOUNT_REG => \Sam\Email\Build\AccountRegistration\PlaceholderBuilder::AVAILABLE_PLACEHOLDERS_ACCOUNT_REGISTRATION,
            Constants\EmailKey::AUCTION_REG => \Sam\Email\Build\AuctionRegistration\PlaceholderBuilder::AVAILABLE_PLACEHOLDERS,
            Constants\EmailKey::REGISTRATION_REMINDER => [
                'first_name',
                'last_name',
                'username',
                'reset_password_url',
                'auction_name',
                'auction_date',
                'auction_url',
                'auction_url_bidding',
                'auction_id',
                'sale_no',
            ],
            Constants\EmailKey::AUCTION_APP => \Sam\Email\Build\AuctionApproved\PlaceholderBuilder::AVAILABLE_PLACEHOLDERS,
            Constants\EmailKey::INVOICE => \Sam\Email\Build\Invoice\PlaceholderBuilder::AVAILABLE_PLACEHOLDERS,
            Constants\EmailKey::BIDDER_INFO => \Sam\Email\Build\BidderInfo\PlaceholderBuilder::AVAILABLE_PLACEHOLDERS,
            Constants\EmailKey::COUNTER_OFFER,
            Constants\EmailKey::COUNTER_DECLINED => \Sam\Email\Build\CounterOffer\PlaceholderBuilder::AVAILABLE_PLACEHOLDERS,
            Constants\EmailKey::OFFER_ACCEPTED,
            Constants\EmailKey::OFFER_DECLINED => \Sam\Email\Build\OfferAcceptedDeclined\PlaceholderBuilder::AVAILABLE_PLACEHOLDERS,
            Constants\EmailKey::OFFER_SUBMITTED => \Sam\Email\Build\OfferSubmitted\PlaceholderBuilder::AVAILABLE_PLACEHOLDERS,
            Constants\EmailKey::COUNTER_ACCEPT => \Sam\Email\Build\CounterAccept\PlaceholderBuilder::AVAILABLE_PLACEHOLDERS,
            Constants\EmailKey::OUTBID => \Sam\Email\Build\Outbid\PlaceholderBuilder::AVAILABLE_PLACEHOLDERS,
            Constants\EmailKey::WINNER_BID,
            Constants\EmailKey::WINNER_BID_REVERSE => [
                'first_name',
                'last_name',
                'lot_number',
                'lot_name',
                'image_url',
                'lot_url',
                'user_bid_amount',
                'new_asking_bid_amount',
                'currency'
            ],
            Constants\EmailKey::ITEM_WON_LIVE => [
                'first_name',
                'last_name',
                'winning_bid_amount',
                'lot_number',
                'lot_name',
                'lot_url',
                'lot_id',
                'image_url',
                'auction_id',
                'sale_no',
                'sale_name',
                'currency',
                'consignor_username',
                'consignor_firstname',
                'consignor_lastname',
                'consignor_company',
                'consignor_email',
                'consignor_phone'
            ],
            Constants\EmailKey::BUY_NOW_ADMIN,
            Constants\EmailKey::ITEM_WON_TIMED => [
                'first_name',
                'last_name',
                'winning_bid_amount',
                'lot_number',
                'lot_name',
                'lot_url',
                'lot_id',
                'image_url',
                'auction_id',
                'sale_no',
                'currency',
                'consignor_username',
                'consignor_firstname',
                'consignor_lastname',
                'consignor_company',
                'consignor_email',
                'consignor_phone',
                'consignor_payment_info'
            ],
            Constants\EmailKey::BUY_NOW_ADMIN_REVERSE => [
                'first_name',
                'last_name',
                'winning_bid_amount',
                'lot_number',
                'lot_name',
                'lot_url',
                'lot_id',
                'image_url',
                'auction_id',
                'sale_no',
                'currency'
            ],
            Constants\EmailKey::RESET_PASS => [
                'first_name',
                'last_name',
                'username',
                'reset_url'
            ],
            Constants\EmailKey::PAYMENT_CONF => [
                'first_name',
                'last_name',
                'invoice_number',
                'amount_paid',
                'invoice_html',
                'currency',
                'invoice_url',
                'invoice_total',
                'invoice_balance'
            ],
            Constants\EmailKey::SETTLEMENT => [
                'first_name',
                'last_name',
                'settlement_total',
                'settlement_number',
                'settlement_url',
                'settlement_html',
                'currency'
            ],
            Constants\EmailKey::ABSENTEE_BID => \Sam\Email\Build\AbsenteeBid\PlaceholderBuilderOld::AVAILABLE_PLACEHOLDERS,
            Constants\EmailKey::EMAIL_VERIFICATION => [
                'first_name',
                'last_name',
                'confirmation_url'
            ],
            Constants\EmailKey::SEND_A_FRIEND => [
                'friend_name',
                'first_name',
                'last_name',
                'email',
                'personal_message',
                'sale_no',
                'sale_name',
                'lot_num',
                'lot_name',
                'lot_url',
                'lot_id',
                'image_url',
                'auction_id'
            ],
            Constants\EmailKey::SECOND_CHANCE_OFFER => [
                'first_name',
                'last_name',
                'auction_name',
                'lot_number',
                'lot_title',
                'lot_description',
                'lot_url',
                'image_url',
                'users_highest_bid_on_this_lot',
                'full_name_of_current_admin_user',
                'company_name_of_current_admin_user',
                'current_admin_user_email',
                'current_admin_user_phone',
                'currency',
                'reserve_price'
            ],
            Constants\EmailKey::ACCOUNT_ADMIN_TMP_PASS_GENERATION => [
                'first_name',
                'last_name',
                'username',
                'tmp_password'
            ],
            Constants\EmailKey::MY_SEARCH => [
                'first_name',
                'last_name',
                'search_results'
            ],
            Constants\EmailKey::ABSENTEE_OUTBID => [
                'first_name',
                'last_name',
                'customer_no',
                'absentee_bid_amount',
                'sale_no',
                'sale_name',
                'lot_number',
                'lot_name',
                'lot_url',
                'lot_id',
                'image_url',
                'auction_id',
                'currency'
            ],
            Constants\EmailKey::CONSIGNOR_RESERVE_PRICE_CHANGED => [
                'sale_name',
                'sale_no',
                'lot_number',
                'image_url',
                'consignor_name',
                'consignor_customer_no',
                'consignor_email',
                'item_num',
                'item_name',
                'old_reserve',
                'new_reserve',
                'link_to_catalog',
                'currency'
            ],
            Constants\EmailKey::AUTH_FAILED, Constants\EmailKey::AUTH_SUCCESS => [
                'first_name',
                'last_name',
                'amount',
                'auction_name',
                'currency',
                'catalog_url'
            ],
            Constants\EmailKey::BUYER_ACCEPT_AND_SELL => [
                'buyer_first_name',
                'buyer_last_name',
                'buyer_username',
                'buyer_company',
                'hammer_price',
                'lot_number',
                'lot_name',
                'item_number',
                'lot_url',
                'image_url',
                'seller_username',
                'seller_company',
                'seller_email',
                'seller_phone',
                'currency'
            ],
            Constants\EmailKey::SELLER_ACCEPT_AND_SELL => [
                'seller_first_name',
                'seller_last_name',
                'seller_username',
                'hammer_price',
                'lot_number',
                'lot_name',
                'item_number',
                'lot_url',
                'image_url',
                'buyer_username',
                'buyer_company',
                'buyer_email',
                'buyer_phone',
                'currency'
            ],
            Constants\EmailKey::TIMED_BID_CONSIGNOR_NOTIFICATION,
            Constants\EmailKey::ABSENTEE_BID_CONSIGNOR_NOTIFICATION => [
                'first_name',
                'last_name',
                'username',
                'bidder_number',
                'lot_url',
                'lot_id',
                'auction_id',
                'sale_no',
                'lot_name',
                'lot_number',
                'image_url',
                'sale_name',
                'bid_amount',
                'consignor_username',
                'consignor_firstname',
                'consignor_lastname',
                'currency'
            ],
            Constants\EmailKey::WINNER_BID_CONSIGNOR_NOTIFICATION => [
                'first_name',
                'last_name',
                'user_bid_amount',
                'new_current_bid_amount',
                'new_asking_bid_amount',
                'lot_number',
                'lot_name',
                'lot_url',
                'lot_id',
                'image_url',
                'auction_id',
                'sale_no',
                'consignor_username',
                'consignor_firstname',
                'consignor_lastname',
                'currency'
            ],
            Constants\EmailKey::ABSENTEE_AND_TIMED_BID_LOT_MODIFICATION_NOTIFICATION => [
                'first_name',
                'last_name',
                'username',
                'bidder_number',
                'lot_number',
                'image_url',
                'title',
                'description',
                'estimates',
                'warranty',
                'currency'
            ],
            Constants\EmailKey::BUY_NOW_CONFIRM_LIVE => [
                'first_name',
                'last_name',
                'winning_bid_amount',
                'lot_number',
                'lot_name',
                'lot_url',
                'lot_id',
                'image_url',
                'auction_id',
                'sale_no',
                'seller_username',
                'seller_companyname',
                'seller_email',
                'seller_phone',
                'currency',
                'consignor_username',
                'consignor_firstname',
                'consignor_lastname',
                'consignor_company',
                'consignor_email',
                'consignor_phone'
            ],
            Constants\EmailKey::BUY_NOW_CONFIRM_LIVE_SELLER => [
                'seller_first_name',
                'seller_last_name',
                'winning_bid_amount',
                'lot_number',
                'lot_name',
                'lot_url',
                'lot_id',
                'image_url',
                'auction_id',
                'sale_no',
                'buyer_username',
                'buyer_companyname',
                'buyer_email',
                'buyer_phone',
                'currency'
            ],
            Constants\EmailKey::WINNING_BID_NOTIFICATION_SENT_CONSIGNOR => [
                'first_name',
                'last_name',
                'winning_bid_amount',
                'lot_number',
                'lot_name',
                'lot_url',
                'lot_id',
                'image_url',
                'auction_id',
                'sale_no',
                'currency',
                'bidder_username',
                'bidder_firstname',
                'bidder_lastname',
                'bidder_company',
                'bidder_email',
                'bidder_phone',
                'bidder_billing_contact_type',
                'bidder_billing_firstname',
                'bidder_billing_lastname',
                'bidder_billing_company',
                'bidder_billing_phone',
                'bidder_billing_fax',
                'bidder_billing_address',
                'bidder_billing_address2',
                'bidder_billing_address3',
                'bidder_billing_city',
                'bidder_billing_country',
                'bidder_billing_state',
                'bidder_billing_zip',
                'bidder_shipping_contact_type',
                'bidder_shipping_firstname',
                'bidder_shipping_lastname',
                'bidder_shipping_company',
                'bidder_shipping_phone',
                'bidder_shipping_fax',
                'bidder_shipping_address',
                'bidder_shipping_address2',
                'bidder_shipping_address3',
                'bidder_shipping_city',
                'bidder_shipping_country',
                'bidder_shipping_state',
                'bidder_shipping_zip'
            ],
            Constants\EmailKey::DOWNLOAD_DOCUMENT_CUSTOM_FIELD => [
                'lot_number',
                'lot_name',
                'lot_url',
                'lot_id',
                'image_url',
                'auction_id',
                'sale_no',
                'bidder_username',
                'bidder_firstname',
                'bidder_lastname',
                'bidder_company',
                'bidder_email',
                'bidder_phone',
                'document_type',
                'document_url'
            ],
            Constants\EmailKey::SETTLEMENT_PAYMENT_CONF => [
                'first_name',
                'last_name',
                'settlement_number',
                'amount_paid',
                'settlement_html',
                'currency'
            ],
            Constants\EmailKey::ACCOUNT_ADMIN_REGISTRATION_EMAIL => [
                'first_name',
                'last_name',
                'username',
                'reset_password_url',
                'company',
                'sso_openid_register_url',
            ],
            Constants\EmailKey::CONSIGNOR_REPORT => [
                'date',
                'date_tz',
                'consignor_username',
                'consignor_custno',
                'consignor_firstname',
                'consignor_lastname',
                'sale_id',
                'sale_no',
                'sale_name',
                'sale_start_date',
                'sale_start_time',
                'sale_start_tz',
                'sale_end_date',
                'sale_end_time',
                'sale_end_tz',
                'consignor_billing_contact_type',
                'consignor_billing_firstname',
                'consignor_billing_lastname',
                'consignor_billing_company',
                'consignor_billing_phone',
                'consignor_billing_fax',
                'consignor_billing_address',
                'consignor_billing_address2',
                'consignor_billing_address3',
                'consignor_billing_city',
                'consignor_billing_country',
                'consignor_billing_state',
                'consignor_billing_zip',
                'consignor_shipping_contact_type',
                'consignor_shipping_firstname',
                'consignor_shipping_lastname',
                'consignor_shipping_company',
                'consignor_shipping_phone',
                'consignor_shipping_fax',
                'consignor_shipping_address',
                'consignor_shipping_address2',
                'consignor_shipping_address3',
                'consignor_shipping_city',
                'consignor_shipping_country',
                'consignor_shipping_state',
                'consignor_shipping_zip',
            ],
            Constants\EmailKey::CONSIGNOR_REPORT_2 => [
                'date',
                'date_tz',
                'consignor_username',
                'consignor_custno',
                'consignor_firstname',
                'consignor_lastname',
                'sale_id',
                'sale_no',
                'sale_name',
                'lot_status',
                'sale_start_date',
                'sale_start_time',
                'sale_start_tz',
                'sale_end_date',
                'sale_end_time',
                'sale_end_tz',
                'consignor_billing_contact_type',
                'consignor_billing_firstname',
                'consignor_billing_lastname',
                'consignor_billing_company',
                'consignor_billing_phone',
                'consignor_billing_fax',
                'consignor_billing_address',
                'consignor_billing_address2',
                'consignor_billing_address3',
                'consignor_billing_city',
                'consignor_billing_country',
                'consignor_billing_state',
                'consignor_billing_zip',
                'consignor_shipping_contact_type',
                'consignor_shipping_firstname',
                'consignor_shipping_lastname',
                'consignor_shipping_company',
                'consignor_shipping_phone',
                'consignor_shipping_fax',
                'consignor_shipping_address',
                'consignor_shipping_address2',
                'consignor_shipping_address3',
                'consignor_shipping_city',
                'consignor_shipping_country',
                'consignor_shipping_state',
                'consignor_shipping_zip',
            ],
            Constants\EmailKey::PICKUP_REMINDER => [
                'first_name',
                'last_name',
                'username',
                'reset_password_url',
                'invoice_total',
                'invoice_balance',
                'invoice_number',
                'invoice_html',
                'invoice_url',
                'currency',
                'lots_number',
                'lot_names',
                'item_numbers'
            ],
            Constants\EmailKey::PAYMENT_REMINDER => [
                'first_name',
                'last_name',
                'username',
                'reset_password_url',
                'invoice_total',
                'invoice_balance',
                'invoice_number',
                'invoice_html',
                'invoice_url',
                'currency'
            ],
            Constants\EmailKey::RESELLER_CERT_RENEWAL => [
                'first_name',
                'last_name',
                'profile_url'
            ],
            default => [],
        };
    }

    /**
     * Check if repeater block is used in e-mail template
     *
     * @param string $emailKey
     * @return bool
     */
    public function hasRepeaterPlaceholders(string $emailKey): bool
    {
        return in_array($emailKey, [Constants\EmailKey::CONSIGNOR_REPORT, Constants\EmailKey::CONSIGNOR_REPORT_2], true);
    }

    /**
     * Return placeholders, which are used in repeater block
     *
     * @param string $emailKey
     * @return array
     */
    public function getRepeaterPlaceholders(string $emailKey): array
    {
        $placeholders = [];
        switch ($emailKey) {
            case Constants\EmailKey::CONSIGNOR_REPORT:    // SAM-1521
                $placeholders = [
                    'lot_num',
                    'lot_name',
                    'description',
                    'item_num',
                    'item_id',
                    'sale_id',
                    'low_est',
                    'high_est',
                    'reserve',
                    'start_bid',
                    'hammer_price',
                    'lot_status',
                    'currency',
                    'consignor_commission'
                ];
                $placeholders = array_merge($placeholders, $this->getLotCustomFieldVariables());
                break;
            case Constants\EmailKey::CONSIGNOR_REPORT_2:
                $placeholders = [
                    'lot_num',
                    'lot_name',
                    'description',
                    'item_num',
                    'item_id',
                    'sale_id',
                    'low_est',
                    'high_est',
                    'reserve',
                    'start_bid',
                    'hammer_price',
                    'lot_status',
                    'currency',
                    'consignor_commission'
                ];
                $placeholders = array_merge($placeholders, $this->getLotCustomFieldVariables());
                break;
        }
        return $placeholders;
    }

    /**
     * @return array of lot item custom field placeholders
     */
    private function getLotCustomFieldVariables(): array
    {
        $lotCustomFieldPlaceholders = [];
        $lotCustomFields = $this->createLotCustomFieldLoader()->loadAll(true);
        $dbTransformer = DbTextTransformer::new();
        foreach ($lotCustomFields as $lotCustomField) {
            $lotCustomFieldPlaceholders[] = sprintf('custom_field_%s', $dbTransformer->toDbColumn($lotCustomField->Name));
        }
        return $lotCustomFieldPlaceholders;
    }
}
