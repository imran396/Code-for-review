<?php
/**
 * SAM-4641: Refactor lot "All data CSV" report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/1/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\LotList;

use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\UserBilling\UserBillingReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Report\Lot\LotList
 */
class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use SystemAccountAwareTrait;
    use UserBillingReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * get user billing and shipping information  by user id.
     *
     * @param int $userId user.id
     * @param bool $isCcInfo
     * @return array
     */
    public function loadUserBillingShippingById(int $userId, bool $isCcInfo = true): array
    {
        $repo = $this->createUserBillingReadRepository()
            ->enableReadOnlyDb(true)
            ->joinUserShipping()
            ->filterUserId($userId);
        $select = [
            'us.company_name as ship_com_name',
            'us.first_name as ship_first_name',
            'us.last_name as ship_last_name',
            'us.phone as ship_phone',
            'us.fax as ship_fax',
            'us.country as ship_country',
            'us.address as ship_address',
            'us.address2 as ship_address_ln2',
            'us.address3 as ship_address_ln3',
            'us.city as ship_city',
            'us.state as ship_state',
            'us.zip as ship_zip',
            'us.contact_type as ship_con_type',
            'ub.company_name as bill_com_name',
            'ub.first_name as bill_first_name',
            'ub.last_name as bill_last_name',
            'ub.phone as bill_phone',
            'ub.fax as bill_fax',
            'ub.country as bill_country',
            'ub.address as bill_address',
            'ub.address2 as bill_address_ln2',
            'ub.address3 as bill_address_ln3',
            'ub.city as bill_city',
            'ub.state as bill_state',
            'ub.zip as bill_zip',
            'ub.contact_type as bill_con_type',
        ];
        if ($isCcInfo) {
            $select = array_merge(
                $select,
                [
                    'ub.cc_type as bill_cc_type',
                    'ub.cc_number as bill_cc_number',
                    'ub.cc_exp_date as bill_exp_date',
                    'ub.use_card as bill_use_card',
                    'ub.bank_routing_number as bill_bank_routing_num',
                    'ub.bank_account_number as bill_account_number',
                    'ub.bank_account_type as bill_bank_account',
                    'ub.bank_name as bill_bank_name',
                    'ub.bank_account_name as bill_bank_account_name',
                ]
            );
        }

        $rows = $repo
            ->select($select)
            ->loadRow();
        return $rows;
    }
}
