<?php
/**
 *
 * SAM-4751: Refactor mailing list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-15
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\MailingList\Report\Csv;


/**
 * Class QueryBuilder
 * @package Sam\Report\MailingList\Report\Csv
 */
class QueryBuilder extends \Sam\Report\MailingList\Report\Base\QueryBuilder
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get SQL Select Clause
     * @return string
     */
    protected function getSelectClause(): string
    {
        $returnFields = [
            'ul.ip_address AS ip_address',
            'location.name AS location_name',
            'u.id AS user_id',
            'u.account_id AS account_id',
            'u.username AS username',
            'u.created_on AS created_on',
            'u.email AS email',
            'u.flag AS flag',
            'u.use_permanent_bidderno AS use_permanent_bidderno',
            'u.log_date AS log_date',
            'ui.referrer AS referrer',
            'ui.referrer_host AS referrer_host',
            'ui.news_letter AS news_letter',
            'ui.company_name AS company_name',
            'ui.first_name AS first_name',
            'ui.last_name AS last_name',
            'ui.phone AS phone',
            'ui.phone_type AS phone_type',
            'ui.send_text_alerts AS send_text_alerts',
            'u.customer_no AS customer_no',
            'ui.sales_tax AS sales_tax',
            'ui.tax_application AS tax_application',
            'ui.note AS note',
            'ui.identification_type AS identification_type',
            'ui.identification AS identification',
            'ui.location_id AS location_id',
            'ub.contact_type AS billing_contact_type',
            'ub.company_name AS billing_company_name',
            'ub.first_name AS billing_first_name',
            'ub.last_name AS billing_last_name',
            'ub.phone AS billing_phone',
            'ub.fax AS billing_fax',
            'ub.country AS billing_country',
            'ub.address AS billing_address',
            'ub.address2 AS billing_address2',
            'ub.address3 AS billing_address3',
            'ub.city AS billing_city',
            'ub.state AS billing_state',
            'ub.zip AS billing_zip',
            'ub.cc_type AS cc_type',
            'ub.cc_number AS cc_number',
            'ub.cc_exp_date AS cc_exp_date',
            'ub.bank_routing_number AS bank_routing_number',
            'ub.bank_account_number AS bank_account_number',
            'ub.bank_account_type AS bank_account_type',
            'ub.bank_account_name AS bank_account_name',
            'ub.bank_name AS bank_name',
            'ub.cc_exp_date AS cc_exp_date',
            'ush.contact_type AS shipping_contact_type',
            'ush.company_name AS shipping_company',
            'ush.first_name AS shipping_first_name',
            'ush.last_name AS shipping_last_name',
            'ush.phone AS shipping_phone',
            'ush.fax AS shipping_fax',
            'ush.country AS shipping_country',
            'ush.address AS shipping_address',
            'ush.address2 AS shipping_address2',
            'ush.address3 AS shipping_address3',
            'ush.city AS shipping_city',
            'ush.state AS shipping_state',
            'ush.zip AS shipping_zip',
        ];

        $query = '';
        foreach ($returnFields as $returnField) {
            $query .= ($query ? ', ' : '');
            $query .= $returnField;
        }
        return sprintf('SELECT %s ', $query);
    }
}
