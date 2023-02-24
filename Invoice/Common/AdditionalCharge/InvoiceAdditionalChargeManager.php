<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           22.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Common\AdditionalCharge;

use Invoice;
use InvoiceAdditional;
use RuntimeException;
use Sam\Billing\CreditCard\Build\CcSurchargeDetectorCreateTrait;
use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Entity\Model\Invoice\Tax\InvoiceTaxPureCalculator;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Calculate\PaymentInvoiceAdditionalCalculationResult;
use Sam\Storage\DeleteRepository\Entity\InvoiceAdditional\InvoiceAdditionalDeleteRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceAdditional\InvoiceAdditionalReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceAdditional\InvoiceAdditionalWriteRepositoryAwareTrait;

/**
 * Class InvoiceAdditionalChargeManager
 */
class InvoiceAdditionalChargeManager extends CustomizableClass
{
    use CcSurchargeDetectorCreateTrait;
    use CreditCardLoaderAwareTrait;
    use EntityFactoryCreateTrait;
    use InvoiceAdditionalDeleteRepositoryCreateTrait;
    use InvoiceAdditionalReadRepositoryCreateTrait;
    use InvoiceAdditionalWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function load(int $id, bool $isReadOnlyDb = false): ?InvoiceAdditional
    {
        $invoiceAdditional = $this->createInvoiceAdditionalReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($id)
            ->loadEntity();
        return $invoiceAdditional;
    }

    public function loadByPaymentId(int $paymentId, bool $isReadOnlyDb = false): ?InvoiceAdditional
    {
        $invoiceAdditional = $this->createInvoiceAdditionalReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterPaymentId($paymentId)
            ->loadEntity();
        return $invoiceAdditional;
    }

    /**
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return InvoiceAdditional[]
     */
    public function loadForInvoice(int $invoiceId, bool $isReadOnlyDb = false): array
    {
        return $this->createInvoiceAdditionalReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterInvoiceId($invoiceId)
            ->orderById()
            ->loadEntities();
    }

    /**
     * @param int $invoiceId
     * @param array $skipInvoiceIds
     */
    public function deleteForInvoice(int $invoiceId, array $skipInvoiceIds = []): void
    {
        $this->createInvoiceAdditionalDeleteRepository()
            ->filterInvoiceId($invoiceId)
            ->skipId($skipInvoiceIds)
            ->delete();
    }

    /**
     * @param int $type
     * @param int $invoiceId
     * @param string $name
     * @param float $amount
     * @param int $editorUserId
     * @param string $note
     * @param int|null $taxSchemaId
     * @param int|null $couponId null means coupon_id is unknown
     * @param string|null $couponCode null means coupon_code is unknown
     * @param int|null $paymentId null means payment_id is unknown
     * @param PaymentInvoiceAdditionalCalculationResult|null $paymentCalculationResult
     * @return InvoiceAdditional
     */
    public function add(
        int $type,
        int $invoiceId,
        string $name,
        float $amount,
        int $editorUserId,
        string $note = '',
        ?int $taxSchemaId = null,
        ?int $couponId = null,
        ?string $couponCode = null,
        ?int $paymentId = null,
        ?PaymentInvoiceAdditionalCalculationResult $paymentCalculationResult = null
    ): InvoiceAdditional {
        $invoiceAdditional = $this->createEntityFactory()->invoiceAdditional();
        $invoiceAdditional->Amount = $amount;
        $invoiceAdditional->CouponCode = (string)$couponCode;
        $invoiceAdditional->CouponId = $couponId;
        $invoiceAdditional->InvoiceId = $invoiceId;
        $invoiceAdditional->Name = $name;
        $invoiceAdditional->Note = $note;
        $invoiceAdditional->PaymentId = $paymentId;
        $invoiceAdditional->TaxSchemaId = $taxSchemaId;
        $invoiceAdditional->Type = $type;
        if (
            $paymentCalculationResult
            && $paymentCalculationResult->taxCalculationResult
        ) {
            $invoiceAdditional->TaxAmount = $paymentCalculationResult->taxAmount;
            $invoiceAdditional->CountryTaxAmount = $paymentCalculationResult->taxCalculationResult->getCountryTaxAmount();
            $invoiceAdditional->StateTaxAmount = $paymentCalculationResult->taxCalculationResult->getStateTaxAmount();
            $invoiceAdditional->CountyTaxAmount = $paymentCalculationResult->taxCalculationResult->getCountyTaxAmount();
            $invoiceAdditional->CityTaxAmount = $paymentCalculationResult->taxCalculationResult->getCityTaxAmount();
        }
        $this->getInvoiceAdditionalWriteRepository()->saveWithModifier($invoiceAdditional, $editorUserId);
        return $invoiceAdditional;
    }

    public function update(
        int $invoiceAdditionalId,
        int $invoiceId,
        string $name,
        float $amount,
        int $editorUserId,
        string $note = '',
        ?int $taxSchemaId = null,
        ?int $couponId = null,
        ?string $couponCode = null,
        ?int $paymentId = null
    ): InvoiceAdditional {
        $invoiceAdditional = $this->load($invoiceAdditionalId);
        if (
            !$invoiceAdditional
            || $invoiceAdditional->InvoiceId !== $invoiceId
        ) {
            throw new RuntimeException('Invalid invoice charge id' . composeSuffix(['iac' => $invoiceAdditionalId, 'i' => $invoiceId]));
        }

        $invoiceAdditional->Amount = $amount;
        $invoiceAdditional->CouponCode = (string)$couponCode;
        $invoiceAdditional->CouponId = $couponId;
        $invoiceAdditional->Name = $name;
        $invoiceAdditional->Note = $note;
        $invoiceAdditional->PaymentId = $paymentId;
        $invoiceAdditional->TaxSchemaId = $taxSchemaId;
        $this->getInvoiceAdditionalWriteRepository()->saveWithModifier($invoiceAdditional, $editorUserId);
        return $invoiceAdditional;
    }

    /**
     * Get Invoice Credit Card Surcharge
     *
     * @param int|null $creditCardId payment.credit_card_id null means no billing information(CC) for the user
     * @param float $amount
     * @param int $accountId
     * @return array $result = [
     *  'name' => string, // CC based surcharge record name
     *  'amount' => float, // Surcharge amount
     * ]
     */
    public function buildCcSurcharge(?int $creditCardId, float $amount, int $accountId): array
    {
        $creditCard = $this->getCreditCardLoader()->load($creditCardId);
        $creditCardSurcharge = $this->createCcSurchargeDetector()->detect($creditCardId, $accountId);
        $result = [];
        if (
            $creditCard
            && $creditCardSurcharge > 0
        ) {
            $name = $creditCard->Name . ' Surcharge';
            $amount *= ($creditCardSurcharge / 100);
            $result = [
                'name' => $name,
                'amount' => $amount,
            ];
        }
        return $result;
    }


    /**
     * Calculates CC Surcharge, add the surcharge to the db. Add to the charge amount the surcharge + tax.
     *
     * @see https://bidpath.atlassian.net/browse/SAM-6468 SAM-6238 CC Surcharge calculation incorrect
     * @param int|null $ccType
     * @param float $amount
     * @param Invoice $invoice
     * @param int $editorUserId
     * @return float
     */
    public function addCcSurchargeToTheAmount(
        ?int $ccType,
        float $amount,
        Invoice $invoice,
        int $editorUserId
    ): float {
        $ccSurcharge = $this->buildCcSurcharge($ccType, $amount, $invoice->AccountId);

        if ($ccSurcharge) {
            $amount += $ccSurcharge['amount'];
            $this->add(
                Constants\Invoice::IA_CC_SURCHARGE,
                $invoice->Id,
                $ccSurcharge['name'],
                $ccSurcharge['amount'],
                $editorUserId
            );
            $taxServiceAmount = InvoiceTaxPureCalculator::new()->calcBuyerTaxService(
                (float)$ccSurcharge['amount'],
                0,
                (float)$invoice->TaxChargesRate,
                0
            );
            $amount += $taxServiceAmount;
        }
        return $amount;
    }
}
