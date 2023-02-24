<?php
/**
 * Trait for Lot Buyer Group Member Manager
 *
 * SAM-4439 : Move lot's buyer group logic to Sam\Lot\BuyerGroup namespace
 * https://bidpath.atlassian.net/browse/SAM-4439
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/6/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\BuyerGroup\Member;

/**
 * Trait LotBuyerGroupMemberManagerAwareTrait
 * @package Sam\Lot\BuyerGroup\Member
 */
trait LotBuyerGroupMemberManagerAwareTrait
{
    /**
     * @var LotBuyerGroupMemberManager|null
     */
    protected ?LotBuyerGroupMemberManager $lotBuyerGroupMemberManager = null;

    /**
     * @return LotBuyerGroupMemberManager
     */
    protected function getLotBuyerGroupMemberManager(): LotBuyerGroupMemberManager
    {
        if ($this->lotBuyerGroupMemberManager === null) {
            $this->lotBuyerGroupMemberManager = LotBuyerGroupMemberManager::new();
        }
        return $this->lotBuyerGroupMemberManager;
    }

    /**
     * @param LotBuyerGroupMemberManager $lotBuyerGroupMemberManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotBuyerGroupMemberManager(LotBuyerGroupMemberManager $lotBuyerGroupMemberManager): static
    {
        $this->lotBuyerGroupMemberManager = $lotBuyerGroupMemberManager;
        return $this;
    }

}
