<?php
/**
 * DataProvider for ItemNoLotNoUniquenessValidator
 *
 * SAM-9462: Lot CSV import - fix item# and lot# uniqueness check
 * SAM-6148: Allow editing lot numbers in CSV upload, but prevent duplication
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 4, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\AuctionLot\Validate\Internal\Unique\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;

/**
 * Class UploadDataLoaderAwareTrait
 * @package Sam\Upload\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Load not concatenated item and lot numbers
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return ItemNoLotNoDto[]
     */
    public function loadItemNoLotNoDtos(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $rows = AuctionLotItemReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->select(
                [
                    'ali.lot_num',
                    'ali.lot_num_prefix',
                    'ali.lot_num_ext',
                    'li.item_num',
                    'li.item_num_ext',
                ]
            )
            ->loadRows();
        $dtos = ArrayHelper::toArrayByNamedConstructor($rows, ItemNoLotNoDto::new(), 'fromDbRow');
        return $dtos;
    }
}
