<?php
/**
 * Helper class for QCodo controllers (drafts), which work with lot item custom field filtering controls at mobile side.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Benjo
 * @package         com.swb.sam2
 * @version         SVN: $Id: FilterControls.php 14533 2013-09-13 12:54:13Z SWB\igors $
 * @since           Oct 19, 2012
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Qform;

use QDateTimePicker;
use QJavaScriptAction;
use QKeyUpEvent;
use QListBox;
use QTextBox;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Qform\PublicErrorCollectionAwareTrait;
use Sam\SharedService\PostalCode\PostalCodeSharedServiceClient;


/**
 * Class LotCustomFieldMobileFilterControlsManager
 */
class LotCustomFieldMobileFilterControlsManager extends LotCustomFieldFilterControlsManager
{
    use PublicErrorCollectionAwareTrait;

    private string $wrapperElement = 'li';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create and initialize array of controls for custom field filtering options
     * We also register some javascript functionality related to controls
     *
     * @return static
     */
    public function create(): static
    {
        $js = null;
        $controls = [];
        $translation = $this->getLotCustomFieldTranslationManager();
        foreach ($this->lotCustomFields as $lotCustomField) {
            $fieldName = $this->isPublic ? $translation->translateName($lotCustomField) : $lotCustomField->Name;
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                case Constants\CustomField::TYPE_DECIMAL:
                    $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                    $txtMin = new QTextBox($this->parent, $paramKeyMin);
                    if (isset($this->getParams[$paramKeyMin])) {
                        $txtMin->Text = $this->getParams[$paramKeyMin];
                    }
                    $txtMin->Name = $fieldName;
                    $txtMin->SetCustomAttribute('placeholder', 'Min');
                    $controls[$paramKeyMin] = $txtMin;

                    $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                    $txtMax = new QTextBox($this->parent, $paramKeyMax);
                    if (isset($this->getParams[$paramKeyMax])) {
                        $txtMax->Text = $this->getParams[$paramKeyMax];
                    }
                    $txtMax->Name = $fieldName;
                    $txtMax->SetCustomAttribute('placeholder', 'Max');
                    $controls[$paramKeyMax] = $txtMax;
                    break;

                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_FILE:
                case Constants\CustomField::TYPE_YOUTUBELINK:
                    $paramKey = $this->makeParamKey($lotCustomField->Id);
                    $txt = new QTextBox($this->parent, $paramKey);
                    if (isset($this->getParams[$paramKey])) {
                        $txt->Text = $this->getParams[$paramKey];
                    }
                    $txt->Name = $fieldName;
                    $txt->SetCustomAttribute('placeholder', $fieldName);
                    $controls[$paramKey] = $txt;
                    break;

                case Constants\CustomField::TYPE_SELECT:
                    $paramKey = $this->makeParamKey($lotCustomField->Id);
                    $optionNames = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($lotCustomField->Parameters);
                    $optionValueList = $this->isPublic
                        ? $translation->translateParameters($lotCustomField)
                        : $lotCustomField->Parameters;
                    $optionValues = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($optionValueList);
                    $lst = new QListBox($this->parent, $paramKey);
                    $lst->AddItem($fieldName);
                    foreach ($optionNames as $k => $optionName) {
                        $optionValue = trim($optionName);
                        $optionNameOutput = $optionValues[$k] ?? $optionName;
                        $optionNameOutput = trim($optionNameOutput);
                        $lst->AddItem($optionNameOutput, $optionValue);
                    }
                    if (isset($this->getParams[$paramKey])) {
                        $lst->SelectedValue = $this->getParams[$paramKey];
                    }
                    $lst->Name = $fieldName;
                    $controls[$paramKey] = $lst;
                    break;

                case Constants\CustomField::TYPE_DATE:
                    $format = $this->getDateHelper()->getDateDisplayFormat($this->getSystemAccountId(), true);

                    $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                    $txtMin = new QDateTimePicker($this->parent, $paramKeyMin, $format);
                    if (isset($this->getParams[$paramKeyMin])) {
                        $txtMin->Text = $this->getParams[$paramKeyMin];
                    }
                    $txtMin->Name = $fieldName;
                    $controls[$paramKeyMin] = $txtMin;

                    $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                    $txtMax = new QDateTimePicker($this->parent, $paramKeyMax, $format);
                    if (isset($this->getParams[$paramKeyMax])) {
                        $txtMax->Text = $this->getParams[$paramKeyMax];
                    }
                    $txtMax->Name = $fieldName;
                    $controls[$paramKeyMax] = $txtMax;
                    break;

                case Constants\CustomField::TYPE_POSTALCODE:
                    $paramKeyForRadius = $this->makeParamKeyForRadius($lotCustomField->Id);
                    $paramKeyPostalCode = $this->makeParamKeyForPostalCode($lotCustomField->Id);
                    $txtPostalCode = new QTextBox($this->parent, $paramKeyPostalCode);
                    if (isset($this->getParams[$paramKeyPostalCode])) {
                        $txtPostalCode->Text = $this->getParams[$paramKeyPostalCode];
                    }
                    $txtPostalCode->Name = $fieldName;
                    $jsToggleRadius = "sam.toggleRadius('" . $paramKeyPostalCode . "', '" . $paramKeyForRadius . "');";
                    $txtPostalCode->AddAction(new QKeyUpEvent(), new QJavaScriptAction($jsToggleRadius));
                    $js .= $jsToggleRadius;
                    $lstRadius = new QListBox($this->parent, $paramKeyForRadius);
                    $radiuses = $this->cfg()->get('core->lot->customField->postalCode->searchRadius')->toArray();
                    foreach ($radiuses as $radius) {
                        $name = $radius . ' ' . $this->getTranslator()->translate('SEARCH_RADIUS_MILES', 'search');
                        $lstRadius->AddItem($name, $radius);
                    }
                    if (isset($this->getParams[$paramKeyForRadius])) {
                        $lstRadius->SelectedValue = $this->getParams[$paramKeyForRadius];
                    }
                    $lstRadius->Name = $this->getTranslator()->translate('SEARCH_RADIUS', 'search');
                    $txtPostalCode->SetCustomAttribute('placeholder', $fieldName);
                    $controls[$paramKeyPostalCode] = $txtPostalCode;
                    $controls[$paramKeyForRadius] = $lstRadius;
                    break;
            }
        }
        $this->controls = $controls;
        if ($js) {
            $this->getQformHelper()->executeJs($js);
        }
        return $this;
    }

    public function validate(): bool
    {
        $this->getPublicErrorCollection()->clearErrors();
        $this->validatePostalCode();
        $this->validateIntegerRange();
        $this->validateDecimalRange();
        $hasError = $this->getPublicErrorCollection()->hasErrors();
        return !$hasError;
    }

    /**
     * Validate controls of custom fields filtering options and related options (Sort By selection)
     */
    protected function validatePostalCode(): void
    {
        $isPostalCodeFilled = false;
        $translation = $this->getLotCustomFieldTranslationManager();
        foreach ($this->lotCustomFields as $lotCustomField) {
            $fieldName = $this->isPublic ? $translation->translateName($lotCustomField) : $lotCustomField->Name;
            if ($lotCustomField->Type === Constants\CustomField::TYPE_POSTALCODE) {
                $paramKeyForPostalCode = $this->makeParamKeyForPostalCode($lotCustomField->Id);
                $txtPostalCode = $this->controls[$paramKeyForPostalCode];
                $paramKeyForRadius = $this->makeParamKeyForRadius($lotCustomField->Id);
                $lstRadius = $this->controls[$paramKeyForRadius];
                $postalCode = trim($txtPostalCode->Text);
                if ($postalCode !== '') {
                    $isPostalCodeFilled = true;
                    $errorMessage = '';
                    // Check postal code
                    if (!AddressChecker::new()->isPostalCode($postalCode)) {
                        $errorMessage = $this->getTranslator()->translate('SEARCH_ERR_POSTAL_CODE_FORMAT', 'search');
                    } else {
                        $postalCodeCoordinates[$postalCode] = PostalCodeSharedServiceClient::new()->findCoordinates($postalCode);
                        if (
                            !$postalCodeCoordinates[$postalCode]
                            || $postalCodeCoordinates[$postalCode]['latitude'] === null
                            || $postalCodeCoordinates[$postalCode]['longitude'] === null
                        ) {
                            $errorMessage = $this->getTranslator()->translate('SEARCH_ERR_POSTAL_CODE_NOT_FOUND', 'search');
                        }
                    }
                    if ($errorMessage) {
                        $this->getPublicErrorCollection()->addError($txtPostalCode->ControlId, $errorMessage, $fieldName);
                    }
                    // Check radius
                    if ($lstRadius->SelectedValue === null) {
                        $errorMessage = $this->getTranslator()->translate('SEARCH_ERR_SELECT_RADIUS', 'search');
                        $this->getPublicErrorCollection()->addError($lstRadius->ControlId, $errorMessage, $fieldName);
                    }
                }
            }
        }
        // Check ordering by distance related values filled correctly
        if (
            $this->lstSortCriteria
            && $this->lstSortCriteria->SelectedValue === 'distance'
            && !$isPostalCodeFilled
        ) {
            $errorMessage = $this->getTranslator()->translate('SEARCH_ERR_POSTAL_CODE_EMPTY', 'search');
            $this->getPublicErrorCollection()->addError(
                $this->lstSortCriteria->ControlId . '_ctl > .sbHolder',
                $errorMessage,
                "Postal Code"
            );
        }
    }

    /**
     * Validate 'Integer Type' custom field
     */
    protected function validateIntegerRange(): void
    {
        $translation = $this->getLotCustomFieldTranslationManager();
        foreach ($this->lotCustomFields as $lotCustomField) {
            if ($lotCustomField->Type === Constants\CustomField::TYPE_INTEGER) {
                $fieldName = $this->isPublic ? $translation->translateName($lotCustomField) : $lotCustomField->Name;
                $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                $txtMin = $this->controls[$paramKeyMin];
                $txtMax = $this->controls[$paramKeyMax];
                $min = trim($txtMin->Text);
                $max = trim($txtMax->Text);

                if (
                    $min !== ''
                    && !NumberValidator::new()->isInt($min)
                ) {
                    $minErrorMessage = $this->getTranslator()->translate('SEARCH_ERR_MIN_NUMERIC_INTEGER', 'search');
                    $this->getPublicErrorCollection()->addError($txtMin->ControlId, $minErrorMessage, $fieldName);
                }

                if (
                    $max !== ''
                    && !NumberValidator::new()->isInt($max)
                ) {
                    $maxErrorMessage = $this->getTranslator()->translate('SEARCH_ERR_MAX_NUMERIC_INTEGER', 'search');
                    $this->getPublicErrorCollection()->addError($txtMax->ControlId, $maxErrorMessage, $fieldName);
                }

                if (
                    NumberValidator::new()->isInt($min)
                    && NumberValidator::new()->isInt($max)
                    && $max <= $min
                ) {
                    $maxErrorMessage = $this->getTranslator()->translate('SEARCH_ERR_MAX_SHOULD_BE_GREATER_THAN_MIN', 'search');
                    $this->getPublicErrorCollection()->addError($txtMax->ControlId, $maxErrorMessage, $fieldName);
                }
            }
        }
    }

    /**
     * Validate Decimal Type custom field
     */
    protected function validateDecimalRange(): void
    {
        $translation = $this->getLotCustomFieldTranslationManager();
        foreach ($this->lotCustomFields as $lotCustomField) {
            if ($lotCustomField->Type === Constants\CustomField::TYPE_DECIMAL) {
                $fieldName = $this->isPublic ? $translation->translateName($lotCustomField) : $lotCustomField->Name;
                $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                $txtMin = $this->controls[$paramKeyMin];
                $txtMax = $this->controls[$paramKeyMax];
                $min = trim($txtMin->Text);
                $max = trim($txtMax->Text);

                if (
                    $min !== ''
                    && !NumberValidator::new()->isReal($min)
                ) {
                    $minErrorMessage = $this->getTranslator()->translate('SEARCH_ERR_MIN_NUMERIC', 'search');
                    $this->getPublicErrorCollection()->addError($txtMin->ControlId, $minErrorMessage, $fieldName);
                }

                if (
                    $max !== ''
                    && !NumberValidator::new()->isReal($max)
                ) {
                    $maxErrorMessage = $this->getTranslator()->translate('SEARCH_ERR_MAX_NUMERIC', 'search');
                    $this->getPublicErrorCollection()->addError($txtMax->ControlId, $maxErrorMessage, $fieldName);
                }

                if (
                    NumberValidator::new()->isReal($min)
                    && NumberValidator::new()->isReal($max)
                    && Floating::lteq($max, $min)
                ) {
                    $maxErrorMessage = $this->getTranslator()->translate('SEARCH_ERR_MAX_SHOULD_BE_GREATER_THAN_MIN', 'search');
                    $this->getPublicErrorCollection()->addError($txtMax->ControlId, $maxErrorMessage, $fieldName);
                }
            }
        }
    }

    /**
     * Return html for filtering by custom fields and pre-format for mobile UI
     *
     * @return string
     */
    public function getHtml(): string
    {
        $output = '';
        $clearTag = '<div class="clear"></div>';
        foreach ($this->lotCustomFields as $lotCustomField) {
            $output .= '<' . $this->wrapperElement;
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                case Constants\CustomField::TYPE_DECIMAL:
                case Constants\CustomField::TYPE_DATE:
                    $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                    $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                    $output .= ' class="sminput custom-ranged bottom-border">';
                    $output .= '<label class="title">' . $this->controls[$paramKeyMin]->Name . '</label>';
                    $output .= $clearTag;
                    /** @var QTextBox $txtMin */
                    $txtMin = $this->controls[$paramKeyMin];
                    $output .= $txtMin->RenderWithError(false);
                    $output .= ' <label class="separator">-</label> ';
                    /** @var QTextBox $txtMax */
                    $txtMax = $this->controls[$paramKeyMax];
                    $output .= $txtMax->RenderWithError(false);
                    break;

                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                    $paramKey = $this->makeParamKey($lotCustomField->Id);
                    $output .= ' class="bottom-border">';
                    /** @var QTextBox $txtText */
                    $txtText = $this->controls[$paramKey];
                    $output .= $txtText->Render(false);
                    $output .= $clearTag;
                    break;

                case Constants\CustomField::TYPE_SELECT:
                    $paramKey = $this->makeParamKey($lotCustomField->Id);
                    /** @var QListBox $lstSelect */
                    $lstSelect = $this->controls[$paramKey];
                    $output .= ' class="drppad bottom-border">';
                    $output .= '<label class="title">' . $lstSelect->Name . '</label>';
                    $output .= $clearTag;
                    $output .= '<div class="drplist">';
                    $output .= $lstSelect->Render(false, "CssClass=customselect");
                    $output .= $clearTag;
                    $output .= '</div>';
                    break;

                case Constants\CustomField::TYPE_POSTALCODE:
                    $paramKeyForPostalCode = $this->makeParamKeyForPostalCode($lotCustomField->Id);
                    $paramKeyForRadius = $this->makeParamKeyForRadius($lotCustomField->Id);
                    $output .= ' class="sminput bottom-border">';
                    /** @var QTextBox $txtPostalCode */
                    $txtPostalCode = $this->controls[$paramKeyForPostalCode];
                    $output .= $txtPostalCode->RenderWithError(false);
                    $radiusHtml = '<span class="radius">' . $this->getTranslator()->translate('SEARCH_RADIUS', 'search') . ':</span>';
                    /** @var QListBox $lstRadius */
                    $lstRadius = $this->controls[$paramKeyForRadius];
                    $radiusHtml .= $lstRadius->RenderWithError(false, "CssClass=customselect");
                    $output .= $clearTag;
                    $output .= sprintf('<span id="div' . $paramKeyForRadius . '"><br />%s</span>', $radiusHtml);
                    break;

                default:
                    $output .= '><!-- type: ' . $lotCustomField->Type . '-->';
                    break;
            }

            $output .= $clearTag . '</' . $this->wrapperElement . '>';
        }
        return $output;
    }

    /**
     * @param string $wrapperElement
     * @return static
     */
    public function setWrapperElement(string $wrapperElement): static
    {
        $this->wrapperElement = $wrapperElement;
        return $this;
    }

}
