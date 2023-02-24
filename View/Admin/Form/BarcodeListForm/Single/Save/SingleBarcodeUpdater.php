<?php
/**
 * Single Barcode Updater
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

namespace Sam\View\Admin\Form\BarcodeListForm\Single\Save;

use AuctionLotItem;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\View\Admin\Form\BarcodeListForm\Single\Load\DataLoaderCreateTrait;
use Sam\View\Admin\Form\BarcodeListForm\Single\Load\SingleBarcodeDto;

/**
 * Class SingleBarcodeUpdater
 */
class SingleBarcodeUpdater extends CustomizableClass
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use DataLoaderCreateTrait;
    use DbConnectionTrait;
    use FilterAccountAwareTrait;

    protected int $lotCustomFieldId;
    protected string $barcodeText;
    protected string $barcodeName;
    protected string $report = '';

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
     * @param string $barcodeName
     * @return $this
     */
    public function construct(int $lotCustomFieldId, string $barcodeText, string $barcodeName): static
    {
        $this->lotCustomFieldId = $lotCustomFieldId;
        $this->barcodeText = $barcodeText;
        $this->barcodeName = $barcodeName;
        return $this;
    }

    /**
     * @param string $report
     * @return $this
     */
    public function setReport(string $report): static
    {
        $this->report = $report;
        return $this;
    }

    /**
     * @return string
     */
    public function getReport(): string
    {
        if ($this->report === '') {
            $dataLoader = $this->createDataLoader();
            $lotItemIdsAndAuctionNames = $this->getLotItemIdsAndAuctionNames(
                $dataLoader->load(
                    $this->lotCustomFieldId,
                    $this->barcodeText,
                    Constants\Lot::LS_RECEIVED
                )
            );
            if (count($lotItemIdsAndAuctionNames['lotItemIds']) > 0) {
                $this->report = sprintf(
                    "item(s) with barcode:%s (%s) hasn't been sold in any auction.<br />" .
                    "item(s) with barcode:%s (%s) has been received in the following auctions: <br /> %s <br /><br />",
                    $this->barcodeText,
                    $this->barcodeName,
                    $this->barcodeText,
                    $this->barcodeName,
                    implode("<br />", $lotItemIdsAndAuctionNames['auctionNames'])
                );
            } else {
                $this->report = sprintf(
                    "item(s) with barcode:%s (%s) hasn't been sold in any auction.<br /><br />",
                    $this->barcodeText,
                    $this->barcodeName
                );
            }
        }
        return $this->report;
    }

    /**
     * Update Auction Lots status id for single barcode
     * @param int $editorUserId
     */
    public function update(int $editorUserId): void
    {
        $dataLoader = $this->createDataLoader();
        $lotItemIdsAndAuctionNames = $this->getLotItemIdsAndAuctionNames(
            $dataLoader->load(
                $this->lotCustomFieldId,
                $this->barcodeText,
                Constants\Lot::LS_SOLD,
                $this->getFilterAccountId()
            )
        );

        if (count($lotItemIdsAndAuctionNames['lotItemIds']) > 0) {
            $availableAuctionStatusList = implode(',', Constants\Auction::$availableAuctionStatuses);
            $availableLotStatusList = implode(',', Constants\Lot::$availableLotStatuses);
            $barcodeId = $this->escape($this->lotCustomFieldId);
            $barcode = $this->escape($this->barcodeText);
            $lsSold = Constants\Lot::LS_SOLD;
            $auctionLotsQuery = <<<SQL
SELECT ali.* FROM auction_lot_item AS ali
INNER JOIN auction AS a ON ali.auction_id = a.id AND a.auction_status_id IN ({$availableAuctionStatusList}) 
INNER JOIN lot_item_cust_data AS licd ON licd.lot_item_id = ali.lot_item_id AND ali.lot_status_id IN ({$availableLotStatusList}) 
INNER JOIN lot_item_cust_field AS licf ON licd.lot_item_cust_field_id = licf.id 
WHERE 
licf.id = {$barcodeId} 
AND licd.text = {$barcode} 
AND ali.lot_status_id = '{$lsSold}'
SQL;

            $auctionLots = AuctionLotItem::InstantiateDbResult($this->query($auctionLotsQuery));
            foreach ($auctionLots as $auctionLot) {
                $auctionLot->toReceived();
                $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
            }
            $this->setReport(
                sprintf(
                    "%d item(s) with barcode:%s (%s) has been set to received status in the following auctions: <br /> %s <br /><br />",
                    count($lotItemIdsAndAuctionNames['lotItemIds']),
                    $this->barcodeText,
                    $this->barcodeName,
                    implode("<br />", $lotItemIdsAndAuctionNames['auctionNames'])
                )
            );
        }
    }

    /**
     * @param SingleBarcodeDto[] $dtos
     * @return array
     */
    protected function getLotItemIdsAndAuctionNames(array $dtos): array
    {
        $lotItemIds = [];
        $auctionNames = [];
        foreach ($dtos as $dto) {
            $lotItemIds[] = $dto->lotItemId;
            $auctionNames[] = $dto->auctionName;
        }
        $lotItemIds = array_unique($lotItemIds);
        $auctionNames = array_unique($auctionNames);

        return ['lotItemIds' => $lotItemIds, 'auctionNames' => $auctionNames];
    }
}
