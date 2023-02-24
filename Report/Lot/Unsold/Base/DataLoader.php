<?php
/**
 * SAM-4687: Refactor "Unsold Lots" report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/28/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
 * This might include adding, changing, or moving columns, modifying header names, modifying data or data format
 */

namespace Sam\Report\Lot\Unsold\Base;

use Sam\Core\Constants;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Report\Lot\Unsold\Base
 */
class DataLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use AuctionReadRepositoryCreateTrait;
    use FilterAuctionAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load data for rendering lot table
     * @return array
     */
    public function load(): array
    {
        $rows = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb(true)
            ->joinConsignorUser()
            ->filterAuctionId($this->getFilterAuctionId())
            ->filterLotStatusId(Constants\Lot::LS_UNSOLD)
            ->select(
                [
                    'ali.general_note',
                    'ali.lot_item_id',
                    'ali.lot_num',
                    'ali.lot_num_prefix',
                    'ali.lot_num_ext',
                    'ali.note_to_clerk',
                    'ali.quantity',
                    'li.account_id',
                    'li.name',
                    'li.reserve_price',
                    'li.low_estimate',
                    'li.high_estimate',
                    'li.item_num',
                    'li.item_num_ext',
                    'li.description',
                    'li.starting_bid',
                    'li.cost',
                    'li.replacement_price',
                    'li.sales_tax',
                    'uc.username',
                    'COALESCE(
                        ali.quantity_digits, 
                        li.quantity_digits, 
                        (SELECT lc.quantity_digits
                         FROM lot_category lc
                           INNER JOIN lot_item_category lic ON lc.id = lic.lot_category_id
                         WHERE lic.lot_item_id = li.id
                           AND lc.active = 1
                         ORDER BY lic.id
                         LIMIT 1), 
                        (SELECT seta.quantity_digits FROM setting_auction seta WHERE seta.account_id = li.account_id)
                    ) as quantity_scale'
                ]
            )
            ->orderByLotNumPrefix()
            ->orderByLotNumExt()
            ->orderByLotNum()
            ->loadRows();
        return $rows;
    }

    /**
     * Load data required for auction info rendering
     * @return array
     */
    public function loadAuctionData(): array
    {
        $row = $this->createAuctionReadRepository()
            ->enableReadOnlyDb(true)
            ->joinAccount()
            ->filterId($this->getFilterAuctionId())
            ->select(
                [
                    'a.id AS auction_id',
                    'a.auction_type',
                    'a.end_date',
                    'a.event_type',
                    'a.name AS auction_name',
                    'a.sale_num',
                    'a.sale_num_ext',
                    'a.start_date',
                    'a.start_closing_date',
                    'a.test_auction',
                    'a.timezone_id',
                    'acc.name AS account_name',
                ]
            )
            ->loadRow();
        return $row;
    }
}
