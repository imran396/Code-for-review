<?php
/**
 * Config manager aware trait
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 9, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Web;

use InvalidArgumentException;

/**
 * Trait ConfigManagerAwareTrait
 * @package Sam\Details
 */
trait ConfigManagerAwareTrait
{
    protected ?ConfigManager $configManager = null;

    public function getConfigManager(): ConfigManager
    {
        if ($this->configManager === null) {
            $this->initConfigManager(ConfigManager::new());
        }
        if ($this->configManager === null) {
            throw new InvalidArgumentException('ConfigManager not defined');
        }
        return $this->configManager;
    }

    /**
     * @param ConfigManager $auctionConfigManager
     */
    public function setConfigManager($auctionConfigManager): static
    {
        $this->configManager = $auctionConfigManager;
        return $this;
    }

    protected function initConfigManager(ConfigManager $auctionConfigManager): void
    {
        $auctionConfigManager->construct();
        $this->setConfigManager($auctionConfigManager);
    }
}
