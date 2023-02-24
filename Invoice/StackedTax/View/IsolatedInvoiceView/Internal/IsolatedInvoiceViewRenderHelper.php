<?php
/**
 * SAM-11160: Stacked Tax. Admin/Public Single Invoice Printing View page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\View\IsolatedInvoiceView\Internal;

use DateTime;
use Invoice;
use LotItemCustData;
use LotItemCustField;
use Sam\Address\AddressFormatterCreateTrait;
use Sam\Application\Url\Build\Config\Barcode\BarcodeUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Date\Render\DateRendererCreateTrait;
use Sam\Invoice\Common\Load\InvoiceHeaderDataLoaderAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settings\TermsAndConditionsManagerAwareTrait;
use Sam\Tax\StackedTax\GeoType\Config\StackedTaxGeoTypeConfigProvider;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class IsolatedInvoiceViewRenderHelper
 * @package Sam\Invoice\StackedTax\View\IsolatedInvoiceView\Internal
 */
class IsolatedInvoiceViewRenderHelper extends CustomizableClass
{
    use AddressFormatterCreateTrait;
    use CreditCardLoaderAwareTrait;
    use DateHelperAwareTrait;
    use DateRendererCreateTrait;
    use InvoiceHeaderDataLoaderAwareTrait;
    use LotAmountRendererFactoryCreateTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;
    use TermsAndConditionsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function renderInvoiceAddress(Invoice $invoice): string
    {
        $output = '';
        $row = $this->getInvoiceHeaderDataLoader()->load($invoice->Id, true);
        if ($row) {
            $output = $this->createAddressFormatter()->format($row['country'], $row['state'], $row['city'], $row['zip'], $row['address']);
        }
        if (!$output) {
            $invoiceTerm = $this->getTermsAndConditionsManager()->load(
                $invoice->AccountId,
                Constants\TermsAndConditions::INVOICE,
                true
            );
            if (!$invoiceTerm) {
                log_error(
                    "Available Terms and Conditions record not found for rendering invoice print"
                    . composeSuffix(['acc' => $invoice->AccountId, 'key' => Constants\TermsAndConditions::INVOICE])
                );
                return '';
            }
            $output = $invoiceTerm->Content;
        }
        return $output;
    }

    public function renderAuctionDate(?DateTime $auctionDate, string $auctionType, ?int $eventType, int $accountId, int $languageId): string
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isTimedOngoing($auctionType, $eventType)) {
            return $this->getTranslator()->translate('AUCTIONS_EVENT_TYPE', 'auctions', null, $languageId);
        }

        if (!$auctionDate) {
            return '';
        }

        $auctionDateTz = $this->getDateHelper()->convertUtcToSys($auctionDate, $accountId);
        $format = $this->getTranslator()->translate('MYINVOICES_SALE_DATE', 'myinvoices', null, $languageId);
        $dateHelper = $this->getDateHelper();
        if (strrpos($format, "F") !== false) {
            $month = date("F", $auctionDateTz->getTimestamp());
            $langMonth = $this->createDateRenderer()->monthTranslated((int)$month);
            $dateFormatted = $dateHelper->formattedDate($auctionDateTz, $accountId, null, null, $format);
            $dateFormatted = str_replace($month, $langMonth, $dateFormatted);
        } else {
            $dateFormatted = $dateHelper->formattedDate($auctionDateTz, $accountId, null, null, $format);
        }
        return $dateFormatted;
    }

    public function renderDate(DateTime|string|null $date, int $accountId, int $languageId): string
    {
        if ($date) {
            $date = is_string($date) ? new DateTime($date) : $date;
            $dateTz = $this->getDateHelper()->convertUtcToSys($date, $accountId);
            $format = $this->getTranslator()->translate('MYINVOICES_INVOICE_DATE', 'myinvoices', null, $languageId);
            return $this->getDateHelper()->formattedDate(date: $dateTz, format: $format);
        }
        return '';
    }

    public function renderQuantity(?float $quantity, int $quantityScale, int $invoiceAccountId): string
    {
        $lotAmountRenderer = $this->createLotAmountRendererFactory()->createForInvoice($invoiceAccountId);
        $qty = Floating::gt($quantity, 0, $quantityScale)
            ? $lotAmountRenderer->makeQuantity($quantity, $quantityScale)
            : '-';
        return $qty;
    }

    public function renderLotCustomFieldValue(?LotItemCustData $lotCustomData, LotItemCustField $lotCustomField): string|int|null
    {
        if (!$lotCustomData) {
            return '';
        }

        if ($lotCustomField->Type === Constants\CustomField::TYPE_DATE) {
            $dateSys = $this->getDateHelper()->convertUtcToSysByTimestamp($lotCustomData->Numeric);
            $customFieldValue = $this->getDateHelper()->formattedDate($dateSys);
        } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_DECIMAL) {
            $precision = (int)$lotCustomField->Parameters;
            $value = $lotCustomData->calcDecimalValue($precision);
            $customFieldValue = $this->getNumberFormatter()->format($value, $precision);
        } elseif (
            $lotCustomField->Type === Constants\CustomField::TYPE_TEXT
            && $lotCustomField->Barcode
            && $lotCustomData->Text !== ''
        ) {
            $url = $this->getUrlBuilder()->build(
                BarcodeUrlConfig::new()->forWeb($lotCustomData->Text, $lotCustomField->BarcodeType)
            );
            $customFieldValue = sprintf('<img src="%s" alt="%s" title="" />', addslashes($url), ee($lotCustomData->Text));
        } else {
            $customFieldValue = $lotCustomField->isNumeric()
                ? $lotCustomData->Numeric
                : ee($lotCustomData->Text);
        }

        return $customFieldValue;
    }

    public function renderCreditCardName(?int $paymentMethodId, ?int $creditCardId): string
    {
        if (
            !$creditCardId
            || !in_array($paymentMethodId, Constants\Payment::CC_PAYMENT_METHODS, true)
        ) {
            return '';
        }
        $creditCardName = $this->getCreditCardLoader()->dropFilterActive()->load($creditCardId)?->Name;
        if ($creditCardName) {
            return '(' . ee($creditCardName) . ')';
        }
        return '';
    }

    public function calculateSubtotalColSpan(int $accountId): int
    {
        $sm = $this->getSettingsManager();
        $colSpan = 3;
        $isShowCategory = $sm->get(Constants\Setting::CATEGORY_IN_INVOICE, $accountId);
        if ($isShowCategory) {
            $colSpan++;
        }

        $isMultipleSale = $sm->get(Constants\Setting::MULTIPLE_SALE_INVOICE, $accountId);
        if ($isMultipleSale) {
            $colSpan += 4;
        }

        $isShowQuantity = $sm->get(Constants\Setting::QUANTITY_IN_INVOICE, $accountId);
        if ($isShowQuantity) {
            $colSpan++;
        }
        return $colSpan;
    }

    /**
     * @param string|null $taxCountry null for default country key
     * @param int $geoType
     * @return bool
     */
    public function hasGeoTax(?string $taxCountry, int $geoType): bool
    {
        return StackedTaxGeoTypeConfigProvider::new()->inInvoice($taxCountry, $geoType);
    }

    /**
     * @param string|null $taxCountry null for default country key
     * @param int $geoType
     * @param int $invoiceAccountId
     * @param int $languageId
     * @param string $currencySign
     * @param int|null $amountSource
     * @return string
     */
    public function translateGeoType(
        ?string $taxCountry,
        int $geoType,
        int $invoiceAccountId,
        int $languageId,
        string $currencySign,
        ?int $amountSource = null
    ): string {
        $configProvider = StackedTaxGeoTypeConfigProvider::new();
        if ($amountSource === Constants\StackedTax::AS_HAMMER_PRICE) {
            $translationKey = $configProvider->getPublicInvoiceTaxOnHpTranslationKey($taxCountry, $geoType);
        } elseif ($amountSource === Constants\StackedTax::AS_BUYERS_PREMIUM) {
            $translationKey = $configProvider->getPublicInvoiceTaxOnBpTranslationKey($taxCountry, $geoType);
        } else {
            $translationKey = $configProvider->getPublicInvoiceTaxTranslationKey($taxCountry, $geoType);
        }
        $domain = StackedTaxGeoTypeConfigProvider::new()->getPublicTranslationDomain();
        $langTpl = $this->getTranslator()->translate($translationKey, $domain, $invoiceAccountId, $languageId);
        return sprintf($langTpl, $currencySign);
    }
}
