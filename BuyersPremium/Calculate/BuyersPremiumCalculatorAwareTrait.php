<?php
/**
 * SAM-10463: Refactor BP calculator for v3-7 and cover with unit tests
 * SAM-3382: Multiple Buyers Premium rates
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/23/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Calculate;

/**
 * Trait BuyersPremiumCalculatorAwareTrait
 * @package Sam\BuyersPremium
 */
trait BuyersPremiumCalculatorAwareTrait
{
    protected ?BuyersPremiumCalculator $buyersPremiumCalculator = null;

    /**
     * @return BuyersPremiumCalculator
     */
    protected function getBuyersPremiumCalculator(): BuyersPremiumCalculator
    {
        if ($this->buyersPremiumCalculator === null) {
            $this->buyersPremiumCalculator = BuyersPremiumCalculator::new();
        }
        return $this->buyersPremiumCalculator;
    }

    /**
     * @param BuyersPremiumCalculator $buyersPremiumCalculator
     * @return static
     * @internal
     */
    public function setBuyersPremiumCalculator(BuyersPremiumCalculator $buyersPremiumCalculator): static
    {
        $this->buyersPremiumCalculator = $buyersPremiumCalculator;
        return $this;
    }
}
