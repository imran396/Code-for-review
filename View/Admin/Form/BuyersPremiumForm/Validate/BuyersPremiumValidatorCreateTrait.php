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

namespace Sam\View\Admin\Form\BuyersPremiumForm\Validate;

/**
 * Trait BuyersPremiumValidatorCreateTrait
 * @package Sam\View\Admin\Form\BuyersPremiumForm\Validate
 */
trait BuyersPremiumValidatorCreateTrait
{
    protected ?BuyersPremiumValidator $buyersPremiumValidator = null;

    /**
     * @return BuyersPremiumValidator
     */
    protected function createBuyersPremiumValidator(): BuyersPremiumValidator
    {
        return $this->buyersPremiumValidator ?: BuyersPremiumValidator::new();
    }

    /**
     * @param BuyersPremiumValidator $buyersPremiumValidator
     * @return static
     * @internal
     */
    public function setBuyersPremiumValidator(BuyersPremiumValidator $buyersPremiumValidator): static
    {
        $this->buyersPremiumValidator = $buyersPremiumValidator;
        return $this;
    }
}
