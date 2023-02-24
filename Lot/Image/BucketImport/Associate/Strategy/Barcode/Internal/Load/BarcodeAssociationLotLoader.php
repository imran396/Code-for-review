<?php
/**
 * SAM-1537: Walmart - Bulk Barcode/Image Import
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode\Internal\Load;

use LotItem;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BarcodeAssociationLotLoader
 * @package Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode\Internal\Load
 */
class BarcodeAssociationLotLoader extends CustomizableClass
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
     * @param string $barcode
     * @param int $customFieldId
     * @param int $auctionId
     * @return array
     */
    public function loadByBarcode(string $barcode, int $customFieldId, int $auctionId): array
    {
        $barcodeEscaped = $this->escape($barcode);
        $availableLotStatusesList = implode(',', Constants\Lot::$availableLotStatuses);
        $query = <<<SQL
SELECT li.*
FROM lot_item li
       INNER JOIN auction_lot_item ali 
         ON ali.lot_item_id = li.id
          AND ali.auction_id = {$auctionId}
          AND ali.lot_status_id IN ({$availableLotStatusesList})
       INNER JOIN lot_item_cust_data licd ON li.id = licd.lot_item_id
       INNER JOIN lot_item_cust_field licf ON licf.id = licd.lot_item_cust_field_id
WHERE licf.id = {$customFieldId}
  AND licd.text = {$barcodeEscaped}
SQL;

        $dbResult = $this->query($query);
        $lotItems = LotItem::InstantiateDbResult($dbResult);
        return $lotItems;
    }
}
