<?php
/**
 * SAM-4632 : Refactor payment report
 * https://bidpath.atlassian.net/browse/SAM-4632
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/1/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Payment\Base;

use QMySqli5DatabaseResult;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Entity\FilterCurrencyAwareTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Entity\FilterLocationAwareTrait;
use Sam\Core\Filter\Entity\FilterUserAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataLoader
 * @package Sam\Report\Payment\Base
 */
abstract class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterCurrencyAwareTrait;
    use FilterDatePeriodAwareTrait;
    use FilterLocationAwareTrait;
    use FilterUserAwareTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    /**
     * We load data by portions of $chunkSize row count
     */
    protected ?int $chunkSize = null;
    /**
     * QueryBuilder is responsible for mysql query generating with respective LIMIT clause
     * Offset value increased in data loading method
     */
    protected ?QueryBuilder $queryBuilder = null;

    /**
     * @return QueryBuilder
     */
    abstract protected function getQueryBuilder(): QueryBuilder;

    /**
     * Set QueryBuilder
     * @param QueryBuilder $queryBuilder
     * @return static
     */
    public function setQueryBuilder(QueryBuilder $queryBuilder): static
    {
        $this->queryBuilder = $queryBuilder;
        return $this;
    }

    /**
     * Count all rows
     * @return int total number of results
     */
    public function count(): int
    {
        $count = 0;
        $query = $this->getQueryBuilder()->buildCountQuery();
        if ($query) {
            $dbResult = $this->query($query);
            $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
            $count = (int)$row['total'];
        }
        return $count;
    }

    /**
     * @return static
     */
    protected function initQueryBuilder(): static
    {
        $this->queryBuilder
            ->filterAccountId($this->getFilterAccountId())
            ->filterAuctionId($this->getFilterAuctionId())
            ->filterCurrencyId($this->getFilterCurrencyId())
            ->filterLocationId($this->getFilterLocationId())
            ->filterUserId($this->getFilterUserId())
            ->enableFilterDatePeriod($this->isFilterDatePeriod())
            ->filterStartDateSysIso($this->getFilterStartDateSysIso())
            ->filterEndDateSysIso($this->getFilterEndDateSysIso())
            ->setSortColumn($this->getSortColumn())
            ->enableAscendingOrder($this->isAscendingOrder());
        return $this;
    }
}
