<?php
/**
 * Buyers Premium Base Deleter
 *
 * SAM-5950: Refactor buyers premium page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 30, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyersPremiumForm\Delete;

/**
 * Trait BuyersPremiumDeleterCreateTrait
 * @package Sam\View\Admin\Form\BuyersPremiumForm\Delete
 */
trait BuyersPremiumDeleterCreateTrait
{
    protected ?BuyersPremiumDeleter $buyersPremiumDeleter = null;

    /**
     * @return BuyersPremiumDeleter
     */
    protected function createBuyersPremiumDeleter(): BuyersPremiumDeleter
    {
        return $this->buyersPremiumDeleter ?: BuyersPremiumDeleter::new();
    }

    /**
     * @param BuyersPremiumDeleter $buyersPremiumDeleter
     * @return static
     * @internal
     */
    public function setBuyersPremiumDeleter(BuyersPremiumDeleter $buyersPremiumDeleter): static
    {
        $this->buyersPremiumDeleter = $buyersPremiumDeleter;
        return $this;
    }
}
