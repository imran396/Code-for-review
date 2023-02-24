<?php
/**
 * SAM-6397: Runtime config options
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Log\RuntimeOption;

/**
 * Trait RuntimeOptionLoggerCreateTrait
 * @package Sam\Application\Log\RuntimeOption
 */
trait RuntimeOptionLoggerCreateTrait
{
    /**
     * @var RuntimeOptionLogger|null
     */
    protected ?RuntimeOptionLogger $runtimeOptionLogger = null;

    /**
     * @return RuntimeOptionLogger
     */
    protected function createRuntimeOptionLogger(): RuntimeOptionLogger
    {
        return $this->runtimeOptionLogger ?: RuntimeOptionLogger::new();
    }

    /**
     * @param RuntimeOptionLogger $runtimeOptionLogger
     * @return $this
     * @internal
     */
    public function setRuntimeOptionLogger(RuntimeOptionLogger $runtimeOptionLogger): static
    {
        $this->runtimeOptionLogger = $runtimeOptionLogger;
        return $this;
    }
}
