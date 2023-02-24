<?php
/**
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

namespace Sam\Lot\BuyerGroup\Category;

/**
 * Trait LotBuyerGroupCategoryManagerAwareTrait
 * @package Sam\Lot\BuyerGroup\Category
 */
trait LotBuyerGroupCategoryManagerAwareTrait
{
    /**
     * @var LotBuyerGroupCategoryManager|null
     */
    protected ?LotBuyerGroupCategoryManager $lotBuyerGroupCategoryManager = null;

    /**
     * @param LotBuyerGroupCategoryManager $lotBuyerGroupCategoryManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotBuyerGroupCategoryManager(LotBuyerGroupCategoryManager $lotBuyerGroupCategoryManager): static
    {
        $this->lotBuyerGroupCategoryManager = $lotBuyerGroupCategoryManager;
        return $this;
    }

    /**
     * @return LotBuyerGroupCategoryManager
     */
    protected function getLotBuyerGroupCategoryManager(): LotBuyerGroupCategoryManager
    {
        if ($this->lotBuyerGroupCategoryManager === null) {
            $this->lotBuyerGroupCategoryManager = LotBuyerGroupCategoryManager::new();
        }
        return $this->lotBuyerGroupCategoryManager;
    }
}
