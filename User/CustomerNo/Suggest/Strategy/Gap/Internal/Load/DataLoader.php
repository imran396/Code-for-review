<?php
/**
 * SAM-4666: User customer no adviser
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\CustomerNo\Suggest\Strategy\Gap\Internal\Load;

use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataLoader
 * @package Sam\User\CustomerNo\Suggest\Strategy\Gap\Internal\Load
 * @internal
 */
class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return array $gaps = [
     *      [
     *          'start' => (int),
     *          'end' => (int)
     *      ]
     * ]
     */
    public function loadGaps(bool $isReadOnlyDb = false): array
    {
        $activeUserStatus = Constants\User::US_ACTIVE;
        $query = <<<SQL
SELECT ul.customer_no+1 AS `start`, MIN(ur.customer_no)-1 AS `end`
FROM `user` AS ul, `user` AS ur
WHERE ul.customer_no < ur.customer_no
  AND ul.user_status_id = {$activeUserStatus}
  AND ur.user_status_id = {$activeUserStatus}
GROUP BY ul.customer_no
HAVING start <= end;
SQL;

        $result = $this->query($query, $isReadOnlyDb);
        $gaps = [];
        while ($row = $result->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $gaps[] = [
                'start' => (int)$row['start'],
                'end' => (int)$row['end']
            ];
        }

        return $gaps;
    }
}
