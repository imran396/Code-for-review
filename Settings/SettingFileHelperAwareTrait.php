<?php
/**
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           22.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Settings;

/**
 * Trait SettingFileHelperAwareTrait
 * @package Sam\Settings
 */
trait SettingFileHelperAwareTrait
{
    /**
     * @var SettingFileHelper|null
     */
    protected ?SettingFileHelper $settingFileHelper = null;

    /**
     * @return SettingFileHelper
     */
    protected function getSettingFileHelper(): SettingFileHelper
    {
        if ($this->settingFileHelper === null) {
            $this->settingFileHelper = SettingFileHelper::new();
        }
        return $this->settingFileHelper;
    }

    /**
     * @param SettingFileHelper $settingFileHelper
     * @return static
     * @internal
     */
    public function setSettingFileHelper(SettingFileHelper $settingFileHelper): static
    {
        $this->settingFileHelper = $settingFileHelper;
        return $this;
    }
}
