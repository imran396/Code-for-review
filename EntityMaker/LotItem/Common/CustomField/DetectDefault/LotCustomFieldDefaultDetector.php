<?php
/**
 * SAM-10200: Lot entity-maker: issues with default value assigning for custom fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
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
 */

namespace Sam\EntityMaker\LotItem\Common\CustomField\DetectDefault;

use LotCategoryCustData;
use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Help\LotCustomFieldHelper;
use Sam\EntityMaker\LotItem\Common\CustomField\DetectDefault\Internal\Load\DataProviderCreateTrait;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use QBaseClass;

/**
 * Class LotCustomFieldDefaultDetector
 * @package Sam\EntityMaker\LotItem
 */
class LotCustomFieldDefaultDetector extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Init custom field by default value.
     * Default value is either populated from main category assigned to lot item,
     * or from "Parameters" property of custom field,
     * or Barcode can be generated,
     * or default Postal Code of auction.
     * @param LotItemCustField $lotCustomField
     * @param LotItemMakerInputDto $inputDto - We read: categoriesNames, categoriesIds.
     * @param LotItemMakerConfigDto $configDto - We read: auctionId and pass $configDto to custom methods (encoding, auctionId, mode).
     * @param bool $isReadOnlyDb
     * @return mixed
     */
    public function detect(
        LotItemCustField $lotCustomField,
        LotItemMakerInputDto $inputDto,
        LotItemMakerConfigDto $configDto,
        bool $isReadOnlyDb = false
    ): mixed {
        $dataProvider = $this->createDataProvider();
        $firstCategoryId = $this->detectFirstCategoryId($inputDto);

        $categoryAutoComplete = $dataProvider->loadCategoryCustomDataForAutoComplete($firstCategoryId, $lotCustomField->Id, $isReadOnlyDb);
        $value = $this->initByCustomMethodInitByDefault($lotCustomField, $configDto);
        if ($value === null) {
            $value = $this->initByCustomMethodInitByCategory($lotCustomField, $categoryAutoComplete, $configDto);
        }

        if ($value === null) {
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $value = ($categoryAutoComplete
                        && $categoryAutoComplete->Numeric !== null)
                        ? $categoryAutoComplete->Numeric
                        : Cast::toInt($lotCustomField->Parameters);
                    break;
                case Constants\CustomField::TYPE_DECIMAL:
                    if ($categoryAutoComplete) {
                        $precision = (int)$lotCustomField->Parameters;
                        $value = $categoryAutoComplete->calcDecimalValue($precision);
                    }
                    break;
                case Constants\CustomField::TYPE_TEXT:
                    if ($lotCustomField->Unique) {
                        break;
                    }
                    $value = ($categoryAutoComplete
                        && $categoryAutoComplete->Text !== '')
                        ? $categoryAutoComplete->Text
                        : $lotCustomField->Parameters;
                    if (
                        $value === ''
                        && $lotCustomField->Barcode
                        && $lotCustomField->BarcodeAutoPopulate
                    ) {
                        $value = $dataProvider->generateBarcode($lotCustomField->Id);
                    }
                    break;
                case Constants\CustomField::TYPE_FULLTEXT:
                    $value = ($categoryAutoComplete
                        && $categoryAutoComplete->Text !== '')
                        ? $categoryAutoComplete->Text
                        : $lotCustomField->Parameters;
                    break;
                case Constants\CustomField::TYPE_SELECT:
                    $value = ($categoryAutoComplete
                        && $categoryAutoComplete->Text !== '')
                        ? $categoryAutoComplete->Text
                        : null;
                    break;
                case Constants\CustomField::TYPE_POSTALCODE:
                    $auction = $dataProvider->loadAuction((int)$configDto->auctionId);
                    $value = $auction->DefaultLotPostalCode ?? $lotCustomField->Parameters;
                    break;
            }
        }

        return $value;
    }

    /**
     * Init field by default using optional custom method if exist
     * @param LotItemCustField $lotCustomField CustomField
     * @param LotItemMakerConfigDto $configDto
     * @return mixed
     */
    protected function initByCustomMethodInitByDefault(QBaseClass $lotCustomField, LotItemMakerConfigDto $configDto): mixed
    {
        $initByDefaultMethod = LotCustomFieldHelper::new()
            ->makeCustomMethodName($lotCustomField->Name, 'InitByDefault');
        if (method_exists($this, $initByDefaultMethod)) {
            return $this->$initByDefaultMethod($lotCustomField, $configDto);
        }
        return null;
    }

    /**
     * Init field by category using optional custom method if exist
     * @param LotItemCustField $lotCustomField CustomField
     * @param LotCategoryCustData|null $lotCategoryCustomData
     * @param LotItemMakerConfigDto $configDto
     * @return mixed
     */
    protected function initByCustomMethodInitByCategory(
        LotItemCustField $lotCustomField,
        ?LotCategoryCustData $lotCategoryCustomData,
        LotItemMakerConfigDto $configDto
    ): mixed {
        if (!$lotCategoryCustomData) {
            return null;
        }

        $initByCategoryMethod = LotCustomFieldHelper::new()
            ->makeCustomMethodName($lotCustomField->Name, 'InitByCategory');
        if (method_exists($this, $initByCategoryMethod)) {
            return $this->$initByCategoryMethod($lotCustomField, $lotCategoryCustomData, $configDto);
        }
        return null;
    }

    private function detectFirstCategoryId(LotItemMakerInputDto $inputDto): ?int
    {
        $categoriesNames = $inputDto->categoriesNames;
        if ($categoriesNames) {
            return $this->createDataProvider()->loadLotCategoryIdByName(trim($categoriesNames[0]));
        }

        $categoryIds = $inputDto->categoriesIds;
        if ($categoryIds) {
            return reset($categoryIds);
        }

        return null;
    }
}
