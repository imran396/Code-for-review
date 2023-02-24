<?php
/**
 * SAM-6928: Sales staff user assignment and filtering control adjustments at the "User Edit" and the "Sales Report" pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @since           02-03, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\AddedBy\Autocomplete\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;
use Sam\User\AddedBy\Common\Repository\UserRepositoryForSalesStaffFactory;

/**
 * Class UserAddedByDataLoader
 * @package Sam\User\AddedBy\Internal\Load
 */
class UserAddedByDataLoader extends CustomizableClass
{
    /**
     * Fields required for UserAddedByDto construction
     * @var string[]
     */
    private const LEGITIMATE_AGENT_SELECT_FIELDS = [
        'u.id',
        'u.username',
        'ui.first_name',
        'ui.last_name',
        'true AS ' . UserReadRepository::ALIAS_IS_SALES_STAFF,
    ];

    private const ANY_USER_SELECT_FIELDS = [
        'u.id',
        'u.username',
        'ui.first_name',
        'ui.last_name',
        UserReadRepository::SELECT_IS_SALES_STAFF . ' AS ' . UserReadRepository::ALIAS_IS_SALES_STAFF,
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $searchTerm
     * @param int[] $filterAccountIds
     * @param bool $isReadOnlyDb
     * @return UserAddedByDto[]
     */
    public function load(string $searchTerm, array $filterAccountIds, bool $isReadOnlyDb = false): array
    {
        $userRepository = UserRepositoryForSalesStaffFactory::new()
            ->create($isReadOnlyDb)
            ->filterAccountId($filterAccountIds)
            ->joinUserInfoLikeFirstName("%{$searchTerm}%")
            ->joinUserInfoLikeLastName("%{$searchTerm}%")
            ->likeUsername("%{$searchTerm}%")
            ->orderByUsername()
            ->select(self::LEGITIMATE_AGENT_SELECT_FIELDS);
        $dtos = [];
        $rows = $userRepository->loadRows();
        foreach ($rows as $row) {
            $dtos[] = UserAddedByDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * Loads only legitimate sales staff agent data. I.e. not soft-deleted, with "Sales Staff" privilege.
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return UserAddedByDto|null
     */
    public function loadByLegitimateSalesStaffUserId(int $userId, bool $isReadOnlyDb = false): ?UserAddedByDto
    {
        $userRepository = UserRepositoryForSalesStaffFactory::new()
            ->create($isReadOnlyDb)
            ->joinUserInfo()
            ->filterId($userId)
            ->select(self::LEGITIMATE_AGENT_SELECT_FIELDS);
        $row = $userRepository->loadRow();
        $dto = $row ? UserAddedByDto::new()->fromDbRow($row) : null;
        return $dto;
    }

    /**
     * Loads DTO for any user. I.e. without "Sales staff" privilege or soft-deleted.
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return UserAddedByDto|null
     */
    public function loadByAnyUserId(int $userId, bool $isReadOnlyDb = false): ?UserAddedByDto
    {
        $userRepository = UserReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinUserInfo()
            ->filterId($userId)
            ->select(self::ANY_USER_SELECT_FIELDS);
        $row = $userRepository->loadRow();
        $dto = $row ? UserAddedByDto::new()->fromDbRow($row) : null;
        return $dto;
    }
}
