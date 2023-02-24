<?php
/**
 * Common rendering for Printable View invoices based on responsive Printable View
 * to be used on admin/legacy/responsive
 *
 * based tightly on functions of /m/views/drafts/my_invoice_detail_panel.php and my_invoice_detail_panel.tpl.php
 */

namespace Sam\Invoice\Legacy\View\Print;

use DateTime;
use Invoice;
use InvoiceAdditional;
use LotItemCustField;
use Payment;
use Sam\Address\AddressFormatterCreateTrait;
use Sam\Application\Url\Build\Config\Invoice\AnySingleInvoiceUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidder\BidderInfo\BidderInfoRendererAwareTrait;
use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Billing\Payment\Render\PaymentRendererAwareTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\Invoice\Tax\InvoiceTaxPureCalculator;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\CustomField\User\Render\Web\UserCustomFieldListWebRendererAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Date\Render\DateRendererCreateTrait;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\Common\CustomField\Lot\InvoiceLotCustomFieldRendererCreateTrait;
use Sam\Invoice\Common\Load\InvoiceHeaderDataLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceItem\Dto\InvoicedAuctionDto;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Invoice\Common\Render\InvoiceRendererAwareTrait;
use Sam\Invoice\Legacy\Calculate\Basic\LegacyInvoiceCalculatorAwareTrait;
use Sam\Invoice\Legacy\Calculate\Tax\LegacyInvoiceTaxCalculatorCreateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Category\Renderer\LotCategoryRendererAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Lot\Render\Amount\LotAmountRendererInterface;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settings\TermsAndConditionsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\InvoiceAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Identification\UserIdentificationTransformerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Render\UserRendererAwareTrait;
use UserCustField;

/**
 * @method Invoice getInvoice(bool $isReadOnlyDb = false) - availability is checked at initialization
 */
class LegacyInvoiceViewPrintableRenderer extends CustomizableClass
{
    use AddressFormatterCreateTrait;
    use AuctionAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BidderInfoRendererAwareTrait;
    use CreditCardLoaderAwareTrait;
    use CurrencyLoaderAwareTrait;
    use DateHelperAwareTrait;
    use DateRendererCreateTrait;
    use InvoiceAdditionalChargeManagerAwareTrait;
    use InvoiceAwareTrait;
    use LegacyInvoiceCalculatorAwareTrait;
    use InvoiceHeaderDataLoaderAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceLoaderAwareTrait;
    use InvoiceLotCustomFieldRendererCreateTrait;
    use InvoicePaymentManagerAwareTrait;
    use InvoiceRendererAwareTrait;
    use LegacyInvoiceTaxCalculatorCreateTrait;
    use InvoiceUserLoaderAwareTrait;
    use LotAmountRendererFactoryCreateTrait;
    use LotCategoryRendererAwareTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use NumberFormatterAwareTrait;
    use PaymentRendererAwareTrait;
    use SettingsManagerAwareTrait;
    use TermsAndConditionsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;
    use UserCustomFieldListWebRendererAwareTrait;
    use UserCustomFieldLoaderAwareTrait;
    use UserIdentificationTransformerAwareTrait;
    use UserLoaderAwareTrait;
    use UserRendererAwareTrait;

    private const COLSPAN_SINGLE_ROW = 13;

    /**
     * @var InvoiceAdditional[]
     */
    protected array $charges = [];
    /**
     * @var Payment[]
     */
    protected array $payments = [];

    /**
     * @var string|null
     */
    public ?string $currencySign;

    public bool $isInvoiceIdentification = false;
    public bool $isInvoiceItemSeparateTax = false;
    public bool $isShowSalesTax = false;
    public bool $isShowQuantity = false;
    public bool $isMultipleSale = false;
    public bool $isShowCategory = false;

    public ?float $cashDiscount;
    public ?int $languageId;
    /**
     * @var LotItemCustField[]
     */
    public array $lotCustomFields = [];
    /**
     * @var UserCustField[]
     */
    public array $userCustomFields = [];
    public string $balanceDueHtml = '';
    public float $balanceDue;
    public ?int $accountId = null;
    protected ?LotAmountRendererInterface $lotAmountRenderer = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $invoiceId
     * @param int $viewLanguageId
     * @return bool
     */
    public function initialize(int $invoiceId, int $viewLanguageId): bool
    {
        // We allow to view invoices with deleted user (invoice.bidder)
        $this->getUserLoader()->clear();
        $invoice = $this->getInvoiceLoader()->load($invoiceId, true);
        if (!$invoice) {
            log_error("Available invoice not found" . composeSuffix(['i' => $invoiceId]));
            return false;
        }

        $this->setInvoice($invoice);
        $this->languageId = $viewLanguageId;
        $auctionId = $this->getInvoiceItemLoader()->findFirstInvoicedAuctionId($invoiceId, true);
        $auction = $this->getAuctionLoader()->load($auctionId, true);
        $this->setAuction($auction);
        $this->charges = $this->getInvoiceAdditionalChargeManager()->loadForInvoice($invoiceId, true);
        $this->payments = $this->getInvoicePaymentManager()->loadForInvoice($invoiceId, true);

        $this->accountId = $accountId = $this->getInvoice()->AccountId; // TODO: shouldn't it be system account of visiting domain and passed from caller?
        $this->getTranslator()->setAccountId($accountId);
        $this->getNumberFormatter()->constructForInvoice($accountId);
        $this->lotAmountRenderer = $this->createLotAmountRendererFactory()->createForInvoice($accountId);

        $auctionId = $this->getAuctionId();
        $this->currencySign = $this->getCurrencyLoader()->detectDefaultSign($auctionId, true);
        $sm = $this->getSettingsManager();
        $this->isShowCategory = (bool)$sm->get(Constants\Setting::CATEGORY_IN_INVOICE, $accountId);
        $this->isMultipleSale = (bool)$sm->get(Constants\Setting::MULTIPLE_SALE_INVOICE, $accountId);
        $this->isShowQuantity = (bool)$sm->get(Constants\Setting::QUANTITY_IN_INVOICE, $accountId);
        $this->isShowSalesTax = (bool)$sm->get(Constants\Setting::INVOICE_ITEM_SALES_TAX, $accountId);
        $this->isInvoiceItemSeparateTax = (bool)$sm->get(Constants\Setting::INVOICE_ITEM_SEPARATE_TAX, $accountId);
        $this->isInvoiceIdentification = (bool)$sm->get(Constants\Setting::INVOICE_IDENTIFICATION, $accountId);
        $this->cashDiscount = $sm->get(Constants\Setting::CASH_DISCOUNT, $accountId);

        $this->lotCustomFields = $this->createLotCustomFieldLoader()->loadInInvoices(true);
        $this->userCustomFields = $this->getUserCustomFieldLoader()->loadInInvoices(true);
        $this->balanceDue = $this->getLegacyInvoiceCalculator()->calcRoundedBalanceDue($invoiceId, 2, true);
        $balanceDueFormatted = $this->getNumberFormatter()->formatMoney($this->balanceDue);
        $langBalance = $this->getTranslator()->translate('MYINVOICES_DETAIL_BALANCE', 'myinvoices', null, $this->languageId);
        $this->balanceDueHtml = '<span>' . $langBalance . ':</span>' . $this->currencySign . $balanceDueFormatted;
        return true;
    }

    /**
     * Render one or more invoices depending on the id
     * @param int|array $id can accept positive int[]
     * @param int $viewLanguageId
     * @return string
     */
    public function render($id, int $viewLanguageId): string
    {
        $ids = is_array($id) ? $id : [$id];
        $filterIds = array_filter($ids);

        $output = '';
        if (count($filterIds) === 1) {
            $id = reset($filterIds);
            $output = $this->renderOne((int)$id, $viewLanguageId);
        } elseif (count($filterIds) > 1) {
            foreach ($filterIds as $filterId) {
                $output .= '<div class="multi-print-wrapper">'
                    . $this->renderOne((int)$filterId, $viewLanguageId)
                    . '</div><div class="clear page-break"></div>';
            }
        }
        return $output;
    }

    /**
     * render a single invoice
     * @param int $invoiceId
     * @param int $viewLanguageId
     * @return string
     */
    protected function renderOne(int $invoiceId, int $viewLanguageId): string
    {
        if (!$this->initialize($invoiceId, $viewLanguageId)) {
            return sprintf('Invoice not found by id %s', $invoiceId);
        }

        //=========== template
        $invoiceItemsTable = $this->renderInvoiceItems();

        //get the tabular data at the start to get the balance
        // $strSummaryTable = $this->renderSummary();

        //elements
        $logo = $this->renderLogoImage();
        $address = $this->renderAddress();
        $billingAddress = $this->renderBillingAddress();
        $shippingAddress = $this->renderShippingAddress();
        $userCustomFields = $this->renderUserCustomFields();
        $langInvoiceNo = $this->getTranslator()->translate('MYINVOICES_DETAIL_INVOICENUM', 'myinvoices');
        $invoiceNo = $this->getInvoice()->InvoiceNo;

        $saleList = !$this->isMultipleSale() ? $this->renderSaleListHtml() : '';

        $langDateCreated = $this->getTranslator()->translate('MYINVOICES_DETAIL_DATECREATED', 'myinvoices');
        $langStatus = $this->getTranslator()->translate('MYINVOICES_DETAIL_STATUS', 'myinvoices');

        $js = $this->renderCustomJs();
        $notes = $this->renderNote();

        $notesDisplayNone = (trim($notes) === '') ? 'style="display:none;"' : '';

        $output = <<<HTML
<article class="bodybox">
    <div class="viewtitle">
        <figure>
            <a href="#">{$logo}</a>
        </figure>

        <h3>
            <span>{$address}</span>
        </h3>

        <div class="clearfix"></div>
    </div>
    <ul class="viewinfo">
        <li>
            <div>
                {$billingAddress}
            </div>
            <div>
            $userCustomFields
            </div>
        </li>
        <li>
            <div>
                {$shippingAddress}
            </div>
        </li>
        <li>
            <div>
                <em>
                    {$langInvoiceNo}&nbsp;{$invoiceNo}
                </em>
                <p>
                    {$saleList}
                    {$langDateCreated}: {$this->renderDate()}
                    <br />
                    {$langStatus}: {$this->renderStatus()}
                </p>
                <p class="ttl">
                    {$this->balanceDueHtml}
                </p>
                <div class="unibtn inv-print-btns-printable">{$this->renderPrintLink()}</div>
                <div class="unibtn inv-print-btns-pdf">{$this->renderPdfInvoiceLink()}</div>
            </div>
        </li>
    </ul>
    <div class="tablesection">
        {$invoiceItemsTable}
    </div>
    <ul class="inv_btm">
        <li>
            <div class="notes" {$notesDisplayNone}>
                <h4><span>Notes</span></h4>
                <section>
                    {$notes}
                </section>

            </div>
        </li>

        <li>
            <div class="notes summary">
                <h4><span>Summary</span></h4>
                {$this->renderSummary()}
            </div>
        </li>
    </ul>
    <div class="clear"></div>
</article>
<div class="clear"></div>

<script>
{$js}
</script>

HTML;

        return $output;
    }

    /**
     * @return string
     */
    public function renderLogoImage(): string
    {
        $output = $this->getInvoiceRenderer()->renderLogoTag($this->getInvoice());
        return $output;
    }

    /**
     * @return string
     */
    public function renderId(): string
    {
        return (string)$this->getInvoice()->InvoiceNo;
    }

    /**
     * @return string
     */
    public function renderDate(): string
    {
        $date = $this->getInvoice()->InvoiceDate ?: new DateTime($this->getInvoice()->CreatedOn);
        $date = $this->getDateHelper()->convertUtcToSys($date);
        $langInvoiceDate = $this->getTranslator()->translate('MYINVOICES_INVOICE_DATE', 'myinvoices');
        return $this->getDateHelper()->formattedDate($date, null, null, null, $langInvoiceDate);
    }

    /**
     * @return string
     */
    public function renderAddress(): string
    {
        $output = '';
        $row = $this->getInvoiceHeaderDataLoader()->load($this->getInvoiceId(), true);
        if ($row) {
            $output = $this->createAddressFormatter()->format($row['country'], $row['state'], $row['city'], $row['zip'], $row['address']);
        }
        if (!$output) {
            $invoiceTerm = $this->getTermsAndConditionsManager()->load(
                $this->getInvoice()->AccountId,
                Constants\TermsAndConditions::INVOICE,
                true
            );
            if (!$invoiceTerm) {
                log_error(
                    "Available Terms and Conditions record not found for rendering invoice print"
                    . composeSuffix(['acc' => $this->getInvoice()->AccountId, 'key' => Constants\TermsAndConditions::INVOICE])
                );
                return '';
            }
            $output = $invoiceTerm->Content;
        }
        return $output;
    }

    /**
     * @return string
     */
    public function renderBillingAddress(): string
    {
        $invoiceId = $this->getInvoiceId();
        $auctionId = $this->getAuctionId();
        $invoiceBidderUser = $this->getUserLoader()->load($this->getInvoice()->BidderId, true);
        if (!$invoiceBidderUser) {
            $logInfo = composeSuffix(['u' => $this->getInvoice()->BidderId, 'i' => $invoiceId]);
            log_error("Available invoice bidder user not found" . $logInfo);
            return '';
        }
        $emailHtml = $invoiceBidderUser->Email;
        $bidderHtml = '';
        $billingHtml = '';
        $vatHtml = '';

        if (!$this->isMultipleSale) {
            $bidderHtml = $this->getBidderInfoRenderer()
                ->enableFloorBlank(true)
                ->enableReadOnlyDb(true)
                ->setAuctionId($auctionId)
                ->setLanguageId($this->languageId)
                ->setSystemAccountId($this->accountId)
                ->setUserId($this->getInvoice()->BidderId)
                ->render();
            if (is_numeric($bidderHtml)) {
                $bidderHtml = '<span class="bidder-num">' . $this->getTranslator()->translate("MYINVOICES_BIDDER_NUMBER", "myinvoices") . $bidderHtml . '</span><br />';
            } else {
                $bidderHtml = '<span class="bidder-num">' . $bidderHtml . '</span><br />';
            }
        }

        $userBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreate($invoiceId, true);

        if ($userBilling->CompanyName !== '') {
            $billingHtml .= '<span class="company">' . ee($userBilling->CompanyName) . '</span><br />';
        }
        $fullName = UserPureRenderer::new()->renderFullName($userBilling);
        if ($fullName !== '') {
            $billingHtml .= '<span class="name"> ' . ee($fullName) . '</span><br />';
        }
        if (
            $userBilling->Address !== ''
            || $userBilling->Address2 !== ''
        ) {
            $billingHtml .= '<span class="address">';
            if ($userBilling->Address !== '') {
                $billingHtml .= ee($userBilling->Address);
            }
            if (
                $userBilling->Address !== ''
                && $userBilling->Address2 !== ''
            ) {
                $billingHtml .= '<br />';
            }
            if ($userBilling->Address2 !== '') {
                $billingHtml .= ee($userBilling->Address2);
            }
            if (
                (
                    $userBilling->Address !== ''
                    || $userBilling->Address2 !== ''
                )
                && $userBilling->Address3 !== ''
            ) {
                $billingHtml .= '<br />';
            }
            if ($userBilling->Address3 !== '') {
                $billingHtml .= ee($userBilling->Address3);
            }
            $billingHtml .= '</span><br />';
        }
        if ($userBilling->City !== '') {
            $billingHtml .= '<span class="city">' . ee($userBilling->City) . '</span>, ';
        }
        $state = AddressRenderer::new()->stateName($userBilling->State, $userBilling->Country);
        if ($state !== '') {
            $billingHtml .= '<span class="state">' . ee($state) . '</span> ';
        }
        if ($userBilling->Zip !== '') {
            $billingHtml .= '<span class="zip">' . ee($userBilling->Zip) . '</span>';
        }
        $country = AddressRenderer::new()->countryName($userBilling->Country);
        if ($country !== '') {
            $billingHtml .= '<br /><span class="country">' . ee($country) . '</span> ';
        }
        if ($userBilling->Phone !== '') {
            $billingHtml .= '<br /><span class="phone">' . ee($userBilling->Phone) . '</span>';
        }
        if ($userBilling->Fax !== '') {
            $billingHtml .= '<br /><span class="fax">' . ee($userBilling->Fax) . '</span>';
        }

        if ($billingHtml === '') {
            $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($this->getInvoice()->BidderId, true);
            $fullName = UserPureRenderer::new()->renderFullName($userInfo);
            if ($fullName) {
                $billingHtml .= '<span class="cust-name">' . ee($fullName) . '</span>';
            }
        }

        if ($emailHtml !== '') {
            $emailHtml = '<br /><span class="email">' . ee($emailHtml) . '</span>';
        }

        if ($this->isInvoiceIdentification) {
            $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($this->getInvoice()->BidderId, true);
            if (!$userInfo->isNoneIdentificationType()) {
                $identification = $this->getUserIdentificationTransformer()
                    ->render($userInfo->Identification, $userInfo->IdentificationType);
                $identificationType = $this->getUserRenderer()
                    ->makeIdentificationTypeTranslated($userInfo->IdentificationType);
                $vatHtml .= '<br /><span class="vat">' . $identificationType . ': ' . $identification . '</span>';
            }
        }

        $langBillingInfo = $this->getTranslator()->translate('MYINVOICES_DETAIL_BILLINGINFO', 'myinvoices', null, $this->languageId);
        $output = <<<HTML
            <em>{$langBillingInfo}: </em>
            <p>
            {$bidderHtml}
            {$billingHtml}
            {$emailHtml}
            {$vatHtml}
            </p>
HTML;

        //
        return $output;
    }

    /**
     * @return string
     */
    public function renderShippingAddress(): string
    {
        $shippingHtml = '';
        $invoiceShipping = $this->getInvoiceUserLoader()
            ->loadInvoiceUserShippingOrCreate($this->getInvoiceId(), true);

        if ($invoiceShipping->CompanyName !== '') {
            $shippingHtml .= '<span class="company">' . ee($invoiceShipping->CompanyName) . '</span><br />';
        }
        $fullName = UserPureRenderer::new()->renderFullName($invoiceShipping);
        if ($fullName !== '') {
            $shippingHtml .= '<span class="name">' . ee($fullName) . '</span><br />';
        }
        if (
            $invoiceShipping->Address !== ''
            || $invoiceShipping->Address2 !== ''
        ) {
            $shippingHtml .= '<span class="address">';
            if ($invoiceShipping->Address !== '') {
                $shippingHtml .= ee($invoiceShipping->Address);
            }
            if (
                $invoiceShipping->Address !== ''
                && $invoiceShipping->Address2 !== ''
            ) {
                $shippingHtml .= '<br />';
            }
            if ($invoiceShipping->Address2 !== '') {
                $shippingHtml .= ee($invoiceShipping->Address2);
            }
            if (
                (
                    $invoiceShipping->Address !== ''
                    || $invoiceShipping->Address2 !== ''
                )
                && $invoiceShipping->Address3 !== ''
            ) {
                $shippingHtml .= '<br />';
            }
            if ($invoiceShipping->Address3 !== '') {
                $shippingHtml .= ee($invoiceShipping->Address3);
            }
            $shippingHtml .= '</span><br />';
        }
        if ($invoiceShipping->City !== '') {
            $shippingHtml .= '<span class="city">' . ee($invoiceShipping->City) . '</span>, ';
        }
        $state = AddressRenderer::new()->stateName($invoiceShipping->State, $invoiceShipping->Country);
        if ($state !== '') {
            $shippingHtml .= '<span class="state">' . ee($state) . '</span> ';
        }
        if ($invoiceShipping->Zip !== '') {
            $shippingHtml .= '<span class="zip">' . ee($invoiceShipping->Zip) . '</span>';
        }
        $country = AddressRenderer::new()->countryName($invoiceShipping->Country);
        if ($country !== '') {
            $shippingHtml .= '<br /><span class="country">' . ee($country) . '</span> ';
        }
        if ($invoiceShipping->Phone !== '') {
            $shippingHtml .= '<br /><span class="phone">' . ee($invoiceShipping->Phone) . '</span>';
        }
        if ($invoiceShipping->Fax !== '') {
            $shippingHtml .= '<br /><span class="fax">' . ee($invoiceShipping->Fax) . '</span>';
        }

        if ($shippingHtml !== '') {
            $langShippingInfo = $this->getTranslator()
                ->translate('MYINVOICES_DETAIL_SHIPPINGINFO', 'myinvoices', null, $this->languageId);
            $output = <<<HTML
                <em>{$langShippingInfo}: </em>
                <p>
                {$shippingHtml}
                </p>
HTML;
        } else {
            $output = '&nbsp;';
        }

        return $output;
    }

    /**
     * @return string
     */
    public function renderUserCustomFields(): string
    {
        $customFieldsHtml = $this->getUserCustomFieldListWebRenderer()
            ->setUserCustomFields($this->userCustomFields)
            ->setUserId($this->getInvoice()->BidderId)
            ->render();
        return $customFieldsHtml;
    }

    /**
     * @param InvoicedAuctionDto $invoicedAuctionDto
     * @return string
     */
    public function renderAuctionName(InvoicedAuctionDto $invoicedAuctionDto): string
    {
        if (!$invoicedAuctionDto->auctionId) {
            $output = '-';
        } else {
            $output = $invoicedAuctionDto->makeAuctionName()
                . ' (' . $invoicedAuctionDto->makeSaleNo() . ')';
        }
        return $output;
    }

    /**
     * @param DateTime|null $date
     * @param int $auctionId
     * @param string $auctionType
     * @param int|null $eventType
     * @param int $accountId
     * @return string
     */
    public function makeAuctionDate(?DateTime $date, int $auctionId, string $auctionType, ?int $eventType, int $accountId): string
    {
        if (!$date) {
            return '';
        }

        $dateHtml = '';
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isLiveOrHybrid($auctionType)) {
            $dateHtml = $this->renderSaleDateHtml($date, $accountId);
        } elseif ($auctionStatusPureChecker->isTimedScheduled($auctionType, $eventType)) {
            $dateHtml = $this->renderSaleDateHtml($date, $accountId);
        } elseif ($auctionStatusPureChecker->isTimedOngoing($auctionType, $eventType)) {
            $dateHtml = $this->getTranslator()->translate('AUCTIONS_EVENT_TYPE', 'auctions');
        }
        $output = '<span id="' . 'lsaledate' . $auctionId . '">' . $dateHtml . '</span>';
        return $output;
    }

    /**
     * @param DateTime $date
     * @param int $accountId
     * @return string
     */
    public function renderSaleDateHtml(DateTime $date, int $accountId): string
    {
        $langSaleDate = $this->getTranslator()->translate('MYINVOICES_SALE_DATE', 'myinvoices');
        $dateHelper = $this->getDateHelper();
        if (strrpos($langSaleDate, "F") !== false) {
            $month = date("F", $date->getTimestamp());
            $langMonth = $this->createDateRenderer()->monthTranslated((int)$month);
            $dateFormatted = $dateHelper->formattedDate($date, $accountId, null, null, $langSaleDate);
            $dateFormatted = str_replace($month, $langMonth, $dateFormatted);
        } else {
            $dateFormatted = $dateHelper->formattedDate($date, $accountId, null, null, $langSaleDate);
        }
        return $dateFormatted;
    }

    /**
     * @return string
     */
    public function renderSaleListHtml(): string
    {
        $output = '';
        $invoicedAuctionDtos = $this->getInvoiceItemLoader()->loadInvoicedAuctionDtos($this->getInvoiceId(), true);
        if (count($invoicedAuctionDtos) === 1) {
            $langSale = $this->getTranslator()->translate('MYINVOICES_DETAIL_SALE', 'myinvoices', null, $this->languageId);
            $langSaleDate = $this->getTranslator()->translate('MYINVOICES_DETAIL_SALEDATE', 'myinvoices', null, $this->languageId);
            $invoicedAuctionDto = $invoicedAuctionDtos[0];
            $saleName = $this->renderAuctionName($invoicedAuctionDto);
            $saleDate = $invoicedAuctionDto->detectSaleDate();
            $saleDateTz = $this->getDateHelper()->convertUtcToTzById($saleDate, $invoicedAuctionDto->auctionTimezoneId);
            $saleDateHtml = $this->makeAuctionDate(
                $saleDateTz,
                $invoicedAuctionDto->auctionId,
                $invoicedAuctionDto->auctionType,
                $invoicedAuctionDto->auctionEventType,
                $invoicedAuctionDto->accountId
            );

            $output = <<<HTML
<div class="sale-name">
    <span class="label">{$langSale}: </span><span class="value">{$saleName}</span>
</div>
<div class="sale-date">
    <span class="label">{$langSaleDate}: </span><span class="value">{$saleDateHtml}</span>
</div>

HTML;
        } elseif (count($invoicedAuctionDtos) > 1) {
            foreach ($invoicedAuctionDtos as $invoicedAuctionDto) {
                $saleName = $this->renderAuctionName($invoicedAuctionDto);
                $saleDate = $invoicedAuctionDto->detectSaleDate();
                $saleDateTz = $this->getDateHelper()->convertUtcToTzById($saleDate, $invoicedAuctionDto->auctionTimezoneId);
                $saleDateHtml = $this->makeAuctionDate(
                    $saleDateTz,
                    $invoicedAuctionDto->auctionId,
                    $invoicedAuctionDto->auctionType,
                    $invoicedAuctionDto->auctionEventType,
                    $invoicedAuctionDto->accountId
                );

                $output .= <<<HTML
<div class="sale-name">
    <span class="value">{$saleName}</span>
    <span class="value">{$saleDateHtml}</span>
</div>

HTML;
            }
        }

        return $output;
    }

    /**
     * @return string
     */
    public function renderNote(): string
    {
        $output = '';
        $note = ee($this->getInvoice()->Note);
        if (trim($note) !== '') {
            $output = '<div>' . nl2br($note) . '</div>';
        }
        return $output;
    }

    /**
     * @return string
     */
    public function renderInvoiceItems(): string
    {
        $currency = $this->currencySign;
        //translations
        $tr = $this->getTranslator();
        $colLot = str_replace(' ', '&nbsp;', $tr->translate('MYINVOICES_DETAIL_LOTNUM', 'myinvoices', null, $this->languageId));
        $langColItemNum = $tr->translate('MYINVOICES_DETAIL_ITEMNUM', 'myinvoices', null, $this->languageId);
        $langColItemName = $tr->translate('MYINVOICES_DETAIL_ITEMNAME', 'myinvoices', null, $this->languageId);
        $langColHammer = $tr->translate('MYINVOICES_DETAIL_HAMMER', 'myinvoices', null, $this->languageId);
        $langColBuyersPremium = $tr->translate('MYINVOICES_DETAIL_BUYERSPREMIUM', 'myinvoices', null, $this->languageId);
        $langColSubtotal = $tr->translate('MYINVOICES_DETAIL_SUBTOTAL', 'myinvoices', null, $this->languageId);

        $isShowCategory = $this->isShowCategory;
        $isMultipleSale = $this->isMultipleSale;
        $isShowQuantity = $this->isShowQuantity;
        $isShowSalesTax = $this->isShowSalesTax;

        $lotCategoryHeader = '';
        if ($isShowCategory) {
            $langCategoryHdr = $tr->translate('MYINVOICES_DETAIL_CATEGORY', 'myinvoices', null, $this->languageId);
            $lotCategoryHeader = <<<HTML
                <th data-hide="phone,tablet" class="item-category">$langCategoryHdr</th>
HTML;
        }
        $customFieldRenderer = $this->createInvoiceLotCustomFieldRenderer()
            ->setLotCustomFields($this->lotCustomFields);
        $isCustomFieldInSeparateRow = $this->getSettingsManager()->get(Constants\Setting::RENDER_LOT_CUSTOM_FIELDS_IN_SEPARATE_ROW_FOR_INVOICE, $this->accountId);
        $lotCustomFieldHeader = '';
        if (!$isCustomFieldInSeparateRow) {
            $lotCustomFieldHeader = $customFieldRenderer->renderCustomFieldsHeader();
        }

        $quantityHeader = '';
        if ($isShowQuantity) {
            $langQuantityHdr = $tr->translate('MYINVOICES_DETAIL_QUANTITY', 'myinvoices', null, $this->languageId);
            $quantityHeader = <<<HTML
                <th data-hide="phone,tablet" class="item-quantity">$langQuantityHdr</th>
HTML;
        }

        $salesTaxHeader = '';
        if (
            $isShowSalesTax
            && !$this->isInvoiceItemSeparateTax
        ) {
            $langSalesTaxHdr = $tr->translate('MYINVOICES_DETAIL_SALESTAX', 'myinvoices');
            $salesTaxHeader = <<<HTML
                <th data-hide="phone,tablet" class="item-sales-tax">$langSalesTaxHdr</th>
HTML;
        }

        $taxOnServicesHeader = '';
        if ($this->isInvoiceItemSeparateTax) {
            $langTaxOnServices = $tr->translate('TAX_ON_SERVICES', 'myinvoices');
            $taxOnServicesHeader = <<<HTML
                <th class = "item-services-tax" >$langTaxOnServices</th>
HTML;
        }
        $taxOnGoodsHeader = '';
        if ($this->isInvoiceItemSeparateTax) {
            $langSalesTaxOnGoods = $tr->translate('TAX_ON_GOODS', 'myinvoices');
            $taxOnGoodsHeader = <<<HTML
                <th class = "item-goods-tax">$langSalesTaxOnGoods</th>
HTML;
        }

        $saleHeader = '';
        $saleDateHeader = '';

        if ($isMultipleSale) {
            $langSaleHdr = $tr->translate('MYINVOICES_DETAIL_SALE', 'myinvoices', null, $this->languageId);
            $langSaleDateHdr = $tr->translate('MYINVOICES_DETAIL_SALEDATE', 'myinvoices', null, $this->languageId);
            $saleHeader = <<<HTML
                <th class="item-sale-name">$langSaleHdr</th>
HTML;
            $saleDateHeader = <<<HTML
                <th data-hide="phone,tablet" class="item-sale-date">$langSaleDateHdr</th>
HTML;
            $colLot = str_replace(' ', '&nbsp;', $tr->translate('MYINVOICES_DETAIL_SALENUM', 'myinvoices', null, $this->languageId)) . '&nbsp;/ ' . $colLot;
        }

        $output = <<<HTML
<table class="footable foolarge invoice-datagrid borderOne" id="c2">
<thead>
  <tr>
    $saleHeader
    $saleDateHeader
    <th class="item-sale-lot-num">{$colLot}</th>
    <th data-hide="phone,tablet" class="item-lot-num">{$langColItemNum}</th>
    <th data-hide="phone,tablet">{$langColItemName}</th>
    $lotCategoryHeader
    $lotCustomFieldHeader
    $quantityHeader
    <th data-hide="phone,tablet" class="item-hammer-price">{$langColHammer}</th>
    $salesTaxHeader
    $taxOnGoodsHeader
    <th data-hide="phone,tablet" class="item-buyers-premium">{$langColBuyersPremium}</th>
    $taxOnServicesHeader
    <th class = "item-sub-total">{$langColSubtotal}</th>
  </tr>
</thead>
<tbody>
HTML;

        $rowI = 0;
        $dtos = $this->getInvoiceItemLoader()->loadDtos($this->getInvoiceId(), true);
        foreach ($dtos as $dto) {
            $itemNo = $dto->makeItemNo();
            $lotNo = $dto->makeLotNo();
            $lotName = $dto->makeLotName();
            $hpFormatted = $this->getNumberFormatter()->formatMoney($dto->hammerPrice);
            $bpFormatted = $this->getNumberFormatter()->formatMoney($dto->buyersPremium);
            $subTotalFormatted = $this->getNumberFormatter()->formatMoney($dto->calcSubTotal());

            $categoryColumn = '';
            if ($isShowCategory) {
                $categoryText = $this->getLotCategoryRenderer()->getCategoriesText($dto->lotItemId);
                $categoryColumn = <<<HTML
            <td class="item-category">{$categoryText}</td>
HTML;
            }

            $customFieldColumn = '';
            if (!$isCustomFieldInSeparateRow) {
                $customFieldColumn = $customFieldRenderer->renderCustomFieldsColumn($dto->lotItemId);
            }

            $quantityColumn = '';
            if ($isShowQuantity) {
                $quantity = Floating::gt($dto->quantity, 0, $dto->quantityScale)
                    ? $this->lotAmountRenderer->makeQuantity($dto->quantity, $dto->quantityScale)
                    : '-';
                $quantityColumn = <<<HTML
            <td class="item-quantity">{$quantity}</td>
HTML;
            }

            $salesTaxColumn = '';
            if (
                $isShowSalesTax
                && !$this->isInvoiceItemSeparateTax
            ) {
                $salesTaxAmount = InvoiceTaxPureCalculator::new()->calcSalesTaxApplied(
                    $dto->hammerPrice,
                    $dto->buyersPremium,
                    $dto->salesTaxPercent,
                    $dto->taxApplication
                );
                $salesTaxFormatted = $this->getNumberFormatter()->formatMoney($salesTaxAmount);
                $salesTaxColumn = <<<HTML
            <td class="item-sales-tax number align-right">{$salesTaxFormatted} ({$dto->salesTaxPercent}%)</td>
HTML;
            }

            $salesTaxOnServicesColumn = '';
            if ($this->isInvoiceItemSeparateTax) {
                $salesTaxOnServices = InvoiceTaxPureCalculator::new()->calcSalesTaxAppliedOnServices(
                    $dto->buyersPremium,
                    $dto->salesTaxPercent,
                    $dto->taxApplication
                );
                $salesTaxOnServicesFormatted = $this->getNumberFormatter()->formatMoney($salesTaxOnServices);
                $salesTaxOnServicesColumn = <<<HTML
            <td class = "item-sales-tax number align-right">{$currency}{$salesTaxOnServicesFormatted}</td>
HTML;
            }

            $salesTaxOnGoodsColumn = '';
            if ($this->isInvoiceItemSeparateTax) {
                $salesTaxOnGoods = InvoiceTaxPureCalculator::new()->calcSalesTaxAppliedOnGoods(
                    $dto->hammerPrice,
                    $dto->salesTaxPercent,
                    $dto->taxApplication
                );
                $langSalesTaxOnGoods = $this->getNumberFormatter()->formatMoney($salesTaxOnGoods);
                $salesTaxOnGoodsColumn = <<<HTML
            <td class = "item-sales-tax number align-right">{$currency}{$langSalesTaxOnGoods}</td>
HTML;
            }

            $saleColumn = '';
            $saleDateColumn = '';
            $saleNo = '';
            if ($isMultipleSale) {
                $saleName = ee($dto->makeAuctionName());
                $saleNo = $dto->makeSaleNo();
                $saleNo = $saleNo !== '' ? $saleNo . '&nbsp;/ ' : $saleNo;

                $auctionDate = $dto->detectAuctionDate();
                $auctionDateTz = $this->getDateHelper()->convertUtcToTzById($auctionDate, $dto->auctionTimezoneId);
                $saleDateFormatted = $this->makeAuctionDate(
                    $auctionDateTz,
                    $dto->auctionId,
                    $dto->auctionType,
                    $dto->eventType,
                    $dto->accountId
                );

                $saleColumn = <<<HTML
            <td class = "item-sale-name">{$saleName}</td>
HTML;

                $saleDateColumn = <<<HTML
            <td class = "item-sale-date">{$saleDateFormatted}</td>
HTML;
            }

            $rowClass = ($rowI % 2 === 0) ? 'class="alternate"' : '';
            $lotName = ee($lotName);
            $output .= <<<HTML
<tr id="c2row{$rowI}" {$rowClass}>
    $saleColumn
    $saleDateColumn
    <td class="item-sale-lot-num">{$saleNo}{$lotNo}</td>
    <td class="item-lot-num">{$itemNo}</td>
    <td class="item-lot-name">{$lotName}</td>
    $categoryColumn
    $customFieldColumn
    $quantityColumn
    <td class="item-hammer-price number align-right">{$this->currencySign}{$hpFormatted}</td>
    $salesTaxColumn
    $salesTaxOnGoodsColumn
    <td class="item-buyers-premium number align-right">{$this->currencySign}{$bpFormatted}</td>
    $salesTaxOnServicesColumn
    <td class="item-sub-total number align-right">{$this->currencySign}{$subTotalFormatted}</td>
</tr>
HTML;
            if ($isCustomFieldInSeparateRow) {
                $lotItemCustomFieldsRowData = $customFieldRenderer->renderCustomFieldsDataInSingleRow($dto->lotItemId);
                if ($lotItemCustomFieldsRowData) {
                    $colspan = self::COLSPAN_SINGLE_ROW;
                    $output .= <<<HTML
<tr {$rowClass}>
    <td colspan ="{$colspan}"> {$lotItemCustomFieldsRowData} </td>
</tr>
HTML;
                }
            }
            $rowI++;
        } // end foreach

        //end table before summary
        $output .= '</tbody></table>';

        return $output;
    }

    /**
     * @return string
     */
    public function renderSummary(): string
    {
        $tr = $this->getTranslator();
        $output = '';
        $currency = $this->currencySign;
        $output .= '';
        $invoiceId = $this->getInvoiceId();
        $invoiceCalculator = $this->getLegacyInvoiceCalculator();
        $invoiceTaxCalculator = $this->createLegacyInvoiceTaxCalculator();

        /* summary starts here */

        if (
            Floating::gt($this->cashDiscount, 0)
            && $this->getInvoice()->CashDiscount
        ) {
            /* Cash discount
             *
            * */

            $cashDiscountAmount = $invoiceCalculator
                ->calcCashDiscount($invoiceId, $this->getInvoice()->CashDiscount);
            $cashDiscountAmountFormatted = $this->getNumberFormatter()->formatMoney($cashDiscountAmount);
            $langDiscount = $tr->translate('MYINVOICES_DETAIL_CASHDISCOUNT', 'myinvoices', null, $this->languageId) . '(' . $this->cashDiscount . '%)';
            $output .= <<<HTML
            <section>
                <i><span>{$langDiscount}:</span></i>
                <em>{$currency}{$cashDiscountAmountFormatted}</em>
                <div class="clearfix"></div>
            </section>
HTML;
        }

        /**
         * Subtotal total
         **/
        $subTotal = $invoiceCalculator->calcSubTotal($invoiceId);
        $subTotal = $this->getNumberFormatter()->formatMoney($subTotal);
        $langSubTotal = $tr->translate('MYINVOICES_DETAIL_SUBTOTAL', 'myinvoices', null, $this->languageId);
        $output .= <<<HTML
            <section>
                <i><span>{$langSubTotal}:</span></i>
                <em>{$currency}{$subTotal}</em>
                <div class="clearfix"></div>
            </section>
HTML;

        /* Shipping charge
         *
        * */
        $shipping = $this->getInvoice()->Shipping;
        $shipping = $this->getNumberFormatter()->formatMoney($shipping);
        $langShipping = $tr->translate('MYINVOICES_DETAIL_SHIPPING', 'myinvoices', null, $this->languageId);
        $output .= <<<HTML
            <section>
                <i><span>{$langShipping}:</span></i>
                <em>{$currency}{$shipping}</em>
                <div class="clearfix"></div>
            </section>
HTML;

        /* Extra charges
         *
        * */

        $extraCharges = '<i><span>' . $tr->translate('MYINVOICES_DETAIL_EXTRACHARGES', 'myinvoices', null, $this->languageId) . ':</span></i>';
        $chargeNames = [];
        $chargeAmounts = [];

        //add the header if there are extra charges
        if (count($this->charges) <= 0) {
            $extraCharges = '';
        }

        foreach ($this->charges as $invoiceAdditional) {
            $chargeNames[] = '<span class="charge-name">' . $invoiceAdditional->Name . '</span>';
            $charge = $invoiceAdditional->Amount;
            $charge = $this->getNumberFormatter()->formatMoney($charge);
            $chargeAmounts[] = '<span class="charge-amount">' . $currency . $charge . '</span>';
        }

        $chargeName = implode('<br />', $chargeNames);
        $chargeAmount = implode('<br />', $chargeAmounts);

        if (count($this->charges) > 0) {
            $output .= <<<HTML
            <section>
                {$extraCharges}
                <i><span>{$chargeName}</span></i>
                <em>{$chargeAmount}</em>
                <div class="clearfix"></div>
            </section>
HTML;
        }

        /* Salestax
         *
        * */
        if (!$this->isInvoiceItemSeparateTax) {
            $totalTax = $invoiceTaxCalculator->calcTotalSalesTaxApplied($invoiceId);
            $totalTaxFormatted = $this->getNumberFormatter()->formatMoney($totalTax);
            $langSalesTax = $tr->translate('MYINVOICES_DETAIL_SALESTAX', 'myinvoices', null, $this->languageId);
            $output .= <<<HTML
            <section>
                <i><span>{$langSalesTax}:</span></i>
                <em>{$currency}{$totalTaxFormatted}</em>
                <div class="clearfix"></div>
            </section>
HTML;
        }
        /* Tax on Goods
         *
         * */
        if ($this->isInvoiceItemSeparateTax) {
            $taxOnGoods = $invoiceTaxCalculator->calcTotalTaxOnGoods($invoiceId);
            $taxOnGoodsFormatted = $this->getNumberFormatter()->formatMoney($taxOnGoods);
            $langTaxOnGoods = $tr->translate('TAX_ON_GOODS', 'myinvoices');
            $output .= <<<HTML
    <section>
        <i><span>{$langTaxOnGoods}:</span></i>
        <em>{$currency}{$taxOnGoodsFormatted}</em>
        <div class="clearfix"></div>
    </section>

HTML;
        }

        /* Tax on Services
         *
         * */
        if ($this->isInvoiceItemSeparateTax) {
            $taxOnServices = $invoiceTaxCalculator->calcTotalTaxOnServices($invoiceId);
            $taxOnServicesFormatted = $this->getNumberFormatter()->formatMoney($taxOnServices);
            $langTaxOnServices = $tr->translate('TAX_ON_SERVICES', 'myinvoices');
            $output .= <<<HTML
    <section>
        <i><span>{$langTaxOnServices}:</span></i>
        <em>{$currency}{$taxOnServicesFormatted}</em>
        <div class="clearfix"></div>
    </section>

HTML;
        }

        /* Total
         *
        * */

        $grandTotal = $invoiceCalculator->calcGrandTotal($invoiceId);
        $grandTotalFormatted = $this->getNumberFormatter()->formatMoney($grandTotal);
        $langTotal = $tr->translate('MYINVOICES_DETAIL_TOTAL', 'myinvoices', null, $this->languageId);
        $output .= <<<HTML
            <section>
                <i><span>{$langTotal}:</span></i>
                <em>{$currency}{$grandTotalFormatted}</em>
                <div class="clearfix"></div>
            </section>
HTML;

        /* Payments made
         *
        * */

        $langPaymentsMade = $tr->translate('MYINVOICES_DETAIL_PAYMENTSMADE', 'myinvoices', null, $this->languageId);
        if (count($this->payments) > 0) { // Display only if there is a payment made

            $output .= <<<HTML
            <h4><span>{$langPaymentsMade}</span></h4>
HTML;

            $langPaymentDateFormat = $tr->translate('MYINVOICES_INVOICE_DATE', 'myinvoices');

            foreach ($this->payments as $payment) {
                $creditCard = $this->getCreditCardLoader()->load($payment->CreditCardId, true);
                $cardType = $creditCard ? " - " . $creditCard->Name : '';
                $langPaymentMethodName = $this->getPaymentRenderer()->makePaymentMethodTranslated($payment->PaymentMethodId);
                $paymentMethod = '<span class="payment-method">' . $langPaymentMethodName . $cardType . '</span>';
                $paymentNote = $paymentDate = '';

                if ($payment->PaidOn) {
                    $paidOn = $this->getDateHelper()->convertUtcToSys($payment->PaidOn);
                    $paymentDate .= $this->getDateHelper()->formattedDate($paidOn, null, null, null, $langPaymentDateFormat);
                }

                if (trim($paymentDate) !== '') {
                    $paymentDate = ' - <span class="payment-date">' . $paymentDate . '</span>';
                }

                if ($payment->Note) {
                    $paymentNote = $payment->Note;
                }

                if (trim($paymentNote) !== '') {
                    $paymentNote = '&nbsp; <span class="payment-note">( ' . $paymentNote . ' ' . ')</span>';
                }

                $paymentAmount = $payment->Amount;
                $paymentAmount = $this->getNumberFormatter()->formatMoney($paymentAmount);
                $paymentAmountHtml = '<span class="payment-amount">' . $currency . $paymentAmount . '</span>';
                $output .= <<<HTML
                    <section>
                        <i><span>{$paymentMethod}{$paymentDate}{$paymentNote}</span></i>
                        <em>{$paymentAmountHtml}</em>
                        <div class="clearfix"></div>
                    </section>
HTML;
            }
        } // If

        /* Balance
         *
        * */

        $balanceDueFormatted = $this->getNumberFormatter()->formatMoney($this->balanceDue);
        $langBalance = $tr->translate('MYINVOICES_DETAIL_BALANCE', 'myinvoices', null, $this->languageId);
        $this->balanceDueHtml = '<span>' . $langBalance . ':</span>' . $currency . $balanceDueFormatted;
        $output .= <<<HTML
                    <section class="total">
                        <i><span>{$langBalance}</span></i>
                        <em>{$currency}{$balanceDueFormatted}</em>
                        <div class="clearfix"></div>
                    </section>
HTML;

        return $output;
    }

    /**
     * @return string
     */
    public function renderStatus(): string
    {
        $output = $this->getInvoiceRenderer()->makePaymentStatusTranslated($this->getInvoice()->InvoiceStatusId);
        return $output;
    }

    /**
     * @return string
     */
    public function renderPrintLink(): string
    {
        $urlConfig = AnySingleInvoiceUrlConfig::new()
            ->forWeb(Constants\Url::P_INVOICES_USER_PRINT, $this->getInvoiceId());
        $invoicesUserPrintUrl = $this->getUrlBuilder()->build($urlConfig);
        return sprintf(
            '<a class="orng" href="%s" target="_blank">%s</a>',
            $invoicesUserPrintUrl,
            $this->getTranslator()->translate('MYINVOICES_PRINTABLE', 'myinvoices')
        );
    }

    /**
     * @return string
     */
    public function renderPdfInvoiceLink(): string
    {
        $url = $this->getUrlBuilder()->build(
            AnySingleInvoiceUrlConfig::new()->forWeb(
                Constants\Url::P_INVOICES_PDF,
                $this->getInvoiceId()
            )
        );
        $link = '<a class="orng" href="' . $url . '" target="_blank">'
            . $this->getTranslator()->translate("MYINVOICES_PDF", "myinvoices", null, $this->languageId)
            . '</a>';
        return $link;
    }

    /**
     * @return bool
     */
    public function isMultipleSale(): bool
    {
        return $this->isMultipleSale;
    }

    /**
     * Render additional JS script block
     * @return string
     */
    public function renderCustomJs(): string
    {
        return '';

//        $output = <<<JS
//
// $(document).ready(function(){
//     $('table').footable();
//
//     $('.footable').bind('footable_breakpoint', function(){
//         //click the unexpanded rows
//         $('.footable.breakpoint>tbody>tr:not(.footable-detail-show)>td>span.footable-toggle').trigger('click');
//         //blank out empty fields
//         $('.footable-row-detail-inner .footable-row-detail-value:empty').parent().css('display','none');
//
//     });
//
//     //click the unexpanded rows at startup
//     $('.footable.breakpoint>tbody>tr:not(.footable-detail-show)>td>span.footable-toggle').trigger('click');
//     //blank out empty fields at startup
//     $('.footable-row-detail-inner .footable-row-detail-value:empty').parent().css('display','none');
//
//     //hide the details cell if there are no visible details/custom fields
//     $('.footable-row-detail-cell').each( function(idx){
//         var hasVisible = false;
//         $(this).children('.footable-row-detail-inner').children('.footable-row-detail-row').each(function(){
//             if($(this).is(':visible')){
//                 hasVisible = true;
//                 return false;
//             } else {
//                 hasVisible = false;
//             }
//
//         });
//
//         //if hasVisible remains false, hide the detail cell
//         if(hasVisible == false){
//             $(this).hide();
//         } else {
//             if($(this).not(':visible')){
//                     $(this).show();
//                     console.log ('re-show');
//                 }
//         }
//     });
//
// });
//
//JS;
//        return $output;
    }

}
