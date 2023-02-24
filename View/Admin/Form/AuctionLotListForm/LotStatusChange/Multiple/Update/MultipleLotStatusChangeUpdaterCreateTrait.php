<?php
/**
 * SAM-10177: Decouple the "Lot status change" function at the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Update;

/**
 * Trait MultipleLotStatusChangeUpdaterCreateTrait
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Update
 */
trait MultipleLotStatusChangeUpdaterCreateTrait
{
    protected ?MultipleLotStatusChangeUpdater $multipleLotStatusChangeUpdater = null;

    /**
     * @return MultipleLotStatusChangeUpdater
     */
    protected function createMultipleLotStatusChangeUpdater(): MultipleLotStatusChangeUpdater
    {
        return $this->multipleLotStatusChangeUpdater ?: MultipleLotStatusChangeUpdater::new();
    }

    /**
     * @param MultipleLotStatusChangeUpdater $multipleLotStatusChangeUpdater
     * @return static
     * @internal
     */
    public function setMultipleLotStatusChangeUpdater(MultipleLotStatusChangeUpdater $multipleLotStatusChangeUpdater): static
    {
        $this->multipleLotStatusChangeUpdater = $multipleLotStatusChangeUpdater;
        return $this;
    }
}
