<?php

namespace Sam\Rtb\Log;

/**
 * Trait LoggerAwareTrait
 * @package Sam\Rtb
 */
trait LoggerAwareTrait
{
    /**
     * @var Logger|null
     */
    protected ?Logger $rtbLogger = null;

    /**
     * @return Logger
     */
    protected function getLogger(): Logger
    {
        return $this->rtbLogger;
    }

    /**
     * @param Logger $rtbLogger
     * @return static
     */
    public function setLogger(Logger $rtbLogger): static
    {
        $this->rtbLogger = $rtbLogger;
        return $this;
    }
}
