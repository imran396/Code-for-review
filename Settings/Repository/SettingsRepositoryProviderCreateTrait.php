<?php
/**
 * SAM-10551: Adjust SettingsManager for reading and caching data from different tables
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Repository;

/**
 * Trait SettingsRepositoryProviderCreateTrait
 * @package Sam\Settings\Repository
 */
trait SettingsRepositoryProviderCreateTrait
{
    protected ?SettingsRepositoryProvider $settingsRepositoryProvider = null;

    /**
     * @return SettingsRepositoryProvider
     */
    protected function createSettingsRepositoryProvider(): SettingsRepositoryProvider
    {
        return $this->settingsRepositoryProvider ?: SettingsRepositoryProvider::new();
    }

    /**
     * @param SettingsRepositoryProvider $settingsRepositoryProvider
     * @return static
     * @internal
     */
    public function setSettingsRepositoryProvider(SettingsRepositoryProvider $settingsRepositoryProvider): static
    {
        $this->settingsRepositoryProvider = $settingsRepositoryProvider;
        return $this;
    }
}
