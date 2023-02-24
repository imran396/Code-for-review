<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-11, 2019
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

/**
 * Class HtmlInputRendererForStructArray
 * @package Sam\Installation\Config
 */
class HtmlInputRendererForStructArray extends CustomizableClass
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
     * @param EditComponentData $ecData
     * @return string
     */
    public function render(EditComponentData $ecData): string
    {
        $inputId = $ecData->getInputId();
        $labelHtml = $ecData->getLabelHtml();
        $descriptionHtml = $ecData->getDescriptionHtml();
        $infoHtml = $ecData->getInfoHtml();
        $errorHtml = $ecData->getErrorHtml();
        $inputHtml = $this->buildInputHtml($ecData);

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
     * @param EditComponentData $ecData
     * @return string
     */
    protected function buildInputHtml(EditComponentData $ecData): string
    {
        $descriptor = $ecData->getInputData()->getMetaDescriptor();
        if ($descriptor->getEditComponent() !== Constants\Installation::ECOM_STRUCT_MULTILINE) {
            return '';
        }

        $content = $this->createEditBlockRendererHelper()->buildWebFormReadyInputValueFromStructuredArray($ecData->getInputData());
        $autoAdjustHeightClass = InstallationSettingEditConstants::CSS_TEXTAREA_AUTO_ADJUST_HEIGHT;
        $attributeReadonly = !$descriptor->isEditable() ? ' disabled readonly' : '';
        $eComWrapperCssClass = InstallationSettingEditConstants::CSS_EDIT_CONTROL_WRAPPER;

        return <<<HTML
<p class="{$eComWrapperCssClass}">
    <textarea class="{$autoAdjustHeightClass}" cols="50" rows="10" {$attributeReadonly}>{$content}</textarea>
</p>
HTML;
    }
}
