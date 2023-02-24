<?php
/**
 * SAM-5274: Refactor user "CSV export"
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-07-23
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\User\UserList\Base;

use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\User\Load\UserList\UserListDatasource;
use Sam\User\Load\UserList\UserListDatasourceCreateTrait;

/**
 * Class DataLoader
 */
class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterDatePeriodAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;
    use UserListDatasourceCreateTrait;

    /**
     * We load data by portions of $chunkSize row count
     */
    protected ?int $chunkSize = null;
    /**
     * Offset value increased in data loading method
     */
    protected int $offset = 0;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * We build query for all data fetching by portion way
     * @return static
     */
    public function construct(): static
    {
        return $this;
    }

    /**
     * Return value of chunkSize property
     * @return int|null
     */
    protected function getChunkSize(): ?int
    {
        return $this->chunkSize;
    }

    /**
     * Define chunkSize value and normalize integer value
     * @param int $chunkSize
     * @return static
     */
    public function setChunkSize(int $chunkSize): static
    {
        $this->chunkSize = $chunkSize;
        return $this;
    }

    /**
     * Load data by portions with row count defined by $chunkSize property
     * @return array[]|null
     */
    public function loadNextChunk(): ?array
    {
        $rows = $this->constructDatasource($this->offset)->getResults();
        $this->offset += count($rows);
        return $rows;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $total = $this->constructDatasource()->getCount();
        return $total;
    }

    protected function constructDatasource(int $offset = 0): UserListDatasource
    {
        $fields = [
            'account_id',
            'additional_bp_internet_hybrid',
            'additional_bp_internet_live',
            'admin_id',
            'admin_privileges',
            'agent',
            'archive_auction',
            'available_lots',
            'bid_increments',
            'bidder_id',
            'bidders',
            'bp_range_calculation_hybrid',
            'bp_range_calculation_live',
            'bp_range_calculation_timed',
            'bulk_user_export',
            'buyer_tax_bp',
            'buyer_tax_hp',
            'buyer_tax_services',
            'buyers_premium',
            'commission_id',
            'company_name',
            'cons_commission_calculation_method',
            'cons_commission_level',
            'cons_sold_fee_calculation_method',
            'cons_sold_fee_level',
            'cons_sold_fee_reference',
            'cons_unsold_fee_calculation_method',
            'cons_unsold_fee_level',
            'cons_unsold_fee_reference',
            'consignor_id',
            'consignor_sales_tax',
            'consignor_tax',
            'consignor_tax_comm',
            'consignor_tax_hp',
            'consignor_tax_hp_type',
            'consignor_tax_services',
            'create_bidder',
            'created_on',
            'customer_no',
            'delete_auction',
            'email',
            'first_name',
            'flag',
            'house',
            'identification',
            'identification_type',
            'information',
            'last_name',
            'location_name',
            'log_date',
            'lots',
            'manage_all_auctions',
            'news_letter',
            'note',
            'payment_info',
            'permissions',
            'phone',
            'phone_type',
            'preferred',
            'projector',
            'publish',
            'referrer',
            'referrer_host',
            'remaining_users',
            'reset_auction',
            'run_live_auction',
            'sales_commission_stepdown',
            'sales_tax',
            'send_text_alerts',
            'sold_fee_id',
            'tax_application',
            'ub_address',
            'ub_address2',
            'ub_address3',
            'ub_bank_account_name',
            'ub_bank_account_number',
            'ub_bank_account_type',
            'ub_bank_name',
            'ub_cc_exp_date',
            'ub_cc_number',
            'ub_cc_type',
            'ub_cc_type_name',
            'ub_city',
            'ub_company_name',
            'ub_contact_type',
            'ub_country',
            'ub_fax',
            'ub_first_name',
            'ub_last_name',
            'ub_phone',
            'ub_state',
            'ub_zip',
            'unsold_fee_id',
            'us_address',
            'us_address2',
            'us_address3',
            'us_city',
            'us_company_name',
            'us_contact_type',
            'us_country',
            'us_fax',
            'us_first_name',
            'us_last_name',
            'us_phone',
            'us_state',
            'us_zip',
            'use_permanent_bidderno',
            'user_id',
            'user_passwords',
            'user_privileges',
            'username',
        ];
        $datasource = $this->createUserListDatasource()
            ->construct($fields)
            ->enableFilterDatePeriod($this->isFilterDatePeriod())
            ->filterEndDateSys($this->getFilterEndDateSys())
            ->filterStartDateSys($this->getFilterStartDateSys())
            ->setUserStatusIds([Constants\User::US_ACTIVE])
            ->setSystemAccountId($this->getSystemAccountId())
            ->setLimit($this->getChunkSize())
            ->setOffset($offset)
            ->setSortInfo($this->getSortColumn()) // not used at the moment
            ->enableSortOrderAsc($this->isAscendingOrder()); // not used at the moment
        if ($this->isPortalSystemAccount()) {
            $datasource->setAccountId($this->getSystemAccountId());
        }
        return $datasource;
    }
}
