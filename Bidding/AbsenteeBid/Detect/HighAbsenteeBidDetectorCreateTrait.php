<?php
/**
 * SAM-5095: High Absentee Bid Detector
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/10/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\AbsenteeBid\Detect;

/**
 * Trait HighAbsenteeBidDetectorCreateTrait
 * @package Sam\Bidding\Detect
 */
trait HighAbsenteeBidDetectorCreateTrait
{
    /**
     * @var HighAbsenteeBidDetector|null
     */
    protected ?HighAbsenteeBidDetector $highAbsenteeBidDetector = null;

    /**
     * @return HighAbsenteeBidDetector
     */
    protected function createHighAbsenteeBidDetector(): HighAbsenteeBidDetector
    {
        $highAbsenteeBidDetector = $this->highAbsenteeBidDetector ?: HighAbsenteeBidDetector::new();
        return $highAbsenteeBidDetector;
    }

    /**
     * @param HighAbsenteeBidDetector $highAbsenteeBidDetector
     * @return static
     * @internal
     */
    public function setHighAbsenteeBidDetector(HighAbsenteeBidDetector $highAbsenteeBidDetector): static
    {
        $this->highAbsenteeBidDetector = $highAbsenteeBidDetector;
        return $this;
    }
}
