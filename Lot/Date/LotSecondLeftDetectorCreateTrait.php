<?php
/**
 * SAM-5635: Seconds left and seconds before detector
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Янв. 01, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Date;

/**
 * Trait LotSecondLeftDetectorCreateTrait
 * @package Sam\Lot\Date
 */
trait LotSecondLeftDetectorCreateTrait
{
    /**
     * @var LotSecondLeftDetector|null
     */
    protected ?LotSecondLeftDetector $lotSecondLeftDetector = null;

    /**
     * @return LotSecondLeftDetector
     */
    protected function createLotSecondLeftDetector(): LotSecondLeftDetector
    {
        return $this->lotSecondLeftDetector ?: LotSecondLeftDetector::new();
    }

    /**
     * @param LotSecondLeftDetector $lotSecondLeftDetector
     * @return static
     * @internal
     */
    public function setLotSecondLeftDetector(LotSecondLeftDetector $lotSecondLeftDetector): static
    {
        $this->lotSecondLeftDetector = $lotSecondLeftDetector;
        return $this;
    }
}
