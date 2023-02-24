<?php
/**
 * Help methods for existence checker Lot Category
 * SAM-4040: Lot Category modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 23, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\LotCategory\LotCategoryReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCategory\LotItemCategoryReadRepositoryCreateTrait;

/**
 * Class LotCategoryExistenceChecker
 * @package Sam\Lot\Category
 */
class LotCategoryExistenceChecker extends CustomizableClass
{
    use LotCategoryReadRepositoryCreateTrait;
    use LotCategoryLoaderAwareTrait;
    use LotItemCategoryReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $lotCategoryId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existById(int $lotCategoryId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createLotCategoryReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($lotCategoryId)
            ->exist();
        return $isFound;
    }

    /**
     * @param string $name
     * @param int[] $skipIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByName(string $name, array $skipIds = [], bool $isReadOnlyDb = false): bool
    {
        $name = trim($name);
        $skipIds = array_filter($skipIds);
        $isFound = $this->createLotCategoryReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterName($name)
            ->skipId($skipIds)
            ->exist();
        return $isFound;
    }

    /**
     * @param int $lotCategoryId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasDescendants(int $lotCategoryId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createLotCategoryReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterParentId($lotCategoryId)
            ->filterActive(true)
            ->exist();
        return $isFound;
    }

    /**
     * @param int $lotCategoryId
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existForLot(int $lotCategoryId, int $lotItemId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createLotItemCategoryReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemId($lotItemId)
            ->filterLotCategoryId($lotCategoryId)
            ->exist();
        return $isFound;
    }

    /**
     * Get count of sub-categories for current category
     * @param int $lotCategoryId
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countChildren(int $lotCategoryId, bool $isReadOnlyDb = false): int
    {
        $count = $this->createLotCategoryReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterParentId($lotCategoryId)
            ->filterActive(true)
            ->count();
        return $count;
    }
}
