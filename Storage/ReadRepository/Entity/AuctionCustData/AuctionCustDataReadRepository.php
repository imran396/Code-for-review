<?php
/**
 * General repository for AuctionCustData entity
 *
 * SAM-3686 : Custom field related repositories https://bidpath.atlassian.net/browse/SAM-3686
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           13 Mar, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of AuctionCustData filtered by criteria
 * $auctionCustDataRepository = \Sam\Storage\Repository\AuctionCustDataReadRepository::new()
 *     ->filterName($mainAccountId)          // single value passed as argument
 *     ->filterActive($active)      // array passed as argument
 *     ->skipId([$myId]);                          // search avoiding these user ids
 * $isFound = $auctionCustDataRepository->exist();
 * $count = $auctionCustDataRepository->count();
 * $auctionCustData = $auctionCustDataRepository->loadEntities();
 *
 * // Sample2. Load single AuctionCustData
 * $auctionCustDataRepository = \Sam\Storage\Repository\AuctionCustDataReadRepository::new()
 *     ->filterId(1);
 * $auctionCustData = $auctionCustDataRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionCustData;

/**
 * Class AuctionCustDataReadRepository
 * @package Sam\Storage\Repository
 */
class AuctionCustDataReadRepository extends AbstractAuctionCustDataReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = a.account_id',
        'auction' => 'JOIN auction a ON acd.auction_id = a.id',
        'auction_cust_field' => 'JOIN auction_cust_field acf ON acd.auction_cust_field_id = acf.id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join 'account' table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
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
     * Join `auction_cust_field` table
     * @return static
     */
    public function joinAuctionCustomField(): static
    {
        $this->join('auction_cust_field');
        return $this;
    }

    /**
     * Left join auction table
     * Define filtering by a.account_id
     * @param int|int[] $accountIds
     * @return static
     */
    public function joinAuctionFilterAccountId(int|array|null $accountIds): static
    {
        $this->joinAuction();
        $this->filterArray('a.account_id', $accountIds);
        return $this;
    }

    /**
     * Define ordering by a.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->joinAuction();
        $this->order('a.account_id', $ascending);
        return $this;
    }
}

