<?php

namespace Sam\Rtb\Hybrid;

/**
 * Trait TimeoutHelperAwareTrait
 * @package Sam\Rtb\Hybrid
 */
trait TimeoutHelperAwareTrait
{
    protected ?TimeoutHelper $timeoutHelper = null;

    /**
     * @return TimeoutHelper
     */
    protected function getTimeoutHelper(): TimeoutHelper
    {
        if ($this->timeoutHelper === null) {
            $this->timeoutHelper = TimeoutHelper::new();
        }
        return $this->timeoutHelper;
    }

    /**
     * @param TimeoutHelper $timeoutHelper
     * @return static
     * @internal
     */
    public function setTimeoutHelper(TimeoutHelper $timeoutHelper): static
    {
        $this->timeoutHelper = $timeoutHelper;
        return $this;
    }
}
