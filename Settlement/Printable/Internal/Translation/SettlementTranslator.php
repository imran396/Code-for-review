<?php
/**
 * SAM-7661: Settlement Printable & viewable version to the new layout Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           02-21, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Printable\Internal\Translation;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;

/**
 * Translate settlement lots table columns headers and common labels.
 *
 * Class SettlementTranslator
 * @package Settlement\Printable
 */
class SettlementTranslator extends CustomizableClass
{
    use TranslatorAwareTrait;

    protected const SECTION = 'mysettlements';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isTranslatable We dont need translations for admin area,
     * but we need translations for public area
     * and Email {settlement_html} placeholder context
     * @param bool $isQuantityInSettlement
     *
     * @return SettlementTranslatorDto
     * @see Constants\EmailKey::SETTLEMENT_PAYMENT_CONF
     * @see Constants\EmailKey::SETTLEMENT
     * We implemented non-translatable text at dev@35029
     *
     */
    public function getTranslatedHeaders(bool $isTranslatable = false, bool $isQuantityInSettlement = false): SettlementTranslatorDto
    {
        $section = self::SECTION;
        $dto = SettlementTranslatorDto::new();
        $tr = $this->getTranslator();

        $dto->itemNumHeader = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_ITEMNUM', $section)
            : 'Item #';
        $dto->itemNameHeader = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_ITEMNAME', $section)
            : 'Item Name';
        $dto->lotHeader = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_LOTNUM', $section)
            : 'Lot #';
        $dto->saleHeader = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_SALENUM', $section)
            : 'Sale #';
        $dto->estimateHeader = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_ESTIMATE', $section)
            : 'Estimate';
        $dto->saleNameHeader = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_AUCTION', $section)
            : 'Auction';
        $dto->hammerHeader = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_HAMMER', $section)
            : 'Hammer price';
        $dto->feeHeader = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_FEE', $section)
            : 'Fee';
        $dto->taxOnHPHeader = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_TAXONHP', $section)
            : 'Tax on HP';
        $dto->taxOnCommHeader = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_TAXONCOMM', $section)
            : 'Tax on Comm. and Fee';
        $dto->commissionHeader = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_COMM', $section)
            : 'Commission';
        $dto->subtotalHeader = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_SUBTOTAL', $section)
            : 'Subtotal';
        $dto->quantityHeader = '';
        if ($isQuantityInSettlement) {
            $dto->quantityHeader = $isTranslatable
                ? $tr->translate('MYSETTLEMENTS_DETAIL_QUANTITY', $section)
                : 'Qty';
        }

        return $dto;
    }

    /**
     * @param bool $isTranslatable ; We dont need translations for admin area,
     * but we need translations for public area
     * and Email {settlement_html} placeholder context
     * @return SettlementTranslatorDto
     * @see Constants\EmailKey::SETTLEMENT
     * We implemented non-translatable text at dev@35029
     *
     * @see Constants\EmailKey::SETTLEMENT_PAYMENT_CONF
     */
    public function getTranslatedCommon(bool $isTranslatable = false): SettlementTranslatorDto
    {
        $section = self::SECTION;
        $dto = SettlementTranslatorDto::new();
        $tr = $this->getTranslator();

        $dto->subtotalLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_SUBTOTAL', $section)
            : 'Subtotal';
        $dto->unpaidLotsLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_UNPAID_LOTS', $section)
            : 'Unpaid lots';
        $dto->paidLotsLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_PAID_LOTS', $section)
            : 'Paid lots';
        $dto->commissionLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_COMM', $section)
            : 'Commission';
        $dto->totalLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_TOTAL_DUE', $section)
            : 'Total';
        $dto->extraChargeLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_EXTRA_CHARGE', $section)
            : 'Extra charge';
        $dto->paymentsMadeLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_PAYMENTS_MADE', $section)
            : 'Payments made';
        $dto->balanceDueLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_BALANCEDUE', $section)
            : 'Balance due';
        $dto->commissionSubtotalLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_COMM_SUBTOTAL', $section)
            : 'Commission subtotal';
        $dto->taxExcLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_TAX', $section)
            : 'Tax';
        $dto->taxIncLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_TAX_INC', $section)
            : 'Tax (inclusive)';
        $dto->consignorSettlementLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_CONSIGNOR_SETTLEMENT', $section)
            : 'Consignor settlement';
        $dto->dateCreatedLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_DATECREATED', $section)
            : 'Date created';
        $dto->statusLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_STATUS', $section)
            : 'Status';
        $dto->notesLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_NOTES', $section)
            : 'Notes';
        $dto->subSummaryLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_SUB_SUMMARY', $section)
            : 'Sub-Summary';
        $dto->summaryLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_SUMMARY', $section)
            : 'Summary';
        $dto->userCustomFieldsLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_DETAIL_USER_CUST_FIELDS', $section)
            : 'User Custom Fields';
        $dto->userCustomerNumberLbl = $isTranslatable
            ? $tr->translate('USER_CUSTOMER_NUMBER_SHORT', 'user')
            : 'Customer #';
        $dto->consignorInformationLbl = $isTranslatable
            ? $tr->translate('MYSETTLEMENTS_CONSIGNOR_INFORMATION', $section)
            : 'Consignor information';
        $dto->saleLbl = $isTranslatable
            ? $tr->translate('MYINVOICES_DETAIL_SALE', 'myinvoices')
            : 'Sale';
        $dto->saleDateLbl = $isTranslatable
            ? $tr->translate('MYINVOICES_DETAIL_SALEDATE', 'myinvoices')
            : 'Sale date';
        $dto->saleDateFormat = $tr->translate('MYINVOICES_SALE_DATE', 'myinvoices');

        return $dto;
    }
}
