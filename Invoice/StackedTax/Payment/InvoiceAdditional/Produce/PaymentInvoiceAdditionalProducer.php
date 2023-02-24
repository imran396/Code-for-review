<?php
/**
 * SAM-11338: Stacked Tax. Public page Invoice with CC surcharge and Service tax on surcharge
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Produce;

use InvoiceAdditional;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Calculate\PaymentInvoiceAdditionalCalculationResult;
use Sam\Storage\WriteRepository\Entity\InvoiceAdditional\InvoiceAdditionalWriteRepositoryAwareTrait;
use Sam\Tax\StackedTax\Schema\Snapshot\TaxCalculationResultSnapshotMakerCreateTrait;
use Sam\Translation\TranslationLanguageProviderCreateTrait;

/**
 * Class PaymentInvoiceAdditionalProducer
 * @package Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Produce
 */
class PaymentInvoiceAdditionalProducer extends CustomizableClass
{
    use InvoiceAdditionalChargeManagerAwareTrait;
    use InvoiceAdditionalWriteRepositoryAwareTrait;
    use TaxCalculationResultSnapshotMakerCreateTrait;
    use TranslationLanguageProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function add(
        PaymentInvoiceAdditionalCalculationResult $paymentCalculationResult,
        int $invoiceId,
        int $paymentId,
        int $accountId,
        int $editorUserId,
    ): InvoiceAdditional {
        $invoiceAdditional = $this->getInvoiceAdditionalChargeManager()->add(
            type: Constants\Invoice::IA_CC_SURCHARGE,
            invoiceId: $invoiceId,
            name: $paymentCalculationResult->name,
            amount: $paymentCalculationResult->amount,
            editorUserId: $editorUserId,
            paymentId: $paymentId,
            paymentCalculationResult: $paymentCalculationResult
        );
        if ($paymentCalculationResult->taxCalculationResult) {
            $taxSchemaSnapshot = $this->createTaxCalculationResultSnapshotMaker()->forInvoiceServiceFee(
                calculationResult: $paymentCalculationResult->taxCalculationResult,
                invoiceAdditionalId: $invoiceAdditional->Id,
                invoiceId: $invoiceId,
                editorUserId: $editorUserId,
                language: $this->createTranslationLanguageProvider()->detectLanguage($accountId),
            );
            $invoiceAdditional->TaxSchemaId = $taxSchemaSnapshot->Id;
            $this->getInvoiceAdditionalWriteRepository()->saveWithModifier($invoiceAdditional, $editorUserId);
        }
        return $invoiceAdditional;
    }
}
