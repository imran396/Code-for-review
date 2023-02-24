<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           09-14, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\HtmlRenderers;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Meta\Descriptor\Descriptor;
use Sam\Installation\Config\Edit\Meta\Descriptor\MissingDescriptor;
use Sam\Installation\Config\Edit\Render\EditBlock\EditBlockRendererHelperCreateTrait;
use Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData\InputDataWeb;
use Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData\InputDataWebValueStructured;
use Sam\Core\Constants\Admin\InstallationSettingEditConstants;

/**
 * Class HtmlBuilder
 * @package Sam\Installation\Config
 */
class CommonHtmlRenderer extends CustomizableClass
{
    use EditBlockRendererHelperCreateTrait;

    /**
     * html badges classes.
     * @var string[]
     */
    protected const BADGE_DATA_TYPE_CSS_CLASSES_MAP = [
        Constants\Type::T_STRING => ' bg-info text-dark',
        Constants\Type::T_ARRAY => ' bg-secondary',
        Constants\Type::T_BOOL => ' bg-warning text-dark',
        Constants\Type::T_INTEGER => ' bg-primary',
        Constants\Type::T_FLOAT => ' bg-danger',
        Constants\Type::T_NULL => ' bg-dark',
        Constants\Type::T_UNKNOWN_TYPE => ' bg-light text-dark',
        Constants\Installation::T_STRUCT_ARRAY => ' bg-success'
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
     * render input label html.
     * @param InputDataWeb $inputData
     * @return string
     */
    public function renderLabelHtml(InputDataWeb $inputData): string
    {
        $output = '';
        $explodedInputLabel = explode(Constants\Installation::DELIMITER_GENERAL_OPTION_KEY, $inputData->getLabel());
        foreach ($explodedInputLabel as $k => $value) {
            $d = ($k + 1 === count($explodedInputLabel)) ? '' : Constants\Installation::DELIMITER_RENDER_OPTION_KEY;
            $output .=
                ($k + 1 === count($explodedInputLabel) || $k + 1 === count($explodedInputLabel) - 1)
                    ? sprintf('<b>%s</b>%s', $value, $d)
                    : $value . $d;
        }
        $output .= '<span class="to_top ms-3"><a href="#navigation">to top</a></span>';

        return $output;
    }

    /**
     * render input description text.
     * @param InputDataWeb $inputData
     * @return string
     */
    public function renderDescriptionHtml(InputDataWeb $inputData): string
    {
        $output = '';
        $descriptor = $inputData->getMetaDescriptor();
        if ($descriptor->hasDescription()) {
            $output = sprintf('<div class="inputDescription"><p>%s</p></div>', nl2br($descriptor->getDescription()));
        }
        return $output;
    }

    /**
     * render mark with information about input value data type.
     * @param InputDataWeb $inputData
     * @return string
     */
    public function renderInfoHtml(InputDataWeb $inputData): string
    {
        $metaType = $inputData->getMetaDescriptor()->getType();
        $badgeClass = self::BADGE_DATA_TYPE_CSS_CLASSES_MAP[$metaType];
        $defaultValueHtml = $this->renderDefaultValue($inputData);
        $output = <<<HTML
<p>
    <a href="#" class="badge {$badgeClass} text-decoration-none" onclick="return false;" 
    data-toggle="tooltip" data-placement="top" title="Input data type">
        {$metaType}
    </a>
    {$defaultValueHtml}
</p>
HTML;

        return $output;
    }

    /**
     * render mark with input validation error.
     * @param InputDataWeb $inputData
     * @return string
     */
    public function renderErrorHtml(InputDataWeb $inputData): string
    {
        $output = $inputErrorText = '';
        $inputDataValidation = $inputData->getValidation();
        $errorWrapHtml = '<div class="errorTextWrap"><p class="badge bg-danger">%s</p></div>';

        if ($inputDataValidation->getValidationStatus() === Constants\Installation::V_STATUS_FAIL) {
            foreach ($inputDataValidation->getErrorText() ?: [] as $errorText) {
                $inputErrorText .= sprintf($errorWrapHtml, $errorText);
            }
            $output = sprintf('<div class="inputError">%s</div>', $inputErrorText);
        }

        return $output;
    }

    /**
     * render mark with default input value.
     * @param InputDataWeb $inputData
     * @return string
     */
    protected function renderDefaultValue(InputDataWeb $inputData): string
    {
        $defaultValue = $inputData->getValueDefault();
        if (empty($defaultValue)) {
            return '';
        }

        $rendererHelper = $this->createEditBlockRendererHelper();
        $editComponentTypeFromDescriptor = $rendererHelper->fetchEditComponentTypeFromDescriptor($inputData);
        $useMetaKnownSet = $rendererHelper->fetchMetaKnownSetForEditComponent($inputData);
        $editComponentBuildType = $rendererHelper->fetchEditComponentBuildType($editComponentTypeFromDescriptor, count($useMetaKnownSet));

        if ($editComponentBuildType === InstallationSettingEditConstants::ECOM_BUILD_TYPE_UNKNOWN) {
            return '';
        }

        $metaDescriptor = $inputData->getMetaDescriptor();
        $isEditable = $metaDescriptor->isEditable() ? 1 : 0;
        $isMissed = $metaDescriptor instanceof MissingDescriptor ? 1 : 0;
        $renderValue = ee($defaultValue);
        $cssClassName = InstallationSettingEditConstants::CSS_CONFIG_OPTION_DEFAULT_VALUE_ANCHOR;
        $jsExportDefaultValue = $this->buildJsExportDefaultValue($inputData, $editComponentBuildType, $metaDescriptor);
        $eComForArrayInputControlType = $editComponentBuildType === InstallationSettingEditConstants::ECOM_BUILD_TYPE_ARRAY
            ? $rendererHelper->getInputControlType($inputData)
            : '';

        $output = <<<HTML
, <a href="#" 
    data-is-editable="{$isEditable}" 
    data-is-missed="{$isMissed}" 
    data-edit-component-type="{$editComponentBuildType}"
    data-edit-component-for-array-input-control-type="{$eComForArrayInputControlType}" 
    data-default-value='{$jsExportDefaultValue}'    
    class="badge bg-secondary text-decoration-none {$cssClassName}">
{$renderValue}</a> - default value
HTML;
        return $output;
    }

    protected function buildJsExportDefaultValue(
        InputDataWeb $inputData,
        string $editComponentBuildType,
        Descriptor $metaDescriptor
    ): string {
        $globalValue = $metaDescriptor->getGlobalValue();

        // general edit component type
        if ($editComponentBuildType === InstallationSettingEditConstants::ECOM_BUILD_TYPE_GENERAL) {
            $output = $globalValue === null ? '' : htmlentities((string)$globalValue);
            return $output;
        }

        // radio-button edit component type
        $radioButtonEditComponentTypes = [
            InstallationSettingEditConstants::ECOM_BUILD_TYPE_BOOLEAN,
            InstallationSettingEditConstants::ECOM_BUILD_TYPE_RADIO
        ];
        if (in_array($editComponentBuildType, $radioButtonEditComponentTypes, true)) {
            $output = (string)$globalValue;
            if (is_bool($globalValue)) {
                $output = $globalValue ? '1' : '0';
            }
            return $output;
        }

        // array edit component type
        if ($editComponentBuildType === InstallationSettingEditConstants::ECOM_BUILD_TYPE_ARRAY) {
            $inputDataForArray = clone $inputData;
            $inputDataForArray->setValue($globalValue);
            $output = $this->createEditBlockRendererHelper()
                ->buildWebFormReadyInputValueFromArray($inputDataForArray, true);
            return $output;
        }

        // structured array edit component type
        if ($editComponentBuildType === InstallationSettingEditConstants::ECOM_BUILD_TYPE_STRUCT_ARRAY) {
            $inputDataForStructArray = clone $inputData;
            $inputDataForStructArray->setValue(null);
            $structuredValue = new InputDataWebValueStructured($globalValue);
            $inputDataForStructArray->setValueStructured($structuredValue);
            $output = $this->createEditBlockRendererHelper()
                ->buildWebFormReadyInputValueFromStructuredArray($inputDataForStructArray, true);
            return $output;
        }

        // select-box edit component type
        $selectBoxEditComponentTypes = [
            InstallationSettingEditConstants::ECOM_BUILD_TYPE_SELECT_SINGLE,
            InstallationSettingEditConstants::ECOM_BUILD_TYPE_SELECT_MULTIPLE
        ];
        if (in_array($editComponentBuildType, $selectBoxEditComponentTypes, true)) {
            $output = is_array($globalValue)
                ? implode(',', $globalValue)
                : (string)$globalValue;
            return $output;
        }

        return '';
    }
}
