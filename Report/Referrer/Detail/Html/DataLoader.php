<?php
/**
 * SAM-4856: Refactor Referrer report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-07-31
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Referrer\Detail\Html;

use QMySqli5DatabaseResult;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterCurrencyAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataLoader
 */
class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterCurrencyAwareTrait;
    use FilterDatePeriodAwareTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    public bool $isPurchasedChecked = false;
    public bool $isCollectedChecked = false;
    public string $referrerHost = '';
    protected ?QueryBuilder $queryBuilder = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
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
            $count = (int)$row['referrers_total'];
        }
        return $count;
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
     * @param string $referrerHost
     * @return static
     */
    public function setReferrerHost(string $referrerHost): static
    {
        $this->referrerHost = $referrerHost;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReferrerHost(): ?string
    {
        return $this->referrerHost;
    }

    /**
     * @param bool $isPurchasedChecked
     * @return static
     */
    public function enablePurchasedChecked(bool $isPurchasedChecked): static
    {
        $this->isPurchasedChecked = $isPurchasedChecked;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPurchasedChecked(): bool
    {
        return $this->isPurchasedChecked;
    }

    /**
     * @param bool $isCollectedChecked
     * @return static
     */
    public function enableCollectedChecked(bool $isCollectedChecked): static
    {
        $this->isCollectedChecked = $isCollectedChecked;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCollectedChecked(): bool
    {
        return $this->isCollectedChecked;
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
     * @param QueryBuilder $queryBuilder
     * @return static
     */
    protected function setQueryBuilder(QueryBuilder $queryBuilder): static
    {
        $this->queryBuilder = $queryBuilder;
        return $this;
    }

    /**
     * We build query for single page data fetching way
     * @return static
     */
    protected function initQueryBuilder(): static
    {
        $this->queryBuilder
            ->setOffset($this->getOffset())
            ->setLimit($this->getLimit())
            ->setReferrerHost($this->getReferrerHost())
            ->filterCurrencyId($this->getFilterCurrencyId())
            ->filterAccountId($this->getFilterAccountId())
            ->filterStartDateUtcIso($this->getFilterStartDateUtcIso())
            ->filterEndDateUtcIso($this->getFilterEndDateUtcIso())
            ->enablePurchasedChecked($this->isPurchasedChecked())
            ->enableCollectedChecked($this->isCollectedChecked());
        return $this;
    }
}
