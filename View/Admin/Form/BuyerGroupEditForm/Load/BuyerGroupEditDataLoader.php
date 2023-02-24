<?php
/**
 * Buyer Group Edit Data Loader
 *
 * SAM-5945: Refactor buyer group edit page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 24, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyerGroupEditForm\Load;

use Sam\Core\Constants;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BuyerGroupUser\BuyerGroupUserReadRepository;
use Sam\Storage\ReadRepository\Entity\BuyerGroupUser\BuyerGroupUserReadRepositoryCreateTrait;
use Sam\View\Admin\Form\BuyerGroupEditForm\BuyerGroupEditConstants;

/**
 * Class BuyerGroupEditDataLoader
 */
class BuyerGroupEditDataLoader extends CustomizableClass
{
    use BuyerGroupUserReadRepositoryCreateTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    protected ?int $filterBuyerGroupId = null;
    protected string $filterSearchKey = '';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return count of buyer group users
     * @return int
     */
    public function count(): int
    {
        $buyerGroupRepository = $this->prepareBuyerGroupUserRepository();
        return $buyerGroupRepository->count();
    }

    /**
     * Return array of buyer group users
     * @return array
     */
    public function load(): array
    {
        $buyerGroupUserRepository = $this->prepareBuyerGroupUserRepository();

        if ($this->getLimit()) {
            $buyerGroupUserRepository
                ->offset($this->getOffset())
                ->limit($this->getLimit());
        }

        $isAscending = $this->isAscendingOrder();
        $sortColumn = $this->getSortColumn();
        if ($sortColumn === BuyerGroupEditConstants::ORD_BY_DEFAULT) {
            $buyerGroupUserRepository->orderById($isAscending);
        } elseif ($sortColumn === BuyerGroupEditConstants::ORD_BY_USERNAME) {
            $buyerGroupUserRepository->innerJoinUserOrderByUsername($isAscending);
        } elseif ($sortColumn === BuyerGroupEditConstants::ORD_BY_EMAIL) {
            $buyerGroupUserRepository->innerJoinUserOrderByEmail($isAscending);
        } elseif ($sortColumn === BuyerGroupEditConstants::ORD_BY_FIRST_NAME) {
            $buyerGroupUserRepository->joinUserInfoOrderByFirstName($isAscending);
        } elseif ($sortColumn === BuyerGroupEditConstants::ORD_BY_LAST_NAME) {
            $buyerGroupUserRepository->joinUserInfoOrderByLastName($isAscending);
        } elseif ($sortColumn === BuyerGroupEditConstants::ORD_BY_ADDED_ON) {
            $buyerGroupUserRepository->orderByAddedOn($isAscending);
        }

        $dtos = [];
        foreach ($buyerGroupUserRepository->loadRows() as $row) {
            $dtos[] = BuyerGroupEditDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @param int $id
     * @return static
     */
    public function filterBuyerGroupId(int $id): static
    {
        $this->filterBuyerGroupId = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getFilterBuyerGroupId(): int
    {
        return $this->filterBuyerGroupId;
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
     * @return BuyerGroupUserReadRepository
     */
    private function prepareBuyerGroupUserRepository(): BuyerGroupUserReadRepository
    {
        $repo = $this->createBuyerGroupUserReadRepository()
            ->filterActive(true)
            ->filterBuyerGroupId($this->getFilterBuyerGroupId())
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->joinUserInfo()
            ->select(
                [
                    'u.`id` AS user_id',
                    'u.`username` AS username',
                    'u.`email` AS email',
                    'ui.`first_name` AS first_name',
                    'ui.`last_name` AS last_name',
                    'bgu.`id` AS id',
                    'bgu.`added_on` AS added_on',
                ]
            );

        $key = $this->getFilterSearchKey();
        if ($key !== '') {
            $keys = preg_split("/[\s,]+/", $key);
            $searchExpr = "%{$key}%";
            if (count($keys) === 2) {
                $repo->joinUserInfoLikeFirstLastName($searchExpr);
            } else {
                $repo->joinUserLikeUsername($searchExpr)
                    ->joinUserLikeEmail($searchExpr)
                    ->joinUserInfoLikeFirstName($searchExpr)
                    ->joinUserInfoLikeLastName($searchExpr);
            }
        }

        return $repo;
    }
}
