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

use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceItemFormMultipleItemEditLineInput
 * @package Sam\View\Admin\Form\InvoiceItemForm\EditMultipleItem\Common
 */
class InvoiceItemFormMultipleItemEditRowInput extends CustomizableClass
{
    public int $invoiceItemId;
    public string $lotName;
    public string $hammerPriceFormatted;
    public string $buyersPremiumFormatted;
    public string $salesTaxFormatted;
    public ?int $taxApplication;


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
        string $lotName,
        string $hammerPriceFormatted,
        string $buyersPremiumFormatted,
        string $salesTaxFormatted,
        ?int $taxApplication
    ): static {
        $this->invoiceItemId = $invoiceItemId;
        $this->lotName = $lotName;
        $this->hammerPriceFormatted = $hammerPriceFormatted;
        $this->buyersPremiumFormatted = $buyersPremiumFormatted;
        $this->salesTaxFormatted = $salesTaxFormatted;
        $this->taxApplication = $taxApplication;
        return $this;
    }

}
