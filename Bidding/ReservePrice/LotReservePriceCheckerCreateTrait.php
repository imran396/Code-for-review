<?php
/**
 * SAM-5045: Reserve met label for auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/17/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\ReservePrice;

/**
 * Trait LotReservePriceCheckerAwareTrait
 * @package Sam\Bidding\ReservePrice
 */
trait LotReservePriceCheckerCreateTrait
{
    /**
     * @var LotReservePriceChecker|null
     */
    protected ?LotReservePriceChecker $lotReservePriceChecker = null;

    /**
     * @return LotReservePriceChecker
     */
    protected function createLotReservePriceChecker(): LotReservePriceChecker
    {
        return $this->lotReservePriceChecker ?: LotReservePriceChecker::new();
    }

    /**
     * @param LotReservePriceChecker $lotReservePriceChecker
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setLotReservePriceChecker(LotReservePriceChecker $lotReservePriceChecker): static
    {
        $this->lotReservePriceChecker = $lotReservePriceChecker;
        return $this;
    }
}
