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

namespace Sam\Invoice\Legacy\Generate\Produce\Internal\ShippingCharge;

use Invoice;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Shipping\Save\InvoiceShippingRateProducerAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;

/**
 * Class ShippingChargeProducer
 * @package Sam\Invoice\Legacy\Generate\Produce\Internal\ShippingCharge
 */
class ShippingChargeProducer extends CustomizableClass
{
    use InvoiceShippingRateProducerAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function applyShipping(Invoice $invoice, int $editorUserId): string
    {
        $shippingCharge = $this->getSettingsManager()->get(Constants\Setting::SHIPPING_CHARGE, $invoice->AccountId);
        if ($shippingCharge) {
            $invoice->Shipping = $shippingCharge;
            $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
            return '';
        }

        $shippingRateProducer = $this->getInvoiceShippingRateProducer();
        $shippingRateProducer->update($invoice, $editorUserId);
        $errorMessage = $shippingRateProducer->getErrorMessage();
        return $errorMessage;
    }
}
