<?php
/**
 * SAM-6292: Move fields from auction_cache to auction_details_cache
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug. 31, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionDetailsCache;

/**
 * Class AuctionDetailsCacheReadRepository
 * @package Sam\Storage\ReadRepository\Entity\AuctionDetailsCache
 */
class AuctionDetailsCacheReadRepository extends AbstractAuctionDetailsCacheReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'account' => 'JOIN account AS acc ON acc.id = a.account_id',
        'auction' => 'JOIN auction AS a ON a.id = adc.auction_id'
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Define filtering by acc.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinAccountFilterActive(bool|array|null $active): static
    {
        $this->joinAccount();
        $this->filterArray('acc.active', $active);
        return $this;
    }

    /**
     * Define filtering by a.auction_status_id
     * @param int|int[] $auctionStatusIds
     * @return static
     */
    public function joinAuctionFilterAuctionStatusId(int|array|null $auctionStatusIds): static
    {
        $this->joinAuction();
        $this->filterArray('a.auction_status_id', $auctionStatusIds);
        return $this;
    }

    /**
     * Define filtering by a.zccount_id
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
     * Join 'account' table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->joinAuction();
        $this->join('account');
        return $this;
    }

    /**
     * Join auction table
     * @return static
     */
    public function joinAuction(): static
    {
        $this->join('auction');
        return $this;
    }
}
