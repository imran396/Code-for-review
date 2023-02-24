<?php
/**
 * Help methods for Lot Category lot linking
 * SAM-4040: Lot Category modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 4, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\LotLinker;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotItemCategory\LotItemCategoryReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotItemCategory\LotItemCategoryWriteRepositoryAwareTrait;

/**
 * Class LotCategoryLotLinker
 * @package Sam\Lot\Category\LotLinker
 */
class LotCategoryLotLinker extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use LotItemCategoryReadRepositoryCreateTrait;
    use LotItemCategoryWriteRepositoryAwareTrait;


    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Assign categories for lot
     *
     * @param int[] $lotCategoryIds
     * @param int $lotItemId
     * @param int $editorUserId
     */
    public function assignCategoryForLot(array $lotCategoryIds, int $lotItemId, int $editorUserId): void
    {
        if (!$lotCategoryIds) {
            return;
        }

        foreach ($lotCategoryIds as $lotCategoryId) {
            $lotCategory = $this->createEntityFactory()->lotItemCategory();
            $lotCategory->LotItemId = $lotItemId;
            $lotCategory->LotCategoryId = $lotCategoryId;
            $this->getLotItemCategoryWriteRepository()->saveWithModifier($lotCategory, $editorUserId);
        }
    }

    /**
     * Un-assign categories from lot
     *
     * @param int[] $lotCategoryIds
     * @param int $lotItemId
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return void
     */
    public function unassignCategoryFromLot(array $lotCategoryIds, int $lotItemId, int $editorUserId, bool $isReadOnlyDb = false): void
    {
        if (!empty($lotCategoryIds)) {
            foreach ($lotCategoryIds as $lotCategoryId) {
                $lotItemCategories = $this->createLotItemCategoryReadRepository()
                    ->enableReadOnlyDb($isReadOnlyDb)
                    ->filterLotItemId($lotItemId)
                    ->filterLotCategoryId($lotCategoryId)
                    ->loadEntities();
                foreach ($lotItemCategories as $lotItemCategory) {
                    $this->getLotItemCategoryWriteRepository()->deleteWithModifier($lotItemCategory, $editorUserId);
                }
            }
        }
    }
}
