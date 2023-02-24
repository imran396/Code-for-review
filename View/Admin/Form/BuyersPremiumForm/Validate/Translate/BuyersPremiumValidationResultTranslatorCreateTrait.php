<?php
/**
 * SAM-10477: Reject assigning both BP rules on the same level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyersPremiumForm\Validate\Translate;

/**
 * Trait BuyersPremiumValidationResultTranslatorCreateTrait
 * @package Sam\View\Admin\Form\BuyersPremiumForm\Validate\Translate
 */
trait BuyersPremiumValidationResultTranslatorCreateTrait
{
    protected ?BuyersPremiumValidationResultTranslator $buyersPremiumValidationResultTranslator = null;

    /**
     * @return BuyersPremiumValidationResultTranslator
     */
    protected function createBuyersPremiumValidationResultTranslator(): BuyersPremiumValidationResultTranslator
    {
        return $this->buyersPremiumValidationResultTranslator ?: BuyersPremiumValidationResultTranslator::new();
    }

    /**
     * @param BuyersPremiumValidationResultTranslator $buyersPremiumValidationResultTranslator
     * @return static
     * @internal
     */
    public function setBuyersPremiumValidationResultTranslator(BuyersPremiumValidationResultTranslator $buyersPremiumValidationResultTranslator): static
    {
        $this->buyersPremiumValidationResultTranslator = $buyersPremiumValidationResultTranslator;
        return $this;
    }
}
