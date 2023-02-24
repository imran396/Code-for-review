<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-20, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\WebData;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;

/**
 * Class UrlHashBuilder
 * @package Sam\Installation\Config
 */
class UrlHashBuilder extends CustomizableClass
{
    use OptionHelperAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $optionKey
     * @param string $configName
     * @return string
     */
    public function buildForOptionKey(string $optionKey, string $configName = ''): string
    {
        $optionHelper = $this->getOptionHelper();
        $optionKey = $optionHelper
            ->replaceGeneralDelimiter($optionKey, Constants\Installation::DELIMITER_HASH_OPTION_KEY);
        if ($configName) {
            $optionKey = $optionHelper->prependConfigName($optionKey, $configName, Constants\Installation::DELIMITER_HASH_OPTION_KEY);
        }
        $output = sprintf(Constants\Installation::URL_HASH_FOR_OPTION_KEY, $optionKey);
        return $output;
    }

    /**
     * @param string $string
     * @param string $template
     * @return string
     */
    public function buildForLocalConfigArea(
        string $string,
        string $template = Constants\Installation::URL_HASH_FOR_LOCAL_CONFIG_AREAS
    ): string {
        $output = sprintf($template, $string);
        return $output;
    }

    /**
     * @param string $string
     * @return string
     */
    public function buildForMissedLocalConfigArea(string $string): string
    {
        $output = $this->buildForLocalConfigArea($string, Constants\Installation::URL_HASH_FOR_MISSED_LOCAL_CONFIG_AREAS);
        return $output;
    }

    /**
     * @param string $string
     * @return string
     */
    public function buildForMainNavigation(string $string): string
    {
        $output = sprintf(Constants\Installation::URL_HASH_FOR_NAVIGATION_MENU, $string);
        return $output;
    }

}
