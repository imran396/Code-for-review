<?php
/**
 * SAM-5229: Outrageous bid alert reveals hidden high absentee bid
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           7/20/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\ExcessiveBid;

/**
 * Trait ExcessiveAbsenteeBidDetector
 * @package Sam\Bidding\ExcessiveBid
 */
trait ExcessiveAbsenteeBidDetectorCreateTrait
{
    /**
     * @var ExcessiveAbsenteeBidDetector|null
     */
    protected ?ExcessiveAbsenteeBidDetector $excessiveAbsenteeBidDetector = null;

    /**
     * @return ExcessiveAbsenteeBidDetector
     */
    protected function createExcessiveAbsenteeBidDetector(): ExcessiveAbsenteeBidDetector
    {
        $excessiveBidLiveHelper = $this->excessiveAbsenteeBidDetector ?: ExcessiveAbsenteeBidDetector::new();
        return $excessiveBidLiveHelper;
    }

    /**
     * @param ExcessiveAbsenteeBidDetector $excessiveAbsenteeBidDetector
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setExcessiveAbsenteeBidDetector(ExcessiveAbsenteeBidDetector $excessiveAbsenteeBidDetector): static
    {
        $this->excessiveAbsenteeBidDetector = $excessiveAbsenteeBidDetector;
        return $this;
    }
}
