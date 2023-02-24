<?php
/**
 * Help methods for deleting Lot Category Custom Fields
 * SAM-4040: Lot Category modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 22, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\CustomField;

use LotCategoryCustData;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Observer\EntityObserverProviderCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotCategoryCustData\LotCategoryCustDataReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotCategoryCustData\LotCategoryCustDataWriteRepositoryAwareTrait;


/**
 * Class DataDeleter
 * @package Sam\Lot\Category\CustomField
 */
class DataDeleter extends CustomizableClass
{
    use DbConnectionTrait;
    use EntityObserverProviderCreateTrait;
    use LotCategoryCustDataReadRepositoryCreateTrait;
    use LotCategoryCustDataWriteRepositoryAwareTrait;
    use LotCategoryLoaderAwareTrait;

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
     * @param int $editorUserId
     * @return void
     */
    public function deleteForCategoryId(int $lotCategoryId, int $editorUserId): void
    {
        if ($this->createEntityObserverProvider()->hasObservers(LotCategoryCustData::class)) {
            $this->deleteForCategoryIdWithObserver($lotCategoryId, $editorUserId);
        } else {
            $this->deleteForCategoryIdSkipObserver($lotCategoryId, $editorUserId);
        }
    }

    /**
     * @param int $lotCategoryId
     * @param int $editorUserId
     * @return void
     */
    protected function deleteForCategoryIdWithObserver(int $lotCategoryId, int $editorUserId): void
    {
        $lotsCustData = $this->createLotCategoryCustDataReadRepository()
            ->filterLotCategoryId($lotCategoryId)
            ->loadEntities();
        foreach ($lotsCustData as $data) {
            $data->Active = false;
            $this->getLotCategoryCustDataWriteRepository()->saveWithModifier($data, $editorUserId);
        }
    }

    /**
     * @param int $lotCategoryId
     * @param int $editorUserId
     * @return void
     */
    protected function deleteForCategoryIdSkipObserver(int $lotCategoryId, int $editorUserId): void
    {
        $query = 'UPDATE lot_category_cust_data SET `active` = FALSE, `modified_by` = ' . $this->escape($editorUserId)
            . ' WHERE lot_category_id = ' . $this->escape($lotCategoryId);
        $this->nonQuery($query);
    }

    /**
     * @param int $lotCustomFieldId
     * @param int $editorUserId
     * @return void
     */
    public function deleteForCustomFieldId(int $lotCustomFieldId, int $editorUserId): void
    {
        if ($this->createEntityObserverProvider()->hasObservers(LotCategoryCustData::class)) {
            $this->deleteForCustomFieldIdWithObserver($lotCustomFieldId, $editorUserId);
        } else {
            $this->deleteForCustomFieldIdSkipObserver($lotCustomFieldId, $editorUserId);
        }
    }

    /**
     * Delete category custom data considering observer's logic
     * @param int $customFieldId
     * @param int $editorUserId
     * @return void
     */
    protected function deleteForCustomFieldIdWithObserver(int $customFieldId, int $editorUserId): void
    {
        $lotsCustData = $this->createLotCategoryCustDataReadRepository()
            ->filterLotItemCustFieldId($customFieldId)
            ->loadEntities();
        foreach ($lotsCustData as $data) {
            $data->Active = false;
            $this->getLotCategoryCustDataWriteRepository()->saveWithModifier($data, $editorUserId);
        }
    }

    /**
     * Optimized delete of category custom data
     * @param int $lotCustomFieldId
     * @param int $editorUserId
     * @return void
     */
    protected function deleteForCustomFieldIdSkipObserver(int $lotCustomFieldId, int $editorUserId): void
    {
        $query = 'UPDATE lot_category_cust_data SET active = FALSE, modified_by = ' . $this->escape($editorUserId)
            . ' WHERE lot_item_cust_field_id = ' . $this->escape($lotCustomFieldId);
        $this->nonQuery($query);
    }
}
