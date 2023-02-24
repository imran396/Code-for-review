<?php
/**
 * SAM-4636: Refactor under bidders report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-04-19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\UnderBidder\Html;

use Sam\Core\Filter\Common\LimitInfoAwareTrait;

/**
 * Class DataLoader
 * @extends \Sam\Report\UnderBidder\Base\DataLoader<QueryBuilder>
 */
class DataLoader extends \Sam\Report\UnderBidder\Base\DataLoader
{
    use LimitInfoAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load data by portions with row count defined by $chunkSize property
     * @return string[]|null
     */
    public function loadPage(): ?array
    {
        $rows = null;
        $query = $this->getQueryBuilder()->buildResultQuery();
        if ($query) {
            $this->query($query);
            $rows = $this->fetchAllAssoc();
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
            $queryBuilder = QueryBuilder::new()->setThreshold($this->getThreshold());
            $this->setQueryBuilder($queryBuilder)
                ->initQueryBuilder();
        }
        return $this->queryBuilder;
    }

    /**
     * We build query for single page data fetching way
     * @return static
     */
    protected function initQueryBuilder(): static
    {
        parent::initQueryBuilder();
        $this->queryBuilder
            ->setOffset($this->getOffset())
            ->setLimit($this->getLimit());
        return $this;
    }
}
