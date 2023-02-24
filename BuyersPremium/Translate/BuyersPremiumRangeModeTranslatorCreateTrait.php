<?php
/**
 * SAM-10477: Reject assigning both BP rules on the same level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Translate;

/**
 * Trait BuyersPremiumRangeModeTranslatorCreateTrait
 * @package Sam\BuyersPremium\Translate
 */
trait BuyersPremiumRangeModeTranslatorCreateTrait
{
    protected ?BuyersPremiumRangeModeTranslator $buyersPremiumRangeModeTranslator = null;

    /**
     * @return BuyersPremiumRangeModeTranslator
     */
    protected function createBuyersPremiumRangeModeTranslator(): BuyersPremiumRangeModeTranslator
    {
        return $this->buyersPremiumRangeModeTranslator ?: BuyersPremiumRangeModeTranslator::new();
    }

    /**
     * @param BuyersPremiumRangeModeTranslator $buyersPremiumRangeModeTranslator
     * @return static
     * @internal
     */
    public function setBuyersPremiumRangeModeTranslator(BuyersPremiumRangeModeTranslator $buyersPremiumRangeModeTranslator): static
    {
        $this->buyersPremiumRangeModeTranslator = $buyersPremiumRangeModeTranslator;
        return $this;
    }
}
