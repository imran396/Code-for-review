<?php
/**
 * "Buy Now" function availability checking.
 * This class is inherited from core class, that implements business logic.
 * We use there persistence layer.
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Oct 30, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BuyNow;

/**
 * Trait BuyNowValidatorAwareTrait
 * @package Sam\BuyNow\BuyNowValidator
 */
trait BuyNowValidatorAwareTrait
{
    /**
     * @var BuyNowValidator|null
     */
    protected ?BuyNowValidator $buyNowValidator = null;

    /**
     * @return BuyNowValidator
     */
    protected function getBuyNowValidator(): BuyNowValidator
    {
        if ($this->buyNowValidator === null) {
            $this->buyNowValidator = BuyNowValidator::new();
        }
        return $this->buyNowValidator;
    }

    /**
     * @param BuyNowValidator $buyNowValidator
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setBuyNowValidator(BuyNowValidator $buyNowValidator): static
    {
        $this->buyNowValidator = $buyNowValidator;
        return $this;
    }
}
