<?php
/**
 * SAM-4631 : Refactor internal notes report
 * https://bidpath.atlassian.net/browse/SAM-4631
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           4/25/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\InternalNote\Html;

/**
 * Class QueryBuilder
 * @package Sam\Report\InternalNote\Html
 */
class QueryBuilder extends \Sam\Report\InternalNote\Base\QueryBuilder
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
