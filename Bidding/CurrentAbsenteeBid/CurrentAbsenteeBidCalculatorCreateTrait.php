<?php
/**
 * SAM-3791: Current Absentee Bid calculation refactoring https://bidpath.atlassian.net/browse/SAM-3791
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/2/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\CurrentAbsenteeBid;

/**
 * Trait CurrentAbsenteeBidCalculatorCreateTrait
 * @package Sam\Bidding\CurrentAbsenteeBid
 */
trait CurrentAbsenteeBidCalculatorCreateTrait
{
    /**
     * @var CurrentAbsenteeBidCalculator|null
     */
    protected ?CurrentAbsenteeBidCalculator $currentAbsenteeBidCalculator = null;

    /**
     * @return CurrentAbsenteeBidCalculator
     */
    protected function createCurrentAbsenteeBidCalculator(): CurrentAbsenteeBidCalculator
    {
        $currentAbsenteeBidCalculator = $this->currentAbsenteeBidCalculator
            ?: CurrentAbsenteeBidCalculator::new();
        return $currentAbsenteeBidCalculator;
    }

    /**
     * @param CurrentAbsenteeBidCalculator $currentAbsenteeBidCalculator
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setCurrentAbsenteeBidCalculator(CurrentAbsenteeBidCalculator $currentAbsenteeBidCalculator): static
    {
        $this->currentAbsenteeBidCalculator = $currentAbsenteeBidCalculator;
        return $this;
    }
}
