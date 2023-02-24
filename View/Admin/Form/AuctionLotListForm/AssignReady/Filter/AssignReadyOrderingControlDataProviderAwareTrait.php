<?php
/**
 * SAM-5583: Refactor data loader for Assign-ready item list at Auction Lot List page at admin side
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/26/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\AssignReady\Filter;

/**
 * Trait AssignReadyOrderingControlHelperAwareTrait
 * @package
 */
trait AssignReadyOrderingControlDataProviderAwareTrait
{
    protected ?AssignReadyOrderingControlDataProvider $assignReadyOrderingControlDataProvider = null;

    /**
     * @return AssignReadyOrderingControlDataProvider
     */
    protected function getAssignReadyOrderingControlDataProvider(): AssignReadyOrderingControlDataProvider
    {
        if ($this->assignReadyOrderingControlDataProvider === null) {
            $this->assignReadyOrderingControlDataProvider = AssignReadyOrderingControlDataProvider::new();
        }
        return $this->assignReadyOrderingControlDataProvider;
    }

    /**
     * @param AssignReadyOrderingControlDataProvider $assignReadyOrderingControlHelper
     * @return static
     * @internal
     */
    public function setAssignReadyOrderingControlDataProvider(AssignReadyOrderingControlDataProvider $assignReadyOrderingControlHelper): static
    {
        $this->assignReadyOrderingControlDataProvider = $assignReadyOrderingControlHelper;
        return $this;
    }
}
