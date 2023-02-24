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

use Sam\Core\Constants\Admin\InstallationSettingEditConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Render\EditBlock\EditBlockRendererHelperCreateTrait;
use Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\EditData\EditComponentData;
use Sam\Installation\Config\Edit\Transform\ParsingHelper;

/**
 * Class HtmlInputRendererForArray
 * @package Sam\Installation\Config
 */
class HtmlInputRendererForArray extends CustomizableClass
{
    use EditBlockRendererHelperCreateTrait;

    /**
     * Build input html for array values types
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param EditComponentData $ecData
     * @return string
     */
    public function render(EditComponentData $ecData): string
    {
        $inputId = $ecData->getInputId();
        $inputData = $ecData->getInputData();
        $labelHtml = $ecData->getLabelHtml();
        $descriptionHtml = $ecData->getDescriptionHtml();
        $infoHtml = $ecData->getInfoHtml();
        $errorHtml = $ecData->getErrorHtml();
        $inputName = $ecData->getInputName();
        $isEditable = $inputData->getMetaDescriptor()->isEditable();
        $rendererHelper = $this->createEditBlockRendererHelper();

        $validationPostValue = $rendererHelper->getValidationPostValue($inputData);
        if ($validationPostValue !== null) {
            $value = is_string($validationPostValue)
                ? ParsingHelper::new()->buildArrayFromString($validationPostValue)
                : $validationPostValue;
            $inputData->setValue($value);
        }
        $inputValue = $rendererHelper->buildWebFormReadyInputValueFromArray($inputData);
        $inputControlType = $rendererHelper->getInputControlType($inputData);
        $size = $this->calcSize($inputData->getValue());

        $inputHtml = $this->buildInputHtml($isEditable, $inputControlType, $inputId, $size, $inputValue, $inputName);

        $output = <<<HTML
<div class="form-group">
    <p><label for="{$inputId}">{$labelHtml}</label></p>
    {$descriptionHtml}
    {$infoHtml}
    {$errorHtml}
    {$inputHtml}
</div>
HTML;

        return $output;
    }

    /**
     * @param bool $isEditable
     * @param string $inputControlType
     * @param string $inputId
     * @param int $size
     * @param string $inputValue
     * @param string $inputName
     * @return string
     */
    protected function buildInputHtml(
        bool $isEditable,
        string $inputControlType,
        string $inputId,
        int $size,
        string $inputValue,
        string $inputName
    ): string {
        $attributeReadonly = !$isEditable ? ' disabled readonly' : '';
        if (in_array($inputControlType, Constants\Installation::AVAILABLE_EDIT_COMPONENTS_FOR_ARRAYS, true)) {
            $eComWrapperCssClass = InstallationSettingEditConstants::CSS_EDIT_CONTROL_WRAPPER;
            if ($inputControlType === Constants\Installation::ECOM_MULTILINE) {
                return <<<HTML
<p class="{$eComWrapperCssClass}">
    <textarea class="form-control" name="{$inputName}" id="{$inputId}" cols="95" rows="5" maxlength="1400" {$attributeReadonly}>{$inputValue}</textarea>
</p>
HTML;
            }

            return <<<HTML
<p class="{$eComWrapperCssClass}">
    <input type="text" class="form-control" name="{$inputName}" 
     size="{$size}" value="{$inputValue}" id="{$inputId}" maxlength="400" {$attributeReadonly}>
</p>
HTML;
        }

        return 'Unknown edit component type.';
    }

    /**
     * @param array $value
     * @return int
     */
    protected function calcSize(array $value): int
    {
        $sizeCounter = 0;
        if (count($value)) {
            foreach ($value as $inVal) {
                $sizeCounter += strlen($inVal);
            }
            $size = $sizeCounter + count($value) + 5;
        } else {
            $size = 12;
        }
        return $size;
    }
}
