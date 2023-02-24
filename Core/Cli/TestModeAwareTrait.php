<?php
/**
 * When test mode is enabled, we want to run command without affecting state
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/8/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Cli;

/**
 * Trait TestModeAwareTrait
 * @package Sam\Rtb\Pool\Cli\Command\Base
 */
trait TestModeAwareTrait
{
    /**
     * @var bool
     */
    protected bool $isTestMode = false;

    /**
     * @return bool
     */
    public function isTestMode(): bool
    {
        return $this->isTestMode;
    }

    /**
     * @param bool $isTestMode
     * @return static
     */
    public function enableTestMode(bool $isTestMode): static
    {
        $this->isTestMode = $isTestMode;
        return $this;
    }
}
