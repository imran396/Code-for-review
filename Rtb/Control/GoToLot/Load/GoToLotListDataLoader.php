<?php
/**
 * SAM-6433: Refactor logic for Go to lot list of rtb clerk console
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Control\GoToLot\Load;

use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class GoToLotListDataLoader
 * @package Sam\Rtb
 */
class GoToLotListDataLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return an ordered array with Lot numbers and lot names
     * For the skip to lot drop down in the admin
     * returned array is nested array('lot_num_prefix'=>'', 'lot_num'=>'', 'lot_num_ext'=>'', 'lot_name'=>'')
     *
     * @param int $auctionId auction id
     * @param bool $isConsignorClerk
     * @return array
     */
    public function load(int $auctionId, ?bool $isConsignorClerk = false): array
    {
        $repo = $this->createAuctionLotItemReadRepository();
        $select = [
            'li.id AS lot_item_id',
            'ali.lot_num_prefix AS lot_num_prefix',
            'ali.lot_num AS lot_num',
            'ali.lot_num_ext AS lot_num_ext',
            'li.name AS lot_name',
        ];
        if ($isConsignorClerk) {
            $select[] = 'uc.username AS consignor_username';
            $select[] = 'uci.company_name AS consignor_company_name';
            $repo
                ->joinConsignorUser()
                ->joinConsignorUserInfo();
        }
        $rows = $repo
            ->select($select)
            ->filterAuctionId($auctionId)
            ->joinLotItemFilterActive(true)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->orderByOrder()
            ->orderByLotNum()
            ->orderByLotNumExt()
            ->orderByLotNumPrefix()
            ->loadRows();
        $results = ArrayHelper::produceIndexedArray($rows, 'lot_item_id');
        return $results;
    }
}
