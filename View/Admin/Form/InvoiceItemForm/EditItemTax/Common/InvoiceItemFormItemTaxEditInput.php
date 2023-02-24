<?php
/**
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

namespace Sam\View\Admin\Form\InvoiceItemForm\EditItemTax\Common;

use Sam\Core\Service\CustomizableClass;

class InvoiceItemFormItemTaxEditInput extends CustomizableClass
{
    public int $invoiceItemId;
    public int $invoiceAccountId;
    public ?int $editorUserId;
    public string $taxPercent;
    public string $taxApplication;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $invoiceItemId,
        int $invoiceAccountId,
        ?int $editorUserId,
        string $taxPercent,
        string $taxApplication
    ): static {
        $this->invoiceItemId = $invoiceItemId;
        $this->invoiceAccountId = $invoiceAccountId;
        $this->editorUserId = $editorUserId;
        $this->taxPercent = $taxPercent;
        $this->taxApplication = $taxApplication;
        return $this;
    }

}
