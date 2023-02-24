<?php
/**
 * SAM-6928: Sales staff user assignment and filtering control adjustments at the "User Edit" and the "Sales Report" pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           09-22, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SaleStaff\Autocomplete\Load;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;

/**
 * Class SalesStaffUserLoader
 * @package Sam\Report\SaleStaff\Autocomplete
 */
class SalesStaffUserDataLoader extends CustomizableClass
{
    use UserReadRepositoryCreateTrait;

    /**
     * Fields required for current class construction
     * @var string[]
     */
    private const LEGITIMATE_SALES_STAFF_SELECT_FIELDS = [
        'u.id',
        'u.username',
        'ui.first_name',
        'ui.last_name',
    ];

    /**
     * @var string[]
     */
    private const OTHER_SALES_STAFF_SELECT_FIELDS = [
        'u.added_by'
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadAll(?int $accountId, string $searchTerm = '', $isReadOnlyDb = true): array
    {
        $userRows = $this->loadAllSalesStaff($accountId, $searchTerm, $isReadOnlyDb);

        $userIds = ArrayCast::arrayColumnInt($userRows, 'id');
        $rows = $this->loadAllOtherSalesStaff($accountId, $searchTerm, $userIds, $isReadOnlyDb);

        $userRows = array_merge($userRows, $rows);
        $userRows = array_unique($userRows, SORT_REGULAR);
        $usernames = array_column($userRows, 'username');
        array_multisort($usernames, SORT_ASC, $userRows);
        return $userRows;
    }

    public function loadSingleByUserId(?int $userId, bool $isReadOnlyDb): array
    {
        $row = $this->prepareUserRepository(null, '', $isReadOnlyDb)
            ->filterId([$userId])
            ->loadRow();
        return $row;
    }

    /**
     * @param int|null $accountId null for all accounts
     * @param string $searchTerm
     * @param bool $isReadOnlyDb
     * @return array
     */
    protected function loadAllSalesStaff(
        ?int $accountId = null,
        string $searchTerm = '',
        bool $isReadOnlyDb = true
    ): array {
        $userRepository = $this->prepareUserRepository($accountId, $searchTerm, $isReadOnlyDb)
            ->filterSubquerySalesStaffGreater(0);
        $rows = $userRepository->loadRows();
        return $rows;
    }

    /**
     * @param int|null $accountId null for all accounts
     * @param string $searchTerm
     * @param array $skipUserIds
     * @param bool $isReadOnlyDb
     * @return array
     */
    protected function loadAllOtherSalesStaff(
        ?int $accountId,
        string $searchTerm,
        array $skipUserIds,
        bool $isReadOnlyDb = true
    ): array {
        $userRepository = $this->prepareUserRepository($accountId, '', $isReadOnlyDb, self::OTHER_SALES_STAFF_SELECT_FIELDS)
            ->skipAddedBy($skipUserIds)
            ->inlineCondition('u.added_by IS NOT NULL')
            ->enableDistinct(true);
        $rows = $userRepository->loadRows();

        $addedByUserIds = ArrayCast::arrayColumnInt($rows, 'added_by');
        $userRepositoryFinal = $this->prepareUserRepository($accountId, $searchTerm, $isReadOnlyDb)
            ->filterId($addedByUserIds)
            ->filterSubquerySalesStaffGreater(0);
        $rows = $userRepositoryFinal->loadRows();
        return $rows;
    }

    /**
     * @param int|null $accountId null for all accounts
     * @param string $searchTerm
     * @param bool $isReadOnlyDb
     * @param array|string[] $select
     * @return UserReadRepository
     */
    protected function prepareUserRepository(
        ?int $accountId = null,
        string $searchTerm = '',
        bool $isReadOnlyDb = true,
        array $select = self::LEGITIMATE_SALES_STAFF_SELECT_FIELDS
    ): UserReadRepository {
        $userRepository = $this->createUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinUserInfo()
            ->filterUserStatusId(Constants\User::US_ACTIVE);
        if ($accountId) {
            $userRepository->filterAccountId($accountId);
        }
        if ($searchTerm) {
            $userRepository
                ->joinUserInfoLikeFirstName("%{$searchTerm}%")
                ->joinUserInfoLikeLastName("%{$searchTerm}%")
                ->likeUsername("%{$searchTerm}%");
        }
        if ($select) {
            $userRepository->select($select);
        }

        return $userRepository;
    }
}
