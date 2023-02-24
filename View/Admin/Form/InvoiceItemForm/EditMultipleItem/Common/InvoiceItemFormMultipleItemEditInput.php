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

namespace Sam\View\Admin\Form\InvoiceItemForm\EditMultipleItem\Common;

use DateTime;
use Invoice;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceItemFormMultipleItemEditInput
 * @package Sam\View\Admin\Form\InvoiceItemForm\EditMultipleItem\Common
 */
class InvoiceItemFormMultipleItemEditInput extends CustomizableClass
{
    public int $editorUserId;
    public Invoice $invoice;
    public string $invoiceNo;
    public ?DateTime $invoiceDate;
    public string $taxChargesRateFormatted;
    public string $taxFeesRateFormatted;
    /** @var InvoiceItemFormMultipleItemEditRowInput[] */
    public array $invoiceItems;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $editorUserId,
        Invoice $invoice,
        string $invoiceNo,
        ?DateTime $invoiceDate,
        string $taxChargesRateFormatted,
        string $taxFeesRateFormatted,
        array $invoiceItemInputs
    ): static {
        $this->editorUserId = $editorUserId;
        $this->invoice = $invoice;
        $this->invoiceNo = $invoiceNo;
        $this->invoiceDate = $invoiceDate;
        $this->taxChargesRateFormatted = $taxChargesRateFormatted;
        $this->taxFeesRateFormatted = $taxFeesRateFormatted;
        $this->invoiceItems = $invoiceItemInputs;
        return $this;
    }
}
