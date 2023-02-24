<?php

namespace Sam\Rtb\Load;

/**
 * Trait RtbLoaderAwareTrait
 * @package Sam\Rtb\Load
 */
trait RtbLoaderAwareTrait
{
    /**
     * @var RtbLoader|null
     */
    protected ?RtbLoader $rtbLoader = null;

    /**
     * @return RtbLoader
     */
    protected function getRtbLoader(): RtbLoader
    {
        if ($this->rtbLoader === null) {
            $this->rtbLoader = RtbLoader::new();
        }
        return $this->rtbLoader;
    }

    /**
     * @param RtbLoader $rtbLoader
     * @return static
     * @internal
     */
    public function setRtbLoader(RtbLoader $rtbLoader): static
    {
        $this->rtbLoader = $rtbLoader;
        return $this;
    }
}
