<?php
/**
 * SAM-4635 : Refactor tax report
 * https://bidpath.atlassian.net/browse/SAM-4635
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           4/13/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Tax\Html;

/**
 * Class QueryBuilder
 * @package Sam\Report\Tax\Html
 */
class QueryBuilder extends \Sam\Report\Tax\Base\QueryBuilder
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build Result Query
     * @return string|null
     */
    public function buildSummaryResultQuery(): ?string
    {
        $resultQuery = null;
        $queryParts = $this->buildSummaryQueryParts();
        if ($queryParts) {
            $resultQuery = $queryParts['summary']
                . 'FROM ( '
                . $queryParts['select']
                . $queryParts['from']
                . $queryParts['where']
                . $queryParts['order']
                . $queryParts['limit']
                . ') as t '
                . $queryParts['group'];
        }
        return $resultQuery;
    }

    /**
     * Build query
     * @return string[]
     */
    protected function buildSummaryQueryParts(): array
    {
        $queryParts = [
            'summary' => $this->getSummaryClause(),
            'select' => $this->getSelectClause(),
            'from' => $this->getFromClause(),
            'where' => $this->getWhereClause(),
            'order' => $this->getOrderClause(),
            'group' => $this->getSummaryGroupClause(),
            'limit' => $this->getLimitClause(),
        ];

        return $queryParts;
    }

    /**
     * @return string
     */
    public function getSummaryClause(): string
    {
        $n = "\n";
        $summary = 'SELECT ' . $n .
            'SUM(ROUND(t.tax,2)) AS total_sales_tax, ' . $n .
            't.currency_sign AS currency_sign, ' . $n .
            't.ex_rate AS ex_rate, ' . $n .
            "IF(t.sales_tax > 0, t.sales_tax, 0) AS sales_tax " . $n;

        return $summary;
    }

    /**
     * @return string
     */
    public function getSummaryGroupClause(): string
    {
        $group = 'GROUP BY sales_tax ';
        return $group;
    }
}
