<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotCategory\Common;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\EntityMaker\Base\Dto\ConfigDto;
use Sam\EntityMaker\Base\Dto\ConfigDtoAwareTrait;
use Sam\EntityMaker\Base\Dto\InputDto;
use Sam\EntityMaker\Base\Dto\InputDtoAwareTrait;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerConfigDto;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerInputDto;
use Sam\Lot\Category\CustomField\LotCategoryCustomDataLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotCategoryCustData\LotCategoryCustDataWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class LotCategoryCustomFieldManager
 * @package Sam\EntityMaker\LotCategory\Common
 *
 * @method LotCategoryMakerInputDto getInputDto()
 * @method LotCategoryMakerConfigDto getConfigDto()
 */
class LotCategoryMakerCustomFieldManager extends CustomizableClass
{
    use BaseCustomFieldHelperAwareTrait;
    use ConfigDtoAwareTrait;
    use EntityFactoryCreateTrait;
    use InputDtoAwareTrait;
    use LotCategoryCustDataWriteRepositoryAwareTrait;
    use LotCategoryCustomDataLoaderAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(InputDto $inputDto, ConfigDto $configDto): static
    {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        return $this;
    }

    public function saveCustomFields(int $lotCategoryId, int $editorUserId): void
    {
        $configDto = $this->getConfigDto();
        $dtoCustomFieldValues = $this->getCustomFieldValues();
        foreach ($dtoCustomFieldValues as $id => $value) {
            $lotCustomField = $this->createLotCustomFieldLoader()->load($id, true);
            if ($lotCustomField) {
                $lotCategoryCustomData = $this->getLotCategoryCustomDataLoader()
                    ->load($lotCategoryId, $lotCustomField->Id);
                if (!$lotCategoryCustomData) {
                    $lotCategoryCustomData = $this->createEntityFactory()->lotCategoryCustData();
                    $lotCategoryCustomData->LotCategoryId = $lotCategoryId;
                    $lotCategoryCustomData->LotItemCustFieldId = $lotCustomField->Id;
                }
                switch ($lotCustomField->Type) {
                    case Constants\CustomField::TYPE_INTEGER:
                        $value = trim($this->getNumberFormatter()->removeFormat($value));
                        if ($value === '') {
                            $lotCategoryCustomData->Numeric = null;
                        } elseif (is_numeric($value)) {
                            $lotCategoryCustomData->Numeric = (int)$value;
                        }
                        break;
                    case Constants\CustomField::TYPE_DECIMAL:
                        $value = $configDto->mode->isSoap()
                            ? trim($value)
                            : trim($this->getNumberFormatter()->removeFormat($value));
                        if ($value === '') {
                            $lotCategoryCustomData->Numeric = null;
                        } elseif (is_numeric($value)) {
                            $lotCategoryCustomData->assignDecimalNumeric((float)$value, (int)$lotCustomField->Parameters);
                        }
                        break;
                    case Constants\CustomField::TYPE_TEXT:
                    case Constants\CustomField::TYPE_FULLTEXT:
                    case Constants\CustomField::TYPE_SELECT:
                        $lotCategoryCustomData->Text = (string)$value;
                        break;
                }
                $lotCategoryCustomData->Active = true;
                $this->getLotCategoryCustDataWriteRepository()->saveWithModifier($lotCategoryCustomData, $editorUserId);
            }
        }
    }

    public function getCustomFieldValues(): array
    {
        $inputDto = $this->getInputDto();
        $customValues = [];
        $customFieldsList = $this->getAllPossibleCustomFields();
        // Check if there are customFields in Dto
        foreach ($inputDto->toArray() as $field => $value) {
            if (in_array($field, $customFieldsList, true)) {
                $customValues[array_search($field, $customFieldsList, true)] = $value;
            }
        }
        return $customValues;
    }

    /**
     * Get all possible custom fields from lot_item_cust_field.name
     * @return array
     */
    private function getAllPossibleCustomFields(): array
    {
        $configDto = $this->getConfigDto();
        if (!$configDto->customFields) {
            foreach ($this->createLotCustomFieldLoader()->loadAll() as $lotItemCustomField) {
                $soapTag = $this->getBaseCustomFieldHelper()->makeSoapTagByName($lotItemCustomField->Name);
                $configDto->customFields[$lotItemCustomField->Id] = lcfirst($soapTag);
            }
        }
        return $configDto->customFields;
    }
}
