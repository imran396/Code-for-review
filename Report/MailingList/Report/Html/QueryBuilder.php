<?php
/**
 *
 * SAM-4751: Refactor mailing list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-16
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\MailingList\Report\Html;

use RangeException;

/**
 * Class QueryBuilder
 * @package Sam\Report\MailingList\Report\Html
 */
class QueryBuilder extends \Sam\Report\MailingList\Report\Base\QueryBuilder
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get SQL Select Clause
     * @return string
     */
    protected function getSelectClause(): string
    {
        $returnFields = $this->getReturnFields();
        $query = '';
        foreach ($returnFields as $returnFieldIndex) {
            $query .= ($query ? ', ' : '');
            $query .= $this->availableReturnFields[$returnFieldIndex];
        }

        if ($query === '') {
            throw new RangeException('No ReturnFields defined');
        }
        return sprintf('SELECT %s ', $query);
    }
}
