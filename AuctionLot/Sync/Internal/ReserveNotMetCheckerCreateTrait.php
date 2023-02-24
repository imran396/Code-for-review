<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Internal;

/**
 * Trait ReserveNotMetCheckerCreateTrait
 * @package Sam\AuctionLot\Sync\Internal
 */
trait ReserveNotMetCheckerCreateTrait
{
    protected ?ReserveNotMetChecker $reserveNotMetChecker = null;

    /**
     * @return ReserveNotMetChecker
     */
    protected function createReserveNotMetChecker(): ReserveNotMetChecker
    {
        return $this->reserveNotMetChecker ?: ReserveNotMetChecker::new();
    }

    /**
     * @param ReserveNotMetChecker $reserveNotMetChecker
     * @return static
     * @internal
     */
    public function setReserveNotMetChecker(ReserveNotMetChecker $reserveNotMetChecker): static
    {
        $this->reserveNotMetChecker = $reserveNotMetChecker;
        return $this;
    }
}
