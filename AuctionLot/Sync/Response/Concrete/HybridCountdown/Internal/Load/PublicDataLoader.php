<?php
/**
 * Data loader for hybrid countdown(public side)
 *
 * SAM-6388: Active countdown on admin - auction - lots
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/21/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown\Internal\Load;

use mysqli;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;


/**
 * Class PublicDataLoader
 * @package Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown\Load
 */
class PublicDataLoader extends CustomizableClass implements DataLoaderInterface
{
    use ConfigRepositoryAwareTrait;

    /**
     * @var mysqli|null
     */
    protected ?mysqli $db = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance()
    {
        $server = $this->cfg()->get('core->db->server');
        $username = $this->cfg()->get('core->db->username');
        $password = $this->cfg()->get('core->db->password');
        $database = $this->cfg()->get('core->db->database');
        $this->db = @new mysqli($server, $username, $password, $database);
        return $this;
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function load(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        $orderNum = null;
        $query =
            'SELECT ali.`order` AS order_num'
            . ' FROM auction_lot_item ali'
            . ' INNER JOIN lot_item li ON li.id = ali.lot_item_id AND li.active'
            . ' WHERE ali.auction_id = ' . $auctionId
            . ' AND ali.lot_item_id = ' . $lotItemId
            . ' AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ')';
        $dbResult = $this->db->query($query);
        $row = $dbResult->fetch_assoc();
        if ($row) {
            $orderNum = (int)$row['order_num'];
        }
        return $orderNum;
    }
}
