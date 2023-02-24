<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Delete;

use LotItemCustData;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverProviderCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotItemCustData\LotItemCustDataWriteRepositoryAwareTrait;

/**
 * Class LotItemCustomFieldDataDeleter
 * @package Sam\CustomField\Lot\Delete
 */
class LotItemCustomFieldDataDeleter extends CustomizableClass
{
    use DbConnectionTrait;
    use EntityObserverProviderCreateTrait;
    use LotItemCustDataReadRepositoryCreateTrait;
    use LotItemCustDataWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Soft delete custom field data records for all lots for definite custom field
     *
     * @param int $lotCustomFieldId
     * @param int $editorUserId
     */
    public function deleteAllLotDataForCustomFieldId(int $lotCustomFieldId, int $editorUserId): void
    {
        if ($this->createEntityObserverProvider()->hasObservers(LotItemCustData::class)) {
            $this->deleteAllLotDataForCustomFieldIdWithObserver($lotCustomFieldId, $editorUserId);
        } else {
            $this->deleteAllLotDataForCustomFieldIdSkipObserver($lotCustomFieldId, $editorUserId);
        }
    }

    /**
     * Soft delete custom field data records for all lots for definite custom field
     *
     * @param int $lotCustomFieldId
     * @param int $editorUserId
     */
    protected function deleteAllLotDataForCustomFieldIdWithObserver(int $lotCustomFieldId, int $editorUserId): void
    {
        $repo = $this->createLotItemCustDataReadRepository()
            ->filterLotItemCustFieldId($lotCustomFieldId)
            ->setChunkSize(500);
        while ($lotCustomDatas = $repo->loadEntities()) {
            foreach ($lotCustomDatas as $lotCustomData) {
                $lotCustomData->Active = false;
                $this->getLotItemCustDataWriteRepository()->saveWithModifier($lotCustomData, $editorUserId);
            }
        }
    }

    /**
     * Soft delete custom field data records for all lots for definite custom field
     *
     * @param int $lotCustomFieldId
     * @param int $editorUserId
     */
    protected function deleteAllLotDataForCustomFieldIdSkipObserver(int $lotCustomFieldId, int $editorUserId): void
    {
        // Update query used in optimization purpose. We skip actions in LotItemCustData Observer and Save() method,
        // because search index refreshing is initiated inside LotItemCustField::Save()
        $query = 'UPDATE lot_item_cust_data SET `active` = FALSE, `modified_by` = ' . $this->escape($editorUserId)
            . ' WHERE lot_item_cust_field_id = ' . $this->escape($lotCustomFieldId);
        $this->nonQuery($query);
    }
}
