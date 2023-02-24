<?php
/**
 * Lot Item Cust Fields Data Loader
 *
 * SAM-6237: Refactor Lot Custom Field List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 25, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotCustomFieldListForm\Load;

use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepositoryCreateTrait;
use Sam\View\Admin\Form\LotCustomFieldListForm\LotCustomFieldListConstants;

/**
 * Class LotCustomFieldListDataLoader
 */
class LotCustomFieldListDataLoader extends CustomizableClass
{
    use LimitInfoAwareTrait;
    use LotItemCustFieldReadRepositoryCreateTrait;
    use SortInfoAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int - return value of Lot Custom Fields count
     */
    public function count(): int
    {
        return $this->prepareLotItemCustomFieldRepository()->count();
    }

    /**
     * @return LotCustomFieldListDto[] - return values for Lot Custom Fields
     */
    public function load(): array
    {
        $repo = $this->prepareLotItemCustomFieldRepository();

        if ($this->getSortColumn() === LotCustomFieldListConstants::ORD_ORDER) {
            $repo->orderByOrder($this->isAscendingOrder());
        } else {
            $repo->orderByName($this->isAscendingOrder());
        }

        if ($this->getOffset()) {
            $repo->offset($this->getOffset());
        }

        if ($this->getLimit()) {
            $repo->limit($this->getLimit());
        }

        $dtos = [];
        foreach ($repo->loadRows() as $row) {
            $dtos[] = LotCustomFieldListDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @return LotItemCustFieldReadRepository
     */
    protected function prepareLotItemCustomFieldRepository(): LotItemCustFieldReadRepository
    {
        return $this->createLotItemCustFieldReadRepository()
            ->select(
                [
                    'id',
                    'name',
                    '`order` AS order_no',
                    'type AS field_type',
                    'in_catalog',
                    'search_field',
                    'parameters AS field_parameters',
                    'licf.access',
                ]
            )
            ->filterActive(true);
    }
}
