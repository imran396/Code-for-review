<?php
/**
 *
 * SAM-4751: Refactor mailing list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-15
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\MailingList\Report\Csv;

/**
 * Class DataLoader
 * @package Sam\Report\MailingList\Report\Csv
 * @extends \Sam\Report\MailingList\Report\Base\DataLoader<QueryBuilder>
 */
class DataLoader extends \Sam\Report\MailingList\Report\Base\DataLoader
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
     * @return QueryBuilder
     */
    protected function getQueryBuilder(): QueryBuilder
    {
        if ($this->queryBuilder === null) {
            $this->setQueryBuilder(QueryBuilder::new())
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
