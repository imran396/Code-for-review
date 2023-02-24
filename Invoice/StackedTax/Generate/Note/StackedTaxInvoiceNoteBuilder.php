<?php
/**
 * SAM-4377: Invoice producer
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\StackedTax\Generate\Note;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Generate\Note\Consignor\InvoiceNoteConsignorInfoBuilderAwareTrait;
use Sam\Invoice\StackedTax\Generate\Note\General\InvoiceNoteGeneralInfoBuilderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Core\Constants;

/**
 * Class InvoiceNoteBuilder
 * @package Sam\Invoice\StackedTax\Generate\Note
 */
class StackedTaxInvoiceNoteBuilder extends CustomizableClass
{
    use InvoiceNoteConsignorInfoBuilderAwareTrait;
    use InvoiceNoteGeneralInfoBuilderAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Invoice $invoice
     * @return string
     */
    public function build(Invoice $invoice): string
    {
        $consignorPaymentInfo = $this->getSettingsManager()->getForMain(Constants\Setting::ENABLE_CONSIGNOR_PAYMENT_INFO)
            ? $this->getInvoiceNoteConsignorInfoBuilder()->build($invoice->Id) : '';
        $generalInfo = $this->getInvoiceNoteGeneralInfoBuilder()->build($invoice);
        return trim(
            $generalInfo
            . PHP_EOL
            . PHP_EOL
            . $consignorPaymentInfo
        );
    }

}
