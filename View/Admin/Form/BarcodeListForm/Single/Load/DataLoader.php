<?php
/**
 * Data Loader
 *
 * SAM-5876: Refactor data loader for Barcode List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 4, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BarcodeListForm\Single\Load;

use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class DataLoader
 */
class DataLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use DbConnectionTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $lotCustomFieldId
     * @param string $barcodeText
     * @param int $lotStatusId
     * @param int|null $auctionAccountId null means without filtering by account
     * @return array - return values for Single Barcodes
     * @internal
     */
    public function load(
        int $lotCustomFieldId,
        string $barcodeText,
        int $lotStatusId,
        ?int $auctionAccountId = null
    ): array {
        $repo = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->joinLotItemCustDataFilterText($barcodeText)
            ->joinLotItemCustFieldFilterId($lotCustomFieldId)
            ->filterLotStatusId($lotStatusId);
        if ($auctionAccountId) {
            $repo->filterAccountId($auctionAccountId);
        }
        $repo->orderByLotItemId()->orderByAuctionId();
        $repo->select(
            [
                "ali.lot_item_id AS lot_item_id",
                "a.name AS auction_name",
            ]
        );

        $dtos = [];
        foreach ($repo->loadRows() as $row) {
            $dtos[] = SingleBarcodeDto::new()->fromDbRow($row);
        }
        return $dtos;
    }
}
