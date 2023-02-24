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
use Sam\Installation\Config\Edit\Render\EditBlock\EditBlockRendererHelperCreateTrait;
use Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\EditData\EditComponentData;

/**
 * Class HtmlInputRendererForGeneral
 * @package Sam\Installation\Config
 */
class HtmlInputRendererForGeneral extends CustomizableClass
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
     * Build input html for numeric, string and NULL values types.
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
        $editBlockRendererHelper = $this->createEditBlockRendererHelper();

        $validationPostValue = $editBlockRendererHelper->getValidationPostValue($inputData);
        if ($validationPostValue !== null) {
            $inputData->setValue($validationPostValue);
        }
        $inputDataValue = (string)$inputData->getValue();
        $size = strlen($inputDataValue) > 12 ? strlen($inputDataValue) + 3 : 12;
        $value = htmlentities($inputDataValue);

        $attributeReadonly = !$isEditable ? ' disabled readonly' : '';
        $classDisabled = !$isEditable ? ' disabled' : '';
        $eComWrapperCssClass = InstallationSettingEditConstants::CSS_EDIT_CONTROL_WRAPPER;

        $output = <<<HTML
<div class="form-group">
    <p><label for="{$inputId}">{$labelHtml}</label></p>
    {$descriptionHtml}
    {$infoHtml}
    {$errorHtml}
    <p class="{$eComWrapperCssClass}">
        <input type="text" class="form-control {$classDisabled}" name="{$inputName}" 
        value="{$value}" id="{$inputId}" size="{$size}" maxlength="400" {$attributeReadonly}>                    
    </p>
</div>
HTML;

        return $output;
    }
}
