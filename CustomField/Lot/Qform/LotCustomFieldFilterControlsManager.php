<?php

/**
 * Helper class for QCodo controllers (drafts), which work with custom field filtering controls.
 * Front end: individual lot search, my alerts, sign up with preferences panel.
 * Back end: custom lots report.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: FilterControls.php 21694 2015-06-30 20:56:27Z SWB\bpanizales $
 * @since           Oct 19, 2012
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Qform;

use LotItemCustField;
use QDateTimePicker;
use QForm;
use QJavaScriptAction;
use QKeyUpEvent;
use QListBox;
use QPanel;
use QTextBox;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Css\CssTransformer;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\CustomField\Lot\Translate\LotCustomFieldTranslationManagerAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;
use Sam\Qform\QformHelperAwareTrait;
use Sam\SharedService\PostalCode\PostalCodeSharedServiceClientAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class LotCustomFieldFilterControlsManager
 */
class LotCustomFieldFilterControlsManager extends CustomizableClass
{
    use BaseCustomFieldHelperAwareTrait;
    use ConfigRepositoryAwareTrait;
    use DateHelperAwareTrait;
    use FormStateLongevityAwareTrait;
    use LotCustomFieldTranslationManagerAwareTrait;
    use NumberFormatterAwareTrait;
    use PostalCodeSharedServiceClientAwareTrait;
    use QformHelperAwareTrait;
    use SystemAccountAwareTrait;
    use TranslatorAwareTrait;

    protected array $controls = [];
    /** @var LotItemCustField[] */
    protected array $lotCustomFields = [];
    protected ?string $groupId = null;
    protected bool $isPublic = false;
    protected string $js = '';
    protected QForm|QPanel|null $parent = null;
    protected array $getParams = [];
    protected ?QListBox $lstSortCriteria = null;

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
        $controls = [];
        $dateDisplayFormat = $this->getDateHelper()->getDateDisplayFormat($this->getSystemAccountId());
        $translation = $this->getLotCustomFieldTranslationManager();
        foreach ($this->lotCustomFields as $lotCustomField) {
            $name = $this->isPublic ? $translation->translateName($lotCustomField) : $lotCustomField->Name;
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                case Constants\CustomField::TYPE_DECIMAL:
                    $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                    /** @var QTextBox $txtMin */
                    $txtMin = $this->controls[$paramKeyMin] ?? new QTextBox($this->parent, $paramKeyMin);
                    $txtMin->Width = 100;
                    if (isset($this->getParams[$paramKeyMin])) {
                        $txtMin->Text = $this->getParams[$paramKeyMin];
                    }
                    $txtMin->Name = $name;
                    $txtMin->SetCustomAttribute('placeholder', 'Min');
                    $controls[$paramKeyMin] = $txtMin;

                    $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                    $txtMax = $this->controls[$paramKeyMax] ?? new QTextBox($this->parent, $paramKeyMax);
                    $txtMax->Width = 100;
                    if (isset($this->getParams[$paramKeyMax])) {
                        $txtMax->Text = $this->getParams[$paramKeyMax];
                    }
                    $txtMax->Name = $name;
                    $txtMax->SetCustomAttribute('placeholder', 'Max');
                    $controls[$paramKeyMax] = $txtMax;
                    break;

                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_FILE:
                case Constants\CustomField::TYPE_YOUTUBELINK:
                    $paramKey = $this->makeParamKey($lotCustomField->Id);
                    $txtText = $this->controls[$paramKey] ?? new QTextBox($this->parent, $paramKey);
                    $txtText->Width = 100;
                    if (isset($this->getParams[$paramKey])) {
                        $txtText->Text = $this->getParams[$paramKey];
                    }
                    $txtText->Name = $name;
                    $txtText->SetCustomAttribute('placeholder', $name);
                    $controls[$paramKey] = $txtText;
                    break;

                case Constants\CustomField::TYPE_SELECT:
                    $optionNameList = $this->isPublic
                        ? $translation->translateParameters($lotCustomField)
                        : $lotCustomField->Parameters;
                    $optionNames = [];
                    if ($optionNameList) {
                        $optionNames = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($optionNameList);
                    }
                    $optionValueList = $lotCustomField->Parameters;
                    $optionValues = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($optionValueList);
                    $options = [];
                    foreach ($optionValues as $i => $value) {
                        $options[$value] = $optionNames[$i] ?? $value;
                    }
                    $paramKey = $this->makeParamKey($lotCustomField->Id);
                    /** @var QListBox $lstSelect */
                    $lstSelect = $this->controls[$paramKey] ?? new QListBox($this->parent, $paramKey);
                    $lstSelect->AddItem('-Select-');
                    foreach ($options as $value => $optionName) {
                        $lstSelect->AddItem($optionName, $value);
                    }
                    if (isset($this->getParams[$paramKey])) {
                        $lstSelect->SelectedValue = $this->getParams[$paramKey];
                    }
                    $lstSelect->Name = $name;
                    $controls[$paramKey] = $lstSelect;
                    break;

                case Constants\CustomField::TYPE_DATE:
                    $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);

                    $jscMin = $this->controls[$paramKeyMin] ?? new QDateTimePicker($this->parent, $paramKeyMin, $dateDisplayFormat);
                    $jscMin->Text = $this->getParams[$paramKeyMin] ?? '';
                    $controls[$paramKeyMin] = $jscMin;

                    $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                    $jscMax = $this->controls[$paramKeyMax] ?? new QDateTimePicker($this->parent, $paramKeyMax, $dateDisplayFormat);
                    $jscMax->Text = $this->getParams[$paramKeyMax] ?? '';

                    $controls[$paramKeyMax] = $jscMax;
                    break;

                case Constants\CustomField::TYPE_POSTALCODE:
                    // Text box for Postal code creation
                    $paramKeyForRadius = $this->makeParamKeyForRadius($lotCustomField->Id);
                    $paramKeyPostalCode = $this->makeParamKeyForPostalCode($lotCustomField->Id);
                    $txtPostalCode = $this->controls[$paramKeyPostalCode] ?? new QTextBox($this->parent, $paramKeyPostalCode);
                    $txtPostalCode->Width = 100;
                    if (isset($this->getParams[$paramKeyPostalCode])) {
                        $txtPostalCode->Text = $this->getParams[$paramKeyPostalCode];
                    }
                    $txtPostalCode->Name = $name;
                    $jsToggleRadius = "sam.toggleRadius('" . $paramKeyPostalCode . "', '" . $paramKeyForRadius . "');";
                    $txtPostalCode->AddAction(new QKeyUpEvent(), new QJavaScriptAction($jsToggleRadius));
                    $txtPostalCode->SetCustomAttribute('placeholder', $name);
                    $this->js .= $jsToggleRadius;
                    $controls[$paramKeyPostalCode] = $txtPostalCode;

                    // Search radius selection list creation
                    $lstRadius = $this->controls[$paramKeyForRadius] ?? new QListBox($this->parent, $paramKeyForRadius);
                    $radiuses = $this->cfg()->get('core->lot->customField->postalCode->searchRadius')->toArray();
                    foreach ($radiuses as $radius) {
                        $lstRadius->AddItem($radius . ' ' . $this->getTranslator()->translate('SEARCH_RADIUS_MILES', 'search'), $radius);
                    }
                    if (isset($this->getParams[$paramKeyForRadius])) {
                        $lstRadius->SelectedValue = $this->getParams[$paramKeyForRadius];
                    }
                    $lstRadius->Name = $this->getTranslator()->translate('SEARCH_RADIUS', 'search');
                    $controls[$paramKeyForRadius] = $lstRadius;
                    break;
            }
        }
        if ($this->js) {
            $this->getQformHelper()->executeJs($this->js);
        }
        $this->controls = $controls;
        return $this;
    }

    /**
     * Validate controls of custom fields filtering options and related options (Sort By selection)
     * @return bool
     */
    public function validate(): bool
    {
        $hasError = false;
        $isGeolocationError = false;
        $isPostalCodeFilled = false;
        foreach ($this->lotCustomFields as $lotCustomField) {
            if ($lotCustomField->Type === Constants\CustomField::TYPE_POSTALCODE) {
                $paramKeyForPostalCode = $this->makeParamKeyForPostalCode($lotCustomField->Id);
                /** @var QTextBox $txtPostalCode */
                $txtPostalCode = $this->controls[$paramKeyForPostalCode];
                $paramKeyForRadius = $this->makeParamKeyForRadius($lotCustomField->Id);
                $lstRadius = $this->controls[$paramKeyForRadius];
                if ($txtPostalCode->Text !== '') {
                    $isPostalCodeFilled = true;
                    $translator = $this->getTranslator();
                    // Check postal code
                    if (!AddressChecker::new()->isPostalCode($txtPostalCode->Text)) {
                        $txtPostalCode->Warning = $translator->translate('SEARCH_ERR_POSTAL_CODE_FORMAT', 'search');
                        $isGeolocationError = true;
                    } else {
                        $postalCodeCoordinates[$txtPostalCode->Text] = $this->getPostalCodeSharedServiceClient()
                            ->findCoordinates($txtPostalCode->Text);
                        if (!$postalCodeCoordinates[$txtPostalCode->Text]) {
                            $txtPostalCode->Warning = $translator->translate('SEARCH_ERR_POSTAL_CODE_NOT_FOUND', 'search');
                            $isGeolocationError = true;
                        }
                        // Check radius
                        if ($lstRadius->SelectedValue === null) {
                            $lstRadius->Warning = $translator->translate('SEARCH_ERR_SELECT_RADIUS', 'search');
                            $isGeolocationError = true;
                        }
                    }
                    break;
                }
            }
        }
        // Check ordering by distance related values filled correctly
        if (
            $this->lstSortCriteria
            && $this->lstSortCriteria->SelectedValue === 'distance'
            && (
                !$isPostalCodeFilled
                || $isGeolocationError
            )
        ) {
            $this->lstSortCriteria->Warning = $this->getTranslator()->translate('SEARCH_ERR_POSTAL_CODE_EMPTY', 'search');
            $hasError = true;
        }
        $hasError = $hasError || $isGeolocationError;
        return !$hasError;
    }

    /**
     * Return html for filtering by custom fields
     *
     * @return string
     */
    public function getHtml(): string
    {
        $output = '';
        foreach ($this->lotCustomFields as $lotCustomField) {
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                case Constants\CustomField::TYPE_DECIMAL:
                case Constants\CustomField::TYPE_DATE:
                    $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                    $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                    $output .= $this->controls[$paramKeyMin]->Name . ': <br />';
                    $output .= $this->controls[$paramKeyMin]->RenderWithError(false) . ' - ';
                    $output .= $this->controls[$paramKeyMax]->RenderWithError(false);
                    break;

                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_SELECT:
                case Constants\CustomField::TYPE_YOUTUBELINK:
                    $paramKey = $this->makeParamKey($lotCustomField->Id);
                    /** @var QTextBox $txtText */
                    $txtText = $this->controls[$paramKey];
                    $output .= $txtText->Name . ': <br />';
                    $output .= $txtText->RenderWithError(false);
                    break;

                case Constants\CustomField::TYPE_POSTALCODE:
                    $paramKeyForPostalCode = $this->makeParamKeyForPostalCode($lotCustomField->Id);
                    /** @var QTextBox $txtPostalCode */
                    $txtPostalCode = $this->controls[$paramKeyForPostalCode];
                    $output .= $txtPostalCode->Name . ': <br />';
                    $output .= $txtPostalCode->RenderWithError(false);

                    $paramKeyForRadius = $this->makeParamKeyForRadius($lotCustomField->Id);
                    /** @var QListBox $lstRadius */
                    $lstRadius = $this->controls[$paramKeyForRadius];
                    $radiusHtml = $this->getTranslator()->translate('SEARCH_RADIUS', 'search') . ': <br />';
                    $radiusHtml .= $lstRadius->RenderWithError(false);
                    $paramKey = $this->makeParamKey($lotCustomField->Id);
                    $output .= sprintf('<span id="div' . $paramKey . '"><br />%s</span>', $radiusHtml);
                    break;
            }
            $output .= '<br />';
        }

        return $output;
    }

    /**
     * @return string
     */
    public function getVerticalHtml(): string
    {
        $output = '';
        foreach ($this->lotCustomFields as $lotCustomField) {
            $output .= '<div class="tr-input fleft"><div class="label-input"><div class="label">';
            $output .= ee($lotCustomField->Name) . ': </div><div class="input">';
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                case Constants\CustomField::TYPE_DECIMAL:
                case Constants\CustomField::TYPE_DATE:
                    $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                    $output .= $this->controls[$paramKeyMin]->RenderWithError(false);
                    $output .= '<span class="dividerDash"> - </span>';
                    $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                    $output .= $this->controls[$paramKeyMax]->RenderWithError(false);
                    break;

                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_SELECT:
                case Constants\CustomField::TYPE_FILE:
                case Constants\CustomField::TYPE_YOUTUBELINK:
                    $paramKey = $this->makeParamKey($lotCustomField->Id);
                    $output .= $this->controls[$paramKey]->RenderWithError(false);
                    break;

                case Constants\CustomField::TYPE_POSTALCODE:
                    $paramKeyForPostalCode = $this->makeParamKeyForPostalCode($lotCustomField->Id);
                    $output .= $this->controls[$paramKeyForPostalCode]->RenderWithError(false);
                    $paramKeyForRadius = $this->makeParamKeyForRadius($lotCustomField->Id);
                    $output .= '<div id="div' . $paramKeyForRadius . '">';
                    $output .= $this->getTranslator()->translate('SEARCH_RADIUS', 'search') . ': <br />';
                    $output .= $this->controls[$paramKeyForRadius]->RenderWithError(false);
                    $output .= '</div>';
                    break;
            }
            $output .= '</div></div></div>';
        }
        return $output;
    }

    /**
     * @return string
     */
    public function getListFormatted(): string
    {
        $output = '';
        foreach ($this->lotCustomFields as $lotCustomField) {
            /**
             * YV, SAM-7975, 10.04.2021: We have different css class names for custom fields here,
             * at the same time we use almost same building logic as @see \Sam\View\Responsive\Form\AuctionList::makeCssClassNameByAuctionCustomField
             * I think, CSS class names should be same.
             * (Can we move this building logic to a external class? (some css classes building helper class.. etc.))
             */

            $customClassName = match ($lotCustomField->Type) {
                Constants\CustomField::TYPE_INTEGER => 'custom-field-integer',
                Constants\CustomField::TYPE_DECIMAL => 'custom-field-decimal',
                Constants\CustomField::TYPE_DATE => 'custom-field-date',
                Constants\CustomField::TYPE_TEXT => 'custom-field-text',
                Constants\CustomField::TYPE_FULLTEXT => 'custom-field-fulltext',
                Constants\CustomField::TYPE_YOUTUBELINK => 'custom-field-youtube',
                Constants\CustomField::TYPE_SELECT => 'custom-field-select',
                Constants\CustomField::TYPE_FILE => 'custom-field-file',
                Constants\CustomField::TYPE_POSTALCODE => 'custom-field-postalcode',
                default => '',
            };
            $customClassName .= ($customClassName ? ' ' : '')
                . 'custom-field-name-' . strtolower(CssTransformer::new()->toClassName($lotCustomField->Name));

            $output .= '<div class="group solo custom-field ' . $customClassName . '">' . "\n"
                . '  <div class="group solo">' . "\n";

            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                case Constants\CustomField::TYPE_DECIMAL:
                case Constants\CustomField::TYPE_DATE:
                    $output .= '<div class="label-input">';
                    $output .= '<div class="label">' . ee($lotCustomField->Name) . '</div>';
                    $output .= '<div class="input-min">';
                    $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                    $output .= $this->controls[$paramKeyMin]->RenderWithError(false);
                    $output .= '</div>';
                    $output .= '<div class="input-max">';
                    $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                    $output .= $this->controls[$paramKeyMax]->RenderWithError(false);
                    $output .= '</div>';
                    $output .= '</div>';
                    break;

                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_SELECT:
                case Constants\CustomField::TYPE_FILE:
                case Constants\CustomField::TYPE_YOUTUBELINK:
                    $output .= '<div class="label-input">';
                    $output .= '<div class="label">' . ee($lotCustomField->Name) . '</div>';
                    $paramKey = $this->makeParamKey($lotCustomField->Id);
                    $output .= $this->controls[$paramKey]->RenderWithError(false);
                    $output .= '</div>';
                    break;

                case Constants\CustomField::TYPE_POSTALCODE:
                    $output .= '<div class="label-input">';
                    $output .= '<div class="label">' . ee($lotCustomField->Name) . '</div>';
                    $paramKeyForPostalCode = $this->makeParamKeyForPostalCode($lotCustomField->Id);
                    $output .= $this->controls[$paramKeyForPostalCode]->RenderWithError(false);
                    $paramKeyForRadius = $this->makeParamKeyForRadius($lotCustomField->Id);
                    $output .= '<div id="div' . $paramKeyForRadius . '">';
                    $output .= 'Search ';
                    $output .= $this->controls[$paramKeyForRadius]->RenderWithError(false);
                    $output .= '</div>';
                    $output .= '</div>';
                    break;
            }
            $output .= '  </div>' . "\n"
                . '</div>' . "\n";
        }

        return $output;
    }

    /**
     * Get values from controls and return parameters array, which we are using for filtering by custom fields in result query
     *
     * @return array[lot_item_cust_field.id] => filter params
     */
    public function getFilterParams(): array
    {
        $lotCustomFieldFilters = [];
        foreach ($this->lotCustomFields as $lotCustomField) {
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $min = null;
                    $max = null;
                    $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                    /** @var QTextBox $txtMin */
                    $txtMin = $this->controls[$paramKeyMin];
                    $value = trim($txtMin->Text);
                    $numberFormatter = $this->getNumberFormatter();
                    if ($value !== '') {
                        $min = (int)$numberFormatter->removeFormat($value);
                    }

                    $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                    /** @var QTextBox $txtMax */
                    $txtMax = $this->controls[$paramKeyMax];
                    $value = trim($txtMax->Text);
                    if ($value !== '') {
                        $max = (int)$numberFormatter->removeFormat($value);
                    }
                    if ($min !== null || $max !== null) {
                        $lotCustomFieldFilters[$lotCustomField->Id] = ['min' => $min, 'max' => $max];
                    }
                    break;

                case Constants\CustomField::TYPE_DECIMAL:
                    $min = null;
                    $max = null;
                    $decimal = $lotCustomField->Parameters !== '' ? (int)$lotCustomField->Parameters : 2;
                    $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                    /** @var QTextBox $txtMin */
                    $txtMin = $this->controls[$paramKeyMin];
                    $value = trim($txtMin->Text);
                    $numberFormatter = $this->getNumberFormatter();
                    if ($value !== '') {
                        $min = $numberFormatter->parse($value, $decimal);
                    }

                    $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                    /** @var QTextBox $txtMax */
                    $txtMax = $this->controls[$paramKeyMax];
                    $value = trim($txtMax->Text);
                    if ($value !== '') {
                        $max = $numberFormatter->parse($value, $decimal);
                    }
                    if ($min !== null || $max !== null) {
                        $lotCustomFieldFilters[$lotCustomField->Id] = ['min' => $min, 'max' => $max];
                    }
                    break;

                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_FILE:
                case Constants\CustomField::TYPE_YOUTUBELINK:
                    $paramKey = $this->makeParamKey($lotCustomField->Id);
                    /** @var QTextBox $txtText */
                    $txtText = $this->controls[$paramKey];
                    $value = trim($txtText->Text);
                    if ($value !== '') {
                        $lotCustomFieldFilters[$lotCustomField->Id] = $value;
                    }
                    break;

                case Constants\CustomField::TYPE_SELECT:
                    $paramKey = $this->makeParamKey($lotCustomField->Id);
                    /** @var QListBox $lstSelect */
                    $lstSelect = $this->controls[$paramKey];
                    $selectedValue = (string)$lstSelect->SelectedValue;
                    if ($selectedValue !== '') {
                        $lotCustomFieldFilters[$lotCustomField->Id] = $selectedValue;
                    }
                    break;

                case Constants\CustomField::TYPE_DATE:
                    $min = null;
                    $max = null;
                    $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                    /** @var QDateTimePicker $jscMinDate */
                    $jscMinDate = $this->controls[$paramKeyMin];
                    if (trim($jscMinDate->Text) !== '') {
                        $date = $jscMinDate->convertToDatetime();
                        $min = (int)$date?->getTimestamp();
                    }

                    $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                    /** @var QDateTimePicker $jscMaxDate */
                    $jscMaxDate = $this->controls[$paramKeyMax];
                    if (trim($jscMaxDate->Text) !== '') {
                        $date = $jscMaxDate->convertToDatetime();
                        $max = (int)$date?->getTimestamp();
                    }

                    if (
                        $min !== null
                        || $max !== null
                    ) {
                        $lotCustomFieldFilters[$lotCustomField->Id] = ['min' => $min, 'max' => $max];
                    }
                    break;

                case Constants\CustomField::TYPE_POSTALCODE:
                    $postalCode = null;
                    $radius = null;
                    $paramKeyForPostalCode = $this->makeParamKeyForPostalCode($lotCustomField->Id);
                    /** @var QTextBox $txtPostalCode */
                    $txtPostalCode = $this->controls[$paramKeyForPostalCode];
                    $value = trim($txtPostalCode->Text);
                    if ($value !== '') {
                        $postalCode = $txtPostalCode->Text;
                    }

                    $paramKeyForRadius = $this->makeParamKeyForRadius($lotCustomField->Id);
                    /** @var QListBox $lstRadius */
                    $lstRadius = $this->controls[$paramKeyForRadius];
                    if ($lstRadius->SelectedValue !== null) {
                        $radius = (int)$lstRadius->SelectedValue;
                    }
                    if ($postalCode !== null && $radius !== null) {
                        $lotCustomFieldFilters[$lotCustomField->Id] = ['pcode' => $postalCode, 'radius' => $radius];
                    }
                    break;
            }
        }
        return $lotCustomFieldFilters;
    }

    /**
     * Get values from $_GET and return parameters array, which we are using for filtering by custom fields in result query
     *
     * @return array [lot_item_cust_field.id] => filter params
     */
    public function getFilterParamsByGet(): array
    {
        $lotCustomFieldFilters = [];
        foreach ($this->lotCustomFields as $lotCustomField) {
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                    $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                    $numberFormatter = $this->getNumberFormatter();
                    $min = isset($this->getParams[$paramKeyMin])
                        ? (int)$numberFormatter->removeFormat($this->getParams[$paramKeyMin]) : null;
                    $max = isset($this->getParams[$paramKeyMax])
                        ? (int)$numberFormatter->removeFormat($this->getParams[$paramKeyMax]) : null;
                    if ($min !== null || $max !== null) {
                        $lotCustomFieldFilters[$lotCustomField->Id] = ['min' => $min, 'max' => $max];
                    }
                    break;

                case Constants\CustomField::TYPE_DECIMAL:
                    $min = null;
                    $max = null;
                    $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                    $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                    $minVal = $this->getParams[$paramKeyMin] ?? null;
                    $maxVal = $this->getParams[$paramKeyMax] ?? null;
                    $decimal = $lotCustomField->Parameters !== '' ? (int)$lotCustomField->Parameters : 2;
                    $numberFormatter = $this->getNumberFormatter();
                    if ($minVal) {
                        $min = $numberFormatter->parse($minVal, $decimal);
                    }
                    if ($maxVal) {
                        $max = $numberFormatter->parse($maxVal, $decimal);
                    }
                    if ($min !== null || $max !== null) {
                        $lotCustomFieldFilters[$lotCustomField->Id] = ['min' => $min, 'max' => $max];
                    }
                    break;

                case Constants\CustomField::TYPE_DATE:
                    $min = null;
                    $max = null;
                    $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                    $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                    $minVal = $this->getParams[$paramKeyMin] ?? null;
                    $maxVal = $this->getParams[$paramKeyMax] ?? null;
                    if (
                        $minVal !== null
                        && preg_match('/(\d+)\/(\d+)\/(\d+)/', $minVal, $matches)
                    ) {
                        $min = mktime(0, 0, 0, (int)$matches[1], (int)$matches[2], (int)$matches[3]);
                    }
                    if (
                        $maxVal !== null
                        && preg_match('/(\d+)\/(\d+)\/(\d+)/', $maxVal, $matches)
                    ) {
                        $max = mktime(0, 0, 0, (int)$matches[1], (int)$matches[2], (int)$matches[3]);
                    }
                    if ($min !== null || $max !== null) {
                        $lotCustomFieldFilters[$lotCustomField->Id] = ['min' => $min, 'max' => $max];
                    }
                    break;

                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_YOUTUBELINK:
                case Constants\CustomField::TYPE_SELECT:
                    $paramKey = $this->makeParamKey($lotCustomField->Id);
                    $text = isset($this->getParams[$paramKey]) ? (string)$this->getParams[$paramKey] : '';
                    if ($text !== '') {
                        $lotCustomFieldFilters[$lotCustomField->Id] = $text;
                    }
                    break;

                case Constants\CustomField::TYPE_POSTALCODE:
                    $paramKeyForPostalCode = $this->makeParamKeyForPostalCode($lotCustomField->Id);
                    $paramKeyForRadius = $this->makeParamKeyForRadius($lotCustomField->Id);
                    $postalCode = isset($this->getParams[$paramKeyForPostalCode])
                        ? (string)$this->getParams[$paramKeyForPostalCode]
                        : '';
                    $radius = isset($this->getParams[$paramKeyForRadius]) && is_numeric($this->getParams[$paramKeyForRadius])
                        ? (int)$this->getParams[$paramKeyForRadius]
                        : null;
                    if ($postalCode !== '' && $radius !== null) {
                        $lotCustomFieldFilters[$lotCustomField->Id] = ['pcode' => $postalCode, 'radius' => $radius];
                    }
                    break;
            }
        }
        return $lotCustomFieldFilters;
    }

    /**
     * Return array of filtering parameters ready to be used in query string of url
     * @return array
     */
    public function buildGetArray(): array
    {
        foreach ($this->lotCustomFields as $lotCustomField) {
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                case Constants\CustomField::TYPE_DECIMAL:
                case Constants\CustomField::TYPE_DATE:
                    $paramKeyMin = $this->makeParamKeyForMin($lotCustomField->Id);
                    $paramKeyMax = $this->makeParamKeyForMax($lotCustomField->Id);
                    unset($this->getParams[$paramKeyMin], $this->getParams[$paramKeyMax]);

                    /** @var QTextBox $txtMin */
                    $txtMin = $this->controls[$paramKeyMin];
                    $value = trim($txtMin->Text);
                    if ($value !== '') {
                        $this->getParams[$paramKeyMin] = $value;
                    }
                    /** @var QTextBox $txtMax */
                    $txtMax = $this->controls[$paramKeyMax];
                    $value = trim($txtMax->Text);
                    if ($value !== '') {
                        $this->getParams[$paramKeyMax] = $value;
                    }
                    break;
                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_YOUTUBELINK:
                    $paramKey = $this->makeParamKey($lotCustomField->Id);
                    unset($this->getParams[$paramKey]);
                    /** @var QTextBox $txtText */
                    $txtText = $this->controls[$paramKey];
                    $value = trim($txtText->Text);
                    if ($value !== '') {
                        $this->getParams[$paramKey] = $value;
                    }
                    break;
                case Constants\CustomField::TYPE_SELECT:
                    $paramKey = $this->makeParamKey($lotCustomField->Id);
                    unset($this->getParams[$paramKey]);
                    /** @var QListBox $lstSelect */
                    $lstSelect = $this->controls[$paramKey];
                    $value = (string)$lstSelect->SelectedValue;
                    if ($value !== '') {
                        $this->getParams[$paramKey] = $value;
                    }
                    break;
                case Constants\CustomField::TYPE_POSTALCODE:
                    $paramKeyForPostalCode = $this->makeParamKeyForPostalCode($lotCustomField->Id);
                    $paramKeyForRadius = $this->makeParamKeyForRadius($lotCustomField->Id);
                    unset($this->getParams[$paramKeyForPostalCode], $this->getParams[$paramKeyForRadius]);
                    /** @var QTextBox $txtPostalCode */
                    $txtPostalCode = $this->controls[$paramKeyForPostalCode];
                    $value = trim($txtPostalCode->Text);
                    if ($value !== '') {
                        $this->getParams[$paramKeyForPostalCode] = $value;
                        /** @var QListBox $lstRadius */
                        $lstRadius = $this->controls[$paramKeyForRadius];
                        $this->getParams[$paramKeyForRadius] = $lstRadius->SelectedValue;
                    }
                    break;
            }
        }
        return $this->getParams;
    }

    /**
     * Parent object setter
     *
     * @param QForm|QPanel $parent
     * @return static
     */
    public function setParent(QForm|QPanel $parent): static
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get values setter
     *
     * @param array $params
     * @return static
     */
    public function setGetParams(array $params): static
    {
        $this->getParams = $params;
        return $this;
    }

    /**
     * Set QListBox object
     *
     * @param QListBox|null $lstSortCriteria
     * @return static
     */
    public function setSortCriteria(QListBox $lstSortCriteria = null): static
    {
        $this->lstSortCriteria = $lstSortCriteria;
        return $this;
    }

    /**
     * Set custom field array
     *
     * @param array $lotCustomFields
     * @return static
     */
    public function setCustomFields(array $lotCustomFields): static
    {
        $this->lotCustomFields = $lotCustomFields;
        return $this;
    }

    /**
     * @param array $controls
     * @return static
     * @noinspection PhpUnused
     */
    public function setControls(array $controls): static
    {
        $this->controls = $controls;
        return $this;
    }

    /**
     * Return controls
     * @return array
     */
    public function getControls(): array
    {
        return $this->controls;
    }

    /**
     * @param bool $isPublic
     * @return static
     */
    public function enablePublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;
        return $this;
    }

    /**
     * @param string $groupId
     * @return static
     */
    public function setGroupId(string $groupId): static
    {
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGroupId(): ?string
    {
        return $this->groupId;
    }

    /**
     * @return array|LotItemCustField[]
     */
    public function getCustomFields(): array
    {
        return $this->lotCustomFields;
    }

    /**
     * @param int $customFieldId
     * @return string
     */
    public function makeParamKey(int $customFieldId): string
    {
        return Constants\UrlParam::CUSTOM_FIELD_PREFIX_GENERAL . $customFieldId . $this->groupId;
    }

    public function makeParamKeyForMin(int $customFieldId): string
    {
        return $this->makeParamKey($customFieldId) . Constants\UrlParam::CUSTOM_FIELD_SUFFIX_NUMBER_OR_DATE_MIN;
    }

    public function makeParamKeyForMax(int $customFieldId): string
    {
        return $this->makeParamKey($customFieldId) . Constants\UrlParam::CUSTOM_FIELD_SUFFIX_NUMBER_OR_DATE_MAX;
    }

    public function makeParamKeyForPostalCode(int $customFieldId): string
    {
        return $this->makeParamKey($customFieldId) . Constants\UrlParam::CUSTOM_FIELD_SUFFIX_POSTAL_CODE;
    }

    public function makeParamKeyForRadius(int $customFieldId): string
    {
        return $this->makeParamKey($customFieldId) . Constants\UrlParam::CUSTOM_FIELD_SUFFIX_RADIUS;
    }

    /**
     * Return related javascript
     * TODO: Should be moved to js file when we refactor mobile part . This code called from includes/classes/CustomField/LotItem/Qform/Mobile/FilterControls.php
     * TODO: and from m/views/drafts/advanced_search_panel.tpl.php
     * TODO: and from m/views/drafts/my_alerts_list.php
     * and from /admin/manage-reports/custom-lots
     *
     * @return string
     */
    public function getJs(): string
    {
        $js = <<<JS
window.sam = window.sam || {};
window.sam.toggleRadius = function(paramKeyForPostalCode, paramKeyForRadius) {
    var pcode_val = document.getElementById(paramKeyForPostalCode).value;
    document.getElementById("div" + paramKeyForRadius).style.display = pcode_val != undefined && pcode_val.length > 0 ? '' : "none";
}

JS;
        return $js;
    }
}
