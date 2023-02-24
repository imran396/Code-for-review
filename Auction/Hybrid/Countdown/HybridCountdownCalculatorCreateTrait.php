<?php
/**
 * SAM-6314: Unit tests for hybrid countdown calculator
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Hybrid\Countdown;


/**
 * Trait HybridCountdownCalculatorCreateTrait
 * @package Sam\Auction\Hybrid\Countdown
 */
trait HybridCountdownCalculatorCreateTrait
{
    /**
     * @var HybridCountdownCalculator|null
     */
    protected ?HybridCountdownCalculator $hybridCountdownCalculator = null;

    /**
     * @return HybridCountdownCalculator
     */
    protected function createHybridCountdownCalculator(): HybridCountdownCalculator
    {
        return $this->hybridCountdownCalculator ?: HybridCountdownCalculator::new();
    }

    /**
     * @param HybridCountdownCalculator $hybridCountdownCalculator
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setHybridCountdownCalculator(HybridCountdownCalculator $hybridCountdownCalculator): static
    {
        $this->hybridCountdownCalculator = $hybridCountdownCalculator;
        return $this;
    }
}
