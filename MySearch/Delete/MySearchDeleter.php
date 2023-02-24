<?php
/**
 * SAM-6473: Move "my_search" table related logic to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\MySearch\Delete;


use MySearch;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\DeleteRepository\Entity\MySearchCategory\MySearchCategoryDeleteRepositoryCreateTrait;
use Sam\Storage\DeleteRepository\Entity\MySearchCustom\MySearchCustomDeleteRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\MySearch\MySearchWriteRepositoryAwareTrait;

/**
 * Class MySearchDeleter
 * @package Sam\MySearch\Delete
 */
class MySearchDeleter extends CustomizableClass
{
    use MySearchCategoryDeleteRepositoryCreateTrait;
    use MySearchCustomDeleteRepositoryCreateTrait;
    use MySearchWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete MySearch item with related data
     *
     * @param MySearch $mySearch
     * @param int $editorUserId
     */
    public function delete(MySearch $mySearch, int $editorUserId): void
    {
        $this->removeCustomFields($mySearch->Id);
        $this->removeCategories($mySearch->Id);
        $this->getMySearchWriteRepository()->deleteWithModifier($mySearch, $editorUserId);
    }

    /**
     * Delete related custom fields
     *
     * @param int $mySearchId my_search.id
     */
    private function removeCustomFields(int $mySearchId): void
    {
        $this->createMySearchCustomDeleteRepository()
            ->filterMySearchId($mySearchId)
            ->delete();
    }

    /**
     * Delete related categories
     *
     * @param int $mySearchId my_search.id
     */
    private function removeCategories(int $mySearchId): void
    {
        $this->createMySearchCategoryDeleteRepository()
            ->filterMySearchId($mySearchId)
            ->delete();
    }
}
