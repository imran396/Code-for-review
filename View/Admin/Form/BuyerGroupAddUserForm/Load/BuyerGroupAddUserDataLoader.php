<?php
/**
 * Buyer Group Add User Data Loader
 *
 * SAM-5938: Refactor buyer group add user page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 23, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyerGroupAddUserForm\Load;

use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;
use Sam\View\Admin\Form\BuyerGroupAddUserForm\BuyerGroupAddUserConstants;

/**
 * Class BuyerGroupAddUserDataLoader
 */
class BuyerGroupAddUserDataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;
    use UserReadRepositoryCreateTrait;

    protected string $filterSearchKey = '';
    protected string $sortOrderDefaultIndex = BuyerGroupAddUserConstants::ORD_BY_DEFAULT;
    protected ?int $filterBuyerGroupId = null;

    /** @var string[][] */
    protected array $orderFieldsMapping = [
        BuyerGroupAddUserConstants::ORD_BY_USERNAME => [
            'asc' => 'username ASC',
            'desc' => 'username DESC',
        ],
        BuyerGroupAddUserConstants::ORD_BY_EMAIL => [
            'asc' => 'email ASC',
            'desc' => 'email DESC',
        ],
        BuyerGroupAddUserConstants::ORD_BY_FIRST_NAME => [
            'asc' => 'first_name ASC',
            'desc' => 'first_name DESC',
        ],
        BuyerGroupAddUserConstants::ORD_BY_LAST_NAME => [
            'asc' => 'last_name ASC',
            'desc' => 'last_name DESC',
        ],
        BuyerGroupAddUserConstants::ORD_BY_DEFAULT => [
            'asc' => 'bgu.id ASC',
            'desc' => 'bgu.id DESC',
        ],
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return count of buyer group add users
     * @return int
     */
    public function count(): int
    {
        $userRepository = $this->prepareUserRepository();
        return $userRepository->count();
    }

    /**
     * Return array of buyer group add users
     * @return BuyerGroupAddUserDto[]
     */
    public function load(): array
    {
        $userRepository = $this->prepareUserRepository();

        if ($this->getLimit()) {
            $userRepository
                ->offset($this->getOffset())
                ->limit($this->getLimit());
        }

        $isAscending = $this->isAscendingOrder();
        $sortColumn = $this->getSortColumn();
        if ($sortColumn === BuyerGroupAddUserConstants::ORD_BY_USERNAME) {
            $userRepository->orderByUsername($isAscending);
        } elseif ($sortColumn === BuyerGroupAddUserConstants::ORD_BY_EMAIL) {
            $userRepository->orderByEmail($isAscending);
        } elseif ($sortColumn === BuyerGroupAddUserConstants::ORD_BY_FIRST_NAME) {
            $userRepository->orderByUserInfoFirstFame($isAscending);
        } elseif ($sortColumn === BuyerGroupAddUserConstants::ORD_BY_LAST_NAME) {
            $userRepository->orderByUserInfoLastName($isAscending);
        } elseif ($sortColumn === BuyerGroupAddUserConstants::ORD_BY_DEFAULT) {
            $userRepository->joinBuyerGroupUserOrderById($isAscending);
        }

        $dtos = [];
        foreach ($userRepository->loadRows() as $row) {
            $dtos[] = BuyerGroupAddUserDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @param string $key
     * @return static
     */
    public function filterSearchKey(string $key): static
    {
        $this->filterSearchKey = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilterSearchKey(): string
    {
        return $this->filterSearchKey;
    }

    /**
     * @return int
     */
    public function getFilterBuyerGroupId(): int
    {
        return $this->filterBuyerGroupId;
    }

    /**
     * @param int $filterBuyerGroupId
     * @return $this
     */
    public function filterBuyerGroupId(int $filterBuyerGroupId): static
    {
        $this->filterBuyerGroupId = $filterBuyerGroupId;
        return $this;
    }

    /**
     * Prepare conditions for fetching active users, who are not assigned in any buyer group yet.
     * @return UserReadRepository
     */
    private function prepareUserRepository(): UserReadRepository
    {
        /**
         * LEFT JOIN with conditions gives us possibility to join all `user` records,
         * and then fetch results (with help of filtering by `null`) that don't match required condition or don't present in `buyers_group_user`
         */
        $repo = $this->createUserReadRepository()
            ->joinUserInfo()
            ->extendJoinCondition('buyer_group_user', 'AND bgu.buyer_group_id = ' . $this->escape($this->getFilterBuyerGroupId()) . ' AND bgu.active = 1')
            ->joinBuyerGroupUserFilterId(null)
            ->filterUserStatusId(Constants\User::US_ACTIVE)
            ->select(
                [
                    'u.`id` AS id',
                    'u.`username` AS username',
                    'u.`email` AS email',
                    'ui.`first_name` AS first_name',
                    'ui.`last_name` AS last_name',
                ]
            );

        $key = $this->getFilterSearchKey();
        if ($key !== '') {
            $keys = preg_split('/[\s,]+/', $key);
            $searchExpr = "%{$key}%";
            if (count($keys) === 2) {
                $repo->joinUserInfoLikeFirstLastName($searchExpr);
            } else {
                foreach ($keys as $key) {
                    $searchExpr = "%{$key}%";
                    $repo->likeUsername($searchExpr)
                        ->likeEmail($searchExpr)
                        ->joinUserInfoLikeFirstName($searchExpr)
                        ->joinUserInfoLikeLastName($searchExpr);
                }
            }
        }
        return $repo;
    }
}
