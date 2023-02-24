<?php
/**
 * Immutable value object stores data for single available config item. Used for web rendering of single config item
 * in available configs list at the top of edit web form.
 *
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-16, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Installation\Config\Edit\Render\WebData\ConfigMenu;


/**
 * Class AreaAvailableConfigItemWebData
 * @package Sam\Installation\Config
 */
final class ConfigMenuItemWebData
{

    /** @var string */
    protected string $configName;
    /** @var string */
    protected string $url;
    /** @var bool */
    protected bool $isActive;

    /**
     * AreaAvailableConfigItemWebData constructor.
     * @param string $configName
     * @param string $url
     * @param bool $isActive
     */
    public function __construct(string $configName, string $url, bool $isActive)
    {
        $this->configName = $configName;
        $this->url = $url;
        $this->isActive = $isActive;
    }

    /**
     * @return string
     */
    public function getConfigName(): string
    {
        return $this->configName;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

}
