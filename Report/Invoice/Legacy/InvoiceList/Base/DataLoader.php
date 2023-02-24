<?php
/**
 * SAM-4623 : Refactor invoice list report
 * https://bidpath.atlassian.net/browse/SAM-4623
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/17/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Invoice\Legacy\InvoiceList\Base;

use QMySqli5DatabaseResult;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Entity\FilterCurrencyAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Report\Invoice\Legacy\InvoiceList\Csv\FilterAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotCustomFieldsAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserCustomFieldsAwareTrait;

/**
 * Class DataLoader
 * @package Sam\Report\Invoice\Legacy\InvoiceList\Base
 */
abstract class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterAwareTrait;
    use FilterCurrencyAwareTrait;
    use LotCustomFieldsAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;
    use UserCustomFieldsAwareTrait;

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
            ->enableAccountFiltering($this->isAccountFiltering())
            ->enableCustomFieldRender($this->isCustomFieldRender())
            ->enableMultipleSaleInvoice($this->isMultipleSaleInvoice())
            ->filterAccountId($this->getFilterAccountId())
            ->filterAuctionId($this->getFilterAuctionId())
            ->filterCurrencySign($this->getFilterCurrencySign())
            ->filterInvoiceNo($this->getInvoiceNo())
            ->filterInvoiceStatus($this->getInvoiceStatus())
            ->filterSearchKey($this->getSearchKey())
            ->filterWinningUserId($this->getWinningUserId())
            ->filterWinningUserSearchKey($this->getWinningUserSearchKey())
            ->setLotCustomFields($this->getLotCustomFields())
            ->setPrimarySort($this->getPrimarySort())
            ->setSecondarySort($this->getSecondarySort())
            ->setSortColumnIndex($this->getSortColumnIndex())
            ->setSortDirection($this->getSortDirection())
            ->setUserCustomFields($this->getUserCustomFields());
        return $this;
    }
}
