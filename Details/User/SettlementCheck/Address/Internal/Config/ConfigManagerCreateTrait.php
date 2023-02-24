<?php
/**
 * SAM-10136: Implement conditional logic in print check template field Payee
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\User\SettlementCheck\Address\Internal\Config;

/**
 * Trait ConfigManagerCreateTrait
 * @package Sam\Details\User\SettlementCheck\Address\Internal\Config
 */
trait ConfigManagerCreateTrait
{
    /**
     * @var ConfigManager|null
     */
    protected ?ConfigManager $configManager = null;

    protected function createConfigManager(): ConfigManager
    {
        return $this->configManager ?: ConfigManager::new();
    }

    /**
     * @internal
     */
    public function setConfigManager(ConfigManager $configManager): static
    {
        $this->configManager = $configManager;
        return $this;
    }
}
