<?php

namespace Sam\Storage\ReadRepository\Entity\AuctionCache;

/**
 * Class AuctionCacheReadRepository
 * @package Sam\Storage\Repository
 */
class AuctionCacheReadRepository extends AbstractAuctionCacheReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'account' => 'JOIN account AS acc ON acc.id = a.account_id',
        'auction' => 'JOIN auction AS a ON a.id = ac.auction_id',
        'start_ending_timezone' => 'JOIN timezone AS setz ON setz.id = ac.start_ending_timezone_id',
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
     * Define filtering by auction.auction_type
     * @param string|string[] $auctionType
     * @return static
     */
    public function joinAuctionFilterAuctionType(string|array|null $auctionType): static
    {
        $this->joinAuction();
        $this->filterArray('a.auction_type', $auctionType);
        return $this;
    }

    /**
     * Define filtering by auction.auction_type
     * @param int|int[] $eventType
     * @return static
     */
    public function joinAuctionFilterEventType(int|array|null $eventType): static
    {
        $this->joinAuction();
        $this->filterArray('a.event_type', $eventType);
        return $this;
    }
}
