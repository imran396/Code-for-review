<?php
/**
 * SAM-11110: Stacked Tax. New Invoice Edit page: Service Fee Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Save;

use InvoiceAdditional;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoiceAdditional;
use Sam\Invoice\StackedTax\Calculate\Summary\StackedTaxInvoiceSummaryCalculatorAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceAdditional\InvoiceAdditionalWriteRepositoryAwareTrait;
use Sam\Tax\StackedTax\Calculate\StackedTaxCalculatorCreateTrait;
use Sam\Tax\StackedTax\Schema\Load\TaxSchemaLoaderCreateTrait;
use Sam\Tax\StackedTax\Schema\Snapshot\TaxCalculationResultSnapshotMakerCreateTrait;
use Sam\Tax\StackedTax\Schema\Validate\TaxSchemaExistenceCheckerCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Dto\ServiceFeeEditFormInput;

/**
 * Class ServiceFeeEditFormUpdater
 * @package Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Save
 */
class ServiceFeeEditFormUpdater extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use InvoiceAdditionalChargeManagerAwareTrait;
    use InvoiceAdditionalWriteRepositoryAwareTrait;
    use NumberFormatterAwareTrait;
    use StackedTaxCalculatorCreateTrait;
    use StackedTaxInvoiceSummaryCalculatorAwareTrait;
    use TaxCalculationResultSnapshotMakerCreateTrait;
    use TaxSchemaExistenceCheckerCreateTrait;
    use TaxSchemaLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function update(ServiceFeeEditFormInput $input): InvoiceAdditional
    {
        $editorUserId = $input->editorUserId;
        $invoiceId = $input->invoiceId;
        $numberFormatter = $this->getNumberFormatter()->constructForInvoice($input->invoiceAccountId);
        $amount = $numberFormatter->parseMoney($input->amount);

        $invoiceAdditional = $this->loadOrCreateInvoiceAdditional($input->invoiceAdditionalId);
        $invoiceAdditional->Type = $input->type;
        $invoiceAdditional->Amount = $amount;
        $invoiceAdditional->InvoiceId = $invoiceId;
        $invoiceAdditional->Name = $input->name;
        $invoiceAdditional->Note = $input->note;
        $invoiceAdditional->TaxAmount = 0.;
        $invoiceAdditional->CountryTaxAmount = 0.;
        $invoiceAdditional->StateTaxAmount = 0.;
        $invoiceAdditional->CountyTaxAmount = 0.;
        $invoiceAdditional->CityTaxAmount = 0.;
        $invoiceAdditional->TaxSchemaId = null;
        $this->getInvoiceAdditionalWriteRepository()->saveWithModifier($invoiceAdditional, $editorUserId);

        if (
            $input->type !== Constants\Invoice::IA_CASH_DISCOUNT
            && $input->taxSchemaId
        ) {
            $this->updateTax(
                amount: $amount,
                invoiceAdditional: $invoiceAdditional,
                taxSchemaId: $input->taxSchemaId,
                editorUserId: $editorUserId,
                language: $input->language,
                isReadOnlyDb: $input->isReadOnlyDb
            );
        }
        $this->getStackedTaxInvoiceSummaryCalculator()->recalculateAndSave($invoiceId, $editorUserId);
        return $invoiceAdditional;
    }

    protected function updateTax(
        float $amount,
        InvoiceAdditional $invoiceAdditional,
        int $taxSchemaId,
        int $editorUserId,
        string $language,
        bool $isReadOnlyDb
    ): void {
        $taxSchema = $this->createTaxSchemaLoader()->load($taxSchemaId, $isReadOnlyDb);
        if ($taxSchema) {
            $tax = $this->createStackedTaxCalculator()->calculate($amount, $taxSchema, $isReadOnlyDb);
            $taxSchemaSnapshot = $this->createTaxCalculationResultSnapshotMaker()->forInvoiceServiceFee(
                $tax,
                $invoiceAdditional->Id,
                $invoiceAdditional->InvoiceId,
                $editorUserId,
                $language
            );
            $invoiceAdditional->TaxAmount = $tax->getTaxAmount();
            $invoiceAdditional->CountryTaxAmount = $tax->getCountryTaxAmount();
            $invoiceAdditional->StateTaxAmount = $tax->getStateTaxAmount();
            $invoiceAdditional->CountyTaxAmount = $tax->getCountyTaxAmount();
            $invoiceAdditional->CityTaxAmount = $tax->getCityTaxAmount();
            $invoiceAdditional->TaxSchemaId = $taxSchemaSnapshot->Id;
            $this->getInvoiceAdditionalWriteRepository()->saveWithModifier($invoiceAdditional, $editorUserId);
        }
    }

    protected function loadOrCreateInvoiceAdditional(?int $invoiceAdditionalId): InvoiceAdditional
    {
        if (!$invoiceAdditionalId) {
            return $this->createEntityFactory()->invoiceAdditional();
        }

        $invoiceAdditional = $this->getInvoiceAdditionalChargeManager()->load($invoiceAdditionalId);
        if (!$invoiceAdditional) {
            throw CouldNotFindInvoiceAdditional::withId($invoiceAdditionalId);
        }
        return $invoiceAdditional;
    }
}
