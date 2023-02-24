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

namespace Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load\Query;

/**
 * Trait AssignedAuctionLotListQueryBuilderCreateTrait
 * @package Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load\Query
 */
trait AssignedAuctionLotListQueryBuilderCreateTrait
{
    protected ?AssignedAuctionLotListQueryBuilder $assignedAuctionLotListQueryBuilder = null;

    /**
     * @return AssignedAuctionLotListQueryBuilder
     */
    protected function createAssignedAuctionLotListQueryBuilder(): AssignedAuctionLotListQueryBuilder
    {
        return $this->assignedAuctionLotListQueryBuilder ?: AssignedAuctionLotListQueryBuilder::new();
    }

    /**
     * @param AssignedAuctionLotListQueryBuilder $assignedAuctionLotListQueryBuilder
     * @return static
     * @internal
     */
    public function setAssignedAuctionLotListQueryBuilder(AssignedAuctionLotListQueryBuilder $assignedAuctionLotListQueryBuilder): static
    {
        $this->assignedAuctionLotListQueryBuilder = $assignedAuctionLotListQueryBuilder;
        return $this;
    }
}
