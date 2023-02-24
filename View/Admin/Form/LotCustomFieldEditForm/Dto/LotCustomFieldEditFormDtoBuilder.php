<?php
/**
 * SAM-6308: Refactor custom field management to separate modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotCustomFieldEditForm\Dto;

use Sam\Core\Service\CustomizableClass;
use Laminas\Diactoros\ServerRequest;
use LotItemCustField;
use Sam\Core\Constants\Admin\LotCustomFieldEditFormConstants;
use Sam\Core\Dto\FormDataReaderCreateTrait;
use Symfony\Component\Console\Input\InputInterface;
use Sam\Core\Constants;

/**
 * Class LotCustomFieldEditFormDtoBuilder
 * @package Sam\View\Admin\Form\LotCustomFieldEditForm\Dto
 */
class LotCustomFieldEditFormDtoBuilder extends CustomizableClass
{
    use FormDataReaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItemCustField|null $lotCustomField
     * @param $psrRequest ServerRequest
     * @return LotCustomFieldEditFormDto
     */
    public function fromPsrRequest(?LotItemCustField $lotCustomField, ServerRequest $psrRequest): LotCustomFieldEditFormDto
    {
        $body = $psrRequest->getParsedBody();
        $formDataReader = $this->createFormDataReader();

        $dto = LotCustomFieldEditFormDto::new();
        $dto->access = $formDataReader->readString(LotCustomFieldEditFormConstants::CID_LST_ACCESS, $body);
        $dto->autoComplete = $formDataReader->readCheckbox(LotCustomFieldEditFormConstants::CID_CHK_AUTO_COMPLETE, $body);
        $dto->barcode = $formDataReader->readCheckbox(LotCustomFieldEditFormConstants::CID_CHK_BARCODE, $body);
        $dto->barcodeAutoPopulate = $formDataReader->readCheckbox(LotCustomFieldEditFormConstants::CID_CHK_BARCODE_AUTO_POPULATE, $body);
        $dto->barcodeType = $formDataReader->readString(LotCustomFieldEditFormConstants::CID_LST_BARCODE_TYPE, $body);
        $dto->fckEditor = $formDataReader->readCheckbox(LotCustomFieldEditFormConstants::CID_CHK_FCK_EDITOR, $body);
        $dto->inAdminCatalog = $formDataReader->readCheckbox(LotCustomFieldEditFormConstants::CID_CHK_IN_ADMIN_CATALOG, $body);
        $dto->inAdminSearch = $formDataReader->readCheckbox(LotCustomFieldEditFormConstants::CID_CHK_IN_ADMIN_SEARCH, $body);
        $dto->inCatalog = $formDataReader->readCheckbox(LotCustomFieldEditFormConstants::CID_CHK_IN_CATALOG, $body);
        $dto->inInvoices = $formDataReader->readCheckbox(LotCustomFieldEditFormConstants::CID_CHK_IN_INVOICES, $body);
        $dto->inSettlements = $formDataReader->readCheckbox(LotCustomFieldEditFormConstants::CID_CHK_IN_SETTLEMENTS, $body);
        $dto->lotCategories = $formDataReader->readMultipleStrings(LotCustomFieldEditFormConstants::CID_PNL_LOT_CATEGORY, $body);
        $dto->lotNumAutoFill = $formDataReader->readCheckbox(LotCustomFieldEditFormConstants::CID_CHK_LOT_NUM_AUTO_FILL, $body);
        $dto->name = $formDataReader->readString(LotCustomFieldEditFormConstants::CID_TXT_NAME, $body);
        $dto->order = $formDataReader->readString(LotCustomFieldEditFormConstants::CID_TXT_ORDER, $body);
        $dto->parameters = $formDataReader->readString(LotCustomFieldEditFormConstants::CID_TXT_PARAMETER, $body);
        $dto->searchField = $formDataReader->readCheckbox(LotCustomFieldEditFormConstants::CID_CHK_SEARCH_FIELD, $body);
        $dto->searchIndex = $formDataReader->readCheckbox(LotCustomFieldEditFormConstants::CID_CHK_SEARCH_INDEX, $body);
        $dto->type = $formDataReader->readString(LotCustomFieldEditFormConstants::CID_LST_TYPE, $body);
        $dto->unique = $formDataReader->readCheckbox(LotCustomFieldEditFormConstants::CID_CHK_UNIQUE, $body);

        $dto = $this->normalize($dto);
        $dto = $this->postBuild($lotCustomField, $dto);
        return $dto;
    }

    /**
     * @param LotItemCustField|null $lotCustomField
     * @param InputInterface $input
     * @return LotCustomFieldEditFormDto
     */
    public function fromCli(?LotItemCustField $lotCustomField, InputInterface $input): LotCustomFieldEditFormDto
    {
        $dto = LotCustomFieldEditFormDto::new();
        $inputData = $input->getOptions();
        $availableProperties = $dto->getAvailableFields();
        $inputProperties = array_filter(
            $inputData,
            static function ($value, string $property) use ($availableProperties) {
                return $value !== null && in_array($property, $availableProperties, true);
            },
            ARRAY_FILTER_USE_BOTH
        );
        foreach ($inputProperties as $property => $value) {
            $dto->{$property} = $value;
        }

        $dto = $this->normalize($dto);
        $dto = $this->postBuild($lotCustomField, $dto);
        return $dto;
    }

    /**
     * @param LotCustomFieldEditFormDto $dto
     * @return LotCustomFieldEditFormDto
     */
    private function normalize(LotCustomFieldEditFormDto $dto): LotCustomFieldEditFormDto
    {
        $normalizedBarcodeType = array_search($dto->barcodeType, Constants\CustomField::$barcodeTypeNames, true);
        if ($normalizedBarcodeType !== false) {
            $dto->barcodeType = $normalizedBarcodeType;
        }
        return $dto;
    }

    /**
     * @param LotItemCustField|null $lotCustomField
     * @param LotCustomFieldEditFormDto $dto
     * @return LotCustomFieldEditFormDto
     */
    private function postBuild(?LotItemCustField $lotCustomField, LotCustomFieldEditFormDto $dto): LotCustomFieldEditFormDto
    {
        unset($dto->id);
        if ($lotCustomField !== null) {
            $dto->id = $lotCustomField->Id;
            $dto->type = $dto->type ?? (string)$lotCustomField->Type;
        }
        return $dto;
    }
}
