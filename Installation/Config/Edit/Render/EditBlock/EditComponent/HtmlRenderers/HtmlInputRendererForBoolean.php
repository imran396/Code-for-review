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
 * Class HtmlInputRendererForBoolean
 * @package Sam\Installation\Config
 */
class HtmlInputRendererForBoolean extends CustomizableClass
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
     * Build input html for boolean values types
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

        $validationPostValue = $this->createEditBlockRendererHelper()->getValidationPostValue($inputData); // it may be null too
        if (in_array($validationPostValue, [true, false], true)) {
            $inputData->setValue($validationPostValue);
        }

        $radioHtml = '';
        $num = 0;
        foreach ([false, true] as $iVal) {
            $checked = ($inputData->getValue() === $iVal) ? ' checked' : '';
            $labelBoolTxt = $iVal ? 'true' : 'false';
            $renderNum = sprintf(InstallationSettingEditConstants::WEB_MAIN_CTRL_NUM_SUFFIX, $num);
            $renderValue = $iVal ? 1 : 0;
            $attributeReadonly = !$isEditable ? ' disabled readonly' : '';

            $radioHtmlBlock = <<<HTML
<div class="custom-control custom-radio custom-control-inline">
    <input type="radio" class="form-check-input custom-control-input" 
    name="{$inputName}" value="{$renderValue}" id="{$inputId}{$renderNum}" {$checked} {$attributeReadonly}>
    <label class="custom-control-label" for="{$inputId}{$renderNum}">{$labelBoolTxt}</label>
</div>
HTML;
            $radioHtml .= $radioHtmlBlock;
            $num++;
        }
        $eComWrapperCssClass = InstallationSettingEditConstants::CSS_EDIT_CONTROL_WRAPPER;
        $radioHtml = <<<HTML
<div class="{$eComWrapperCssClass}">{$radioHtml}</div>
HTML;


        $output = "<p>{$labelHtml}</p>{$descriptionHtml}{$infoHtml}{$errorHtml}{$radioHtml}";
        return $output;
    }
}
