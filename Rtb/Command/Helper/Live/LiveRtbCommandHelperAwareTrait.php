<?php
/**
 * SAM-10452: Decouple HelpersAwareTrait to rtb modules for v3-7
 */

namespace Sam\Rtb\Command\Helper\Live;

trait LiveRtbCommandHelperAwareTrait
{
    protected ?LiveRtbCommandHelper $rtbCommandHelper = null;

    /**
     * @return LiveRtbCommandHelper
     */
    public function getRtbCommandHelper(): LiveRtbCommandHelper
    {
        if ($this->rtbCommandHelper === null) {
            $this->rtbCommandHelper = LiveRtbCommandHelper::new();
        }
        return $this->rtbCommandHelper;
    }

    /**
     * @internal
     */
    public function setRtbCommandHelper($rtbCommandHelper): static
    {
        $this->rtbCommandHelper = $rtbCommandHelper;
        return $this;
    }
}
