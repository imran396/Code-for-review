<?php
/**
 * SAM-10824: Stacked Tax. Tax calculation on invoicing
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           25.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\StackedTax\Calculate\Summary;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Storage\ReadRepository\Entity\Currency\CurrencyReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceAdditional\InvoiceAdditionalReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\Payment\PaymentReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;

/**
 * Class InvoiceSummaryCalculator
 * @package Sam\Invoice\Calculate
 */
class StackedTaxInvoiceSummaryCalculator extends CustomizableClass
{
    use CurrencyLoaderAwareTrait;
    use CurrencyReadRepositoryCreateTrait;
    use InvoiceAdditionalReadRepositoryCreateTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceItemReadRepositoryCreateTrait;
    use InvoiceLoaderAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use PaymentReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function recalculateAndSave(int $invoiceId, int $editorUserId, bool $isReadOnlyDb = false): Invoice
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            log_error("Active invoice not found for recalculation" . composeSuffix(['i' => $invoiceId]));
            throw CouldNotFindInvoice::withId($invoiceId);
        }

        $invoice = $this->recalculateInvoiceAndSave($invoice, $editorUserId, $isReadOnlyDb);
        return $invoice;
    }

    public function recalculateInvoiceAndSave(Invoice $invoice, int $editorUserId, bool $isReadOnlyDb = false): Invoice
    {
        $invoice = $this->recalculateInvoice($invoice, $isReadOnlyDb);
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
        return $invoice;
    }

    public function recalculateInvoice(Invoice $invoice, bool $isReadOnlyDb = false): Invoice
    {
        $invoice = $this->calcHpBpTotals($invoice, $isReadOnlyDb);
        $invoice = $this->calcChargeTotals($invoice, $isReadOnlyDb);
        $invoice = $this->calcPaymentTotals($invoice, $isReadOnlyDb);
        $invoice = $this->calcCurrency($invoice, $isReadOnlyDb);
        $invoice->Tax = $invoice->calcTaxTotal();
        return $invoice;
    }

    /**
     * When invoice items are added, we can calculate summary of HP & BP for invoice, because this data affects charges (Extra Fee, Processing Fee).
     */
    public function recalculateHpBpTotalsAndSave(Invoice $invoice, int $editorUserId, bool $isReadOnlyDb = false): Invoice
    {
        $invoice = $this->calcHpBpTotals($invoice, $isReadOnlyDb);
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
        return $invoice;
    }

    public function calcHpBpTotals(Invoice $invoice, bool $isReadOnlyDb = false): Invoice
    {
        $select = [
            'SUM(ii.hammer_price) AS hp_total',
            'SUM(ii.hp_tax_amount) AS hp_tax_amount_total',
            'SUM(ii.hp_country_tax_amount) AS hp_country_tax_amount_total',
            'SUM(ii.hp_state_tax_amount) AS hp_state_tax_amount_total',
            'SUM(ii.hp_county_tax_amount) AS hp_county_tax_amount_total',
            'SUM(ii.hp_city_tax_amount) AS hp_city_tax_amount_total',
            'SUM(ii.buyers_premium) AS bp_total',
            'SUM(ii.bp_tax_amount) AS bp_tax_amount_total',
            'SUM(ii.bp_country_tax_amount) AS bp_country_tax_amount_total',
            'SUM(ii.bp_state_tax_amount) AS bp_state_tax_amount_total',
            'SUM(ii.bp_county_tax_amount) AS bp_county_tax_amount_total',
            'SUM(ii.bp_city_tax_amount) AS bp_city_tax_amount_total',
        ];
        $row = $this->createInvoiceItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterInvoiceId($invoice->Id)
            ->filterRelease([false, null])
            ->select($select)
            ->loadRow();
        $invoice->BidTotal = (float)$row['hp_total'];
        $invoice->BuyersPremium = (float)$row['bp_total'];
        $invoice->HpTaxTotal = (float)$row['hp_tax_amount_total'];
        $invoice->HpCountryTaxTotal = (float)$row['hp_country_tax_amount_total'];
        $invoice->HpStateTaxTotal = (float)$row['hp_state_tax_amount_total'];
        $invoice->HpCountyTaxTotal = (float)$row['hp_county_tax_amount_total'];
        $invoice->HpCityTaxTotal = (float)$row['hp_city_tax_amount_total'];
        $invoice->BpTaxTotal = (float)$row['bp_tax_amount_total'];
        $invoice->BpCountryTaxTotal = (float)$row['bp_country_tax_amount_total'];
        $invoice->BpStateTaxTotal = (float)$row['bp_state_tax_amount_total'];
        $invoice->BpCountyTaxTotal = (float)$row['bp_county_tax_amount_total'];
        $invoice->BpCityTaxTotal = (float)$row['bp_city_tax_amount_total'];
        return $invoice;
    }

    protected function calcChargeTotals(Invoice $invoice, bool $isReadOnlyDb = false): Invoice
    {
        $select = [
            'SUM(iadd.amount) AS extra_charges',
            'SUM(iadd.tax_amount) AS services_tax_total',
            'SUM(iadd.country_tax_amount) AS services_country_tax_total',
            'SUM(iadd.state_tax_amount) AS services_state_tax_total',
            'SUM(iadd.county_tax_amount) AS services_county_tax_total',
            'SUM(iadd.city_tax_amount) AS services_city_tax_total',
        ];
        $row = $this->createInvoiceAdditionalReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterInvoiceId($invoice->Id)
            ->select($select)
            ->loadRow();
        $invoice->ExtraCharges = (float)$row['extra_charges'];
        $invoice->ServicesTaxTotal = (float)$row['services_tax_total'];
        $invoice->ServicesCountryTaxTotal = (float)$row['services_country_tax_total'];
        $invoice->ServicesStateTaxTotal = (float)$row['services_state_tax_total'];
        $invoice->ServicesCountyTaxTotal = (float)$row['services_county_tax_total'];
        $invoice->ServicesCityTaxTotal = (float)$row['services_city_tax_total'];
        return $invoice;
    }

    protected function calcPaymentTotals(Invoice $invoice, bool $isReadOnlyDb = false): Invoice
    {
        $select = [
            'SUM(pmnt.amount) AS total_payment'
        ];
        $row = $this->createPaymentReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterTranType(Constants\Payment::TT_INVOICE)
            ->filterTranId($invoice->Id)
            ->select($select)
            ->loadRow();
        $invoice->TotalPayment = (float)$row['total_payment'];
        return $invoice;
    }

    /**
     * Determin currency fields in similar to Legacy invoice way.
     *
     * @param Invoice $invoice
     * @param bool $isReadOnlyDb
     * @return Invoice
     */
    protected function calcCurrency(Invoice $invoice, bool $isReadOnlyDb = false): Invoice
    {
        $currencyExRate = (float)$this->getCurrencyLoader()->loadExRateBySign(null, $isReadOnlyDb);
        $primaryCurrencySign = $this->getCurrencyLoader()->findPrimaryCurrencySign($isReadOnlyDb);
        $select = [
            "IFNULL(curr.sign, '{$primaryCurrencySign}') AS curr_sign",
            "IF(curr.sign = '{$primaryCurrencySign}' OR curr.sign IS NULL, 1, IFNULL(curr.ex_rate, {$currencyExRate})) AS curr_ex_rate",
        ];
        $auctionId = $this->getInvoiceItemLoader()->findFirstInvoicedAuctionId($invoice->Id, $isReadOnlyDb);
        $row = $this->createCurrencyReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->joinAuctionFilterId($auctionId)
            ->select($select)
            ->loadRow();
        $invoice->CurrencySign = $row['curr_sign'] ?? $primaryCurrencySign;
        $invoice->ExRate = $row['curr_ex_rate'] ?? $currencyExRate;
        return $invoice;
    }
}
