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

namespace Sam\Installation\Config\Edit\Render\WebData\Area\LocalConfig;

use Sam\Core\Constants;
use Sam\Core\Constants\Admin\InstallationSettingEditConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollection;
use Sam\Installation\Config\Edit\Meta\Descriptor\MissingDescriptor;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;
use Sam\Installation\Config\Edit\Render\WebData\Area\LocalConfig\Option\LocalOptionWebData;
use Sam\Installation\Config\Edit\Render\WebData\Area\LocalConfig\Section\LocalOptionSectionWebData;
use Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData\InputDataWeb;
use Sam\Installation\Config\Edit\Render\WebData\Option\OptionDefaultValueRenderer;
use Sam\Installation\Config\Edit\Render\WebData\UrlHashBuilderAwareTrait;

/**
 * Class LocalConfigValuesBuilder
 * @package Sam\Installation\Config
 */
class LocalConfigValuesBuilder extends CustomizableClass
{
    use OptionHelperAwareTrait;
    use UrlHashBuilderAwareTrait;

    public const ABSENT_IN_GLOBAL_CONFIG_OPTIONS = 0;
    public const PRESENT_IN_GLOBAL_CONFIG_OPTIONS = 1;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $formData
     * @param DescriptorCollection $descriptorCollection
     * @return array
     */
    public function build(array $formData, DescriptorCollection $descriptorCollection): array
    {
        $existingOptionCount = $missingOptionCount = 0;
        $existingOptionWebDatas = $missingOptionWebDatas = [];
        $optionHelper = $this->getOptionHelper();
        foreach ($formData as $configArea => $configAreaInputData) {
            /** @var InputDataWeb $inputData */
            foreach ($configAreaInputData as $optionKey => $inputData) {
                if ($inputData->hasLocalConfigValue()) {
                    $descriptor = $inputData->getMetaDescriptor();
                    $dataType = $descriptor->getType();
                    $preparedUrlHash = $this->getUrlHashBuilder()->buildForOptionKey($optionKey, $descriptorCollection->getConfigName());
                    $urlHash = "#{$preparedUrlHash}";
                    $renderOptionKey = $optionHelper->prependConfigName(
                        $optionHelper->replaceGeneralDelimiter($optionKey, Constants\Installation::DELIMITER_RENDER_OPTION_KEY),
                        $descriptorCollection->getConfigName()
                    );

                    $optionDeleteControlsRenderer = OptionDeleteControlsRenderer::new();
                    $optionWebData = new LocalOptionWebData(
                        $renderOptionKey,
                        $urlHash,
                        $dataType,
                        OptionDefaultValueRenderer::new()->render($inputData->getValue(), $dataType),
                        $optionDeleteControlsRenderer->renderOptionDeleteButtonHtml($optionKey, $descriptorCollection),
                        $optionDeleteControlsRenderer->renderOptionDeleteCheckboxHtml($optionKey, $descriptorCollection)
                    );

                    if ($descriptor instanceof MissingDescriptor) {
                        $missingOptionWebDatas[$configArea][] = $optionWebData;
                        $missingOptionCount++;
                    } else {
                        $existingOptionWebDatas[$configArea][] = $optionWebData;
                        $existingOptionCount++;
                    }
                }
            }
        }

        $output = [
            self::ABSENT_IN_GLOBAL_CONFIG_OPTIONS => new LocalOptionSectionWebData(
                'Local config values, that not exists in global configuration',
                $missingOptionWebDatas,
                $missingOptionCount,
                InstallationSettingEditConstants::CSS_LOCAL_VALUE_ABSENT_IN_GLOBAL_CONFIG
            ),
            self::PRESENT_IN_GLOBAL_CONFIG_OPTIONS => new LocalOptionSectionWebData(
                'Local config values',
                $existingOptionWebDatas,
                $existingOptionCount,
                InstallationSettingEditConstants::CSS_LOCAL_VALUE_PRESENT_IN_GLOBAL_CONFIG
            ),
        ];

        return $output;
    }
}
