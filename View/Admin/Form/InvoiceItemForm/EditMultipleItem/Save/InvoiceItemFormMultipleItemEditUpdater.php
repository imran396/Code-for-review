<?php
/**
 * SAM-10934: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Multiple Invoice Items validation and save (#invoice-save-2)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemForm\EditMultipleItem\Save;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Invoice\Subtotal\InvoiceItemSubtotalPureCalculator;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Invoice\Legacy\Calculate\Summary\LegacyInvoiceSummaryCalculatorAwareTrait;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoiceItem;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\InvoiceItemForm\EditMultipleItem\Common\InvoiceItemFormMultipleItemEditInput as Input;

/**
 * Class InvoiceItemFormMultipleItemEditUpdater
 * @package Sam\View\Admin\Form\InvoiceItemForm\EditMultipleItem\Save
 */
class InvoiceItemFormMultipleItemEditUpdater extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use DateHelperAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceItemWriteRepositoryAwareTrait;
    use LegacyInvoiceSummaryCalculatorAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function update(Input $input): void
    {
        $this->saveInvoice($input);
        $this->saveInvoiceItems($input);
        $this->getLegacyInvoiceSummaryCalculator()->recalculate($input->invoice->Id, $input->editorUserId);
    }

    protected function saveInvoice(Input $input): void
    {
        $invoice = $input->invoice;
        $editorUserId = $input->editorUserId;
        $nf = $this->getNumberFormatter()->constructForInvoice($invoice->AccountId);
        $invoiceDate = null;
        if ($input->invoiceDate) {
            $invoiceDate = clone $input->invoiceDate;
            $invoiceDate = $invoiceDate->setTime(12, 0);
            $invoiceDate = $this->getDateHelper()->convertSysToUtc($invoiceDate);
        }
        $invoice->TaxChargesRate = $nf->parsePercent($input->taxChargesRateFormatted);
        $invoice->TaxFeesRate = $nf->parsePercent($input->taxFeesRateFormatted);
        $invoice->InvoiceNo = Cast::toInt($input->invoiceNo);
        $invoice->InvoiceDate = $invoiceDate;
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
    }

    protected function saveInvoiceItems(Input $input): void
    {
        $invoiceAccountId = $input->invoice->AccountId;
        $editorUserId = $input->editorUserId;
        $invoiceItemInputs = $input->invoiceItems;
        $nf = $this->getNumberFormatter()->constructForInvoice($invoiceAccountId);
        foreach ($invoiceItemInputs as $invoiceItemInput) {
            $invoiceItem = $this->getInvoiceItemLoader()->load($invoiceItemInput->invoiceItemId);
            if (!$invoiceItem) {
                throw CouldNotFindInvoiceItem::withId($invoiceItemInput->invoiceItemId);
            }

            $lotItem = $this->getLotItemLoader()->load($invoiceItem->LotItemId, true);
            if (!$lotItem) {
                log_error(
                    "Available lot item not found when saving invoice item"
                    . composeSuffix(['li' => $invoiceItem->LotItemId, 'i' => $invoiceItem->InvoiceId])
                );
                continue;
            }

            $auction = $this->getAuctionLoader()->load($invoiceItem->AuctionId, true);
            $isTestAuction = $auction->TestAuction ?? false;
            $lotName = $this->getLotRenderer()->makeName($lotItem->Name, $isTestAuction);
            $invoiceItem->LotName = trim($invoiceItemInput->lotName) === ''
                ? $lotName
                : $invoiceItemInput->lotName;
            $invoiceItem->HammerPrice = $nf->parseMoney($invoiceItemInput->hammerPriceFormatted);
            $invoiceItem->BuyersPremium = $nf->parseMoney($invoiceItemInput->buyersPremiumFormatted);
            $invoiceItem->SalesTax = $nf->parsePercent($invoiceItemInput->salesTaxFormatted);
            $invoiceItem->TaxApplication = $invoiceItemInput->taxApplication;
            $invoiceItem->Subtotal = InvoiceItemSubtotalPureCalculator::new()->calcByInvoiceItem($invoiceItem);
            $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $editorUserId);
        }
    }

}
