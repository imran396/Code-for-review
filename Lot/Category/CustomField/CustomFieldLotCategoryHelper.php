<?php
/**
 * Help methods for  Lot Item CustomField Lot Category
 * SAM-4040: Lot Category modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 15, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\CustomField;


use LotCategory;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\DeleteRepository\Entity\LotItemCustFieldLotCategory\LotItemCustFieldLotCategoryDeleteRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotCategory\LotCategoryReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustFieldLotCategory\LotItemCustFieldLotCategoryReadRepositoryCreateTrait;

/**
 * Class CustomFieldLotCategoryHelper
 * @package Sam\Lot\Category\CustomField
 */
class CustomFieldLotCategoryHelper extends CustomizableClass
{
    use LotCategoryReadRepositoryCreateTrait;
    use LotItemCustFieldLotCategoryDeleteRepositoryCreateTrait;
    use LotItemCustFieldLotCategoryReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return array of categories, which are linked with custom field
     * @param int $lotCustomFieldId
     * @param bool $isReadOnlyDb
     * @return LotCategory[]
     */
    public function loadByCustomFieldId(int $lotCustomFieldId, bool $isReadOnlyDb = false): array
    {
        $lotCategories = $this->createLotCategoryReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinLotItemCustFieldLotCategoryFilterLotItemCustFieldId($lotCustomFieldId)
            ->filterActive(true)
            ->loadEntities();
        return $lotCategories;
    }

    /**
     * Count categories, which are linked to all custom field
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countAll(bool $isReadOnlyDb = false): int
    {
        $count = $this->createLotItemCustFieldLotCategoryReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinLotCategoryFilterActive(true)
            ->count();
        return $count;
    }

    /**
     * Remove assigned categories from the lot
     *
     * @param int $lotCustomFieldId
     * @return void
     */
    public function removeAllForCustomField(int $lotCustomFieldId): void
    {
        $this->createLotItemCustFieldLotCategoryDeleteRepository()
            ->filterLotItemCustFieldId($lotCustomFieldId)
            ->delete();
    }

    /**
     * Check if category exists
     *
     * @param int $lotCustomFieldId
     * @param int $lotCategoryId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByIdForCustomField(int $lotCustomFieldId, int $lotCategoryId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createLotItemCustFieldLotCategoryReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemCustFieldId($lotCustomFieldId)
            ->filterLotCategoryId($lotCategoryId)
            ->exist();
        return $isFound;
    }
}
