<?php
/**
 * SAM-10923: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract invoice General validation and save (#invoice-save-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Common;

use DateTime;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceItemFormInvoiceEditingInput
 * @package Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Save
 */
class InvoiceItemFormInvoiceEditPaymentInput extends CustomizableClass
{
    public int $index;
    public ?int $paymentId;
    public string $amountFormatted;
    public ?int $paymentMethodId;
    public ?int $creditCardId;
    public ?DateTime $date;
    public string $note;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        ?int $index,
        ?int $paymentId,
        string $amountFormatted,
        ?int $paymentMethodId,
        ?int $creditCardId,
        string $note,
        ?DateTime $date
    ): static {
        $this->index = $index;
        $this->paymentId = $paymentId;
        $this->amountFormatted = $amountFormatted;
        $this->paymentMethodId = $paymentMethodId;
        $this->creditCardId = $creditCardId;
        $this->date = $date;
        $this->note = $note;
        return $this;
    }
}
