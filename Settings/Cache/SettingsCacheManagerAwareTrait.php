<?php
/**
 * SAM-10551: Adjust SettingsManager for reading and caching data from different tables
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Cache;


/**
 * Trait SettingsCacheManagerAwareTrait
 * @package Sam\Settings\Cache
 */
trait SettingsCacheManagerAwareTrait
{
    protected ?SettingsCacheManager $settingsCacheManager = null;

    /**
     * @return SettingsCacheManager
     */
    protected function getSettingsCacheManager(): SettingsCacheManager
    {
        if ($this->settingsCacheManager === null) {
            $this->settingsCacheManager = SettingsCacheManager::new();
        }
        return $this->settingsCacheManager;
    }

    /**
     * @param SettingsCacheManager $settingsCacheManager
     * @return static
     * @internal
     */
    public function setSettingsCacheManager(SettingsCacheManager $settingsCacheManager): static
    {
        $this->settingsCacheManager = $settingsCacheManager;
        return $this;
    }
}
