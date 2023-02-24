<?php
/**
 * SAM-5349: Lot start/end date detector
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Date;

/**
 * Trait TimedLotDateDetectorCreateTrait
 * @package
 */
trait TimedLotDateDetectorCreateTrait
{
    /**
     * @var TimedLotDateDetector|null
     */
    protected ?TimedLotDateDetector $timedLotDateDetector = null;

    /**
     * @return TimedLotDateDetector
     */
    protected function createTimedLotDateDetector(): TimedLotDateDetector
    {
        return $this->timedLotDateDetector ?: TimedLotDateDetector::new();
    }

    /**
     * @param TimedLotDateDetector $timedLotDateDetector
     * @return static
     * @internal
     */
    public function setTimedLotDateDetector(TimedLotDateDetector $timedLotDateDetector): static
    {
        $this->timedLotDateDetector = $timedLotDateDetector;
        return $this;
    }
}
