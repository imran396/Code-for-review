<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Produce;

use Invoice;
use Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Produce\Internal\Calculate\CalculatorCreateTrait;
use Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Produce\Internal\Load\DataProviderCreateTrait;
use Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Produce\Internal\Notify\NotifierCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;

class InvoiceProducer extends CustomizableClass
{
    use DataProviderCreateTrait;
    use CalculatorCreateTrait;
    use NotifierCreateTrait;
    use InvoiceWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produce(
        int $invoiceId,
        int $editorUserId,
        bool $isReadOnlyDb = false,
    ): Invoice {
        $dataProvider = $this->createDataProvider();
        $invoice = $dataProvider->loadInvoice($invoiceId, $isReadOnlyDb);

        if (!$invoice) {
            throw CouldNotFindInvoice::withId($invoiceId);
        }
        $invoice->toPaid();
        $invoice = $this->createCalculator()->calculateAndAssign($invoice, $isReadOnlyDb);
        $this->createNotifier()->notify($invoice, $editorUserId);
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
        return $invoice;
    }
}
