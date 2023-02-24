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

namespace Sam\Report\Auction\AuctionList\Base;

use QMySqli5DatabaseResult;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class DataLoader
 */
abstract class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterDatePeriodAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

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
            ->filterStartDateSysIso($this->getFilterStartDateSysIso())
            ->filterEndDateSysIso($this->getFilterEndDateSysIso())
            ->setSortColumn($this->getSortColumn())
            ->enableAscendingOrder($this->isAscendingOrder());
        return $this;
    }
}
