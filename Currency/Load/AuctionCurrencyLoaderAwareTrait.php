<?php
/**
 * SAM-4560: Currency loaders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/15/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\Load;

/**
 * Trait AuctionCurrencyLoaderAwareTrait
 * @package Sam\Currency\Load
 */
trait AuctionCurrencyLoaderAwareTrait
{
    protected ?AuctionCurrencyLoader $auctionCurrencyLoader = null;

    /**
     * @return AuctionCurrencyLoader
     */
    protected function getAuctionCurrencyLoader(): AuctionCurrencyLoader
    {
        if ($this->auctionCurrencyLoader === null) {
            $this->auctionCurrencyLoader = AuctionCurrencyLoader::new();
        }
        return $this->auctionCurrencyLoader;
    }

    /**
     * @param AuctionCurrencyLoader $auctionCurrencyLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionCurrencyLoader(AuctionCurrencyLoader $auctionCurrencyLoader): static
    {
        $this->auctionCurrencyLoader = $auctionCurrencyLoader;
        return $this;
    }
}
