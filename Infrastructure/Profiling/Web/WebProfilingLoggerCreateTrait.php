<?php
/**
 * SAM-8022: Extract log output of web page profiling to separate service
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Profiling\Web;

/**
 * Trait WebProfilingLoggerCreateTrait
 * @package Sam\Infrastructure\Profiling\Web
 */
trait WebProfilingLoggerCreateTrait
{
    /**
     * @var WebProfilingLogger|null
     */
    protected ?WebProfilingLogger $webProfilingLogger = null;

    /**
     * @return WebProfilingLogger
     */
    protected function createWebProfilingLogger(): WebProfilingLogger
    {
        return $this->webProfilingLogger ?: WebProfilingLogger::new();
    }

    /**
     * @param WebProfilingLogger $webProfilingLogger
     * @return $this
     * @internal
     */
    public function setWebProfilingLogger(WebProfilingLogger $webProfilingLogger): static
    {
        $this->webProfilingLogger = $webProfilingLogger;
        return $this;
    }
}
