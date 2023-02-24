<?php
/**
 * SAM-5633: Lot state detector
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

namespace Sam\Lot\Validate\State;

/**
 * Trait LotStateDetectorCreateTrait
 * @package Sam\Lot\Validate\State
 */
trait LotStateDetectorCreateTrait
{
    /**
     * @var LotStateDetector|null
     */
    protected ?LotStateDetector $lotStateDetector = null;

    /**
     * @return LotStateDetector
     */
    protected function createLotStateDetector(): LotStateDetector
    {
        return $this->lotStateDetector ?: LotStateDetector::new();
    }

    /**
     * @param LotStateDetector $lotStateDetector
     * @return static
     * @internal
     */
    public function setLotStateDetector(LotStateDetector $lotStateDetector): static
    {
        $this->lotStateDetector = $lotStateDetector;
        return $this;
    }
}
