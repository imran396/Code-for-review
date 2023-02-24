<?php
/**
 * Search custom fields duplicates
 *
 * SAM-5071: Data integrity checker - there shall only be one active user_cust_data record for one user
 * and one user_cust_field
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           11 Sep, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Validate;

use Sam\Core\Constants;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserCustData\UserCustDataReadRepository;
use Sam\Storage\ReadRepository\Entity\UserCustData\UserCustDataReadRepositoryCreateTrait;

/**
 * Class UserDataIntegrityChecker
 * @package Sam\User\Validate
 */
class UserDataIntegrityChecker extends CustomizableClass
{
    use FilterAccountAwareTrait;
    use UserCustDataReadRepositoryCreateTrait;
    use UserReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return UserCustDataReadRepository
     */
    public function prepareCustomFieldDuplicateSearch(): UserCustDataReadRepository
    {
        $repo = $this->createUserCustDataReadRepository()
            ->select(
                [
                    'ucf.name',
                    'u.id',
                    'COUNT(1) as count_records',
                    'u.account_id',
                    'acc.name as account_name',
                ]
            )
            ->joinUser()
            ->joinUserCustField()
            ->joinAccount()
            ->filterActive(true)
            ->groupByUserCustFieldId()
            ->groupByUserId()
            ->having('COUNT(1) > 1')
            ->joinUserOrderByAccountId()
            ->orderByUserId()
            ->setChunkSize(200);

        if ($this->getFilterAccountId()) {
            $repo->joinUserFilterAccountId($this->getFilterAccountId());
        }

        return $repo;
    }

    public function prepareCustomerNoDuplicateSearch(): UserReadRepository
    {
        return $this->prepareUserDuplicateSearch('customer_no')
            ->skipCustomerNo(null)
            ->groupByCustomerNo();
    }

    public function prepareEmailDuplicateSearch(): UserReadRepository
    {
        return $this->prepareUserDuplicateSearch('email')
            ->skipEmail('')
            ->groupByEmail();
    }

    public function prepareUsernameDuplicateSearch(): UserReadRepository
    {
        return $this->prepareUserDuplicateSearch('username')->groupByUsername();
    }

    protected function prepareUserDuplicateSearch(string $fieldName): UserReadRepository
    {
        $repo = $this->createUserReadRepository()
            ->select(
                [
                    $fieldName,
                    'COUNT(1) as count_records',
                    'GROUP_CONCAT(id) as ids',
                ]
            )
            ->filterUserStatusId(Constants\User::US_ACTIVE)
            ->having('COUNT(1) > 1')
            ->orderById()
            ->setChunkSize(200);

        if ($this->getFilterAccountId()) {
            $repo->filterAccountId($this->getFilterAccountId());
        }

        return $repo;
    }
}
