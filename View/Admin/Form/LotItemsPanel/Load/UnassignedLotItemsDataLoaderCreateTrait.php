<?php
/**
 * Unassigned Lot Items Data Loader Create Trait
 *
 * SAM-6273: Refactor Lot Items Panel at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 9, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotItemsPanel\Load;

/**
 * Trait UnassignedLotItemsDataLoaderCreateTrait
 */
trait UnassignedLotItemsDataLoaderCreateTrait
{
    protected ?UnassignedLotItemsDataLoader $unassignedLotItemsDataLoader = null;

    /**
     * @return UnassignedLotItemsDataLoader
     */
    protected function createUnassignedLotItemsDataLoader(): UnassignedLotItemsDataLoader
    {
        $unassignedLotItemsDataLoader = $this->unassignedLotItemsDataLoader ?: UnassignedLotItemsDataLoader::new();
        return $unassignedLotItemsDataLoader;
    }

    /**
     * @param UnassignedLotItemsDataLoader $unassignedLotItemsDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setUnassignedLotItemsDataLoader(UnassignedLotItemsDataLoader $unassignedLotItemsDataLoader): static
    {
        $this->unassignedLotItemsDataLoader = $unassignedLotItemsDataLoader;
        return $this;
    }
}
