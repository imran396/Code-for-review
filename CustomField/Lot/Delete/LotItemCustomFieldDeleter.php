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


use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Translate\LotCustomFieldTranslationManagerAwareTrait;
use Sam\Lot\Category\CustomField\DataDeleterAwareTrait as LotCategoryCustomFieldDataDeleter;
use Sam\Lot\LotFieldConfig\Delete\LotFieldConfigDeleterCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotItemCustField\LotItemCustFieldWriteRepositoryAwareTrait;

/**
 * Class LotItemCustomFieldDeleter
 * @package Sam\CustomField\Lot\Delete
 */
class LotItemCustomFieldDeleter extends CustomizableClass
{
    use LotCategoryCustomFieldDataDeleter;
    use LotCustomFieldTranslationManagerAwareTrait;
    use LotFieldConfigDeleterCreateTrait;
    use LotItemCustFieldReadRepositoryCreateTrait;
    use LotItemCustFieldWriteRepositoryAwareTrait;
    use LotItemCustomFieldDataDeleterCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Perform deleting actions for custom field and related data
     *
     * @param int $lotCustomFieldId
     * @param int $editorUserId
     */
    public function deleteById(int $lotCustomFieldId, int $editorUserId): void
    {
        $lotCustomField = $this->createLotItemCustFieldReadRepository()
            ->filterId($lotCustomFieldId)
            ->loadEntity();
        if ($lotCustomField) {
            $lotCustomField->Active = false;
            $this->getLotItemCustFieldWriteRepository()->saveWithModifier($lotCustomField, $editorUserId);
        }

        $this->createLotItemCustomFieldDataDeleter()->deleteAllLotDataForCustomFieldId($lotCustomFieldId, $editorUserId);

        $this->getDataDeleter()->deleteForCustomFieldId($lotCustomFieldId, $editorUserId);
        $this->getLotCustomFieldTranslationManager()->delete($lotCustomField);
        // Not necessary, because config is checked and refreshed on request. Called for coincidence.
        $this->createLotFieldConfigDeleter()->deleteAllByIndex('fc' . $lotCustomFieldId, $editorUserId);
    }
}
