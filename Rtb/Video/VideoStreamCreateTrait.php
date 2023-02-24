<?php
/**
 * SAM-7841: Refactor BidPath_Stream
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Video;

/**
 * Trait VideoStreamCreateTrait
 * @package Sam\Rtb\Video
 */
trait VideoStreamCreateTrait
{
    protected ?VideoStream $videoStream = null;

    /**
     * @return VideoStream
     */
    protected function createVideoStream(): VideoStream
    {
        return $this->videoStream ?: VideoStream::new();
    }

    /**
     * @param VideoStream $videoStream
     * @return static
     * @internal
     */
    public function setVideoStream(VideoStream $videoStream): static
    {
        $this->videoStream = $videoStream;
        return $this;
    }
}
