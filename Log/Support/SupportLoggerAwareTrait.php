<?php
/**
 * Trait adds $logger property to class with getter and setter.
 * It is for general application logging.
 *
 * SAM-9561: Refactor support logger
 * SAM-3312: Replace old error logging with new way https://bidpath.atlassian.net/browse/SAM-3312
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: EditControls.php 13797 2013-07-09 15:26:18Z SWB\igors $
 * @since           Feb 16, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Support;

/**
 * Trait LoggerAwareTrait
 * @package Sam\Log
 */
trait SupportLoggerAwareTrait
{
    /**
     * @var SupportLogger|null
     */
    protected ?SupportLogger $supportLogger = null;

    /**
     * @return SupportLogger
     */
    protected function getSupportLogger(): SupportLogger
    {
        if ($this->supportLogger === null) {
            $this->supportLogger = SupportLogger::new();
        }
        return $this->supportLogger;
    }

    /**
     * @param SupportLogger $supportLogger
     * @return static
     */
    public function setSupportLogger(SupportLogger $supportLogger): static
    {
        $this->supportLogger = $supportLogger;
        return $this;
    }
}
