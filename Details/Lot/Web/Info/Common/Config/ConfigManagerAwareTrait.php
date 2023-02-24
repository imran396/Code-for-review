<?php
/**
 * Config manager aware trait
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 * SAM-6595: Templated-content building - simplify module structure for v3.5
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

namespace Sam\Details\Lot\Web\Info\Common\Config;

use InvalidArgumentException;

/**
 * Trait ConfigManagerAwareTrait
 * @package Sam\Details
 */
trait ConfigManagerAwareTrait
{
    /**
     * @var ConfigManager|null
     */
    protected ?ConfigManager $configManager = null;

    public function getConfigManager(): ConfigManager
    {
        if ($this->configManager === null) {
            throw new InvalidArgumentException('ConfigManager not defined');
        }
        return $this->configManager;
    }

    /**
     * @param ConfigManager $lotConfigManager
     */
    public function setConfigManager($lotConfigManager): static
    {
        $this->configManager = $lotConfigManager;
        return $this;
    }
}
