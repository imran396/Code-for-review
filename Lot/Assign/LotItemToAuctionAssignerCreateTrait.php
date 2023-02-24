<?php
/**
 * ${TICKET}
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Янв. 09, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Assign;

/**
 * Trait LotItemToAuctionAssignerCreateTrait
 * @package Sam\Lot\Assign
 */
trait LotItemToAuctionAssignerCreateTrait
{
    /**
     * @var LotItemToAuctionAssigner|null
     */
    protected ?LotItemToAuctionAssigner $lotItemToAuctionAssigner = null;

    /**
     * @return LotItemToAuctionAssigner
     */
    protected function createLotItemToAuctionAssigner(): LotItemToAuctionAssigner
    {
        return $this->lotItemToAuctionAssigner ?: LotItemToAuctionAssigner::new();
    }

    /**
     * @param LotItemToAuctionAssigner $lotItemToAuctionAssigner
     * @return static
     * @internal
     */
    public function setLotItemToAuctionAssigner(LotItemToAuctionAssigner $lotItemToAuctionAssigner): static
    {
        $this->lotItemToAuctionAssigner = $lotItemToAuctionAssigner;
        return $this;
    }
}
