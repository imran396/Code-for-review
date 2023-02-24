<?php
/**
 * SAM-10177: Decouple the "Lot status change" function at the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class LotDtoLoader
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Load
 */
class LotDtoLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $lotItemIds
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return LotDto[]
     */
    public function loadDtos(array $lotItemIds, int $auctionId, bool $isReadOnlyDb = false): array
    {
        $rows = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemIds)
            ->joinLotItem()
            ->select(
                [
                    'ali.lot_item_id',
                    'ali.lot_num',
                    'ali.lot_num_ext',
                    'ali.lot_num_prefix',
                    'li.hammer_price',
                    'li.item_num',
                    'li.item_num_ext'
                ]
            )
            ->loadRows();
        $result = array_map(
            static function (array $row): LotDto {
                return LotDto::new()->construct(
                    (int)$row['lot_item_id'],
                    Cast::toFloat($row['hammer_price']),
                    (int)$row['item_num'],
                    $row['item_num_ext'],
                    $row['lot_num'],
                    $row['lot_num_ext'],
                    $row['lot_num_prefix']
                );
            },
            $rows
        );
        return $result;
    }
}
