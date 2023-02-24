<?php
/**
 * Buyer Group Edit Updater Create Trait
 *
 * SAM-5945: Refactor buyer group edit page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 25, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyerGroupEditForm\Save;

/**
 * Trait BuyerGroupEditUpdaterCreateTrait
 */
trait BuyerGroupEditUpdaterCreateTrait
{
    protected ?BuyerGroupEditUpdater $buyerGroupEditUpdater = null;

    /**
     * @return BuyerGroupEditUpdater
     */
    protected function createBuyerGroupEditUpdater(): BuyerGroupEditUpdater
    {
        return $this->buyerGroupEditUpdater ?: BuyerGroupEditUpdater::new();
    }

    /**
     * @param BuyerGroupEditUpdater $buyerGroupEditUpdater
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setBuyerGroupEditUpdater(BuyerGroupEditUpdater $buyerGroupEditUpdater): static
    {
        $this->buyerGroupEditUpdater = $buyerGroupEditUpdater;
        return $this;
    }
}
