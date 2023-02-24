<?php
/**
 *
 * SAM-4365: Settlement Calculator module
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/11/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Calculate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Calculate\Basic\LegacyInvoiceCalculatorAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settlement\Calculate\Internal\Load\Dto\SettlementItemDto;
use Sam\Settlement\Calculate\Internal\Load\Dto\SettlementTotalsDto;
use Sam\Settlement\Calculate\Internal\Load\GetSettlementItems\GetSettlementItemsQuery;
use Sam\Settlement\Calculate\Internal\Load\GetSettlementTotals\GetSettlementTotalsQuery;
use Sam\Settlement\Calculate\Internal\Load\SettlementSummaryDataLoaderCreateTrait;
use Sam\Storage\Entity\AwareTrait\SettlementAwareTrait;
use Sam\Storage\WriteRepository\Entity\Settlement\SettlementWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Settlement;

/**
 * Class SettlementSummaryCalculator
 * @package Sam\Settlement\Calculate
 */
class SettlementSummaryCalculator extends CustomizableClass
{
    use LegacyInvoiceCalculatorAwareTrait;
    use SettingsManagerAwareTrait;
    use SettlementAwareTrait;
    use SettlementSummaryDataLoaderCreateTrait;
    use SettlementWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;

    protected const TAXABLE_ITEM = 'taxable';
    protected const NONTAXABLE_ITEM = 'nontaxable';
    protected const EXPORT_ITEM = 'export';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculate/Recalculate summary columns for a certain settlement
     * @param int $editorUserId
     */
    public function recalculate(int $editorUserId): void
    {
        $settlement = $this->getSettlement();
        if (!$settlement) {
            log_error("Available settlement not found" . composeSuffix(['s' => $this->getSettlementId()]));
            return;
        }
        $totals = $this->loadSettlementTotals($settlement);
        if ($totals) {
            $settlement->HpTotal = $totals->hpTotal;
            $settlement->CommTotal = $totals->commTotal;
            $settlement->ExtraCharges = $totals->extraCharges;
            $settlement->TaxInclusive = $totals->taxInclusive;
            $settlement->TaxExclusive = $totals->taxExclusive;
            $settlement->TaxServices = $totals->taxServices;
            $settlement->CostTotal = $totals->costTotal;
            $settlement->TaxableTotal = 0;
            $settlement->NonTaxableTotal = 0;
            $settlement->ExportTotal = 0;

            $getSettlementItemsQuery = GetSettlementItemsQuery::new()->construct($settlement->Id);
            $settlementItems = $this->createSettlementSummaryDataLoader()->loadSettlementItemsDto($getSettlementItemsQuery);
            foreach ($settlementItems as $settlementItem) {
                $taxability = $this->detectSettlementItemTaxability($settlementItem);
                $hp = $settlementItem->hp;
                if ($taxability === self::TAXABLE_ITEM) {
                    $settlement->TaxableTotal += $hp;
                } elseif ($taxability === self::NONTAXABLE_ITEM) {
                    $settlement->NonTaxableTotal += $hp;
                } elseif ($taxability === self::EXPORT_ITEM) {
                    $settlement->ExportTotal += $hp;
                }
            }
        }
        $this->getSettlementWriteRepository()->saveWithModifier($settlement, $editorUserId);
    }

    /**
     * - All taxes in SAM are usually exclusive, so exclusive just means add them on to the total as usual;
     *   inclusive means don't add them on to the total, just display a percentage amount,
     *   it basically tells the reader how much of the amount displayed already includes taxes.
     *
     * - If inclusive HP taxes apply on a consignor settlement, display a line item Tax (Inclusive) which
     *   would be a percentage of the HP total, but it would not affect the settlement total
     *   If commission taxes and inclusive HP taxes applies on a consignor settlement, we should add to
     *   Tax (Inclusive) the percentage of the commission total, but it would not affect the settlement total
     *
     * - If exclusive HP taxes or taxes for Comm./Services apply on a consignor settlement display a tax
     *   line item before the "Total" line, like we currently have on invoices, the settlement total should be reduced accordingly
     *
     * - Depending on which options (HP,Comm.,Services) are checked, a percentage of the hammer price,
     *   of the commission and/or the extra charges should be added up to calculate the applicable tax amount
     *  (There could be situations where we may have two line items, one for the inclusive tax and one for the regular tax.
     *   This can happen in cases where HP Inclusive is selected, because in that case there would be an inclusive
     *   tax line item (no effect on the total), and a regular tax line item (reduces total))
     * @param Settlement $settlement
     * @return SettlementTotalsDto|null
     */
    protected function loadSettlementTotals(Settlement $settlement): ?SettlementTotalsDto
    {
        $getSettlementTotalQuery = GetSettlementTotalsQuery::new()->construct($settlement->Id, $settlement->ConsignorTax);
        if ($settlement->ConsignorTaxHp) {
            if ($settlement->isConsignorTaxHpInclusive()) {
                $getSettlementTotalQuery->enableConsignorTaxHpInclusive(true);
            } elseif ($settlement->isConsignorTaxHpExclusive()) {
                $getSettlementTotalQuery->enableConsignorTaxHpExclusive(true);
            }
        }
        if (
            $settlement->ConsignorTaxComm
            && $settlement->ConsignorTaxHp
            && $settlement->isConsignorTaxHpInclusive()
        ) {
            $getSettlementTotalQuery->enableConsignorTaxCommissionInclusive(true);
        }
        if ($settlement->ConsignorTaxServices) {
            $getSettlementTotalQuery->enableConsignorTaxServices(true);
        }
        $totals = $this->createSettlementSummaryDataLoader()->loadTotalsDto($getSettlementTotalQuery);
        return $totals;
    }

    /**
     * Taxable: Should be the total of all lots whose winning bidder's shipping info
     *          has the same country as is selected as the country the corresponding
     *          auction is held in and whose hammer price is taxable if this lot is
     *          not associated to any auction, fall back to the global default country
     *          selected on http://dev.auctionserver.net/admin/manage-system-parameter/admin-option;
     *          determine taxability based on whether or not the corresponding
     *          non-canceled, non-deleted, non-released invoice line item.
     *          If no such line item exists (for example because the invoice wasn't generated yet),
     *          then determine taxability based on whether or not a tax WOULD be applied
     *          for the corresponding invoice line item if an invoice was generated for its buyer at this time.
     *
     * Non-Taxable: Same criteria as above in terms of country, only that it should include
     *              all the lots whose hammer prices are NOT taxable.
     *
     * Export: Total of HP of all lots whose buyer has a shipping address country different
     *         from the auction/global country (see country matching outlined above under "Taxable")
     *
     * @param SettlementItemDto $settlementItem
     * @return string
     */
    protected function detectSettlementItemTaxability(SettlementItemDto $settlementItem): string
    {
        $defaultCountry = $this->getSettingsManager()->get(Constants\Setting::DEFAULT_COUNTRY, $this->getSettlement()->AccountId);
        $userShipping = $this->getUserLoader()->loadUserShippingOrCreate($settlementItem->winningBidderId);
        $auctionCountry = $settlementItem->auctionId
            ? $settlementItem->auctionCountry
            : $defaultCountry;
        if (
            $userShipping->Country !== ''
            && $userShipping->Country === $auctionCountry
        ) { // Taxable and Non-taxable
            $salesTax = $this->detectSettlementItemSalesTax($settlementItem);
            $taxability = $salesTax > 0 ? self::TAXABLE_ITEM : self::NONTAXABLE_ITEM;
        } else { // Export
            $taxability = self::EXPORT_ITEM;
        }
        return $taxability;
    }

    /**
     * @param SettlementItemDto $settlementItem
     * @return float
     */
    protected function detectSettlementItemSalesTax(SettlementItemDto $settlementItem): float
    {
        if ($settlementItem->invoiceId) {
            log_debug('invoice associated' . composeSuffix(['i' => $settlementItem->invoiceId]));
            $salesTax = $settlementItem->salesTax;
        } else {
            log_debug(
                'no invoice associated get application sales tax' . composeSuffix(
                    [
                        'li' => $settlementItem->lotItemId,
                        'wb' => $settlementItem->winningBidderId,
                        'a' => $settlementItem->auctionId,
                    ]
                )
            );
            $taxResult = $this->getLegacyInvoiceCalculator()->detectApplicableSalesTax(
                $settlementItem->winningBidderId,
                $settlementItem->lotItemId,
                $settlementItem->auctionId
            );
            $salesTax = $taxResult->percent;
        }
        return $salesTax;
    }
}
