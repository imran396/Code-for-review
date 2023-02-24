<?php
/**
 * SAM-5153: Lot counter
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           08.06.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\Lot\Count;


/**
 * Trait LotCounterAwareTrait
 * @package Sam\Lot\Count
 */
trait LotCounterAwareTrait
{
    /**
     * @var LotCounter|null
     */
    protected ?LotCounter $lotCounter = null;

    /**
     * @return LotCounter
     */
    protected function getLotCounter(): LotCounter
    {
        if ($this->lotCounter === null) {
            $this->lotCounter = LotCounter::new();
        }
        return $this->lotCounter;
    }

    /**
     * @param LotCounter $lotCounter
     * @return static
     * @internal
     */
    public function setLotCounter(LotCounter $lotCounter): static
    {
        $this->lotCounter = $lotCounter;
        return $this;
    }
}
