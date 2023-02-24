<?php
/**
 * SAM-4722: Currency deleter
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\Delete;

/**
 * Trait AuctionCurrencyDeleterCreateTrait
 * @package Sam\Currency\Delete
 */
trait AuctionCurrencyDeleterCreateTrait
{
    protected ?AuctionCurrencyDeleter $auctionCurrencyDeleter = null;

    /**
     * @return AuctionCurrencyDeleter
     */
    protected function createAuctionCurrencyDeleter(): AuctionCurrencyDeleter
    {
        return $this->auctionCurrencyDeleter ?: AuctionCurrencyDeleter::new();
    }

    /**
     * @param AuctionCurrencyDeleter $auctionCurrencyDeleter
     * @return $this
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionCurrencyDeleter(AuctionCurrencyDeleter $auctionCurrencyDeleter): static
    {
        $this->auctionCurrencyDeleter = $auctionCurrencyDeleter;
        return $this;
    }
}
