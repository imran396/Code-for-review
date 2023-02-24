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

namespace Sam\Installation\Config\Edit\Render\EditBlock\EditComponent;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Render\EditBlock\InputDataWebAwareTrait;
use Sam\Installation\Config\Edit\Render\EditBlock\OptionKeyAwareTrait;
use Sam\Installation\Config\Edit\Render\EditBlock\EditBlockRendererHelperCreateTrait;
use Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\EditData\EditComponentData;
use Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\HtmlRenderers\CommonHtmlRenderer;
use Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData\InputDataWeb;

/**
 * Class EditComponentRenderer
 * @package Sam\Installation\Config
 */
class EditComponentRenderer extends CustomizableClass
{
    use InputDataWebAwareTrait;
    use OptionKeyAwareTrait;
    use EditBlockRendererHelperCreateTrait;

    protected const HTML_TMPL = '<div id="%s" class="col-sm-11 col-md-11">%s</div>';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Internal class constructor
     *
     * @param InputDataWeb $inputData
     * @param string $optionKey
     * @return $this
     */
    public function construct(InputDataWeb $inputData, string $optionKey): static
    {
        $this->setInputDataWeb($inputData);
        $this->setOptionKey($optionKey);

        return $this;
    }

    /**
     * Render edit component column html.
     * @param string $configName
     * @return string
     */
    public function render(string $configName): string
    {
        $inputData = $this->getInputDataWeb();
        $inputId = $configName . Constants\Installation::DELIMITER_GENERAL_OPTION_KEY . $this->getOptionKey();

        $commonHtmlRenderer = CommonHtmlRenderer::new();
        $labelHtml = $commonHtmlRenderer->renderLabelHtml($inputData);
        $descriptionHtml = $commonHtmlRenderer->renderDescriptionHtml($inputData);
        $infoHtml = $commonHtmlRenderer->renderInfoHtml($inputData);
        $errorHtml = $commonHtmlRenderer->renderErrorHtml($inputData);
        $inputName = $inputData->getMetaDescriptor()->getType() === Constants\Installation::T_STRUCT_ARRAY
            ? ''
            : $inputId;

        $ecData = new EditComponentData(
            $inputId,
            $inputData,
            null,
            $labelHtml,
            $descriptionHtml,
            $infoHtml,
            $errorHtml,
            $inputName
        );
        $editComponentInputRenderer = EditComponentInputRenderer::new();

        $ecData = $ecData->withBuildType(Constants\Installation::ECOM_BUILDER_TYPE_HTML);
        $editComponentInputHtml = $editComponentInputRenderer->render($ecData);

        $ecData = $ecData->withBuildType(Constants\Installation::ECOM_BUILDER_TYPE_ID);
        $editComponentInputId = $editComponentInputRenderer->render($ecData);

        $output = sprintf(self::HTML_TMPL, $editComponentInputId, $editComponentInputHtml);
        return $output;
    }
}
