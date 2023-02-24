<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Save\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * This class is responsible for finding the unsold item auction
 *
 * Class UnsoldItemAuctionIdDetector
 * @package Sam\Settlement\Save
 * @internal
 */
class UnsoldItemAuctionIdDetector extends CustomizableClass
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
     * Finding the unsold item auction id
     *
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function detect(int $lotItemId, bool $isReadOnlyDb = false): ?int
    {
        $this->enableReadOnlyDb($isReadOnlyDb);
        $query = $this->makeQuery($lotItemId);
        $this->query($query);
        $row = $this->fetchAssoc();
        if ($row) {
            return (int)$row['auction_id'];
        }
        return null;
    }

    /**
     * @param int $lotItemId
     * @return string
     */
    protected function makeQuery(int $lotItemId): string
    {
        $availableLotStatusList = implode(',', Constants\Lot::$availableLotStatuses);
        $asDeleted = Constants\Auction::AS_DELETED;
        $sql = <<<SQL
SELECT ali.auction_id
FROM auction_lot_item AS ali
  INNER JOIN auction AS a on a.id = ali.auction_id
  LEFT JOIN consignor_commission_fee AS unsoldccf_by_ali ON ali.consignor_unsold_fee_id = unsoldccf_by_ali.id AND unsoldccf_by_ali.active
  LEFT JOIN consignor_commission_fee AS unsoldccf_by_a ON a.consignor_unsold_fee_id = unsoldccf_by_a.id AND unsoldccf_by_a.active
WHERE ali.lot_status_id IN ({$availableLotStatusList})
  AND a.auction_status_id != {$asDeleted}
  AND ali.lot_item_id = {$lotItemId}
ORDER BY unsoldccf_by_ali.id DESC, unsoldccf_by_a.id DESC
LIMIT 1
SQL;
        return $sql;
    }
}
