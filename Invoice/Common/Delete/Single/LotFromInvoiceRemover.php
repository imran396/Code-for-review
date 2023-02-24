<?php
/**
 * SAM-5834 : Disable MarkUnsoldOnDelete dialog box on Invoice List and Invoice Details
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Feb 21, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Delete\Single;

use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Settlement\Delete\LotFromSettlementRemoverAwareTrait;
use Sam\Storage\ReadRepository\Entity\Invoice\InvoiceReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;

/**
 * Class LotFromInvoiceRemover
 * @package Sam\Invoice\Common\Delete
 */
class LotFromInvoiceRemover extends CustomizableClass
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceItemReadRepositoryCreateTrait;
    use InvoiceItemWriteRepositoryAwareTrait;
    use InvoiceReadRepositoryCreateTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use LotFromSettlementRemoverAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotItemWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * It is a main method for processing invoice lot items
     * @param int $invoiceId
     * @param int $editorUserId
     */
    public function removeByInvoiceId(int $invoiceId, int $editorUserId): void
    {
        $invoiceItemRows = $this->getInvoiceItemLoader()->loadSelectedByInvoiceId(
            ['ii.auction_id', 'ii.lot_item_id'],
            $invoiceId,
            true
        );
        $invoiceItemRows = ArrayCast::castInt($invoiceItemRows);
        $this->removeInvoiceItems($invoiceItemRows, $editorUserId);
    }

    /**
     * @param int $lotItemId
     * @param int|null $auctionId
     * @param int $skipInvoiceId
     * @param int $editorUserId
     */
    public function removeAndWipeOutSoldInfoByLotItemId(int $lotItemId, ?int $auctionId, int $skipInvoiceId, int $editorUserId): void
    {
        $this->markAuctionLotUnsold($lotItemId, $auctionId, $editorUserId);
        $this->wipeOutLotSoldInfo($lotItemId, $editorUserId);
        $this->remove($lotItemId, $editorUserId, $skipInvoiceId);
        $this->getLotFromSettlementRemover()->remove($lotItemId, $editorUserId);
    }

    /**
     * Remove lot on all invoice it is added to
     *
     * @param int $lotItemId lot_item.id
     * @param int $editorUserId
     * @param int|null $skipInvoiceId invoice.id
     */
    public function remove(int $lotItemId, int $editorUserId, ?int $skipInvoiceId = null): void
    {
        $invoiceItemRepository = $this->createInvoiceItemReadRepository()
            ->filterActive(true)
            ->filterLotItemId($lotItemId);
        if ($skipInvoiceId !== null) {
            $invoiceItemRepository->skipInvoiceId($skipInvoiceId);
        }
        $invoiceItems = $invoiceItemRepository->loadEntities();
        foreach ($invoiceItems as $invoiceItem) {
            $invoiceItem->toDeleted();
            $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $editorUserId);
        }
        $invoiceRepository = $this->createInvoiceReadRepository()
            ->filterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses)
            ->joinAccountFilterActive(true)
            ->joinInvoiceItemFilterLotItemId($lotItemId)
            ->inlineCondition('(SELECT COUNT(1) FROM invoice_item WHERE invoice_id = ii.invoice_id AND active = true) = 0');
        if ($skipInvoiceId !== null) {
            $invoiceRepository->skipId($skipInvoiceId);
        }
        $invoices = $invoiceRepository->loadEntities();
        foreach ($invoices as $invoice) {
            $invoice->toDeleted();
            $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
        }
    }

    /**
     * Change lot status to unsold
     * @param int $lotItemId
     * @param int|null $auctionId
     * @param int $editorUserId
     * @return void
     */
    protected function markAuctionLotUnsold(int $lotItemId, ?int $auctionId, int $editorUserId): void
    {
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId, true);
        if ($auctionLot) {
            $auctionLot->toUnsold();
            $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
        }
    }

    /**
     * It is a wrapper method for marking lot item unsold, wipe out sold info and remove lot from settlement
     * @param array $invoiceItemRows [['auction_id' => int, 'lot_item_id' => int], ...]
     * @param int $editorUserId
     */
    protected function removeInvoiceItems(array $invoiceItemRows, int $editorUserId): void
    {
        foreach ($invoiceItemRows as $row) {
            $lotItemId = $row['lot_item_id'];
            $auctionId = $row['auction_id'];
            $this->markAuctionLotUnsold($lotItemId, $auctionId, $editorUserId);
            $this->wipeOutLotSoldInfo($lotItemId, $editorUserId);
            $this->getLotFromSettlementRemover()->remove($lotItemId, $editorUserId);
        }
    }

    /**
     * Removes all lot sold info like hammer price, winning bidder, auction id etc
     * @param int $lotItemId
     * @param int $editorUserId
     */
    protected function wipeOutLotSoldInfo(int $lotItemId, int $editorUserId): void
    {
        $lotItem = $this->getLotItemLoader()->load($lotItemId, true);
        if ($lotItem) {
            $lotItem->wipeOutSoldInfo();
            $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);
        }
    }
}
