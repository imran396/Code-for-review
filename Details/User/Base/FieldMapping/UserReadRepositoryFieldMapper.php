<?php
/**
 * SAM-10136: Implement conditional logic in print check template field Payee
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\User\Base\FieldMapping;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;

/**
 * Class UserReadRepositoryFieldMapper
 * @package Sam\Details\User\Base
 */
class UserReadRepositoryFieldMapper extends CustomizableClass
{
    protected array $resultFieldMapping = [
        'user_info_company_name' => [
            'select' => 'ui.company_name',
            'join' => [Constants\Db::TBL_USER_INFO],
        ],
        'user_info_first_name' => [
            'select' => 'ui.first_name',
            'join' => [Constants\Db::TBL_USER_INFO],
        ],
        'user_info_last_name' => [
            'select' => 'ui.last_name',
            'join' => [Constants\Db::TBL_USER_INFO],
        ],
        'user_billing_address' => [
            'select' => 'ub.address',
            'join' => [Constants\Db::TBL_USER_BILLING],
        ],
        'user_billing_address_2' => [
            'select' => 'ub.address2',
            'join' => [Constants\Db::TBL_USER_BILLING],
        ],
        'user_billing_address_3' => [
            'select' => 'ub.address3',
            'join' => [Constants\Db::TBL_USER_BILLING],
        ],
        'user_billing_city' => [
            'select' => 'ub.city',
            'join' => [Constants\Db::TBL_USER_BILLING],
        ],
        'user_billing_company_name' => [
            'select' => 'ub.company_name',
            'join' => [Constants\Db::TBL_USER_BILLING],
        ],
        'user_billing_country' => [
            'select' => 'ub.country',
            'join' => [Constants\Db::TBL_USER_BILLING],
        ],
        'user_billing_first_name' => [
            'select' => 'ub.first_name',
            'join' => [Constants\Db::TBL_USER_BILLING],
        ],
        'user_billing_last_name' => [
            'select' => 'ub.last_name',
            'join' => [Constants\Db::TBL_USER_BILLING],
        ],
        'user_billing_postal_code' => [
            'select' => 'ub.zip',
            'join' => [Constants\Db::TBL_USER_BILLING],
        ],
        'user_billing_state' => [
            'select' => 'ub.state',
            'join' => [Constants\Db::TBL_USER_BILLING],
        ],
        'user_shipping_address' => [
            'select' => 'us.address',
            'join' => [Constants\Db::TBL_USER_SHIPPING],
        ],
        'user_shipping_address_2' => [
            'select' => 'us.address2',
            'join' => [Constants\Db::TBL_USER_SHIPPING],
        ],
        'user_shipping_address_3' => [
            'select' => 'us.address3',
            'join' => [Constants\Db::TBL_USER_SHIPPING],
        ],
        'user_shipping_city' => [
            'select' => 'us.city',
            'join' => [Constants\Db::TBL_USER_SHIPPING],
        ],
        'user_shipping_company_name' => [
            'select' => 'us.company_name',
            'join' => [Constants\Db::TBL_USER_SHIPPING],
        ],
        'user_shipping_country' => [
            'select' => 'us.country',
            'join' => [Constants\Db::TBL_USER_SHIPPING],
        ],
        'user_shipping_first_name' => [
            'select' => 'us.first_name',
            'join' => [Constants\Db::TBL_USER_SHIPPING],
        ],
        'user_shipping_last_name' => [
            'select' => 'us.last_name',
            'join' => [Constants\Db::TBL_USER_SHIPPING],
        ],
        'user_shipping_postal_code' => [
            'select' => 'us.zip',
            'join' => [Constants\Db::TBL_USER_SHIPPING],
        ],
        'user_shipping_state' => [
            'select' => 'us.state',
            'join' => [Constants\Db::TBL_USER_SHIPPING],
        ],
    ];

    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    protected function applyJoins(UserReadRepository $userReadRepository, array $joinTables): UserReadRepository
    {
        foreach ($joinTables as $joinTable) {
            $userReadRepository = match ($joinTable) {
                Constants\Db::TBL_USER_INFO => $userReadRepository->joinUserInfo(),
                Constants\Db::TBL_USER_SHIPPING => $userReadRepository->joinUserShipping(),
                Constants\Db::TBL_USER_BILLING => $userReadRepository->joinUserBilling(),
                default => throw new \RuntimeException("Invalid join table '{$joinTable}'"),
            };
        }
        return $userReadRepository;
    }

    /**
     * Prepare repository for result set fields
     *
     */
    public function mapToRepository(array $fields, UserReadRepository $userReadRepository): UserReadRepository
    {
        $joinTables = [];
        foreach ($fields as $field) {
            if (!array_key_exists($field, $this->resultFieldMapping)) {
                throw new \RuntimeException("Mapping for field '{$field}' not found");
            }
            $userReadRepository = $userReadRepository->addSelect($this->resultFieldMapping[$field]['select'] . ' AS ' . $field);
            $joinTables[] = $this->resultFieldMapping[$field]['join'] ?? null;
        }

        $joinTables = array_unique(array_merge(...$joinTables));
        return $this->applyJoins($userReadRepository, $joinTables);
    }
}
