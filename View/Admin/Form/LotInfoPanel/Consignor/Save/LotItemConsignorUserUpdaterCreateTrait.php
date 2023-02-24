<?php
/**
 * Lot Item Consignor User Updater Create Trait
 *
 * SAM-6248: Refactor Lot Info Panel at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 8, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\Consignor\Save;

/**
 * Trait LotItemConsignorUserUpdaterCreateTrait
 */
trait LotItemConsignorUserUpdaterCreateTrait
{
    protected ?LotItemConsignorUserUpdater $lotItemConsignorUserUpdater = null;

    /**
     * @return LotItemConsignorUserUpdater
     */
    protected function createLotItemConsignorUserUpdater(): LotItemConsignorUserUpdater
    {
        return $this->lotItemConsignorUserUpdater ?: LotItemConsignorUserUpdater::new();
    }

    /**
     * @param LotItemConsignorUserUpdater $lotItemConsignorUserUpdater
     * @return static
     * @internal
     */
    public function setLotItemConsignorUserUpdater(LotItemConsignorUserUpdater $lotItemConsignorUserUpdater): static
    {
        $this->lotItemConsignorUserUpdater = $lotItemConsignorUserUpdater;
        return $this;
    }
}
