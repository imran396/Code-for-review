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

/**
 * Class QueryBuilder
 * @package Sam\Report\SaleStaff\Html
 */
class QueryBuilder extends \Sam\Report\SaleStaff\Base\QueryBuilder
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function subTotalCountClause(): string
    {
        return 'SELECT SUM(ii.subtotal) AS sum_total ';
    }

    /**
     * Build Result Query
     * @return string|null
     */
    public function buildSubTotalCountQuery(): ?string
    {
        $resultQuery = null;
        $queryParts = $this->getQueryParts();
        if ($queryParts) {
            $resultQuery = $this->subTotalCountClause()
                . $queryParts['from']
                . $queryParts['where'];
        }
        return $resultQuery;
    }

}
