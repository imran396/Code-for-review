<?php
/**
 * SAM-6308: Refactor custom field management to separate modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotCustomFieldEditForm\Save;

use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Data\Normalize\NormalizerAwareTrait;
use Sam\Core\Data\Normalize\NormalizerInterface;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Translate\LotCustomFieldTranslationManagerAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Lot\Category\CustomField\CustomFieldLotCategoryHelperAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItemCustField\LotItemCustFieldWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItemCustFieldLotCategory\LotItemCustFieldLotCategoryWriteRepositoryAwareTrait;
use Sam\View\Admin\Form\LotCustomFieldEditForm\Dto\LotCustomFieldEditFormDto;
use Sam\View\Admin\Form\LotCustomFieldEditForm\Load\LotCustomFieldEditFormDataProviderAwareTrait;

/**
 * Class LotCustomFieldEditFormProducer
 * @package Sam\View\Admin\Form\LotCustomFieldEditForm\Save
 */
class LotCustomFieldEditFormProducer extends CustomizableClass
{
    use CurrentDateTrait;
    use CustomFieldLotCategoryHelperAwareTrait;
    use EntityFactoryCreateTrait;
    use LotCustomFieldEditFormDataProviderAwareTrait;
    use LotCustomFieldTranslationManagerAwareTrait;
    use LotItemCustFieldLotCategoryWriteRepositoryAwareTrait;
    use LotItemCustFieldWriteRepositoryAwareTrait;
    use NormalizerAwareTrait;

    public array $modificationInfo = [];
    protected bool $isNew = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param NormalizerInterface $normalizer
     * @return static
     */
    public function construct(NormalizerInterface $normalizer): static
    {
        $this->setNormalizer($normalizer);
        return $this;
    }

    /**
     * @param LotItemCustField|null $lotCustomField
     * @param LotCustomFieldEditFormDto $dto
     * @param int $editorUserId
     * @return LotItemCustField
     */
    public function save(?LotItemCustField $lotCustomField, LotCustomFieldEditFormDto $dto, int $editorUserId): LotItemCustField
    {
        $this->isNew = $lotCustomField === null;
        if ($this->isNew) {
            $lotCustomField = $this->createEntityFactory()->lotItemCustField();
            $lotCustomField->Active = true;
            $lotCustomField = $this->applyDefaults($lotCustomField, $dto);
        }

        $oldName = $lotCustomField->Name ?: null;

        $this->setIfAssign($lotCustomField, 'access', $dto);
        $this->setIfAssign($lotCustomField, 'autoComplete', $dto, 'bool');
        $this->setIfAssign($lotCustomField, 'barcode', $dto, 'bool');
        $this->setIfAssign($lotCustomField, 'inAdminCatalog', $dto, 'bool');
        $this->setIfAssign($lotCustomField, 'inAdminSearch', $dto, 'bool');
        $this->setIfAssign($lotCustomField, 'inCatalog', $dto, 'bool');
        $this->setIfAssign($lotCustomField, 'inInvoices', $dto, 'bool');
        $this->setIfAssign($lotCustomField, 'inSettlements', $dto, 'bool');
        $this->setIfAssign($lotCustomField, 'lotNumAutoFill', $dto, 'bool');
        $this->setIfAssign($lotCustomField, 'name', $dto);
        $this->setIfAssign($lotCustomField, 'order', $dto, 'int');
        $this->setIfAssign($lotCustomField, 'parameters', $dto);
        $this->setIfAssign($lotCustomField, 'searchField', $dto, 'bool');
        $this->setIfAssign($lotCustomField, 'searchIndex', $dto, 'bool');
        $this->setIfAssign($lotCustomField, 'type', $dto, 'int');
        $this->setIfAssign($lotCustomField, 'unique', $dto, 'bool');
        $this->setIfAssign($lotCustomField, 'fckEditor', $dto, 'bool');
        $this->setIfAssign($lotCustomField, 'barcode', $dto, 'bool');
        $this->setIfAssign($lotCustomField, 'barcodeType', $dto);
        $this->setIfAssign($lotCustomField, 'barcodeAutoPopulate', $dto, 'bool');

        if (!$lotCustomField->BarcodeType) {
            $lotCustomField->BarcodeType = Constants\CustomField::BARCODE_TYPE_DEFAULT;
        }

        $this->modificationInfo = $this->collectModificationInfo($lotCustomField);

        $this->getLotItemCustFieldWriteRepository()->saveWithModifier($lotCustomField, $editorUserId);
        if (isset($dto->lotCategories)) {
            $lotCategories = $this->getNormalizer()->toList($dto->lotCategories);
            $this->updateLotCategories($lotCustomField->Id, $lotCategories, $editorUserId);
        }
        $this->getLotCustomFieldTranslationManager()->refresh($lotCustomField, $oldName);

        return $lotCustomField;
    }

    /**
     * Return modified properties in array [property => ['Old' => old value, 'New' => new value], ... ]
     * @return array
     */
    public function getModificationInfo(): array
    {
        return $this->modificationInfo;
    }

    /**
     * true - when new entity created, false - when existing entity updated
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->isNew;
    }

    /**
     * @param LotItemCustField $lotCustomField
     * @param LotCustomFieldEditFormDto $dto
     * @return LotItemCustField
     */
    protected function applyDefaults(LotItemCustField $lotCustomField, LotCustomFieldEditFormDto $dto): LotItemCustField
    {
        if (!isset($dto->access)) {
            $lotCustomField->Access = Constants\Role::VISITOR;
        }
        if (!isset($dto->barcodeType)) {
            $lotCustomField->BarcodeType = Constants\CustomField::BARCODE_TYPE_DEFAULT;
        }
        if (!isset($dto->unique)) {
            $lotCustomField->Unique = false;
        }
        if (!isset($dto->order)) {
            $lotCustomField->Order = $this->getLotCustomFieldEditFormDataProvider()->suggestCustomFieldOrderValue();
        }
        if (!isset($dto->parameters)) {
            $lotCustomField->Parameters = match ((int)$dto->type) {
                Constants\CustomField::TYPE_DECIMAL => 2,
                Constants\CustomField::TYPE_DATE => 'm/d/Y g:i A',
                Constants\CustomField::TYPE_FILE => Constants\CustomField::FILE_PARAMETERS_DEFAULT,
                default => '',
            };
        }
        return $lotCustomField;
    }

    /**
     * @param int $customFieldId
     * @param array $lotCategories
     * @param int $editorUserId
     * @return array
     */
    public function updateLotCategories(int $customFieldId, array $lotCategories, int $editorUserId): array
    {
        $this->getCustomFieldLotCategoryHelper()->removeAllForCustomField($customFieldId);
        $entityList = [];
        foreach ($lotCategories as $lotCategoryId) {
            $lotCustomFieldCategory = $this->createEntityFactory()->lotItemCustFieldLotCategory();
            $lotCustomFieldCategory->LotItemCustFieldId = $customFieldId;
            $lotCustomFieldCategory->LotCategoryId = (int)$lotCategoryId;
            $this->getLotItemCustFieldLotCategoryWriteRepository()->saveWithModifier($lotCustomFieldCategory, $editorUserId);
            $entityList[] = $lotCustomFieldCategory;
        }
        return $entityList;
    }

    /**
     * Set entity field if it's assigned in dto and apply specific strategy if present
     * @param LotItemCustField $entity
     * @param string $property
     * @param LotCustomFieldEditFormDto $dto
     * @param string|null $normalizeTo
     * @return void
     */
    protected function setIfAssign(
        LotItemCustField $entity,
        string $property,
        LotCustomFieldEditFormDto $dto,
        ?string $normalizeTo = null
    ): void {
        if (isset($dto->{$property})) {
            $value = $this->getNormalizedDtoValue($property, $dto, $normalizeTo);
            $this->setEntityPropertyValue($entity, $property, $value);
        }
    }

    /**
     * @param string $property
     * @param LotCustomFieldEditFormDto $dto
     * @param string|null $normalizeTo
     * @return mixed
     */
    protected function getNormalizedDtoValue(string $property, LotCustomFieldEditFormDto $dto, ?string $normalizeTo = null): mixed
    {
        $value = $dto->{$property};
        if ($normalizeTo) {
            $value = $this->getNormalizer()->{'to' . ucfirst($normalizeTo)}($value);
        }
        return $value;
    }

    /**
     * @param LotItemCustField $lotCustomField
     * @param string $property
     * @param $value
     */
    protected function setEntityPropertyValue(LotItemCustField $lotCustomField, string $property, $value): void
    {
        if (!isset($lotCustomField->{$property})) {
            $property = ucfirst($property);
        }

        $lotCustomField->{$property} = $value;
    }

    /**
     * @param LotItemCustField $lotCustomField
     * @return array
     */
    protected function collectModificationInfo(LotItemCustField $lotCustomField): array
    {
        $modificationInfo = [];
        foreach ($lotCustomField->__Modified as $property => $oldValue) {
            $modificationInfo[$property] = ['Old' => $oldValue, 'New' => $lotCustomField->{$property}];
        }
        return $modificationInfo;
    }
}
