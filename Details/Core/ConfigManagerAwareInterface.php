<?php

namespace Sam\Details\Core;

/**
 * Interface ConfigManagerAwareInterface
 * @package Sam\Details
 */
interface ConfigManagerAwareInterface
{
    public function getConfigManager(): ConfigManager;

    /**
     * @param ConfigManager $configManager
     */
    public function setConfigManager($configManager): self;
}
