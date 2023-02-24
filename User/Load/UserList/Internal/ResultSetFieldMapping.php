<?php
/**
 * SAM-2776: Optimize user csv export
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Load\UserList\Internal;

use InvalidArgumentException;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserList\UserListDatasource;

/**
 * Class ResultSetFieldMapping
 * @package Sam\User\Load\UserList\Internal
 */
class ResultSetFieldMapping extends CustomizableClass
{
    protected const MAP = [
        'user_id' => [
            'select' => 'u.id',
        ],
        'customer_no' => [
            'select' => 'u.customer_no',
        ],
        'username' => [
            'select' => 'u.username',
        ],
        'email' => [
            'select' => 'u.email',
        ],
        'log_date' => [
            'select' => 'u.log_date',
        ],
        'created_on' => [
            'select' => 'u.created_on',
        ],
        'flag' => [
            'select' => 'u.flag',
        ],
        'account_id' => [
            'select' => 'u.account_id'
        ],
        'use_permanent_bidderno' => [
            'select' => 'u.use_permanent_bidderno',
        ],
        'account_name' => [
            'select' => 'acc.name',
            'join' => [UserListDatasource::JOIN_ACCOUNT],
        ],
        'first_name' => [
            'select' => 'ui.first_name',
            'join' => [UserListDatasource::JOIN_USER_INFO],
        ],
        'last_name' => [
            'select' => 'ui.last_name',
            'join' => [UserListDatasource::JOIN_USER_INFO],
        ],
        'company_name' => [
            'select' => 'ui.company_name',
            'join' => [UserListDatasource::JOIN_USER_INFO],
        ],
        'reseller_cert_file' => [
            'select' => 'ui.reseller_cert_file',
            'join' => [UserListDatasource::JOIN_USER_INFO],
        ],
        'reseller_cert_expiration' => [
            'select' => 'ui.reseller_cert_expiration',
            'join' => [UserListDatasource::JOIN_USER_INFO],
        ],
        'reseller_cert_approved' => [
            'select' => 'ui.reseller_cert_approved',
            'join' => [UserListDatasource::JOIN_USER_INFO],
        ],
        'sales_tax' => [
            'select' => 'ui.sales_tax',
            'join' => [UserListDatasource::JOIN_USER_INFO]
        ],
        'tax_application' => [
            'select' => 'ui.tax_application',
            'join' => [UserListDatasource::JOIN_USER_INFO]
        ],
        'note' => [
            'select' => 'ui.note',
            'join' => [UserListDatasource::JOIN_USER_INFO]
        ],
        'phone' => [
            'select' => 'ui.phone',
            'join' => [UserListDatasource::JOIN_USER_INFO]
        ],
        'phone_type' => [
            'select' => 'ui.phone_type',
            'join' => [UserListDatasource::JOIN_USER_INFO]
        ],
        'send_text_alerts' => [
            'select' => 'ui.send_text_alerts',
            'join' => [UserListDatasource::JOIN_USER_INFO]
        ],
        'news_letter' => [
            'select' => 'ui.news_letter',
            'join' => [UserListDatasource::JOIN_USER_INFO]
        ],
        'referrer' => [
            'select' => 'ui.referrer',
            'join' => [UserListDatasource::JOIN_USER_INFO]
        ],
        'referrer_host' => [
            'select' => 'ui.referrer_host',
            'join' => [UserListDatasource::JOIN_USER_INFO]
        ],
        'identification_type' => [
            'select' => 'ui.identification_type',
            'join' => [UserListDatasource::JOIN_USER_INFO]
        ],
        'identification' => [
            'select' => 'ui.identification',
            'join' => [UserListDatasource::JOIN_USER_INFO]
        ],
        'location_name' => [
            'select' => '(SELECT loc.name FROM location loc WHERE loc.id = ui.location_id)',
            'join' => [UserListDatasource::JOIN_USER_INFO]
        ],
        'sysacc_flag' => [
            'select' => 'ua_by_sysacc.flag',
            'join' => [UserListDatasource::JOIN_USER_ACCOUNT_BY_SYSTEM_ACCOUNT]
        ],
        'verified' => [
            'select' => 'IF(uau.email_verified, 1, 0)',
            'join' => [UserListDatasource::JOIN_USER_AUTHENTICATION]
        ],
        'ub_contact_type' => [
            'select' => 'ub.contact_type',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_company_name' => [
            'select' => 'ub.company_name',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_first_name' => [
            'select' => 'ub.first_name',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_last_name' => [
            'select' => 'ub.last_name',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_phone' => [
            'select' => 'ub.phone',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_fax' => [
            'select' => 'ub.fax',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_country' => [
            'select' => 'ub.country',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_address' => [
            'select' => 'ub.address',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_address2' => [
            'select' => 'ub.address2',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_address3' => [
            'select' => 'ub.address3',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_city' => [
            'select' => 'ub.city',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_state' => [
            'select' => 'ub.state',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_zip' => [
            'select' => 'ub.zip',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_cc_type' => [
            'select' => 'ub.cc_type',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_cc_number' => [
            'select' => 'ub.cc_number',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_cc_exp_date' => [
            'select' => 'ub.cc_exp_date',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_bank_account_number' => [
            'select' => 'ub.bank_account_number',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_bank_account_type' => [
            'select' => 'ub.bank_account_type',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_bank_name' => [
            'select' => 'ub.bank_name',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_bank_account_name' => [
            'select' => 'ub.bank_account_name',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'ub_cc_type_name' => [
            'select' => '(SELECT cc.name FROM credit_card cc WHERE cc.id = ub.cc_type)',
            'join' => [UserListDatasource::JOIN_USER_BILLING]
        ],
        'us_contact_type' => [
            'select' => 'us.contact_type',
            'join' => [UserListDatasource::JOIN_USER_SHIPPING]
        ],
        'us_company_name' => [
            'select' => 'us.company_name',
            'join' => [UserListDatasource::JOIN_USER_SHIPPING]
        ],
        'us_first_name' => [
            'select' => 'us.first_name',
            'join' => [UserListDatasource::JOIN_USER_SHIPPING]
        ],
        'us_last_name' => [
            'select' => 'us.last_name',
            'join' => [UserListDatasource::JOIN_USER_SHIPPING]
        ],
        'us_phone' => [
            'select' => 'us.phone',
            'join' => [UserListDatasource::JOIN_USER_SHIPPING]
        ],
        'us_fax' => [
            'select' => 'us.fax',
            'join' => [UserListDatasource::JOIN_USER_SHIPPING]
        ],
        'us_country' => [
            'select' => 'us.country',
            'join' => [UserListDatasource::JOIN_USER_SHIPPING]
        ],
        'us_address' => [
            'select' => 'us.address',
            'join' => [UserListDatasource::JOIN_USER_SHIPPING]
        ],
        'us_address2' => [
            'select' => 'us.address2',
            'join' => [UserListDatasource::JOIN_USER_SHIPPING]
        ],
        'us_address3' => [
            'select' => 'us.address3',
            'join' => [UserListDatasource::JOIN_USER_SHIPPING]
        ],
        'us_city' => [
            'select' => 'us.city',
            'join' => [UserListDatasource::JOIN_USER_SHIPPING]
        ],
        'us_state' => [
            'select' => 'us.state',
            'join' => [UserListDatasource::JOIN_USER_SHIPPING]
        ],
        'us_zip' => [
            'select' => 'us.zip',
            'join' => [UserListDatasource::JOIN_USER_SHIPPING]
        ],
        'commission_id' => [
            'select' => 'uccf.commission_id',
            'join' => [UserListDatasource::JOIN_USER_CONSIGNOR_COMMISSION_FEE],
        ],
        'sold_fee_id' => [
            'select' => 'uccf.sold_fee_id',
            'join' => [UserListDatasource::JOIN_USER_CONSIGNOR_COMMISSION_FEE],
        ],
        'unsold_fee_id' => [
            'select' => 'uccf.unsold_fee_id',
            'join' => [UserListDatasource::JOIN_USER_CONSIGNOR_COMMISSION_FEE],
        ],
        'cons_commission_level' => [
            'select' => 'ccfc.level',
            'join' => [UserListDatasource::JOIN_CONSIGNOR_COMMISSION]
        ],
        'cons_commission_calculation_method' => [
            'select' => 'ccfc.calculation_method',
            'join' => [UserListDatasource::JOIN_CONSIGNOR_COMMISSION]
        ],
        'cons_sold_fee_level' => [
            'select' => 'ccfsf.level',
            'join' => [UserListDatasource::JOIN_CONSIGNOR_SOLD_FEE]
        ],
        'cons_sold_fee_calculation_method' => [
            'select' => 'ccfsf.calculation_method',
            'join' => [UserListDatasource::JOIN_CONSIGNOR_SOLD_FEE]
        ],
        'cons_sold_fee_reference' => [
            'select' => 'ccfsf.fee_reference',
            'join' => [UserListDatasource::JOIN_CONSIGNOR_SOLD_FEE]
        ],
        'cons_unsold_fee_level' => [
            'select' => 'ccfuf.level',
            'join' => [UserListDatasource::JOIN_CONSIGNOR_UNSOLD_FEE]
        ],
        'cons_unsold_fee_calculation_method' => [
            'select' => 'ccfuf.calculation_method',
            'join' => [UserListDatasource::JOIN_CONSIGNOR_UNSOLD_FEE]
        ],
        'cons_unsold_fee_reference' => [
            'select' => 'ccfuf.fee_reference',
            'join' => [UserListDatasource::JOIN_CONSIGNOR_UNSOLD_FEE]
        ],
        'admin_id' => [
            'select' => 'ad.id',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'admin_privileges' => [
            'select' => 'ad.admin_privileges',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'sales_commission_stepdown' => [
            'select' => 'ad.sales_commission_stepdown',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'manage_all_auctions' => [
            'select' => 'ad.manage_all_auctions',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'delete_auction' => [
            'select' => 'ad.delete_auction',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'archive_auction' => [
            'select' => 'ad.archive_auction',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'reset_auction' => [
            'select' => 'ad.reset_auction',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'information' => [
            'select' => 'ad.information',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'publish' => [
            'select' => 'ad.publish',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'lots' => [
            'select' => 'ad.lots',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'available_lots' => [
            'select' => 'ad.available_lots',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'bidders' => [
            'select' => 'ad.bidders',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'remaining_users' => [
            'select' => 'ad.remaining_users',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'run_live_auction' => [
            'select' => 'ad.run_live_auction',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'projector' => [
            'select' => 'ad.projector',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'bid_increments' => [
            'select' => 'ad.bid_increments',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'buyers_premium' => [
            'select' => 'ad.buyers_premium',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'permissions' => [
            'select' => 'ad.permissions',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'create_bidder' => [
            'select' => 'ad.create_bidder',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'user_passwords' => [
            'select' => 'ad.user_passwords',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'bulk_user_export' => [
            'select' => 'ad.bulk_user_export',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'user_privileges' => [
            'select' => 'ad.user_privileges',
            'join' => [UserListDatasource::JOIN_ADMIN]
        ],
        'consignor_id' => [
            'select' => 'cons.id',
            'join' => [UserListDatasource::JOIN_CONSIGNOR]
        ],
        'consignor_sales_tax' => [
            'select' => 'cons.sales_tax',
            'join' => [UserListDatasource::JOIN_CONSIGNOR]
        ],
        'buyer_tax_hp' => [
            'select' => 'cons.buyer_tax_hp',
            'join' => [UserListDatasource::JOIN_CONSIGNOR]
        ],
        'buyer_tax_bp' => [
            'select' => 'cons.buyer_tax_bp',
            'join' => [UserListDatasource::JOIN_CONSIGNOR]
        ],
        'buyer_tax_services' => [
            'select' => 'cons.buyer_tax_services',
            'join' => [UserListDatasource::JOIN_CONSIGNOR]
        ],
        'consignor_tax' => [
            'select' => 'cons.consignor_tax',
            'join' => [UserListDatasource::JOIN_CONSIGNOR]
        ],
        'consignor_tax_hp' => [
            'select' => 'cons.consignor_tax_hp',
            'join' => [UserListDatasource::JOIN_CONSIGNOR]
        ],
        'consignor_tax_hp_type' => [
            'select' => 'cons.consignor_tax_hp_type',
            'join' => [UserListDatasource::JOIN_CONSIGNOR]
        ],
        'consignor_tax_comm' => [
            'select' => 'cons.consignor_tax_comm',
            'join' => [UserListDatasource::JOIN_CONSIGNOR]
        ],
        'consignor_tax_services' => [
            'select' => 'cons.consignor_tax_services',
            'join' => [UserListDatasource::JOIN_CONSIGNOR]
        ],
        'payment_info' => [
            'select' => 'cons.payment_info',
            'join' => [UserListDatasource::JOIN_CONSIGNOR]
        ],
        'bidder_id' => [
            'select' => 'b.id',
            'join' => [UserListDatasource::JOIN_BIDDER]
        ],
        'preferred' => [
            'select' => 'b.preferred',
            'join' => [UserListDatasource::JOIN_BIDDER]
        ],
        'house' => [
            'select' => 'b.house',
            'join' => [UserListDatasource::JOIN_BIDDER]
        ],
        'agent' => [
            'select' => 'b.agent',
            'join' => [UserListDatasource::JOIN_BIDDER]
        ],
        'bp_range_calculation_live' => [
            'select' => 'b.bp_range_calculation_live',
            'join' => [UserListDatasource::JOIN_BIDDER]
        ],
        'bp_range_calculation_timed' => [
            'select' => 'b.bp_range_calculation_timed',
            'join' => [UserListDatasource::JOIN_BIDDER]
        ],
        'bp_range_calculation_hybrid' => [
            'select' => 'b.bp_range_calculation_hybrid',
            'join' => [UserListDatasource::JOIN_BIDDER]
        ],
        'additional_bp_internet_live' => [
            'select' => 'b.additional_bp_internet_live',
            'join' => [UserListDatasource::JOIN_BIDDER]
        ],
        'additional_bp_internet_hybrid' => [
            'select' => 'b.additional_bp_internet_hybrid',
            'join' => [UserListDatasource::JOIN_BIDDER]
        ]
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getFieldMapping(string $field): array
    {
        if (!array_key_exists($field, self::MAP)) {
            throw new InvalidArgumentException("Mapping for field '{$field}' not found");
        }
        return self::MAP[$field];
    }
}
