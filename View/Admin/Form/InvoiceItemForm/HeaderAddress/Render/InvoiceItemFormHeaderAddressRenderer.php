<?php
/**
 * SAM-10899: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Header Address rendering
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

namespace Sam\View\Admin\Form\InvoiceItemForm\HeaderAddress\Render;

use Invoice;
use Sam\Address\AddressFormatterCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceHeaderDataLoaderAwareTrait;
use Sam\Settings\TermsAndConditionsManagerAwareTrait;

/**
 * Class InvoiceItemFormHeaderAddressRenderer
 * @package
 */
class InvoiceItemFormHeaderAddressRenderer extends CustomizableClass
{
    use AddressFormatterCreateTrait;
    use InvoiceHeaderDataLoaderAwareTrait;
    use TermsAndConditionsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Invoice $invoice
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function renderAddress(Invoice $invoice, bool $isReadOnlyDb = false): string
    {
        $output = '';
        $row = $this->getInvoiceHeaderDataLoader()->load($invoice->Id, $isReadOnlyDb);
        if ($row) {
            $output = $this->createAddressFormatter()->format(
                $row['country'],
                $row['state'],
                $row['city'],
                $row['zip'],
                $row['address']
            );
        }
        if (!$output) {
            $invoiceTerm = $this->getTermsAndConditionsManager()->load(
                $invoice->AccountId,
                Constants\TermsAndConditions::INVOICE,
                true
            );
            if ($invoiceTerm) {
                $output = $invoiceTerm->Content;
            } else {
                log_error(
                    "Available Terms and Conditions record not found for rendering invoice"
                    . composeSuffix(
                        ['acc' => $invoice->AccountId, 'key' => Constants\TermsAndConditions::INVOICE]
                    )
                );
            }
        }
        return $output;
    }
}
