<?php

namespace Sam\Invoice\Legacy\View\Print;

use DateTime;
use Exception;
use InvalidArgumentException;
use Sam\Address\AddressFormatterCreateTrait;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionInfoUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveCatalogUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Barcode\BarcodeUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\Config\Invoice\ResponsiveInvoiceViewUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Application\Url\UrlAdvisorAwareTrait;
use Sam\Auction\Load\AuctionLoader;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidder\BidderInfo\BidderInfoRenderer;
use Sam\Billing\CreditCard\Load\CreditCardLoader;
use Sam\Billing\Payment\Render\PaymentRendererAwareTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Entity\Model\Invoice\Tax\InvoiceTaxPureCalculator;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceHeaderDataLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceItem\Dto\InvoicedAuctionDto;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Invoice\Common\Render\InvoiceRendererAwareTrait;
use Sam\Invoice\Legacy\Calculate\Basic\LegacyInvoiceCalculatorAwareTrait;
use Sam\Invoice\Legacy\Calculate\Tax\LegacyInvoiceTaxCalculatorCreateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Category\Renderer\LotCategoryRenderer;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRenderer;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Lot\Render\Amount\LotAmountRendererInterface;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settings\TermsAndConditionsManagerAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

class LegacyInvoiceViewPrintRenderer extends CustomizableClass
{
    use AddressFormatterCreateTrait;
    use AuctionLoaderAwareTrait;
    use CurrencyLoaderAwareTrait;
    use DateHelperAwareTrait;
    use InvoiceAdditionalChargeManagerAwareTrait;
    use LegacyInvoiceCalculatorAwareTrait;
    use InvoiceHeaderDataLoaderAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceLoaderAwareTrait;
    use InvoicePaymentManagerAwareTrait;
    use InvoiceRendererAwareTrait;
    use LegacyInvoiceTaxCalculatorCreateTrait;
    use InvoiceUserLoaderAwareTrait;
    use LotAmountRendererFactoryCreateTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotItemLoaderAwareTrait;
    use NumberFormatterAwareTrait;
    use PaymentRendererAwareTrait;
    use SettingsManagerAwareTrait;
    use TermsAndConditionsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UrlAdvisorAwareTrait;
    use UrlBuilderAwareTrait;
    use UserLoaderAwareTrait;

    protected ?LotAmountRendererInterface $lotAmountRenderer = null;

    public static $InvDetailTableStyle = 'width:760px; margin:5px 5px 5px 5px;';
    public static $InvDetailTableTdStyle = 'font-size:12px;font-family:Arial, Tahoma, sans-serif;';
    public static $NumberDateStyle = 'font-size:14px;';
    public static $NumberDateLabelStyle = 'font-weight:bold;';
    public static $SaleNameStyle = 'font-size:14px;';
    public static $SaleNameLabelStyle = 'font-weight:bold;';
    public static $SaleDateStyle = 'font-size:14px;';
    public static $SaleDateLabelStyle = 'font-weight:bold;';
    public static $AddressStyle = 'text-align:right;';
    public static $ActionStyle = 'text-align:right;';
    public static $DividerStyle = 'padding:5px 0;';
    public static $BillingTitleStyle = 'font-weight:bold;';
    public static $ShippingTitleStyle = 'font-weight:bold;';
    public static $InvoiceDatagridStyle = 'border-collapse:collapse;width:100%;border-style:dashed;';
    public static $InvoiceDatagridTrStyle = 'background-color: #fff;';
    public static $InvoiceDatagridTrNoborderStyle = 'border-bottom:0px none #fff;';
    public static $InvoiceDatagridTrAStyle = 'background-color: #AED1FF;';
    public static $InvoiceDatagridTdStyle = 'padding: 3px; font-size:11px;';
    public static $InvoiceDatagridTdNumberStyle = 'text-align:right;';
    public static $InvoiceDatagridTdLabelStyle = 'text-align:right; font-weight:bold;';
    public static $InvoiceDatagridTdNopadStyle = 'padding:0 3px;';
    public static $InvoiceDatagridThStyle = 'background-color: #ccc; padding: 3px; text-align: left; font-size:11px;color: #000;';

    /**
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * TODO: need to refactor this huge method, split its logic to separate class(es) (InvoicePrintRenderer class) methods. In fact, this method alone makes up more than 90% of the entire code of this class.
     * TODO: Do we need to pass and use system account of visiting domain instead of invoice entity account (for translations, look and feel settings)?
     * TODO: Pass $languageId from caller. Take it from cookie context, if it is web page caller?
     *
     * @param int $invoiceId
     * @param int $viewLanguageId
     * @return string
     * @throws Exception
     */
    public function render(int $invoiceId, int $viewLanguageId): string
    {
        $invDetailTableStyle = self::$InvDetailTableStyle;
        $invDetailTableTdStyle = self::$InvDetailTableTdStyle;
        $numberDateStyle = self::$NumberDateStyle;
        $numberDateLabelStyle = self::$NumberDateLabelStyle;
        $saleNameStyle = self::$SaleNameStyle;
        $saleNameLabelStyle = self::$SaleNameLabelStyle;
        $saleDateStyle = self::$SaleDateStyle;
        $saleDateLabelStyle = self::$SaleDateLabelStyle;
        $addressStyle = self::$AddressStyle;
        $actionStyle = self::$ActionStyle;
        $dividerStyle = self::$DividerStyle;
        $billingTitleStyle = self::$BillingTitleStyle;
        $shippingTitleStyle = self::$ShippingTitleStyle;
        $invoiceDatagridStyle = self::$InvoiceDatagridStyle;
        $invoiceDatagridTrStyle = self::$InvoiceDatagridTrStyle;
        $invoiceDatagridTrAStyle = self::$InvoiceDatagridTrAStyle;
        $invoiceDatagridTdStyle = self::$InvoiceDatagridTdStyle;
        $invoiceDatagridTdNumberStyle = self::$InvoiceDatagridTdNumberStyle;
        $invoiceDatagridTdLabelStyle = self::$InvoiceDatagridTdLabelStyle;
        $invoiceDatagridThStyle = self::$InvoiceDatagridThStyle;
        $invoiceDatagridTdNopadStyle = self::$InvoiceDatagridTdNopadStyle;
        $invoiceDatagridTrNoborderStyle = self::$InvoiceDatagridTrNoborderStyle;

        // We allow to view invoices with deleted user (invoice.bidder)
        $this->getUserLoader()->clear();
        $invoice = $this->getInvoiceLoader()->load($invoiceId, true);
        if (!$invoice) {
            $message = "Available invoice not found, when rendering invoice print" . composeSuffix(['i' => $invoiceId]);
            log_error($message);
            return $message;
        }
        $this->getTranslator()
            ->setAccountId($invoice->AccountId)
            ->setLanguageId($viewLanguageId);
        $this->getNumberFormatter()->constructForInvoice($invoice->AccountId);
        $this->lotAmountRenderer = $this->createLotAmountRendererFactory()->createForInvoice($invoice->AccountId);
        $invoiceCalculator = $this->getLegacyInvoiceCalculator();
        $invoiceTaxCalculator = $this->createLegacyInvoiceTaxCalculator();
        $lotCustomFields = $this->createLotCustomFieldLoader()->loadInInvoices(true);

        $sm = $this->getSettingsManager();
        $isShowCategory = (bool)$sm->get(Constants\Setting::CATEGORY_IN_INVOICE, $invoice->AccountId);
        $isMultipleSale = (bool)$sm->get(Constants\Setting::MULTIPLE_SALE_INVOICE, $invoice->AccountId);
        $isShowQuantity = (bool)$sm->get(Constants\Setting::QUANTITY_IN_INVOICE, $invoice->AccountId);
        $isInvoiceItemSeparateTax = (bool)$sm->get(Constants\Setting::INVOICE_ITEM_SEPARATE_TAX, $invoice->AccountId);
        $cashDiscount = $sm->get(Constants\Setting::CASH_DISCOUNT, $invoice->AccountId);
        $isShowSalesTax = $sm->get(Constants\Setting::INVOICE_ITEM_SALES_TAX, $invoice->AccountId);

        $auctionId = $this->getInvoiceItemLoader()->findFirstInvoicedAuctionId($invoiceId, true);
        $auction = AuctionLoader::new()->load($auctionId, true);
        $auctionId = $auction->Id ?? null;
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign($auctionId, true);
        $invoiceAdditionals = $this->getInvoiceAdditionalChargeManager()->loadForInvoice($invoice->Id, true);
        $payments = $this->getInvoicePaymentManager()->loadForInvoice($invoice->Id, true);

        // Generate URL for email and with email way domain detection, SAM-2944
        $invoiceUrl = $this->getUrlBuilder()->build(
            ResponsiveInvoiceViewUrlConfig::new()->forDomainRule(
                $invoice->Id,
                [UrlConfigConstants::OP_ACCOUNT_ID => $invoice->AccountId]
            )
        );
        $invoiceUrlLink = "<a href=\"{$invoiceUrl}\">{$invoice->InvoiceNo}</a>";

        $logoTag = $this->getInvoiceRenderer()->renderLogoTag($invoice);

        $dateLabel = $this->getTranslator()->translate('MYINVOICES_DETAIL_DATECREATED', 'myinvoices');
        $dateSys = $invoice->InvoiceDate ?: new DateTime($invoice->CreatedOn);
        $dateSys = $this->getDateHelper()->convertUtcToSys($dateSys);
        $dateValue = $dateSys->format(Constants\Date::US_DATE);

        $saleDiv = '';

        if (!$isMultipleSale) {
            $invoicedAuctionDtos = $this->getInvoiceItemLoader()->loadInvoicedAuctionDtos($invoice->Id, true);
            if (count($invoicedAuctionDtos) === 1) {
                $langSaleLabel = $this->getTranslator()->translate('MYINVOICES_DETAIL_SALE', 'myinvoices');
                $langSaleDateLabel = $this->getTranslator()->translate('MYINVOICES_DETAIL_SALEDATE', 'myinvoices');
                $invoicedAuctionDto = current($invoicedAuctionDtos);
                $saleNameHtml = $saleDateFormatted = '';
                if ($invoicedAuctionDto->auctionId) {
                    $saleNameHtml = $this->renderSaleName($invoicedAuctionDto);
                    $saleDate = $invoicedAuctionDto->detectSaleDate();
                    $saleDate = $this->getDateHelper()->convertUtcToTzById($saleDate, $invoicedAuctionDto->auctionTimezoneId);
                    $saleDateFormatted = $saleDate
                        ? $this->makeSaleDate($saleDate, $invoicedAuctionDto->auctionType, $invoicedAuctionDto->auctionEventType)
                        : '';
                }
                $saleDiv = <<<HTML
                    <div style="{$saleNameStyle}">
                        <span style="{$saleNameLabelStyle}">{$langSaleLabel}: </span><span class="value">{$saleNameHtml}</span>
                    </div>
                    <div style="{$saleDateStyle}">
                        <span style="{$saleDateLabelStyle}">{$langSaleDateLabel}: </span><span class="value">{$saleDateFormatted}</span>
                    </div>

HTML;
            } elseif (count($invoicedAuctionDtos) > 1) {
                foreach ($invoicedAuctionDtos as $invoicedAuctionDto) {
                    $saleNameHtml = $saleDateFormatted = '';
                    if ($invoicedAuctionDto->auctionId) {
                        $saleNameHtml = $this->renderSaleName($invoicedAuctionDto);
                        $saleDate = $invoicedAuctionDto->detectSaleDate();
                        $saleDate = $this->getDateHelper()->convertUtcToTzById($saleDate, $invoicedAuctionDto->auctionTimezoneId);
                        $saleDateFormatted = $this->makeSaleDate($saleDate, $invoicedAuctionDto->auctionType, $invoicedAuctionDto->auctionEventType);
                    }
                    $saleDiv .= <<<HTML
                            <div style="{$saleNameStyle}">
                                <span>{$saleNameHtml}</span>
                                <span>{$saleDateFormatted}</span>
                            </div>

HTML;
                }
            }
        }

        $invoiceAddress = '';
        $rowHeader = $this->getInvoiceHeaderDataLoader()->load($invoice->Id, true);
        if ($rowHeader) {
            $invoiceAddress = $this->createAddressFormatter()->format(
                $rowHeader['country'],
                $rowHeader['state'],
                $rowHeader['city'],
                $rowHeader['zip'],
                $rowHeader['address']
            );
        }
        if (!$invoiceAddress) {
            $invoiceTerm = $this->getTermsAndConditionsManager()->load(
                $invoice->AccountId,
                Constants\TermsAndConditions::INVOICE,
                true
            );
            if ($invoiceTerm) {
                $invoiceAddress = $invoiceTerm->Content;
            } else {
                log_error(
                    "Available invoice Terms and Conditions record not found for rendering invoice print"
                    . composeSuffix(['acc' => $invoice->AccountId, 'key' => Constants\TermsAndConditions::INVOICE])
                );
            }
        }

        $statusLabel = $this->getTranslator()->translate('MYINVOICES_DETAIL_STATUS', 'myinvoices');
        $statusValue = Constants\Invoice::$invoiceStatusNames[$invoice->InvoiceStatusId];

        $auctionId = $auction->Id ?? null;
        $invoiceWinningUser = $this->getUserLoader()->load($invoice->BidderId, true);
        if (!$invoiceWinningUser) {
            $message = "Available invoice winning user not found, when building print report"
                . composeSuffix(['u' => $invoice->BidderId, 'i' => $invoice->Id]);
            log_error($message);
            return $message;
        }
        $emailHtml = $invoiceWinningUser->Email;

        $bidderHtml = '';
        $billingHtml = '';
        $langShipping = '';
        $shippingInfoHtml = '';
        if (!$isMultipleSale) {
            $bidderHtml = BidderInfoRenderer::new()
                ->enableFloorBlank(true)
                ->enableReadOnlyDb(true)
                ->enableTranslation(true)
                ->setAuctionId($auctionId)
                ->setLanguageId($viewLanguageId)
                ->setSystemAccountId($invoice->AccountId) // TODO: replace with system account of visiting domain for translations?
                ->setUserId($invoice->BidderId)
                ->render();
            if (is_numeric($bidderHtml)) {
                $bidderHtml = '<span class="bidder-num">Bidder #' . $bidderHtml . '</span><br />';
            } else {
                $bidderHtml = '<span class="bidder-num">' . $bidderHtml . '</span><br />';
            }
        }
        $invoiceBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreate($invoice->Id, true);
        if ($invoiceBilling->CompanyName !== '') {
            $billingHtml .= '<span class="company">' . ee($invoiceBilling->CompanyName) . '</span><br />';
        }
        $fullName = UserPureRenderer::new()->renderFullName($invoiceBilling);
        if ($fullName !== '') {
            $billingHtml .= '<span class="name"> ' . ee($fullName) . '</span><br />';
        }
        if (
            $invoiceBilling->Address !== ''
            || $invoiceBilling->Address2 !== ''
        ) {
            $billingHtml .= '<span class="address">';
            if ($invoiceBilling->Address !== '') {
                $billingHtml .= ee($invoiceBilling->Address);
            }
            if ($invoiceBilling->Address !== '' && $invoiceBilling->Address2 !== '') {
                $billingHtml .= '<br />';
            }
            if ($invoiceBilling->Address2 !== '') {
                $billingHtml .= ee($invoiceBilling->Address2);
            }
            if (
                (
                    $invoiceBilling->Address !== ''
                    || $invoiceBilling->Address2 !== ''
                )
                && $invoiceBilling->Address3 !== ''
            ) {
                $billingHtml .= '<br />';
            }
            if ($invoiceBilling->Address3 !== '') {
                $billingHtml .= ee($invoiceBilling->Address3);
            }
            $billingHtml .= '</span><br />';
        }
        if ($invoiceBilling->City !== '') {
            $billingHtml .= '<span class="city">' . ee($invoiceBilling->City) . '</span>, ';
        }
        $state = AddressRenderer::new()->stateName($invoiceBilling->State, $invoiceBilling->Country);
        if ($state !== '') {
            $billingHtml .= '<span class="state">' . ee($state) . '</span> ';
        }
        if ($invoiceBilling->Zip !== '') {
            $billingHtml .= '<span class="zip">' . ee($invoiceBilling->Zip) . '</span>';
        }

        $country = AddressRenderer::new()->countryName($invoiceBilling->Country);
        if ($country !== '') {
            $billingHtml .= '<br /><span class="country">' . ee($country) . '</span> ';
        }
        if ($invoiceBilling->Phone !== '') {
            $billingHtml .= '<br /><span class="phone">' . ee($invoiceBilling->Phone) . '</span>';
        }
        if ($invoiceBilling->Fax !== '') {
            $billingHtml .= '<br /><span class="fax">' . ee($invoiceBilling->Fax) . '</span>';
        }

        if ($emailHtml !== '') {
            $emailHtml = '<br /><span class="email">' . ee($emailHtml) . '</span>';
        }

        if ($billingHtml === '') {
            $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($invoice->BidderId, true);
            $fullName = UserPureRenderer::new()->renderFullName($userInfo);
            if ($fullName !== '') {
                $billingHtml .= '<span class="cust-name">' . ee($fullName) . '</span>';
            }
        }

        $billingInfoHtml = <<<HTML
            <span style="{$billingTitleStyle}">Billing Information: </span><br />
            {$bidderHtml}
            {$billingHtml}
            {$emailHtml}

HTML;

        $invoiceShipping = $this->getInvoiceUserLoader()->loadInvoiceUserShippingOrCreate($invoice->Id, true);
        if ($invoiceShipping->CompanyName !== '') {
            $langShipping .= '<span class="company">' . ee($invoiceShipping->CompanyName) . '</span><br />';
        }
        $fullName = UserPureRenderer::new()->renderFullName($invoiceShipping);
        if ($fullName !== '') {
            $langShipping .= '<span class="name">' . ee($fullName) . '</span><br />';
        }
        if (
            $invoiceShipping->Address !== ''
            || $invoiceShipping->Address2 !== ''
        ) {
            $langShipping .= '<span class="address">';
            if ($invoiceShipping->Address !== '') {
                $langShipping .= ee($invoiceShipping->Address);
            }
            if (
                $invoiceShipping->Address !== ''
                && $invoiceShipping->Address2 !== ''
            ) {
                $langShipping .= '<br />';
            }
            if ($invoiceShipping->Address2 !== '') {
                $langShipping .= ee($invoiceShipping->Address2);
            }
            if (
                (
                    $invoiceShipping->Address !== ''
                    || $invoiceShipping->Address2 !== ''
                )
                && $invoiceShipping->Address3 !== ''
            ) {
                $langShipping .= '<br />';
            }
            if ($invoiceShipping->Address3 !== '') {
                $langShipping .= ee($invoiceShipping->Address3);
            }
            $langShipping .= '</span><br />';
        }
        if ($invoiceShipping->City !== '') {
            $langShipping .= '<span class="city">' . ee($invoiceShipping->City) . '</span>, ';
        }
        $state = AddressRenderer::new()->stateName($invoiceShipping->State, $invoiceShipping->Country);
        if ($state !== '') {
            $langShipping .= '<span class="state">' . ee($state) . '</span> ';
        }
        if ($invoiceShipping->Zip !== '') {
            $langShipping .= '<span class="zip">' . ee($invoiceShipping->Zip) . '</span>';
        }
        $country = AddressRenderer::new()->countryName($invoiceShipping->Country);
        if ($country !== '') {
            $langShipping .= '<br /><span class="country">' . ee($country) . '</span> ';
        }
        if ($invoiceShipping->Phone !== '') {
            $langShipping .= '<br /><span class="phone">' . ee($invoiceShipping->Phone) . '</span>';
        }
        if ($invoiceShipping->Fax !== '') {
            $langShipping .= '<br /><span class="fax">' . ee($invoiceShipping->Fax) . '</span>';
        }

        if ($langShipping !== '') {
            $shippingInfoHtml = <<<HTML
            <span style="$shippingTitleStyle">Shipping Information: </span><br />
            {$langShipping}

HTML;
        }

        $invoiceItemTr = '';
        $dtos = $this->getInvoiceItemLoader()->loadDtos($invoice->Id, true);
        if ($dtos) {
            $lotCategoryHeader = '';
            if ($isShowCategory) {
                $categoryHdr = $this->getTranslator()->translate('MYINVOICES_DETAIL_CATEGORY', 'myinvoices');
                $lotCategoryHeader = <<<HTML
                <th style="{$invoiceDatagridThStyle} width:110px;">$categoryHdr</th>
HTML;
            }
            $lotCustomFieldHeader = '';
            foreach ($lotCustomFields as $lotCustomField) {
                $lotCustomFieldName = ee($lotCustomField->Name);
                $lotCustomFieldHeader .= <<<HTML
                <th style="{$invoiceDatagridThStyle} width:30px;">{$lotCustomFieldName}</th>
HTML;
            }

            $quantityHeader = '';
            if ($isShowQuantity) {
                $langQuantityHdr = $this->getTranslator()
                    ->translate('MYINVOICES_DETAIL_QUANTITY', 'myinvoices');
                $quantityHeader = <<<HTML
                <th style="{$invoiceDatagridThStyle} width:30px;">$langQuantityHdr</th>
HTML;
            }
            $saleHeader = '';
            $saleDateHeader = '';
            $lotHeader = $this->getTranslator()
                ->translate('MYINVOICES_DETAIL_LOTNUM', 'myinvoices');
            if ($isMultipleSale) {
                $saleHdr = $this->getTranslator()
                    ->translate('MYINVOICES_DETAIL_SALE', 'myinvoices');
                $saleDateHdr = $this->getTranslator()
                    ->translate('MYINVOICES_DETAIL_SALEDATE', 'myinvoices');
                $saleHeader = <<<HTML
                <th style="{$invoiceDatagridThStyle} width:110px;">$saleHdr</th>
HTML;
                $saleDateHeader = <<<HTML
                <th style="{$invoiceDatagridThStyle} width:80px;">$saleDateHdr</th>
HTML;
                $lotHeader = $this->getTranslator()
                    ->translate('MYINVOICES_DETAIL_SALENUM', 'myinvoices');
                $lotHeader .= ' / ' . $lotHeader;
            }

            $itemNumHeader = $this->getTranslator()
                ->translate('MYINVOICES_DETAIL_ITEMNUM', 'myinvoices');
            $itemNameHeader = $this->getTranslator()
                ->translate('MYINVOICES_DETAIL_ITEMNAME', 'myinvoices');
            $hammerHeader = $this->getTranslator()
                ->translate('MYINVOICES_DETAIL_HAMMER', 'myinvoices');
            $buyersPremiumHeader = $this->getTranslator()
                ->translate('MYINVOICES_DETAIL_BUYERSPREMIUM', 'myinvoices');
            $subtotalHeader = $this->getTranslator()
                ->translate('MYINVOICES_DETAIL_SUBTOTAL', 'myinvoices');

            $salesTaxHeader = '';
            if (
                $isShowSalesTax
                && !$isInvoiceItemSeparateTax
            ) {
                $salesTaxHeaderLang = $this->getTranslator()->translate('MYINVOICES_DETAIL_SALESTAX', 'myinvoices');
                $salesTaxHeader = <<<HTML
                <th style="{$invoiceDatagridThStyle}width:110px;" class="item-sales-tax">$salesTaxHeaderLang</th>
HTML;
            }

            $taxOnServicesHeader = '';
            if ($isInvoiceItemSeparateTax) {
                $taxOnServicesHeaderLang = $this->getTranslator()->translate('TAX_ON_SERVICES', 'myinvoices');
                $taxOnServicesHeader = <<<HTML
<th style="{$invoiceDatagridThStyle} width:130px;">$taxOnServicesHeaderLang</th>
HTML;
            }

            $taxOnGoodsHeader = '';
            if ($isInvoiceItemSeparateTax) {
                $taxOnGoodsHeaderLang = $this->getTranslator()->translate('TAX_ON_GOODS', 'myinvoices');
                $taxOnGoodsHeader = <<<HTML
<th style="{$invoiceDatagridThStyle} width:130px;">$taxOnGoodsHeaderLang</th>
HTML;
            }

            $invoiceItemTr = <<<HTML
<div id="c2_ctl" style="display:inline;"><table id="c2" style="{$invoiceDatagridStyle}" class="borderOne">
<thead>
  <tr >
    $saleHeader
    $saleDateHeader
    <th style="{$invoiceDatagridThStyle} width:50px;">$lotHeader</th>
    <th style="{$invoiceDatagridThStyle}">$itemNumHeader</th>
    <th style="{$invoiceDatagridThStyle} width:200px;">$itemNameHeader</th>
    $lotCategoryHeader
    $lotCustomFieldHeader
    $quantityHeader
    <th style="{$invoiceDatagridThStyle} width:130px;">$hammerHeader</th>
    $salesTaxHeader
    $taxOnGoodsHeader
    <th style="{$invoiceDatagridThStyle} width:130px;">$buyersPremiumHeader</th>
    $taxOnServicesHeader
    <th style="{$invoiceDatagridThStyle} width:130px;">$subtotalHeader</th>
  </tr>
</thead>
<tbody>

HTML;

            $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
            $rowIndex = 0;
            foreach ($dtos as $dto) {
                $itemNo = $dto->makeItemNo();
                $lotName = $dto->makeLotName();

                $linkTpl = '<a href="%s" target="_blank">%s</a>';

                /**
                 * Check if the lot item is not removed from auction lot table.
                 * if not removed clicking this link will redirect to item details page
                 * if removed clicking this link will redirect to catalog page
                 */
                if ($auctionLotStatusPureChecker->isAmongAvailableLotStatuses($dto->lotStatusId)) {
                    $url = $lotDetailsUrl = $this->getUrlBuilder()->build(
                        ResponsiveLotDetailsUrlConfig::new()->forDomainRule(
                            $dto->lotItemId,
                            $dto->auctionId,
                            null,
                            [UrlConfigConstants::OP_ACCOUNT_ID => $dto->accountId]
                        )
                    );
                    $lotNo = $dto->makeLotNo();
                    $lotNoLink = sprintf($linkTpl, $lotDetailsUrl, $lotNo);
                } else {
                    $url = $this->getUrlBuilder()->build(
                        ResponsiveCatalogUrlConfig::new()->forDomainRule(
                            $dto->auctionId,
                            null,
                            [UrlConfigConstants::OP_ACCOUNT_ID => $dto->accountId]
                        )
                    );
                    $lotNoLink = '';
                }
                $lotNameLink = sprintf($linkTpl, $url, ee($lotName));

                $hpFormatted = $this->getNumberFormatter()->formatMoney($dto->hammerPrice);
                $bpFormatted = $this->getNumberFormatter()->formatMoney($dto->buyersPremium);
                $subTotalFormatted = $this->getNumberFormatter()->formatMoney($dto->calcSubTotal());
                $taxOnGoodsColumn = '';
                $taxOnServicesColumn = '';

                $salesTaxColumn = '';

                if (
                    $isShowSalesTax
                    && !$isInvoiceItemSeparateTax
                ) {
                    $salesTaxAmount = InvoiceTaxPureCalculator::new()->calcSalesTaxApplied(
                        $dto->hammerPrice,
                        $dto->buyersPremium,
                        $dto->salesTaxPercent,
                        $dto->taxApplication
                    );
                    $salesTaxAmount = $this->getNumberFormatter()->formatMoney($salesTaxAmount);
                    $salesTaxColumn = <<<HTML
            <td style="{$invoiceDatagridTdStyle}" class="item-sales-tax number">{$currencySign}{$salesTaxAmount}</td>
HTML;
                }

                if ($isInvoiceItemSeparateTax) {
                    $taxOnServices = InvoiceTaxPureCalculator::new()->calcSalesTaxAppliedOnServices(
                        $dto->buyersPremium,
                        $dto->salesTaxPercent,
                        $dto->taxApplication
                    );
                    $taxOnServices = $this->getNumberFormatter()->formatMoney($taxOnServices);
                    $taxOnServicesColumn = <<<HTML
            <td style="$invoiceDatagridTdStyle" class="item-sales-tax number">{$currencySign}$taxOnServices</td>
HTML;
                    $taxOnGoods = InvoiceTaxPureCalculator::new()->calcSalesTaxAppliedOnGoods(
                        $dto->hammerPrice,
                        $dto->salesTaxPercent,
                        $dto->taxApplication
                    );
                    $taxOnGoods = $this->getNumberFormatter()->formatMoney($taxOnGoods);
                    $taxOnGoodsColumn = <<<HTML
            <td style="$invoiceDatagridTdStyle" class="item-sales-tax number">{$currencySign}$taxOnGoods</td>
HTML;
                }

                $categoryColumn = '';
                if ($isShowCategory) {
                    $categoriesText = LotCategoryRenderer::new()->getCategoriesText($dto->lotItemId);
                    $categoryColumn = <<<HTML
            <td style="$invoiceDatagridTdStyle">{$categoriesText}</td>
HTML;
                }

                $customFieldColumn = '';
                foreach ($lotCustomFields as $lotCustomField) {
                    $lotCustomData = $this->createLotCustomDataLoader()->load($lotCustomField->Id, $dto->lotItemId, true);

                    $custData = '';
                    if (
                        $lotCustomData
                        && $lotCustomField->isNumeric()
                    ) {
                        if ($lotCustomData->Numeric === null) {
                            $custData = '';
                        } else {
                            if ($lotCustomField->Type === Constants\CustomField::TYPE_DATE) {
                                $dateSys = $this->getDateHelper()->convertUtcToSysByTimestamp($lotCustomData->Numeric);
                                $custData = $this->getDateHelper()->formattedDate($dateSys);
                            } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_DECIMAL) {
                                $precision = (int)$lotCustomField->Parameters;
                                $value = $lotCustomData->calcDecimalValue($precision);
                                $custData = $this->getNumberFormatter()->format($value, $precision);
                            } else {
                                $custData = $lotCustomData->Numeric;
                            }
                        }
                    } elseif ($lotCustomData) { //text
                        if (
                            $lotCustomField->Type === Constants\CustomField::TYPE_TEXT
                            && $lotCustomField->Barcode
                            && $lotCustomData->Text !== ''
                        ) { // Barcode
                            $url = $this->getUrlBuilder()->build(
                                BarcodeUrlConfig::new()->forWeb($lotCustomData->Text, $lotCustomField->BarcodeType)
                            );
                            $custData = "<img src=\"{$url}\" alt=\"\"/>";
                        } else {
                            $custData = $lotCustomData->Text;
                        }
                    }

                    $customFieldColumn .= <<<HTML
            <td style="$invoiceDatagridTdStyle">{$custData}</td>
HTML;
                }

                $quantityColumn = '';
                if ($isShowQuantity) {
                    $quantity = Floating::gt($dto->quantity, 0, $dto->quantityScale)
                        ? $this->lotAmountRenderer->makeQuantity($dto->quantity, $dto->quantityScale)
                        : '-';
                    $quantityColumn = <<<HTML
            <td style="$invoiceDatagridTdStyle">{$quantity}</td>
HTML;
                }
                $saleColumn = '';
                $saleDateColumn = '';
                $saleNo = '';
                if ($isMultipleSale) {
                    $saleNameHtml = $saleDateHtml = '';
                    if ($dto->auctionId) {
                        $saleNo = $dto->makeSaleNo();
                        $saleNameHtml = ee($dto->makeAuctionName());
                        $saleDate = $dto->detectAuctionDate();
                        $saleDate = $this->getDateHelper()->convertUtcToTzById($saleDate, $dto->auctionTimezoneId);
                        $saleDateHtml = $this->makeSaleDate($saleDate, $dto->auctionType, $dto->eventType);
                    }

                    $saleColumn = <<<HTML
            <td style="{$invoiceDatagridTdStyle}">{$saleNameHtml}</td>
HTML;
                    $saleDateColumn = <<<HTML
            <td style="{$invoiceDatagridTdStyle}">{$saleDateHtml}</td>
HTML;
                }

                $rowClass = ($rowIndex % 2 === 0) ? $invoiceDatagridTrStyle : $invoiceDatagridTrAStyle;

                $invoiceItemTr .= <<<HTML
<tr id="c2row{$rowIndex}" style="{$rowClass}">
    {$saleColumn}
    {$saleDateColumn}
    <td style="{$invoiceDatagridTdStyle}">{$saleNo} / {$lotNoLink}</td>
    <td style="{$invoiceDatagridTdStyle}">{$itemNo}</td>
    <td style="{$invoiceDatagridTdStyle}">{$lotNameLink}</td>
    {$categoryColumn}
    {$customFieldColumn}
    {$quantityColumn}
    <td style="{$invoiceDatagridTdStyle}{$invoiceDatagridTdNumberStyle}">{$currencySign}{$hpFormatted}</td>
    {$salesTaxColumn}
    {$taxOnGoodsColumn}
    <td style="{$invoiceDatagridTdStyle}{$invoiceDatagridTdNumberStyle}">{$currencySign}{$bpFormatted}</td>
    {$taxOnServicesColumn}
    <td style="{$invoiceDatagridTdStyle}{$invoiceDatagridTdNumberStyle}">{$currencySign}{$subTotalFormatted}</td>
</tr>

HTML;

                $rowIndex++;
            } // end foreach

            $rowSpan = 6;
            $colSpan = 4;
            if ($isInvoiceItemSeparateTax) {
                $rowSpan = 7;
                $colSpan = 5;
            }

            $paymentCount = count($payments);
            if ($paymentCount > 0) {
                $rowSpan += $paymentCount + 1;
            }

            if ($isShowCategory) {
                $colSpan++;
            }
            if ($isMultipleSale) {
                $colSpan += 2;
            }
            if ($isShowQuantity) {
                $colSpan++;
            }

            if ($isShowSalesTax) {
                $colSpan++;
            }

            $colSpan += count($lotCustomFields);
            $rowSpanHtml = '<td style="' . $invoiceDatagridTdStyle . '" colspan="' . $colSpan . '" rowspan="' . $rowSpan . '">&nbsp;</td>';

            if (
                Floating::gt($cashDiscount, 0)
                && $invoice->CashDiscount
            ) {
                /* Cash discount
                 *
                 * */

                $rowSpan++;
                $rowSpanHtml = '';
                $cashDiscountAmount = $invoiceCalculator
                    ->calcCashDiscount($invoice->Id, $invoice->CashDiscount);
                $cashDiscountAmountFormatted = $this->getNumberFormatter()->formatMoney($cashDiscountAmount);
                $discountCash = 'Cash discount(' . $cashDiscount . '%)';

                $invoiceItemTr .= <<<HTML
    <tr id="c2row{$rowIndex}">
        <td style="$invoiceDatagridTdStyle" colspan="$colSpan" rowspan="$rowSpan">&nbsp;</td>
        <td style="$invoiceDatagridTdStyle$invoiceDatagridTdLabelStyle">{$discountCash}:</td>
        <td style="$invoiceDatagridTdStyle$invoiceDatagridTdNumberStyle">{$currencySign}{$cashDiscountAmountFormatted}</td>
    </tr>

HTML;

                $rowIndex++;
            }

            /* Subtotal total
             *
             * */
            $subTotalFormatted = $invoiceCalculator->calcSubTotal($invoice->Id);
            $subTotalFormatted = $this->getNumberFormatter()->formatMoney($subTotalFormatted);
            $langSubTotal = $this->getTranslator()->translate('MYINVOICES_DETAIL_SUBTOTAL', 'myinvoices');

            $invoiceItemTr .= <<<HTML
<tr id="c2row{$rowIndex}">
    {$rowSpanHtml}
    <td style="$invoiceDatagridTdStyle$invoiceDatagridTdLabelStyle">{$langSubTotal}:</td>
    <td style="$invoiceDatagridTdStyle$invoiceDatagridTdNumberStyle">{$currencySign}{$subTotalFormatted}</td>
</tr>

HTML;
            $rowIndex++;

            /* Shipping charge
             *
             * */
            $shipping = $invoice->Shipping;
            $shipping = $this->getNumberFormatter()->formatMoney($shipping);
            $langShipping = $this->getTranslator()->translate('MYINVOICES_DETAIL_SHIPPING', 'myinvoices');

            $invoiceItemTr .= <<<HTML
<tr id="c2row{$rowIndex}" valign="bottom">
    <td style="$invoiceDatagridTdStyle$invoiceDatagridTdLabelStyle">{$langShipping}:</td>
    <td style="$invoiceDatagridTdStyle$invoiceDatagridTdNumberStyle">{$currencySign}{$shipping}</td>
</tr>

HTML;
            $rowIndex++;

            /* Extra charges
             *
             * */

            $extraCharges = $this->getTranslator()->translate('MYINVOICES_DETAIL_EXTRACHARGES', 'myinvoices');
            $chargeName = '';
            $chargeAmount = '';
            foreach ($invoiceAdditionals as $invoiceAdditional) {
                $chargeName .= '<span class="charge-name">' . $invoiceAdditional->Name . '</span><br />';
                $charge = $invoiceAdditional->Amount;
                $charge = $this->getNumberFormatter()->formatMoney($charge);
                $chargeAmount .= '<span class="charge-amount">' . $currencySign . $charge . '</span><br />';
            }

            if (count($invoiceAdditionals) === 0) { // if no charges
                $charge = 0;
                $charge = $this->getNumberFormatter()->formatMoney($charge);
                $chargeAmount .= '<span class="charge-amount">' . $currencySign . $charge . '</span><br />';
            }

            $invoiceItemTr .= <<<HTML
<tr id="c2row{$rowIndex}" valign="bottom">
    <td style="$invoiceDatagridTdStyle$invoiceDatagridTdLabelStyle">{$extraCharges}[{$currencySign}]: <br />
    $chargeName </td>
    <td style="$invoiceDatagridTdStyle$invoiceDatagridTdNumberStyle">{$chargeAmount}</td>
</tr>

HTML;
            $rowIndex++;

            if (!$isInvoiceItemSeparateTax) {
                /* Salestax
                 *
                 * */
                $salesTaxAmount = $invoiceTaxCalculator->calcTotalSalesTaxApplied($invoice->Id);
                $salesTaxAmountFormatted = $this->getNumberFormatter()->formatMoney($salesTaxAmount);
                //$strSalesTax = 'Sale tax(' . $this->getNumberFormatter()->format($invoice->SalesTax) . '%)';
                $langSalesTax = $this->getTranslator()->translate('MYINVOICES_DETAIL_SALESTAX', 'myinvoices');
                $invoiceItemTr .= <<<HTML
<tr id="c2row{$rowIndex}" valign="bottom">
    <td style="$invoiceDatagridTdStyle$invoiceDatagridTdLabelStyle">{$langSalesTax}:</td>
    <td style="$invoiceDatagridTdStyle$invoiceDatagridTdNumberStyle">{$currencySign}{$salesTaxAmountFormatted}</td>
</tr>

HTML;
                $rowIndex++;
            }

            /* Tax on Goods
            *
            * */
            if ($isInvoiceItemSeparateTax) {
                $taxOnGoods = $invoiceTaxCalculator->calcTotalTaxOnGoods($invoice->Id);
                $taxOnGoodsFormatted = $this->getNumberFormatter()->formatMoney($taxOnGoods);
                $langTaxOnGoods = $this->getTranslator()->translate('TAX_ON_GOODS', 'myinvoices');
                $invoiceItemTr .= <<<HTML
    <tr id="c2row{$rowIndex}" valign="bottom">
        <td style="$invoiceDatagridTdStyle$invoiceDatagridTdLabelStyle">{$langTaxOnGoods}:</td>
        <td style="$invoiceDatagridTdStyle$invoiceDatagridTdNumberStyle">{$currencySign}{$taxOnGoodsFormatted}</td>
    </tr>

HTML;
                $rowIndex++;
            }

            /* Tax on Services
             *
             * */
            if ($isInvoiceItemSeparateTax) {
                $taxOnServices = $invoiceTaxCalculator->calcTotalTaxOnServices($invoice->Id);
                $taxOnServicesFormatted = $this->getNumberFormatter()->formatMoney($taxOnServices);
                $langTaxOnServices = $this->getTranslator()->translate('TAX_ON_SERVICES', 'myinvoices');
                $invoiceItemTr .= <<<HTML
    <tr id="c2row{$rowIndex}" valign="bottom">
        <td style="$invoiceDatagridTdStyle$invoiceDatagridTdLabelStyle">{$langTaxOnServices}:</td>
        <td style="$invoiceDatagridTdStyle$invoiceDatagridTdNumberStyle">{$currencySign}{$taxOnServicesFormatted}</td>
    </tr>

HTML;
                $rowIndex++;
            }

            /* Total
             *
             * */

            $grandTotal = $invoiceCalculator->calcGrandTotal($invoice->Id);
            $grandTotalFormatted = $this->getNumberFormatter()->formatMoney($grandTotal);
            $langTotal = $this->getTranslator()->translate('MYINVOICES_DETAIL_TOTAL', 'myinvoices');
            $invoiceItemTr .= <<<HTML
<tr id="c2row{$rowIndex}" valign="bottom" style="border-top:1px solid;">
    <td style="$invoiceDatagridTdStyle$invoiceDatagridTdLabelStyle">{$langTotal}:</td>
    <td style="$invoiceDatagridTdStyle$invoiceDatagridTdNumberStyle">{$currencySign}{$grandTotalFormatted}</td>
</tr>

HTML;
            $rowIndex++;

            /* Payments made
             *
             * */

            $langPaymentsMade = $this->getTranslator()->translate('MYINVOICES_DETAIL_PAYMENTSMADE', 'myinvoices');

            if (count($payments) > 0) { // Display only if there is a payment made

                $invoiceItemTr .= <<<HTML
    <tr id="c2row{$rowIndex}" valign="bottom" style="$invoiceDatagridTrNoborderStyle">
        <td style="$invoiceDatagridTdStyle$invoiceDatagridTdLabelStyle">{$langPaymentsMade}:</td>
        <td style="$invoiceDatagridTdStyle$invoiceDatagridTdNumberStyle"></td>
    </tr>

HTML;
                $rowIndex++;

                foreach ($payments as $payment) {
                    $paymentNote = '';
                    $creditCard = '';

                    if ($payment->CreditCardId) {
                        $creditCardSeparator = " - ";
                        $creditCard = CreditCardLoader::new()->load($payment->CreditCardId, true);
                        if ($creditCard !== null) {
                            $creditCard = $creditCardSeparator . $creditCard->Name;
                        } else {
                            $creditCard = $creditCardSeparator;
                        }
                    }
                    $paymentMethodName = $this->getPaymentRenderer()->makePaymentMethodTranslated($payment->PaymentMethodId);
                    $paymentMethod = '<span class="payment-method">' . $paymentMethodName . $creditCard . '</span>';
                    if ($payment->Note) {
                        $paymentNote = '(<span class="payment-note">' . $payment->Note . '</span>)';
                    }
                    $payment = $payment->Amount;
                    $payment = $this->getNumberFormatter()->formatMoney($payment);
                    $paymentAmount = '<span class="payment-amount">' . $currencySign . $payment . '</span>';

                    $invoiceItemTr .= <<<HTML
    <tr id="c2row{$rowIndex}" valign="bottom" style="$invoiceDatagridTrNoborderStyle">
        <td style="$invoiceDatagridTdStyle$invoiceDatagridTdLabelStyle$invoiceDatagridTdNopadStyle">{$paymentMethod}{$paymentNote}</td>
        <td style="$invoiceDatagridTdStyle$invoiceDatagridTdNumberStyle$invoiceDatagridTdNopadStyle">{$paymentAmount}</td>
    </tr>

HTML;

                    $rowIndex++;
                }
            }

            /*
             * Balance
             * */

            $balanceDue = $invoiceCalculator->calcRoundedBalanceDue($invoice->Id);
            $balanceDueFormatted = $this->getNumberFormatter()->formatMoney($balanceDue);
            $langBalance = $this->getTranslator()->translate('MYINVOICES_DETAIL_BALANCE', 'myinvoices');
            $invoiceItemTr .= <<<HTML
<tr id="c2row{$rowIndex}" valign="bottom" style="border-top:1px solid;">
    <td style="$invoiceDatagridTdStyle$invoiceDatagridTdLabelStyle">{$langBalance}:</td>
    <td style="$invoiceDatagridTdStyle$invoiceDatagridTdNumberStyle">{$currencySign}{$balanceDueFormatted}</td>
</tr>

HTML;

            $invoiceItemTr .= <<<HTML
</tbody>
</table></div>

HTML;
        }

        $note = $invoice->Note;
        $note = nl2br($note);

        $output = <<<HTML


<div id="main">
    <div id="print-area">

        <table style="{$invDetailTableStyle}">
            <thead>
                <tr valign="top">
                    <td width="50%" style="{$invDetailTableTdStyle}">
                        {$logoTag}
                        <div style="{$numberDateStyle}">
                            <span style="{$numberDateLabelStyle}">Invoice #</span><span>{$invoiceUrlLink}</span>
                            - <span style="{$numberDateLabelStyle}">{$dateLabel}:</span> <span>{$dateValue}</span>
                        </div>
                        {$saleDiv}
                        <div class="clear"></div>
                    </td>
                    <td style="{$invDetailTableTdStyle}">
                        <div style="{$addressStyle}">{$invoiceAddress}</div>
                        <div style="{$actionStyle}">
                        {$statusLabel} : {$statusValue}<br />
                        </div>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="{$invDetailTableTdStyle}" colspan="2"><div style="{$dividerStyle}"><hr /></div></td>
                </tr>
                <tr valign="top">
                    <td style="{$invDetailTableTdStyle}">
                    {$billingInfoHtml}
                    </td>
                    <td style="{$invDetailTableTdStyle}">
                    {$shippingInfoHtml}
                    </td>
                </tr>
                <tr>
                    <td style="{$invDetailTableTdStyle}" colspan="2"><div style="{$dividerStyle}"><hr /></div></td>
                </tr>
                <tr>
                    <td style="{$invDetailTableTdStyle}" colspan="2">
                    <div class="invoice-item">
                    {$invoiceItemTr}
                    </div>
                    </td>
                </tr>
                <tr>
                    <td style="{$invDetailTableTdStyle}" colspan="2">
                    <div class="invoice-note">
                    {$note}
                    </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</div>


HTML;
        return $output;
    }

    /**
     * @param InvoicedAuctionDto $invoicedAuctionDto
     * @return string
     */
    protected function renderSaleName(InvoicedAuctionDto $invoicedAuctionDto): string
    {
        $auctionInfoUrl = $this->getUrlBuilder()->build(
            ResponsiveAuctionInfoUrlConfig::new()->forDomainRule(
                $invoicedAuctionDto->auctionId,
                null,
                [
                    UrlConfigConstants::OP_ACCOUNT_ID => $invoicedAuctionDto->accountId,
                    UrlConfigConstants::OP_AUCTION_INFO_LINK => $invoicedAuctionDto->auctionInfoLink
                ]
            )
        );
        $name = ee($invoicedAuctionDto->makeAuctionName());
        $saleNo = $invoicedAuctionDto->makeSaleNo();
        $link = "<a href=\"{$auctionInfoUrl}\" target=\"_blank\">{$name} ({$saleNo})</a>";
        return $link;
    }

    /**
     * @param DateTime|null $date null when timed auction with ongoing event type
     * @param string $auctionType
     * @param int|null $eventType
     * @return string
     */
    protected function makeSaleDate(?DateTime $date, string $auctionType, ?int $eventType): string
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isTimedOngoing($auctionType, $eventType)) {
            return Constants\Auction::$eventTypeFullNames[Constants\Auction::ET_ONGOING];
        }

        if (!$date) {
            $message = "Sale date unknown" . composeSuffix(['auction type' => $auctionType, 'event type' => $eventType]);
            log_errorBackTrace($message);
            throw new InvalidArgumentException($message);
        }

        $output = $date->format('F j')
            . '<sup>' . $date->format('S') . '</sup>, '
            . $date->format('Y');
        return $output;
    }
}
