<?php
/**
 * SAM-11091: Stacked Tax. New Invoice Edit page: Invoice Item Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\DeleteLot;

use InvoiceItem;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoiceItem;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\StackedTax\Calculate\Summary\StackedTaxInvoiceSummaryCalculatorAwareTrait;
use Sam\Invoice\StackedTax\ReleaseLot\StackedTaxInvoiceLotReleaserCreateTrait;
use Sam\Settlement\Delete\LotFromSettlementRemoverAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;
use Sam\Invoice\StackedTax\DeleteLot\Internal\Load\DataProviderCreateTrait;
use Sam\Tax\StackedTax\Schema\Snapshot\TaxSchemaSnapshotDeleterCreateTrait;

/**
 * Class StackedTaxInvoiceLotDeleter
 * @package Sam\Tax\StackedTax\Invoice\DeleteItem
 */
class StackedTaxInvoiceLotDeleter extends CustomizableClass
{
    use DataProviderCreateTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceItemWriteRepositoryAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use LotFromSettlementRemoverAwareTrait;
    use StackedTaxInvoiceLotReleaserCreateTrait;
    use StackedTaxInvoiceSummaryCalculatorAwareTrait;
    use TaxSchemaSnapshotDeleterCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function delete(int $invoiceItemId, int $editorUserId): void
    {
        $invoiceItem = $this->getInvoiceItemLoader()->load($invoiceItemId);
        if (!$invoiceItem) {
            throw CouldNotFindInvoiceItem::withId($invoiceItemId);
        }

        $this->deleteInvoiceItem($invoiceItem, $editorUserId);
        $this->deleteInvoiceItemTaxSchemaSnapshots($invoiceItem, $editorUserId);
        $this->deleteEmptyInvoicesAndRecalculateOthers([$invoiceItem->InvoiceId], $editorUserId);
    }

    public function deleteAndWipeOutSoldInfo(int $invoiceItemId, int $editorUserId): void
    {
        $invoiceItem = $this->getInvoiceItemLoader()->load($invoiceItemId);
        if (!$invoiceItem) {
            throw CouldNotFindInvoiceItem::withId($invoiceItemId);
        }
        $this->createStackedTaxInvoiceLotReleaser()->wipeOutLotSoldInfo($invoiceItem->LotItemId, $editorUserId);
        $this->deleteLotItemFromAllInvoices($invoiceItem->LotItemId, $editorUserId);
        $this->getLotFromSettlementRemover()->remove($invoiceItem->LotItemId, $editorUserId);
    }

    public function deleteLotItemFromAllInvoices(int $lotItemId, int $editorUserId): void
    {
        $invoiceItems = $this->createDataProvider()->loadInvoiceItemsByLotItemId($lotItemId);
        $invoiceIds = [];
        foreach ($invoiceItems as $invoiceItem) {
            $invoiceIds[] = $invoiceItem->InvoiceId;
            $this->deleteInvoiceItem($invoiceItem, $editorUserId);
            $this->deleteInvoiceItemTaxSchemaSnapshots($invoiceItem, $editorUserId);
        }
        $this->deleteEmptyInvoicesAndRecalculateOthers($invoiceIds, $editorUserId);
    }

    protected function deleteInvoiceItem(InvoiceItem $invoiceItem, int $editorUserId): void
    {
        $invoiceItem->toDeleted();
        $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $editorUserId);
    }

    protected function deleteInvoiceItemTaxSchemaSnapshots(InvoiceItem $invoiceItem, int $editorUserId): void
    {
        $this->deleteInvoiceItem($invoiceItem, $editorUserId);
        if ($invoiceItem->HpTaxSchemaId) {
            $this->createTaxSchemaSnapshotDeleter()->delete($invoiceItem->HpTaxSchemaId, $editorUserId);
        }
        if ($invoiceItem->BpTaxSchemaId) {
            $this->createTaxSchemaSnapshotDeleter()->delete($invoiceItem->BpTaxSchemaId, $editorUserId);
        }
    }

    protected function deleteEmptyInvoicesAndRecalculateOthers(array $invoiceIds, int $editorUserId): void
    {
        $emptyInvoices = $this->createDataProvider()->findEmptyInvoices($invoiceIds);
        $emptyInvoiceIds = [];
        foreach ($emptyInvoices as $emptyInvoice) {
            $emptyInvoiceIds[] = $emptyInvoice->Id;
            $emptyInvoice->toDeleted();
            $this->getInvoiceWriteRepository()->saveWithModifier($emptyInvoice, $editorUserId);
        }

        $stackedTaxInvoiceSummaryCalculator = $this->getStackedTaxInvoiceSummaryCalculator();
        $notEmptyInvoiceIds = array_diff($invoiceIds, $emptyInvoiceIds);
        foreach ($notEmptyInvoiceIds as $emptyInvoiceId) {
            $stackedTaxInvoiceSummaryCalculator->recalculateAndSave($emptyInvoiceId, $editorUserId);
        }
    }
}
