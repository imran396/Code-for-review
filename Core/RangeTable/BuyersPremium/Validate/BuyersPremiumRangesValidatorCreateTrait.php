<?php
/**
 * SAM-8106: Improper validations displayed for invalid inputs
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\RangeTable\BuyersPremium\Validate;

/**
 * Trait BuyersPremiumRangesValidatorCreateTrait
 * @package Sam\Core\RangeTable\BuyersPremium\Validate
 */
trait BuyersPremiumRangesValidatorCreateTrait
{
    /**
     * @var BuyersPremiumRangesValidator|null
     */
    protected ?BuyersPremiumRangesValidator $buyersPremiumRangesValidator = null;

    /**
     * @return BuyersPremiumRangesValidator
     */
    protected function createBuyersPremiumRangesValidator(): BuyersPremiumRangesValidator
    {
        return $this->buyersPremiumRangesValidator ?: BuyersPremiumRangesValidator::new();
    }

    /**
     * @param BuyersPremiumRangesValidator $buyersPremiumRangesValidator
     * @return $this
     * @internal
     */
    public function setBuyersPremiumRangesValidator(BuyersPremiumRangesValidator $buyersPremiumRangesValidator): static
    {
        $this->buyersPremiumRangesValidator = $buyersPremiumRangesValidator;
        return $this;
    }
}
