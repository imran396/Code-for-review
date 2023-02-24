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

namespace Sam\Settings\Save\Internal;

/**
 * Trait SettingDefaultValueApplierCreateTrait
 * @package Sam\Settings\Save\Internal
 */
trait SettingDefaultValueApplierCreateTrait
{
    protected ?SettingDefaultValueApplier $settingDefaultValueApplier = null;

    /**
     * @return SettingDefaultValueApplier
     */
    protected function createSettingDefaultValueApplier(): SettingDefaultValueApplier
    {
        return $this->settingDefaultValueApplier ?: SettingDefaultValueApplier::new();
    }

    /**
     * @param SettingDefaultValueApplier $settingDefaultValueApplier
     * @return static
     * @internal
     */
    public function setSettingDefaultValueApplier(SettingDefaultValueApplier $settingDefaultValueApplier): static
    {
        $this->settingDefaultValueApplier = $settingDefaultValueApplier;
        return $this;
    }
}
