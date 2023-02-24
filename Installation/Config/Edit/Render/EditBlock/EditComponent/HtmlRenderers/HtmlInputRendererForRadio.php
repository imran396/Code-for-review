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
 * Class HtmlBuilderRadio
 * @package Sam\Installation\Config
 */
class HtmlInputRendererForRadio extends CustomizableClass
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
     * Build input control HTML for knownSet values using radio buttons as control elements.
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
        $eComWrapperCssClass = InstallationSettingEditConstants::CSS_EDIT_CONTROL_WRAPPER;

        $radioOptionsHtml = $this->renderRadioOptions($inputData, $inputId, $inputName);
        $radioHtml = <<<HTML
<div class="{$eComWrapperCssClass}">{$radioOptionsHtml}</div>
HTML;
        $output = "<p>{$labelHtml}</p>{$descriptionHtml}{$infoHtml}{$errorHtml}{$radioHtml}";
        return $output;
    }

    protected function renderRadioOptions(InputDataWeb $inputData, string $inputId, string $inputName): string
    {
        $descriptor = $inputData->getMetaDescriptor();
        $dataType = $descriptor->getType();
        $validationPostValue = $this->createEditBlockRendererHelper()->getValidationPostValue($inputData);
        if ($dataType === Constants\Type::T_BOOL) {
            $validationPostValue = (bool)$validationPostValue;
        }
        if (!empty($validationPostValue)) {
            $inputData->setValue($validationPostValue);
        }

        $metaKnownSet = $descriptor->getKnownSet();
        $metaKnownSetNames = $descriptor->getKnownSetNames();
        $useMetaSet = count($metaKnownSet) ? $metaKnownSet : $metaKnownSetNames;
        $knownSetType = count($metaKnownSetNames) ? 'withNames' : 'simple';
        if ($dataType === Constants\Type::T_BOOL) {
            ksort($useMetaSet);
        }

        $inputDataValue = $inputData->getValue();
        $attributeReadonly = !$descriptor->isEditable() ? ' disabled readonly' : '';
        $radioOptionsHtml = '';
        $num = 0;
        foreach ($useMetaSet ?: [] as $setValue => $setTitle) {
            $numSuffix = sprintf(InstallationSettingEditConstants::WEB_MAIN_CTRL_NUM_SUFFIX, $num);
            if ($knownSetType === 'withNames') {
                $setValue = $this->normalizeKnownSetValue($setValue, $dataType);
                // Compare with type-cast, because numeric string used for array key is converted to integer, but we expect string.
                $checked = (string)$inputDataValue === (string)$setValue ? ' checked' : '';
                if ($dataType === Constants\Type::T_BOOL) {
                    $titleValue = $setValue ? 'true' : 'false';
                    $renderValue = $setValue ? 1 : 0;
                    $renderTitle =
                        sprintf(InstallationSettingEditConstants::WEB_KNOWNSET_NAMES_TITLE_TPL, $titleValue, $setTitle);
                } else {
                    $renderValue = $setValue;
                    $renderTitle =
                        sprintf(InstallationSettingEditConstants::WEB_KNOWNSET_NAMES_TITLE_TPL, $setValue, $setTitle);
                }
            } else {
                $renderTitle = $setTitle;
                $renderValue = $setTitle;
                if ($dataType === Constants\Type::T_BOOL) {
                    $setTitle = (bool)$setTitle;
                    $renderValue = $setTitle ? 1 : 0;
                }
                $checked = $inputDataValue === $setTitle ? ' checked' : '';
            }

            $radioHtmlBlock = <<<HTML
<div class="custom-control custom-radio custom-control-inline">
    <input type="radio" class="form-check-input custom-control-input"
    name="{$inputName}" value="{$renderValue}" id="{$inputId}{$numSuffix}" {$checked} {$attributeReadonly}>
    <label class="custom-control-label" for="{$inputId}{$numSuffix}">{$renderTitle}</label>
</div>
HTML;

            $radioOptionsHtml .= $radioHtmlBlock;
            $num++;
        }

        return $radioOptionsHtml;
    }

    protected function normalizeKnownSetValue(mixed $setValue, string $dataType): mixed
    {
        if (
            $dataType === Constants\Type::T_BOOL
            && !is_bool($setValue)
        ) {
            return (bool)$setValue;
        }

        return $setValue;
    }
}
