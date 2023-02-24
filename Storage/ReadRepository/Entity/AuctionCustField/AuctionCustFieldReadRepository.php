<?php
/**
 * General repository for AuctionCustField entity
 *
 * SAM-3686 : Custom field related repositories https://bidpath.atlassian.net/browse/SAM-3686
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           25 April, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of AuctionCustField filtered by criteria
 * $auctionCustFieldRepository = \Sam\Storage\ReadRepository\Entity\AuctionCustField\AuctionCustFieldReadRepository::new()
 *     ->filterName($mainAccountId)          // single value passed as argument
 *     ->filterActive($active)      // array passed as argument
 *     ->skipId([$myId]);                          // search avoiding these user ids
 * $isFound = $auctionCustFieldRepository->exist();
 * $count = $auctionCustFieldRepository->count();
 * $auctionCustField = $auctionCustFieldRepository->loadEntities();
 *
 * // Sample2. Load single AuctionCustField
 * $auctionCustFieldRepository = \Sam\Storage\ReadRepository\Entity\AuctionCustField\AuctionCustFieldReadRepository::new()
 *     ->filterId(1);
 * $auctionCustField = $auctionCustFieldRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionCustField;

/**
 * Class AuctionCustFieldReadRepository
 * @package Sam\Storage\ReadRepository\Entity\AuctionCustField
 */
class AuctionCustFieldReadRepository extends AbstractAuctionCustFieldReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'auction_cust_data' => 'JOIN auction_cust_data AS acd ON acf.id = acd.auction_cust_field_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join `auction_cust_data` table
     * @return static
     */
    public function joinAuctionCustData(): static
    {
        $this->join('auction_cust_data');
        return $this;
    }

    /**
     * Define filtering by acd.active
     * @param bool $active
     * @return static
     */
    public function joinAuctionCustDataFilterActive(bool $active): static
    {
        $this->joinAuctionCustData();
        $this->filterArray('acd.active', $active);
        return $this;
    }

    /**
     * Define filtering by acd.auction_id
     * @param int|int[] $auctionId
     * @return static
     */
    public function joinAuctionCustDataFilterAuctionId(int|array|null $auctionId): static
    {
        $this->joinAuctionCustData();
        $this->filterArray('acd.auction_id', $auctionId);
        return $this;
    }

    /**
     * Define filtering by acd.text
     * @param string|string[] $text
     * @return static
     */
    public function joinAuctionCustDataFilterText(string|array|null $text): static
    {
        $this->joinAuctionCustData();
        $this->filterArray('acd.text', $text);
        return $this;
    }
}
