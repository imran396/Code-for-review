<?php
/**
 * SAM-4632 : Refactor payment report
 * https://bidpath.atlassian.net/browse/SAM-4632
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/2/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Payment\Html;

/**
 * Class QueryBuilder
 * @package Sam\Report\Payment\Html
 */
class QueryBuilder extends \Sam\Report\Payment\Base\QueryBuilder
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
                . ') as p '
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
        // @formatter:off
        $summary =
            'SELECT ' . $n .
                'p.currency_sign AS currency_sign, ' . $n .
                'sum(p.amount) AS total, ' . $n .
                'payment_method_id ' . $n;
        // @formatter:on
        return $summary;
    }

    /**
     * @return string
     */
    public function getSummaryGroupClause(): string
    {
        return 'GROUP BY payment_method_id';
    }
}
