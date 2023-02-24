<?php
/**
 * SAM-4633:Refactor sales staff report
 * https://bidpath.atlassian.net/browse/SAM-4633
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/13/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SaleStaff\Html;

use QMySqli5DatabaseResult;

/**
 * Class DataLoader
 * @package Sam\Report\SaleStaff\Html
 * @property QueryBuilder|null $queryBuilder
 */
class DataLoader extends \Sam\Report\SaleStaff\Base\DataLoader
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load data by portions with row count defined by $chunkSize property
     * @return string[][]
     */
    public function loadPage(): array
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
     * @return float
     */
    public function subTotalCount(): float
    {
        $sumTotal = 0.;
        $query = $this->getQueryBuilder()->buildSubTotalCountQuery();
        if ($query) {
            $dbResult = $this->query($query);
            $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
            $sumTotal = (float)$row['sum_total'];
        }
        return $sumTotal;
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
