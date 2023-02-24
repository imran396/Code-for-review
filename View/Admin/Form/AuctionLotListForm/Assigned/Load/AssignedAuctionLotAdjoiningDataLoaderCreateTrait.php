<?php
/**
 * SAM-6606: Refactoring classes in the \MySearch namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 09, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load;

/**
 * Trait AssignedAuctionLotAdjoiningDataLoaderCreateTrait
 * @package Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load
 */
trait AssignedAuctionLotAdjoiningDataLoaderCreateTrait
{
    protected ?AssignedAuctionLotAdjoiningDataLoader $assignedAuctionLotAdjoiningDataLoader = null;

    /**
     * @return AssignedAuctionLotAdjoiningDataLoader
     */
    protected function createAssignedAuctionLotAdjoiningDataLoader(): AssignedAuctionLotAdjoiningDataLoader
    {
        return $this->assignedAuctionLotAdjoiningDataLoader ?: AssignedAuctionLotAdjoiningDataLoader::new();
    }

    /**
     * @param AssignedAuctionLotAdjoiningDataLoader $assignedAuctionLotAdjoiningDataLoader
     * @return static
     * @internal
     */
    public function setAssignedAuctionLotAdjoiningDataLoader(AssignedAuctionLotAdjoiningDataLoader $assignedAuctionLotAdjoiningDataLoader): static
    {
        $this->assignedAuctionLotAdjoiningDataLoader = $assignedAuctionLotAdjoiningDataLoader;
        return $this;
    }
}
