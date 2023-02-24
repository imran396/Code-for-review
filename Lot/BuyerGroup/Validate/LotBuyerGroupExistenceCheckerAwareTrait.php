<?php
/**
 * Trait for Lot Buyer Group Existence Checker
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

namespace Sam\Lot\BuyerGroup\Validate;

/**
 * Trait LotBuyerGroupExistenceCheckerAwareTrait
 * @package Sam\Lot\BuyerGroup\Validate
 */
trait LotBuyerGroupExistenceCheckerAwareTrait
{
    /**
     * @var LotBuyerGroupExistenceChecker|null
     */
    protected ?LotBuyerGroupExistenceChecker $lotBuyerGroupExistenceChecker = null;

    /**
     * @param LotBuyerGroupExistenceChecker $lotBuyerGroupExistenceChecker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotBuyerGroupExistenceChecker(LotBuyerGroupExistenceChecker $lotBuyerGroupExistenceChecker): static
    {
        $this->lotBuyerGroupExistenceChecker = $lotBuyerGroupExistenceChecker;
        return $this;
    }

    /**
     * @return LotBuyerGroupExistenceChecker
     */
    protected function getLotBuyerGroupExistenceChecker(): LotBuyerGroupExistenceChecker
    {
        if ($this->lotBuyerGroupExistenceChecker === null) {
            $this->lotBuyerGroupExistenceChecker = LotBuyerGroupExistenceChecker::new();
        }
        return $this->lotBuyerGroupExistenceChecker;
    }
}
