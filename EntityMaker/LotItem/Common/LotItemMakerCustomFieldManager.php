<?php
/**
 * Contains all the logic of working with custom fields
 *
 * SAM-4015: Auction Lot and Lot Item Entity Makers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 16, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Custom methods can be used there or in customized class (SAM-1573)
 *
 * Optional method to initialize custom lot item field by default value
 * param LotItemCustField $lotCustomField the custom lot item field object
 * param LotItemMakerConfigDto $configDto (contains encoding, auction id, mode)
 * return mixed the value for custom field initialization
 * public function LotCustomField_{Field name}_InitByDefault(LotItemCustField $lotCustomField, LotItemMakerConfigDto $configDto);
 *
 * Optional method to initialize custom lot item field QControl by category related data
 * param LotItemCustField $lotCustomField the custom lot item field object
 * param LotCategoryCustData $lotCategoryCustomData the category data for custom lot item field
 * param LotItemMakerConfigDto $configDto (contains encoding, auction id, mode)
 * public function LotCustomField_{Field name}_InitByCategory(LotItemCustField $lotCustomField, LotCategoryCustData $lotCategoryCustomData, LotItemMakerConfigDto $configDto)
 *
 * Optional method called for validation before saving lot item custom field values.
 * param LotItemCustField $lotCustomField the custom lot item field object
 * param mixed $value input value
 * param LotItemMakerConfigDto $configDto (contains encoding, auction id, mode)
 * return boolean whether validation passed or failed
 * public function LotCustomField_{Field name}_Validate(LotItemCustField $lotCustomField, $value, LotItemMakerConfigDto $configDto)
 *
 * Optional method called when saving lot item custom field values.
 * param LotItemCustField $lotCustomField
 * param LotItemCustData $lotCustomData
 * param mixed $value input value
 * param LotItemMakerConfigDto $configDto (contains encoding, auction id, mode)
 * public function LotCustomField_{Field name}_Save(LotItemCustField $lotCustomField, LotItemCustData $lotCustomData, $value, LotItemMakerConfigDto $configDto)
 *
 * {Field name} - Camel cased name of custom field (see TextTransformer::toCamelCase())
 */

namespace Sam\EntityMaker\LotItem\Common;

use LotCategory;
use LotItemCustData;
use LotItemCustField;
use QBaseClass;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\CustomField\Base\TextType\Barcode\Build\BarcodeGeneratorCreateTrait;
use Sam\CustomField\Base\TextType\Barcode\Validate\BarcodeValidatorCreateTrait;
use Sam\CustomField\Lot\Help\LotCustomFieldHelperCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\Lot\Save\LotItemCustomFieldDataUpdaterCreateTrait;
use Sam\CustomField\Lot\Validate\LotCustomDataExistenceCheckerCreateTrait;
use Sam\EntityMaker\Base\Common\CustomFieldManager;
use Sam\EntityMaker\Base\Dto\ConfigDto;
use Sam\EntityMaker\Base\Dto\InputDto;
use Sam\EntityMaker\LotItem\Common\CustomField\DetectDefault\LotCustomFieldDefaultDetector;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\Lot\Category\CustomField\CustomFieldLotCategoryHelperAwareTrait;
use Sam\Lot\Category\CustomField\LotCategoryCustomDataLoaderAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\LotFieldConfigProviderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\Map\EntityMakerFieldMap;

/**
 * Class LotItemMakerCustomFieldManager
 * @package Sam\EntityMaker\LotItem
 * @method LotItemMakerInputDto getInputDto()
 * @method LotItemMakerConfigDto getConfigDto()
 */
class LotItemMakerCustomFieldManager extends CustomFieldManager
{
    use AuctionLoaderAwareTrait;
    use BarcodeGeneratorCreateTrait;
    use BarcodeValidatorCreateTrait;
    use CustomFieldLotCategoryHelperAwareTrait;
    use EntityFactoryCreateTrait;
    use LotCategoryCustomDataLoaderAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCustomDataExistenceCheckerCreateTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldHelperCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotFieldConfigProviderAwareTrait;
    use LotItemCustomFieldDataUpdaterCreateTrait;

    /**
     * @var string[]
     */
    protected array $errorMessages = [
        self::CUSTOM_FIELD_DATE_ERROR => 'Invalid date',
        self::CUSTOM_FIELD_DECIMAL_ERROR => 'Should be numeric',
        self::CUSTOM_FIELD_FILE_ERROR => 'Invalid file extension',
        self::CUSTOM_FIELD_INTEGER_ERROR => 'Should be numeric integer',
        self::CUSTOM_FIELD_POSTAL_CODE_ERROR => 'Invalid format',
        self::CUSTOM_FIELD_SELECT_ENCODING_ERROR => 'Invalid encoding',
        self::CUSTOM_FIELD_REQUIRED_ERROR => 'Required',
        self::CUSTOM_FIELD_SELECT_INVALID_OPTION_ERROR => 'Has invalid option',
        self::CUSTOM_FIELD_TEXT_ENCODING_ERROR => 'Invalid encoding',
        self::CUSTOM_FIELD_TEXT_UNIQUE_ERROR => 'Value must be unique',
        self::CUSTOM_FIELD_THOUSAND_SEPARATOR => 'Should not contain a thousand separator',
        self::CUSTOM_FIELD_INVALID_BARCODE => 'Invalid Barcode',
        self::CUSTOM_FIELD_HIDDEN_ERROR => 'Hidden',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritdoc
     */
    public function construct(
        InputDto $inputDto,
        ConfigDto $configDto
    ): static {
        $this->getLotFieldConfigProvider()->setFieldMap(EntityMakerFieldMap::new());
        return parent::construct($inputDto, $configDto);
    }

    /**
     * @param LotItemCustField $lotCustomField
     * @param LotItemCustData $lotCustomData
     * @param LotItemCustData $lotCustomDataOld
     * @param int $editorUserId
     */
    protected function additionalSavingActions($lotCustomField, $lotCustomData, $lotCustomDataOld, int $editorUserId): void
    {
        $this->refreshGeoLocation($lotCustomField, $lotCustomData, $lotCustomDataOld, $editorUserId);
    }

    /**
     * Create/update lot_item_geolocation table row (lot_item_id, lot_item_cust_data_id)
     * @param LotItemCustField $lotCustomField
     * @param LotItemCustData $lotCustomData
     * @param LotItemCustData $lotCustomDataOld
     * @param int $editorUserId
     */
    protected function refreshGeoLocation(
        LotItemCustField $lotCustomField,
        LotItemCustData $lotCustomData,
        LotItemCustData $lotCustomDataOld,
        int $editorUserId
    ): void {
        if (
            $lotCustomField->Type === Constants\CustomField::TYPE_POSTALCODE
            && $lotCustomData->Text !== $lotCustomDataOld->Text
        ) {
            $lotItemId = (int)$this->getInputDto()->id;
            $this->createLotItemCustomFieldDataUpdater()
                ->refreshGeoLocation($lotItemId, $lotCustomData, $editorUserId);
        }
    }

    /**
     * Init custom fields if a lot is new or customField is not exist for the lot
     */
    protected function initCustomFieldsByDefaultData(): void
    {
        $inputDto = $this->getInputDto();
        $lotItemId = $inputDto->id;
        $existingCustomData = $lotItemId ? $this->createLotCustomDataLoader()->loadValues((int)$lotItemId) : null;

        foreach ($this->getAllCustomFields() as $customField) {
            $id = $customField->Id;
            $field = $this->getCustomFieldsTagName($customField->Name);

            $fieldNotInIncomingData = !$lotItemId
                && !isset($inputDto->$field);

            $fieldNotInDb = $lotItemId
                && !isset($existingCustomData[$id])
                && !isset($inputDto->$field);

            if (
                $fieldNotInIncomingData
                || $fieldNotInDb
            ) {
                $defaultValue = $this->initCustomField($customField);
                if ($defaultValue !== null) {
                    $inputDto->$field = $defaultValue;
                }
            }
        }
    }

    private function getCategoryIds(): array
    {
        $categoriesNames = $this->getInputDto()->categoriesNames;
        if ($categoriesNames) {
            $categoryIds = [];
            foreach ($categoriesNames as $categoryName) {
                $category = $this->getLotCategoryLoader()->loadByName(trim($categoryName));
                if ($category) {
                    $categoryIds[] = $category->Id;
                }
            }
            return $categoryIds;
        }

        return $this->getInputDto()->categoriesIds ?? [];
    }

    /**
     * Init custom field by default value.
     * Default value is either populated from main category assigned to lot item,
     * or from "Parameters" property of custom field,
     * or Barcode can be generated,
     * or default Postal Code of auction.
     * @param LotItemCustField $lotCustomField
     * @return mixed
     */
    public function initCustomField(QBaseClass $lotCustomField): mixed
    {
        return LotCustomFieldDefaultDetector::new()
            ->detect($lotCustomField, $this->getInputDto(), $this->getConfigDto());
    }

    public function findCustomFieldByName(string $name): ?LotItemCustField
    {
        foreach ($this->getAllCustomFields() as $customField) {
            if ($this->getCustomFieldsTagName($customField->Name) === $name) {
                return $customField;
            }
        }
        return null;
    }

    /**
     * Load custom fields from lot_item_cust_field table
     * @return LotItemCustField[]
     */
    protected function loadCustomFields(): array
    {
        return $this->createLotCustomFieldLoader()->loadAll();
    }

    /**
     * Load or create lotItem custom data
     * @param int $lotItemId
     * @param int $customFieldId
     * @return LotItemCustData
     */
    protected function loadCustomDataOrCreate(int $lotItemId, int $customFieldId): LotItemCustData
    {
        if ($this->allCustomData === null) {
            $this->allCustomData = $this->createLotCustomDataLoader()->loadForLot($lotItemId);
        }
        $lotCustomData = current(
            array_filter(
                $this->allCustomData,
                static function ($data) use ($customFieldId) {
                    return $data->LotItemCustFieldId === $customFieldId;
                }
            )
        );
        if ($lotCustomData) {
            return $lotCustomData;
        }

        $lotCustomData = $this->createEntityFactory()->lotItemCustData();
        $lotCustomData->LotItemId = $lotItemId;
        $lotCustomData->LotItemCustFieldId = $customFieldId;
        return $lotCustomData;
    }

    /* Loading optional custom methods */

    /**
     * Save field using optional custom method if exist
     * @param LotItemCustField $customField CustomField
     * @param string $value CustomField value
     * @param LotItemCustData|null $customData
     * @return bool true, when custom save method found and executed; otherwise - false
     */
    protected function customSave($customField, $value, $customData = null): bool
    {
        $saveMethod = $this->createLotCustomFieldHelper()
            ->makeCustomMethodName($customField->Name, 'Save');
        if (method_exists($this, $saveMethod)) {
            $this->$saveMethod($customField, $customData, $value, $this->getConfigDto());
            return true;
        }
        return false;
    }

    /**
     * Validate field using optional custom method if exist
     * @param LotItemCustField $lotCustomField CustomField
     * @param string $value CustomField value
     * @return string
     */
    protected function customValidation($lotCustomField, string $value): string
    {
        $validateMethod = $this->createLotCustomFieldHelper()
            ->makeCustomMethodName($lotCustomField->Name, 'Validate');
        if (method_exists($this, $validateMethod)) {
            return (string)$this->$validateMethod($lotCustomField, $value, $this->getConfigDto());
        }
        return '';
    }

    /**
     * Check customField of type text
     * @override parent function for LotItemCustField specific validations.
     * @param LotItemCustField $customField CustomField
     * @param string $value CustomField value
     */
    protected function checkCustomFieldText($customField, string $value): void
    {
        parent::checkCustomFieldText($customField, $value);
        if ($customField->Barcode) {
            $this->checkCustomFieldBarcodeType($customField, $value);
        }
        if ($customField->Unique) {
            $this->checkCustomFieldUniqueType($customField, $value);
        }
    }

    /**
     * @param LotItemCustField $customField
     * @param string $value
     */
    protected function checkCustomFieldBarcodeType(LotItemCustField $customField, string $value): void
    {
        $isValid = $this->createBarcodeValidator()
            ->setBarcode(strtoupper($value))
            ->setType($customField->BarcodeType)
            ->validate();
        if (!$isValid) {
            $this->addError(self::CUSTOM_FIELD_INVALID_BARCODE, null, $customField);
        }
    }

    /**
     * @param LotItemCustField $customField
     * @param string $value
     */
    protected function checkCustomFieldUniqueType(LotItemCustField $customField, string $value): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        // TODO: this shouldn't be service account, but account of lot item,
        // although entity-maker service account is always equal to account of lot item
        $entityAccountId = $configDto->serviceAccountId;
        $isFound = $this->createLotCustomDataExistenceChecker()->existValue(
            $customField->Id,
            $value,
            $entityAccountId,
            [(int)$inputDto->id]
        );
        if ($isFound) {
            $this->addError(self::CUSTOM_FIELD_TEXT_UNIQUE_ERROR, null, $customField);
        }
    }

    /**
     * @param LotItemCustField $customField CustomField
     * @return bool
     */
    protected function isVisible($customField): bool
    {
        $configDto = $this->getConfigDto();
        return $this->getLotFieldConfigProvider()->isVisibleCustomField(
            $customField->Id,
            $configDto->serviceAccountId
        );
    }

    /**
     * @param LotItemCustField $customField
     * @return bool
     */
    protected function isRequired($customField): bool
    {
        $configDto = $this->getConfigDto();
        $isRequired = $this->getLotFieldConfigProvider()->isRequiredCustomField(
            $customField->Id,
            $configDto->serviceAccountId
        );
        if (!$isRequired) {
            return false;
        }

        $customFieldCategories = $this->getCustomFieldLotCategoryHelper()->loadByCustomFieldId($customField->Id);

        return !$customFieldCategories
            || $this->isCustomFieldCategoriesIntersectsWithSelectedCategories($customFieldCategories);
    }

    /**
     * @param LotCategory[] $customFieldCategories
     * @return bool
     */
    protected function isCustomFieldCategoriesIntersectsWithSelectedCategories(array $customFieldCategories): bool
    {
        $categoryIds = $this->getCategoryIds();
        $customFieldCategoryIds = array_map(
            static function (LotCategory $lotCategory): int {
                return $lotCategory->Id;
            },
            $customFieldCategories
        );
        $intersect = array_intersect($customFieldCategoryIds, $categoryIds);
        return count($intersect) > 0;
    }
}
