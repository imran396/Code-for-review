<?php
/**
 * SAM-6063: Race condition on Buy Now action
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           5/8/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Lock;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\DataSource\DbConnectionTrait;

/**
 * Class LotItemLocker
 * @package
 */
class LotItemLocker extends CustomizableClass
{
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Lock timed online item entry inside transaction
     * @param int $lotItemId
     */
    public function lockInTransaction(int $lotItemId): void
    {
        $db = $this->getDb();
        $query = <<<SQL
SELECT modified_on FROM lot_item
WHERE id = {$this->escape($lotItemId)}
FOR UPDATE
SQL;
        $db->NonQuery($query);
    }
}
