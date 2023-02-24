<?php
/**
 * SAM-4627: Refactor auction list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-05-02
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\AuctionList\Csv;

/**
 * Class DataLoader
 */
class DataLoader extends \Sam\Report\Auction\AuctionList\Base\DataLoader
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return value of chunkSize property
     * @return int|null
     */
    protected function getChunkSize(): ?int
    {
        return $this->chunkSize;
    }

    /**
     * Define chunkSize value and normalize integer value
     * @param int $chunkSize
     * @return static
     */
    public function setChunkSize(int $chunkSize): static
    {
        $this->chunkSize = $chunkSize;
        return $this;
    }

    /**
     * Load data by portions with row count defined by $chunkSize property
     * @return array[]|null
     */
    public function loadNextChunk(): ?array
    {
        $rows = null;
        $query = $this->getQueryBuilder()->buildResultQuery();
        if ($query) {
            $this->query($query);
            $rows = $this->fetchAllAssoc();
            $incrementOffset = count($rows);
            $this->getQueryBuilder()->addOffset($incrementOffset);
        }
        return $rows;
    }

    /**
     * Get QueryBuilder
     * @return \Sam\Report\Auction\AuctionList\Base\QueryBuilder|QueryBuilder
     */
    protected function getQueryBuilder(): \Sam\Report\Auction\AuctionList\Base\QueryBuilder
    {
        if ($this->queryBuilder === null) {
            $queryBuilder = QueryBuilder::new();
            $this->setQueryBuilder($queryBuilder)
                ->initQueryBuilder();
        }
        return $this->queryBuilder;
    }

    /**
     * We build query for all data fetching by portion way
     * @return static
     */
    protected function initQueryBuilder(): static
    {
        parent::initQueryBuilder();
        $this->queryBuilder
            ->setLimit($this->getChunkSize());
        return $this;
    }
}
