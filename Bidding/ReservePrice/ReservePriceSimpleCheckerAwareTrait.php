<?php
/**
 * SAM-5045: Reserve met label for auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/3/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\ReservePrice;

/**
 * Trait ReservePriceSimpleCheckerAwareTrait
 * @package Sam\Bidding\ReservePrice
 */
trait ReservePriceSimpleCheckerAwareTrait
{
    /**
     * @var ReservePriceSimpleChecker|null
     */
    protected ?ReservePriceSimpleChecker $reservePriceSimpleChecker = null;

    /**
     * @return ReservePriceSimpleChecker
     */
    protected function getReservePriceSimpleChecker(): ReservePriceSimpleChecker
    {
        if ($this->reservePriceSimpleChecker === null) {
            $this->reservePriceSimpleChecker = ReservePriceSimpleChecker::new();
        }
        return $this->reservePriceSimpleChecker;
    }

    /**
     * @param ReservePriceSimpleChecker $reservePriceSimpleChecker
     * @return static
     * @internal
     */
    public function setReservePriceSimpleChecker(ReservePriceSimpleChecker $reservePriceSimpleChecker): static
    {
        $this->reservePriceSimpleChecker = $reservePriceSimpleChecker;
        return $this;
    }
}
