<?php
/**
 * Buyer Group List Data Loader
 *
 * SAM-5949: Refactor buyer group list page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 26, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyerGroupListForm\Load;

use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BuyerGroup\BuyerGroupReadRepository;
use Sam\Storage\ReadRepository\Entity\BuyerGroup\BuyerGroupReadRepositoryCreateTrait;
use Sam\View\Admin\Form\BuyerGroupListForm\BuyerGroupListConstants;

/**
 * Class BuyerGroupListDataLoader
 */
class BuyerGroupListDataLoader extends CustomizableClass
{
    use BuyerGroupReadRepositoryCreateTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return count of Buyer Group
     * @return int
     */
    public function count(): int
    {
        $buyerGroupRepository = $this->prepareBuyerGroupRepository();
        return $buyerGroupRepository->count();
    }

    /**
     * Return array of Buyer Group
     * @return array
     */
    public function load(): array
    {
        $buyerGroupRepository = $this->prepareBuyerGroupRepository();

        if ($this->getLimit()) {
            $buyerGroupRepository
                ->offset($this->getOffset())
                ->limit($this->getLimit());
        }

        $isAscending = $this->isAscendingOrder();
        switch ($this->getSortColumn()) {
            case BuyerGroupListConstants::ORD_BY_DEFAULT:
                $buyerGroupRepository->orderById($isAscending);
                break;
            case BuyerGroupListConstants::ORD_BY_NAME:
                $buyerGroupRepository->orderByName($isAscending);
                break;
            case BuyerGroupListConstants::ORD_BY_USERS:
                $buyerGroupRepository->orderByUsers($isAscending);
                break;
        }

        $dtos = [];
        foreach ($buyerGroupRepository->loadRows() as $row) {
            $dtos[] = BuyerGroupListDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @return BuyerGroupReadRepository
     */
    private function prepareBuyerGroupRepository(): BuyerGroupReadRepository
    {
        return $this->createBuyerGroupReadRepository()
            ->filterActive(true)
            ->select(
                [
                    'bg.`id`',
                    'bg.`name`',
                    'bg.`users`',
                ]
            );
    }
}
