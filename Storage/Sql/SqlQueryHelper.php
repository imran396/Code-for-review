<?php
/**
 * SAM-9626: Fix integer overflow because of request parameters
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Sql;

use Sam\Application\Index\Base\Exception\BadRequest;
use Sam\Core\Service\CustomizableClass;

/**
 * Class QueryHelper
 * @package Sam\Storage\Sql
 */
class SqlQueryHelper extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detect offset value of LIMIT clause by page# and items per page.
     * @param int|null $page starts with 1
     * @param int|null $itemsPerPage
     * @return int Result with 0 when it is the 1st page or when unexpected/incorrect/undefined inputs
     * #[Pure]
     */
    public function calcOffset(?int $page, ?int $itemsPerPage): int
    {
        $page = (int)$page;
        $itemsPerPage = (int)$itemsPerPage;
        if (
            $page < 1
            || $itemsPerPage < 1
        ) {
            return 0;
        }
        $offset = (($page - 1) * $itemsPerPage);
        /**
         * Offset should not be float but some cases(big integer multiplication) it can be float which is not acceptable.
         * ex: 10 * 9223372036854775807 -> results with float
         */
        if (is_float($offset)) { //  @phpstan-ignore-line
            $message = "Unexpected offset value " . composeSuffix(['page number' => $page, 'itemsPerPage' => $itemsPerPage]);
            log_errorBackTrace($message);
            throw new BadRequest();
        }
        return $offset;
    }
}
