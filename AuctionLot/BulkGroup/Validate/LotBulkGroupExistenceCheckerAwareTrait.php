<?php
/**
 * SAM-5408 : Apply LotBulkGroupExistenceChecker
 * https://bidpath.atlassian.net/browse/SAM-5408
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 21, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\BulkGroup\Validate;

/**
 * Trait LotBulkGroupExistenceCheckerAwareTrait
 * @package Sam\AuctionLot\BulkGroup
 */
trait LotBulkGroupExistenceCheckerAwareTrait
{
    /**
     * @var LotBulkGroupExistenceChecker|null
     */
    protected ?LotBulkGroupExistenceChecker $lotBulkGroupExistenceChecker = null;

    /**
     * @return LotBulkGroupExistenceChecker
     */
    protected function getLotBulkGroupExistenceChecker(): LotBulkGroupExistenceChecker
    {
        if ($this->lotBulkGroupExistenceChecker === null) {
            $this->lotBulkGroupExistenceChecker = LotBulkGroupExistenceChecker::new();
        }
        return $this->lotBulkGroupExistenceChecker;
    }

    /**
     * @param LotBulkGroupExistenceChecker $lotBulkGroupExistenceChecker
     * @return static
     * @internal
     */
    public function setLotBulkGroupExistenceChecker(LotBulkGroupExistenceChecker $lotBulkGroupExistenceChecker): static
    {
        $this->lotBulkGroupExistenceChecker = $lotBulkGroupExistenceChecker;
        return $this;
    }
}
