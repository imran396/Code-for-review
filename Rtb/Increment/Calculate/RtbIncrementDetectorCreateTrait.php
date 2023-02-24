<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Increment\Calculate;

/**
 * Trait RtbIncrementDetectorCreateTrait
 * @package Sam\Rtb\Increment\Calculate
 */
trait RtbIncrementDetectorCreateTrait
{
    /**
     * @var RtbIncrementDetector|null
     */
    protected ?RtbIncrementDetector $rtbIncrementDetector = null;

    /**
     * @return RtbIncrementDetector
     */
    protected function createRtbIncrementDetector(): RtbIncrementDetector
    {
        $rtbIncrementDetector = $this->rtbIncrementDetector ?: RtbIncrementDetector::new();
        return $rtbIncrementDetector;
    }

    /**
     * @param RtbIncrementDetector $rtbIncrementDetector
     * @return static
     * @internal
     */
    public function setRtbIncrementDetector(RtbIncrementDetector $rtbIncrementDetector): static
    {
        $this->rtbIncrementDetector = $rtbIncrementDetector;
        return $this;
    }
}
