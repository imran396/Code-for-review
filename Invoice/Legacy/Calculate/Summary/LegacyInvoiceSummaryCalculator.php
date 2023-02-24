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

namespace Sam\Invoice\Legacy\Calculate\Summary;

use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Model\Invoice\Tax\InvoiceTaxPureCalculator;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Core\Constants;
use QMySqli5DatabaseResult;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;

/**
 * Class InvoiceSummaryCalculator
 * @package Sam\Invoice\Calculate
 */
class LegacyInvoiceSummaryCalculator extends CustomizableClass
{
    use CurrencyLoaderAwareTrait;
    use DbConnectionTrait;
    use InvoiceLoaderAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;
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
     * Calculate/Recalculate summary columns for
     * @param int $invoiceId
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return void
     */
    public function recalculate(int $invoiceId, int $editorUserId, bool $isReadOnlyDb = false): void
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            log_error("Active invoice not found for recalculation" . composeSuffix(['i' => $invoiceId]));
            return;
        }

        $isMultipleSaleInvoice = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::MULTIPLE_SALE_INVOICE, $invoice->AccountId);
        $currencyExRate = (float)$this->getCurrencyLoader()->loadExRateBySign(null, $isReadOnlyDb);
        $primaryCurrencySign = $this->getCurrencyLoader()->findPrimaryCurrencySign($isReadOnlyDb);
        $invoiceStatusList = implode(',', Constants\Invoice::$openInvoiceStatuses);
        $multipleSaleInclude = $multipleSaleQuery = '';

        if (!$isMultipleSaleInvoice) {
            $multipleSaleQuery = "
IFNULL (
  (
      SELECT bidder_num
      FROM auction_bidder 
      WHERE auction_id = (
        SELECT auction_id
        FROM invoice_item 
        WHERE active AND invoice_id = i.id AND auction_id > 0 LIMIT 1
      ) 
      AND user_id = i.bidder_id LIMIT 1
  ), '--') AS bidder_num, 
`bidder_number` as `bidder_number`, 
";
        }

        $taHpBp = Constants\User::TAX_HP_BP;
        $taHp = Constants\User::TAX_HP;
        $taBp = Constants\User::TAX_BP;

        $query = <<<SQL
SELECT 
    i.id AS id, 
    i.created_on AS created_on, 
    i.invoice_status_id AS invoice_status_id, 
    i.tax_charges_rate AS tax_charges_rate, 
    i.tax_fees_rate AS tax_fees_rate, 
    inv_sum.bid_total AS bid_total, 
    inv_sum.premium AS premium, 
    inv_sum.tax AS tax, 
    ( IFNULL(inv_charge.total_charge, 0) ) AS extra_charges, 
    ( IFNULL(inv_shipping.shipping_charge, 0) ) AS shipping_fees, 
    IFNULL(inv_payment.total_payment, 0) AS total_payment, 
    (
        inv_sum.bid_total + 
        inv_sum.premium + 
        inv_sum.tax +  
        (IFNULL(inv_shipping.shipping_charge, 0) + IFNULL(inv_charge.total_charge, 0))
    ) AS total, 
    (
      (
        inv_sum.bid_total + 
        inv_sum.premium + 
        inv_sum.tax +  
        (IFNULL(inv_shipping.shipping_charge, 0) + IFNULL(inv_charge.total_charge, 0))
      ) -
      IFNULL(inv_payment.total_payment, 0)
    ) AS balance, 
    IFNULL(currency.sign, '{$primaryCurrencySign}') AS curr_sign, 
    IF(currency.sign = '{$primaryCurrencySign}' OR currency.sign IS NULL, 1, IFNULL(currency.ex_rate, {$currencyExRate})) AS curr_ex_rate,
    {$multipleSaleQuery}
    IFNULL(auc.sale_id, 0) AS aid, 
    IFNULL(auc.currency, 0) AS curr
FROM invoice AS i  
    LEFT JOIN (
        SELECT ii.invoice_id, a.id AS sale_id, a.currency{$multipleSaleInclude} 
        FROM auction a 
          LEFT JOIN invoice_item ii ON a.id = ii.auction_id 
        WHERE ii.active AND ii.auction_id > 0 AND ii.invoice_id  = {$this->escape($invoiceId)}
        GROUP BY ii.invoice_id
    ) AS auc ON i.id = auc.invoice_id 
    LEFT JOIN currency ON auc.currency = currency.id 
    LEFT JOIN (
        SELECT 
            i.id AS invoice_id, 
            SUM(ii.hammer_price) AS bid_total, 
            SUM(ii.buyers_premium) AS premium, 
            (
                SELECT SUM(
                    CASE ii2.tax_application
                        WHEN {$taHpBp} THEN ii2.hammer_price * (ii2.sales_tax / 100) + ii2.buyers_premium * (ii2.sales_tax / 100)
                        WHEN {$taHp} THEN ii2.hammer_price * (ii2.sales_tax / 100)
                        WHEN {$taBp} THEN ii2.buyers_premium * (ii2.sales_tax / 100)
                        ELSE 0
                    END
                )
                FROM invoice_item ii2
                WHERE ii2.invoice_id = i.id AND ii2.active = true) AS tax 
        FROM invoice AS i
        LEFT JOIN invoice_item AS ii ON i.id = ii.invoice_id 
        WHERE i.invoice_status_id IN ({$invoiceStatusList}) AND ii.active = true AND i.id = {$this->escape($invoiceId)}
        GROUP BY i.id
    ) AS inv_sum ON i.id = inv_sum.invoice_id 
    LEFT JOIN (
        SELECT 
            id AS invoice_id, 
            SUM(shipping) AS shipping_charge 
        FROM invoice 
        WHERE invoice_status_id IN ({$invoiceStatusList}) AND id = {$this->escape($invoiceId)}
        GROUP BY id
    ) AS inv_shipping ON i.id = inv_shipping.invoice_id 
    LEFT JOIN (
        SELECT 
            invoice_id, SUM(amount) AS total_charge 
        FROM invoice_additional iadd 
        WHERE iadd.active = true
        GROUP BY invoice_id
    ) AS inv_charge ON i.id = inv_charge.invoice_id 
    LEFT JOIN (
        SELECT tran_id, SUM(amount) AS total_payment
        FROM payment 
        WHERE tran_type = {$this->escape(Constants\Payment::TT_INVOICE)} AND active = true 
        GROUP BY tran_id
    ) AS inv_payment ON i.id = inv_payment.tran_id 
WHERE i.id = {$this->escape($invoiceId)}
LIMIT 1;
SQL;

        $dbResult = $this->query($query);
        $invoiceTaxPureCalculator = InvoiceTaxPureCalculator::new();
        while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $taxService = $invoiceTaxPureCalculator->calcBuyerTaxService(
                (float)$row['extra_charges'],
                (float)$row['shipping_fees'],
                (float)$row['tax_charges_rate'],
                (float)$row['tax_fees_rate']
            );

            if (
                !$isMultipleSaleInvoice
                && isset($row['bidder_num'])
            ) {
                $invoice->BidderNumber = $row['bidder_num'];
            }

            $invoice->BidTotal = $row['bid_total'];
            $invoice->BuyersPremium = $row['premium'];
            $invoice->CurrencySign = (string)$row['curr_sign'];
            $invoice->ExRate = $row['curr_ex_rate'];
            $invoice->ExtraCharges = $row['extra_charges'];
            $invoice->ShippingFees = $row['shipping_fees'];
            $invoice->Tax = $row['tax'] + $taxService;
            $invoice->TaxService = $taxService;
            $invoice->TotalPayment = $row['total_payment'];
        }
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
    }

}
