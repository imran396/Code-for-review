<?php
/**
 * This service is called, when we are editing individual invoiced item at the Invoice Edit page.
 *
 * SAM-10900: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Individual Invoiced Item Sales Tax validation and updating
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemForm\EditItemTax\Save;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Invoice\Subtotal\InvoiceItemSubtotalPureCalculator;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Calculate\Summary\LegacyInvoiceSummaryCalculatorAwareTrait;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoiceItem;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\InvoiceItemForm\EditItemTax\Common\InvoiceItemFormItemTaxEditInput as Input;

class InvoiceItemFormItemTaxEditUpdater extends CustomizableClass
{
    use InvoiceItemLoaderAwareTrait;
    use InvoiceItemWriteRepositoryAwareTrait;
    use LegacyInvoiceSummaryCalculatorAwareTrait;
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function update(Input $input, bool $isReadOnlyDb = false): static
    {
        $nf = $this->getNumberFormatter()->constructForInvoice($input->invoiceAccountId);
        $invoiceItem = $this->getInvoiceItemLoader()->load($input->invoiceItemId, $isReadOnlyDb);
        if (!$invoiceItem) {
            log_error("Available invoice item not found on updating individual line" . composeSuffix(['ii' => $input->invoiceItemId]));
            throw CouldNotFindInvoiceItem::withId($input->invoiceItemId);
        }

        $invoiceItem->SalesTax = $nf->parsePercent($input->taxPercent);
        $invoiceItem->TaxApplication = Cast::toInt($input->taxApplication);
        $invoiceItem->Subtotal = InvoiceItemSubtotalPureCalculator::new()->calcByInvoiceItem($invoiceItem);

        $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $input->editorUserId);
        $this->getLegacyInvoiceSummaryCalculator()->recalculate($invoiceItem->InvoiceId, $input->editorUserId);
        return $this;
    }

}
