<?php
/**
 * Buyer Group List Deleter Create Trait
 *
 * SAM-5949: Refactor buyer group list page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 26, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyerGroupListForm\Delete;

/**
 * Trait BuyerGroupListDeleterCreateTrait
 */
trait BuyerGroupListDeleterCreateTrait
{
    protected ?BuyerGroupListDeleter $buyerGroupListDeleter = null;

    /**
     * @return BuyerGroupListDeleter
     */
    protected function createBuyerGroupListDeleter(): BuyerGroupListDeleter
    {
        return $this->buyerGroupListDeleter ?: BuyerGroupListDeleter::new();
    }

    /**
     * @param BuyerGroupListDeleter $buyerGroupListDeleter
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setBuyerGroupListDeleter(BuyerGroupListDeleter $buyerGroupListDeleter): static
    {
        $this->buyerGroupListDeleter = $buyerGroupListDeleter;
        return $this;
    }
}
