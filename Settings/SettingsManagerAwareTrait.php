<?php
/**
 * Trait that implements settings manager property and accessors
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           24 Feb, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings;

/**
 * Trait SettingsManagerAwareTrait
 * @package Sam\Settings
 */
trait SettingsManagerAwareTrait
{
    protected ?SettingsManagerInterface $settingsManager = null;

    protected function setting(): SettingsManagerInterface
    {
        return $this->getSettingsManager();
    }

    /**
     * @return SettingsManagerInterface
     */
    protected function getSettingsManager(): SettingsManagerInterface
    {
        if (!$this->settingsManager) {
            $this->settingsManager = SettingsManager::new();
        }
        return $this->settingsManager;
    }

    /**
     * @param SettingsManagerInterface $settingsManager
     * @return static
     * @internal
     */
    public function setSettingsManager(SettingsManagerInterface $settingsManager): static
    {
        $this->settingsManager = $settingsManager;
        return $this;
    }
}
