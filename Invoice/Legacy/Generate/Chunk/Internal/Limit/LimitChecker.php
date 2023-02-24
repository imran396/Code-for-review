<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Generate\Chunk\Internal\Limit;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LimitChecker
 * @package Sam\Invoice\Legacy\Generate\Chunk\Internal\Limit
 */
class LimitChecker extends CustomizableClass
{
    public LimitConfig $limitConfig;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(LimitConfig $limitConfig): static
    {
        $this->limitConfig = $limitConfig;
        return $this;
    }

    /**
     * Check, if memory or execution time limits are exceeded and log debug message on error
     * @return bool
     */
    public function areLimitsExceededAndLog(): bool
    {
        if ($this->isExecutionTimeExceeded()) {
            log_debug("Execution time exceeded" . composeLogData(['exe time' => time()]));
            return true;
        }

        if ($this->isMemoryLimitExceeded()) {
            log_debug("Memory limit exceeded" . composeLogData(['mem peak' => memory_get_peak_usage()]));
            return true;
        }

        return false;
    }

    /**
     * Check, if memory limit is exceeded
     * @return bool
     */
    protected function isMemoryLimitExceeded(): bool
    {
        $isExceeded = false;
        if ($this->limitConfig->memoryLimit) {
            $isExceeded = memory_get_peak_usage() > $this->limitConfig->memoryLimit;
        }
        return $isExceeded;
    }

    /**
     * Check, if execution time limit is exceeded
     * @return bool
     */
    protected function isExecutionTimeExceeded(): bool
    {
        $isExceeded = false;
        if ($this->limitConfig->execMaxDuration) {
            $isExceeded = time() > ($this->limitConfig->execStartTime + $this->limitConfig->execMaxDuration);
        }
        return $isExceeded;
    }

    public function logData(): array
    {
        return [
            'memory_limit' => $this->limitConfig->memoryLimit,
            'start time' => $this->limitConfig->execStartTime,
            'max duration' => $this->limitConfig->execMaxDuration,
        ];
    }
}
