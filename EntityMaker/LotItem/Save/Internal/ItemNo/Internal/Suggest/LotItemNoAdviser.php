<?php
/**
 * SAM-10599: Supply uniqueness of lot item fields: item# - Adjust item# auto-assignment with internal locking
 * SAM-4975: Lot Item No adviser
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           4/5/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Save\Internal\ItemNo\Internal\Suggest;

use QMySqli5DatabaseResult;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Save\Internal\ItemNo\Internal\Suggest\Extension\CouldNotFindAvailableItemNo;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;

/**
 * Class LotItemNoAdviser
 * @package Sam\Lot\ItemNo
 */
class LotItemNoAdviser extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DbConnectionTrait;
    use LotItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return next item number or first available
     * @param int $lotAccountId
     * @param array $skipItemNums
     * @param bool $isAvoidDeleted
     * @param bool $isReadOnlyDb
     * @return int
     * @throws CouldNotFindAvailableItemNo
     */
    public function suggest(
        int $lotAccountId,
        array $skipItemNums = [],
        bool $isAvoidDeleted = false,
        bool $isReadOnlyDb = false
    ): int {
        $repo = $this->createLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($lotAccountId)
            ->skipItemNum($skipItemNums)
            ->select(['MAX(item_num) AS `max_item_num`']);
        if ($isAvoidDeleted) {
            $repo->filterActive(true);
        }
        $row = $repo->loadRow();
        $itemNumMax = (int)$row['max_item_num'];
        do {
            $itemNumMax++;
        } while (in_array($itemNumMax, $skipItemNums, true));

        $mysqlMaxInt = $this->cfg()->get('core->db->mysqlMaxInt');
        if ($itemNumMax > $mysqlMaxInt) {
            $n = "\n";
            $condSkipItemNums = '';
            if ($skipItemNums) {
                $condSkipItemNums = sprintf(
                    'AND IFNULL(li.item_num, 0)+1 NOT IN (%s) ' . $n,
                    implode(', ', $this->escapeArray($skipItemNums))
                );
            }

            $query =
                // @formatter:off
                'SELECT IFNULL(li.item_num, 0)+1 AS next_item_num ' . $n .
                'FROM lot_item li ' . $n .
                'LEFT JOIN lot_item li2 ON li2.account_id=li.account_id ' . $n .
                    'AND li2.item_num=IFNULL(li.item_num, 0)+1 ' . $n .
                    ($isAvoidDeleted ? '' : 'AND li2.active ') . $n .
                'WHERE ' . $n .
                    'li.account_id=' . $this->escape($lotAccountId) . ' ' . $n .
                    ($isAvoidDeleted ? '' : 'AND li.active ') . $n .
                    'AND li2.id IS NULL ' . $n .
                    $condSkipItemNums .
                'GROUP BY li.item_num ' . $n .
                'LIMIT 1';
                // @formatter:on
            $this->enableReadOnlyDb($isReadOnlyDb);
            $dbResult = $this->query($query);
            $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
            $itemNumMax = (int)$row['next_item_num'];
            if ($itemNumMax > $mysqlMaxInt) {
                $message = 'There is no item numbers available. Max number exceeded'
                    . composeSuffix(['max_num' => $this->cfg()->get('core->db->mysqlMaxInt')]);
                log_error($message);
                throw CouldNotFindAvailableItemNo::becauseMysqlMaxIntExceeded();
            }
        }
        return $itemNumMax;
    }
}
