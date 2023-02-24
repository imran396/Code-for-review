<?php
/**
 * SAM-10464: Separate BP manager to services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Validate;

/**
 * Trait BuyersPremiumExistenceCheckerCreateTrait
 * @package Sam\BuyersPremium\Validate
 */
trait BuyersPremiumRangeExistenceCheckerCreateTrait
{
    protected ?BuyersPremiumRangeExistenceChecker $buyersPremiumRangeExistenceChecker = null;

    protected function createBuyersPremiumRangeExistenceChecker(): BuyersPremiumRangeExistenceChecker
    {
        return $this->buyersPremiumRangeExistenceChecker ?: BuyersPremiumRangeExistenceChecker::new();
    }

    /**
     * @internal
     */
    public function setBuyersPremiumRangeExistenceChecker(BuyersPremiumRangeExistenceChecker $buyersPremiumRangeExistenceChecker): static
    {
        $this->buyersPremiumRangeExistenceChecker = $buyersPremiumRangeExistenceChecker;
        return $this;
    }
}
