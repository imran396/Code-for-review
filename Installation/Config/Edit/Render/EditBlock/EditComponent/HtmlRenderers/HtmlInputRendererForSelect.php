<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           09-13, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\HtmlRenderers;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\InstallationSettingEditConstants;
use Sam\Installation\Config\Edit\Render\EditBlock\EditBlockRendererHelperCreateTrait;
use Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\EditData\EditComponentData;
use Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData\InputDataWeb;

/**
 * Class HtmlInputRendererForSelect
 * @package Sam\Installation\Config
 */
class HtmlInputRendererForSelect extends CustomizableClass
{
    use EditBlockRendererHelperCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build input control HTML for knownSet values using select list as control elements.
     *
     * @param EditComponentData $ecData
     * @param string $selectBoxType
     * @return string
     * @see \Sam\Core\Constants\Installation::AVAILABLE_ECOM_SELECT_TYPES
     */
    public function render(EditComponentData $ecData, string $selectBoxType): string
    {
        $inputId = $ecData->getInputId();
        $inputData = $ecData->getInputData();
        $labelHtml = $ecData->getLabelHtml();
        $descriptionHtml = $ecData->getDescriptionHtml();
        $infoHtml = $ecData->getInfoHtml();
        $errorHtml = $ecData->getErrorHtml();
        $inputName = $ecData->getInputName();
        $validationPostValue = $this->createEditBlockRendererHelper()->getValidationPostValue($inputData);
        if (!empty($validationPostValue)) {
            $inputData->setValue($validationPostValue);
        }
        $selectBoxType = $this->normalizeSelectType($selectBoxType);
        $renderSelectType = $selectBoxType === Constants\Installation::ECOM_SELECT_TYPE_MULTIPLE ? ' multiple' : '';
        $renderControlName = $selectBoxType === Constants\Installation::ECOM_SELECT_TYPE_MULTIPLE
            ? "{$inputName}[]"
            : $inputName;
        $attributeReadonly = !$inputData->getMetaDescriptor()->isEditable() ? ' disabled readonly' : '';
        $eComWrapperCssClass = InstallationSettingEditConstants::CSS_EDIT_CONTROL_WRAPPER;
        $selectOptionsHtml = $this->renderSelectOptions($inputData);

        $selectHtml = <<<HTML
<select {$renderSelectType} name="{$renderControlName}" class="form-control" id="{$inputId}" {$attributeReadonly}>
    {$selectOptionsHtml}
</select>
HTML;
        $output = <<<HTML
<p>{$labelHtml}</p>
{$descriptionHtml}
{$infoHtml}
{$errorHtml}
<div class="form-group {$eComWrapperCssClass}">{$selectHtml}</div>  
HTML;


        return $output;
    }

    protected function renderSelectOptions(InputDataWeb $inputData): string
    {
        $descriptor = $inputData->getMetaDescriptor();
        $metaKnownSet = $descriptor->getKnownSet();
        $metaKnownSetNames = $descriptor->getKnownSetNames();
        $inputDataValue = $inputData->getValue();
        $useMetaSet = count($metaKnownSet) ? $metaKnownSet : $metaKnownSetNames;
        $knownSetType = count($metaKnownSetNames) ? 'withNames' : 'simple';
        $isNamedKnownSet = $knownSetType === 'withNames';

        $selectOptionsHtml = '';
        foreach ($useMetaSet ?: [] as $setValue => $setTitle) {
            $renderValue = $isNamedKnownSet ? $setValue : $setTitle;
            $selected = $this->renderSelectedOption($renderValue, $inputDataValue);
            $renderTitle = $this->renderOptionTitle($isNamedKnownSet, $setTitle, $renderValue);

            $selectOption = <<<HTML
<option value="{$renderValue}" {$selected}>{$renderTitle}</option>
HTML;
            $selectOptionsHtml .= $selectOption;
        }

        return $selectOptionsHtml;
    }

    /**
     * @param string $renderValue
     * @param array|string $inputDataValue
     * @return string
     */
    protected function renderSelectedOption(string $renderValue, array|string $inputDataValue): string
    {
        if (is_array($inputDataValue)) {
            return in_array($renderValue, $inputDataValue, false) ? ' selected' : '';
        }
        return $inputDataValue === $renderValue ? ' selected' : '';
    }

    /**
     * @param bool $isNamedKnownSet
     * @param string $setTitle
     * @param string $renderValue
     * @return string
     */
    protected function renderOptionTitle(bool $isNamedKnownSet, string $setTitle, string $renderValue): string
    {
        if ($isNamedKnownSet) {
            return sprintf(InstallationSettingEditConstants::WEB_KNOWNSET_NAMES_TITLE_TPL, $setTitle, $renderValue);
        }
        return $renderValue;
    }

    /**
     * @param string $selectType
     * @return string
     */
    protected function normalizeSelectType(string $selectType): string
    {
        $output = Constants\Installation::ECOM_SELECT_TYPE_SINGLE;
        if (in_array($selectType, Constants\Installation::AVAILABLE_ECOM_SELECT_TYPES, true)) {
            $output = $selectType;
        }
        return $output;
    }
}
