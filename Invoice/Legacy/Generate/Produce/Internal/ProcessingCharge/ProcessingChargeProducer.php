<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Generate\Produce\Internal\ProcessingCharge;

use Invoice;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Legacy\Calculate\Basic\LegacyInvoiceCalculatorAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class ProcessingChargeProducer
 * @package Sam\Invoice\Legacy\Generate\Produce\Internal\InvoiceLineItemCharge
 */
class ProcessingChargeProducer extends CustomizableClass
{
    use InvoiceAdditionalChargeManagerAwareTrait;
    use LegacyInvoiceCalculatorAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function applyProcessingFeeCharge(Invoice $invoice, int $editorUserId, bool $isReadOnlyDb): void
    {
        $processingCharge = $this->getSettingsManager()->get(Constants\Setting::PROCESSING_CHARGE, $invoice->AccountId);
        if (Floating::gt($processingCharge, 0)) {
            $total = $this->getLegacyInvoiceCalculator()->calcTotal($invoice->Id, $isReadOnlyDb);
            $processCharge = $total * ($processingCharge / 100);
            $this->getInvoiceAdditionalChargeManager()->add(
                Constants\Invoice::IA_PROCESSING_FEE,
                $invoice->Id,
                'Processing fee',
                $processCharge,
                $editorUserId
            );
        }
    }

}
