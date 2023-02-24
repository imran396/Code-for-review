<?php
/**
 * General repository for Currency entity
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
 * // Sample1. Check, count and load array of Currency filtered by criteria
 * $currencyRepository = \Sam\Storage\ReadRepository\Entity\Currency\CurrencyReadRepository::new()
 *     ->filterName($mainAccountId)          // single value passed as argument
 *     ->filterActive($active)      // array passed as argument
 *     ->skipId([$myId]);                          // search avoiding these user ids
 * $isFound = $currencyRepository->exist();
 * $count = $currencyRepository->count();
 * $currency = $currencyRepository->loadEntities();
 *
 * // Sample2. Load single Currency
 * $currencyRepository = \Sam\Storage\ReadRepository\Entity\Currency\CurrencyReadRepository::new()
 *     ->filterId(1);
 * $currency = $currencyRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\Currency;

/**
 * Class CurrencyReadRepository
 * @package Sam\Storage\ReadRepository\Entity\Currency
 */
class CurrencyReadRepository extends AbstractCurrencyReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'auction' => 'JOIN auction AS a ON a.currency = curr.id',
        'auction_currency' => 'JOIN auction_currency AS acurr ON acurr.currency_id = curr.id'
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join `auction` table
     * @return static
     */
    public function joinAuction(): static
    {
        $this->join('auction');
        return $this;
    }

    /**
     * Define filtering by a.id
     * @param int|int[] $auctionId
     * @return static
     */
    public function joinAuctionFilterId(int|array|null $auctionId): static
    {
        $this->joinAuction();
        $this->filterArray('a.id', $auctionId);
        return $this;
    }

    /**
     * Join `auction_currency` table
     * @return static
     */
    public function joinAuctionCurrency(): static
    {
        $this->join('auction_currency');
        return $this;
    }

    /**
     * Define filtering by a.id
     * @param int|int[] $auctionId
     * @return static
     */
    public function joinAuctionCurrencyFilterAuctionId(int|array|null $auctionId): static
    {
        $this->joinAuctionCurrency();
        $this->filterArray('acurr.auction_id', $auctionId);
        return $this;
    }
}
