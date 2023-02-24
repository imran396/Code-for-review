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

namespace Sam\Report\MailingList\Report\Base;

use QMySqli5DatabaseResult;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\MailingListTemplateAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class DataLoader
 * @package Sam\Report\MailingList\Report\Base
 * @template QueryBuilderTemplate of QueryBuilder
 */
abstract class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use MailingListTemplateAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * We load data by portions of $chunkSize row count
     */
    protected ?int $chunkSize = null;
    /**
     * QueryBuilder is responsible for mysql query generating with respective LIMIT clause
     * Offset value increased in data loading method
     * @var QueryBuilderTemplate
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
            ->setMailingListTemplateId($this->getMailingListTemplateId())
            ->setSortColumn($this->getSortColumn())
            ->enableAscendingOrder($this->isAscendingOrder());
        return $this;
    }
}
