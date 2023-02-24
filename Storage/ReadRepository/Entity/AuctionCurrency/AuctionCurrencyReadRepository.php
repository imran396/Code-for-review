<?php
/**
 * General repository for AuctionCurrency entity
 *
 * SAM-3687 : Currency related repositories  https://bidpath.atlassian.net/browse/SAM-3687
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           30 April, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of AuctionCurrency filtered by criteria
 * $auctionCurrencyRepository = \Sam\Storage\ReadRepository\Entity\AuctionCurrency\AuctionCurrencyReadRepository::new()
 *     ->filterName($mainAccountId)          // single value passed as argument
 *     ->filterActive($active)      // array passed as argument
 *     ->skipId([$myId]);                          // search avoiding these user ids
 * $isFound = $auctionCurrencyRepository->exist();
 * $count = $auctionCurrencyRepository->count();
 * $auctionCurrency = $auctionCurrencyRepository->loadEntities();
 *
 * // Sample2. Load single AuctionCurrency
 * $auctionCurrencyRepository = \Sam\Storage\ReadRepository\Entity\AuctionCurrency\AuctionCurrencyReadRepository::new()
 *     ->filterId(1);
 * $auctionCurrency = $auctionCurrencyRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionCurrency;

/**
 * Class AuctionCurrencyReadRepository
 * @package Sam\Storage\ReadRepository\Entity\AuctionCurrency
 */
class AuctionCurrencyReadRepository extends AbstractAuctionCurrencyReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
