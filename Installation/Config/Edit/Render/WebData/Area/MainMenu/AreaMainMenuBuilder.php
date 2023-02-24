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

namespace Sam\Installation\Config\Edit\Render\WebData\Area\MainMenu;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;
use Sam\Installation\Config\Edit\Render\WebData\Area\LocalConfig\LocalConfigValuesBuilder;
use Sam\Installation\Config\Edit\Render\WebData\Option\LocalOptionNavigationWebData;
use Sam\Installation\Config\Edit\Render\WebData\Area\LocalConfig\Section\LocalOptionSectionWebData;
use Sam\Installation\Config\Edit\Render\WebData\UrlHashBuilderAwareTrait;

/**
 * Class NavigationBuilder
 * @package Sam\Installation\Config
 */
class AreaMainMenuBuilder extends CustomizableClass
{
    use OptionHelperAwareTrait;
    use UrlHashBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $actualOptions with flat option keys.
     * @return array
     */
    public function build(array $actualOptions): array
    {
        $output = [];
        $multiDimActualOptions = $this->getOptionHelper()->transformFlatKeyToMultiDimKeyOptions($actualOptions);
        $pathKeys = array_keys($multiDimActualOptions);
        $urlHashBuilder = $this->getUrlHashBuilder();
        foreach ($pathKeys as $pathKey) {
            $urlHash = $urlHashBuilder->buildForMainNavigation($pathKey);
            $output[$pathKey] = new AreaMainMenuItemWebData($pathKey, $urlHash);
        }
        return $output;
    }

    /**
     * @param array $localConfigValues
     * @return array
     */
    public function buildLocalConfigNavigation(array $localConfigValues): array
    {
        $output = [];
        $urlHashBuilder = $this->getUrlHashBuilder();
        /** @var LocalOptionSectionWebData $localConfigOptions */
        foreach ($localConfigValues ?: [] as $sectionType => $localConfigOptions) {
            $pathKeys = array_keys($localConfigOptions->getData());
            foreach ($pathKeys ?: [] as $pathKey) {
                $urlHash = $sectionType === LocalConfigValuesBuilder::PRESENT_IN_GLOBAL_CONFIG_OPTIONS
                    ? $urlHashBuilder->buildForLocalConfigArea($pathKey)
                    : $urlHashBuilder->buildForMissedLocalConfigArea($pathKey);
                $output[$sectionType][$pathKey] = new LocalOptionNavigationWebData($pathKey, $urlHash);
            }
        }
        return $output;
    }
}
