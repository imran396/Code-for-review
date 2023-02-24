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

namespace Sam\Report\Payment\Csv;

/**
 * Class QueryBuilder
 * @package Sam\Report\Payment\Csv
 */
class QueryBuilder extends \Sam\Report\Payment\Base\QueryBuilder
{
    /**
     * Class instantiation
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

}
