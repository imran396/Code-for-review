<?php
/**
 * SAM-9561: Refactor support logger
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Support\Internal\Write;

/**
 * Trait LogWriterAwareTrait
 * @package Sam\Log\Support\Internal\Write
 */
trait LogWriterAwareTrait
{
    protected ?LogWriter $logWriter = null;

    /**
     * @return LogWriter
     */
    protected function getLogWriter(): LogWriter
    {
        if ($this->logWriter === null) {
            $this->logWriter = LogWriter::new();
        }
        return $this->logWriter;
    }

    /**
     * @param LogWriter $logWriter
     * @return $this
     * @internal
     */
    public function setLogWriter(LogWriter $logWriter): static
    {
        $this->logWriter = $logWriter;
        return $this;
    }
}
