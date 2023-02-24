<?php
/**
 * SAM-10452: Decouple HelpersAwareTrait to rtb modules for v3-7
 */

namespace Sam\Rtb\Command\Helper\Hybrid;

trait HybridRtbCommandHelperAwareTrait
{
    protected ?HybridRtbCommandHelper $rtbCommandHelper = null;

    public function getRtbCommandHelper(): HybridRtbCommandHelper
    {
        if ($this->rtbCommandHelper === null) {
            $this->rtbCommandHelper = HybridRtbCommandHelper::new();
        }
        return $this->rtbCommandHelper;
    }

    /**
     * @internal
     */
    public function setRtbCommandHelper($commandHelper): static
    {
        $this->rtbCommandHelper = $commandHelper;
        return $this;
    }
}
