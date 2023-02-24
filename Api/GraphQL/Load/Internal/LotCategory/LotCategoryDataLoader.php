<?php
/**
 * SAM-10844: Extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\LotCategory;

use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotCategory\LotCategoryReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCategory\LotItemCategoryReadRepositoryCreateTrait;

/**
 * Class LotCategoryDataLoader
 * @package Sam\Api\GraphQL\Load\Internal\LotCategory
 */
class LotCategoryDataLoader extends CustomizableClass
{
    use LotCategoryReadRepositoryCreateTrait;
    use LotItemCategoryReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function load(array $ids, array $fields, bool $isReadOnlyDb = false): array
    {
        if (!in_array('id', $fields, true)) {
            $fields[] = 'id';
        }
        $rows = $this->createLotCategoryReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($ids)
            ->filterActive(true)
            ->select($fields)
            ->loadRows();
        return ArrayHelper::produceIndexedArray($rows, 'id');
    }

    public function loadCategoryIdsForLotItem(array $lotItemIds, bool $isReadOnlyDb = false): array
    {
        $rows = $this->createLotItemCategoryReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemId($lotItemIds)
            ->select(['lot_item_id', 'lot_category_id'])
            ->joinLotCategoryFilterActive(true)
            ->loadRows();

        $result = [];
        foreach ($rows as $row) {
            $result[$row['lot_item_id']][] = $row['lot_category_id'];
        }
        return $result;
    }
}
