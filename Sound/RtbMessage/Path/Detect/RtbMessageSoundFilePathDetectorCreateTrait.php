<?php
/**
 * SAM-9373: Refactor play sound to avoid client side caching of stale files
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           07-19, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sound\RtbMessage\Path\Detect;

/**
 * Trait RtbMessageSoundFilePathDetectorCreateTrait
 * @package Sam\Sound\RtbMessage\Path\Detect
 */
trait RtbMessageSoundFilePathDetectorCreateTrait
{
    protected ?RtbMessageSoundFilePathDetector $rtbMessageSoundFilePathDetector = null;

    /**
     * @return RtbMessageSoundFilePathDetector
     */
    protected function createRtbMessageSoundFilePathDetector(): RtbMessageSoundFilePathDetector
    {
        return $this->rtbMessageSoundFilePathDetector ?: RtbMessageSoundFilePathDetector::new();
    }

    /**
     * @param RtbMessageSoundFilePathDetector $rtbMessageSoundFilePathDetector
     * @return $this
     * @internal
     */
    public function setRtbMessageSoundFilePathDetector(RtbMessageSoundFilePathDetector $rtbMessageSoundFilePathDetector): static
    {
        $this->rtbMessageSoundFilePathDetector = $rtbMessageSoundFilePathDetector;
        return $this;
    }
}




