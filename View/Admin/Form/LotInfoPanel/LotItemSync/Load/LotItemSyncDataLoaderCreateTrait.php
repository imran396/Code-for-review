<?php
/**
 * Lot Item Sync Data Loader Create Trait
 *
 * SAM-6248: Refactor Lot Info Panel at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\LotItemSync\Load;

/**
 * Trait LotItemSyncDataLoaderCreateTrait
 */
trait LotItemSyncDataLoaderCreateTrait
{
    protected ?LotItemSyncDataLoader $lotItemSyncDataLoader = null;

    /**
     * @return LotItemSyncDataLoader
     */
    protected function createLotItemSyncDataLoader(): LotItemSyncDataLoader
    {
        return $this->lotItemSyncDataLoader ?: LotItemSyncDataLoader::new();
    }

    /**
     * @param LotItemSyncDataLoader $lotItemSyncDataLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotItemSyncDataLoader(LotItemSyncDataLoader $lotItemSyncDataLoader): static
    {
        $this->lotItemSyncDataLoader = $lotItemSyncDataLoader;
        return $this;
    }
}
