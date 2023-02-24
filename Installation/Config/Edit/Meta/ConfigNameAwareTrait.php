<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/2/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Meta;

use InvalidArgumentException;
use Sam\Core\Constants;

/**
 * Trait ConfigNameAwareTrait
 * @package Sam\Installation\Config
 */
trait ConfigNameAwareTrait
{
    /**
     * @var string
     */
    protected string $configName = Constants\Installation::DEFAULT_CONFIG;

    /**
     * @return string
     */
    public function getConfigName(): string
    {
        if (!$this->configName) {
            throw new InvalidArgumentException('Config name undefined');
        }
        return $this->configName;
    }

    /**
     * @param string $configName
     * @return static
     */
    public function setConfigName(string $configName): static
    {
        $this->configName = $configName;
        return $this;
    }
}
