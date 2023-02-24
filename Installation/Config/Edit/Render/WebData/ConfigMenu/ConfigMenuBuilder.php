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

namespace Sam\Installation\Config\Edit\Render\WebData\ConfigMenu;

use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\InstallationSettingEditConstants;
use Sam\Core\Url\UrlParserAwareTrait;

/**
 * Class AvailableConfigsListBuilder
 * @package Sam\Installation\Config
 */
class ConfigMenuBuilder extends CustomizableClass
{
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;

    /** @var string|null */
    protected ?string $url = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->url = $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_INSTALLATION_SETTING_EDIT)
        );
        return $this;
    }

    /**
     * @param string $configName
     * @return ConfigMenuItemWebData[]
     */
    public function build(string $configName): array
    {
        $output = [];
        foreach (Constants\Installation::AVAILABLE_CONFIG_NAMES as $name) {
            $url = $this->prepareUrlForConfigName($name);
            $isActive = $name === $configName;
            $output[] = new ConfigMenuItemWebData($name, $url, $isActive);
        }
        return $output;
    }

    /**
     * @param string $configName
     * @return string
     */
    protected function prepareUrlForConfigName(string $configName): string
    {
        $url = $this->getUrl();
        $url = $this->getUrlParser()->replaceParams($url, [InstallationSettingEditConstants::HID_CONFIG_NAME => $configName]);
        return $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

}
