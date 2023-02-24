<?php
/**
 * SAM-4978: Timed Item existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/24/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Validate;

/**
 * Trait TimedItemExistenceCheckerAwareTrait
 * @package Sam\AuctionLot\Validate
 */
trait TimedItemExistenceCheckerAwareTrait
{
    /**
     * @var TimedItemExistenceChecker|null
     */
    protected ?TimedItemExistenceChecker $timedItemExistenceChecker = null;

    /**
     * @return TimedItemExistenceChecker
     */
    protected function getTimedItemExistenceChecker(): TimedItemExistenceChecker
    {
        if ($this->timedItemExistenceChecker === null) {
            $this->timedItemExistenceChecker = TimedItemExistenceChecker::new();
        }
        return $this->timedItemExistenceChecker;
    }

    /**
     * @param TimedItemExistenceChecker $timedItemExistenceChecker
     * @return static
     * @internal
     */
    public function setTimedItemExistenceChecker(TimedItemExistenceChecker $timedItemExistenceChecker): static
    {
        $this->timedItemExistenceChecker = $timedItemExistenceChecker;
        return $this;
    }
}
