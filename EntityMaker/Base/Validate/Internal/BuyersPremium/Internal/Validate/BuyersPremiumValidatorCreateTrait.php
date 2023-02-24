<?php
/**
 * SAM-8107: Issues related to Validation and Values of Buyer's Premium
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Validate\Internal\BuyersPremium\Internal\Validate;

/**
 * Trait BuyersPremiumValidatorCreateTrait
 * @package Sam\EntityMaker\Base\Validate\Internal\BuyersPremium
 */
trait BuyersPremiumValidatorCreateTrait
{
    /**
     * @var BuyersPremiumValidator|null
     */
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
     * @return $this
     * @internal
     */
    public function setBuyersPremiumValidator(BuyersPremiumValidator $buyersPremiumValidator): static
    {
        $this->buyersPremiumValidator = $buyersPremiumValidator;
        return $this;
    }
}
