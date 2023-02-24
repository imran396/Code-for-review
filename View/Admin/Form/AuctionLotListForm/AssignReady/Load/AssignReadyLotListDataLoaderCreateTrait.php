<?php
/**
 * SAM-5583: Refactor data loader for Assign-ready item list at Auction Lot List page at admin side
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: Sam\View\Admin\Form\AuctionLotListForm\AssignReadyLoad: $
 * @since           12/26/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\AssignReady\Load;

/**
 * Trait AssignReadyLotListDataLoaderCreateTrait
 * @package
 */
trait AssignReadyLotListDataLoaderCreateTrait
{
    protected ?AssignReadyLotListDataLoader $assignReadyLotListDataLoader = null;

    /**
     * @return AssignReadyLotListDataLoader
     */
    protected function createAssignReadyLotListDataLoader(): AssignReadyLotListDataLoader
    {
        return $this->assignReadyLotListDataLoader ?: AssignReadyLotListDataLoader::new();
    }

    /**
     * @param AssignReadyLotListDataLoader $assignReadyLotListDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAssignReadyLotListDataLoader(AssignReadyLotListDataLoader $assignReadyLotListDataLoader): static
    {
        $this->assignReadyLotListDataLoader = $assignReadyLotListDataLoader;
        return $this;
    }
}
