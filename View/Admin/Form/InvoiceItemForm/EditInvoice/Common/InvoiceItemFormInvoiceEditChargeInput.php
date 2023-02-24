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

use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceItemFormInvoiceEditingInput
 * @package Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Save
 */
class InvoiceItemFormInvoiceEditChargeInput extends CustomizableClass
{
    public int $index;
    public ?int $invoiceAdditionalId;
    public string $amountFormatted;
    public string $couponId;
    public string $couponCode;
    public string $name;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $index,
        ?int $invoiceAdditionalId,
        string $amountFormatted,
        string $couponId,
        string $couponCode,
        string $name
    ): static {
        $this->index = $index;
        $this->invoiceAdditionalId = $invoiceAdditionalId;
        $this->amountFormatted = $amountFormatted;
        $this->couponId = $couponId;
        $this->couponCode = $couponCode;
        $this->name = $name;
        return $this;
    }
}
