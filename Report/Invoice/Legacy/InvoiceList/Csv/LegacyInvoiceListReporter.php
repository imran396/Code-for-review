<?php
/**
 * SAM-4624 : Refactor invoice item sold report
 * https://bidpath.atlassian.net/browse/SAM-4623
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/14/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Invoice\Legacy\InvoiceList\Csv;

use DateTime;
use LotItemCustField;
use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Billing\Payment\Render\PaymentRendererAwareTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Entity\FilterCurrencyAwareTrait;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\Lot\Render\Csv\LotCustomFieldCsvRendererAwareTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\CustomField\User\Render\Csv\UserCustomFieldCsvRendererAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Invoice\Legacy\Calculate\Basic\LegacyInvoiceCalculatorAwareTrait;
use Sam\Invoice\Legacy\Calculate\Tax\LegacyInvoiceTaxCalculatorCreateTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Report\Invoice\Legacy\InvoiceList\Csv\Internal\Load\DataProviderCreateTrait;
use Sam\Report\Invoice\Legacy\InvoiceList\Csv\Internal\Render\Nested\NestedArrayBuilder;
use Sam\Storage\Entity\AwareTrait\LotCustomFieldsAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserCustomFieldsAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use UserCustField;

/**
 * Class InvoiceListReporter
 * @package Sam\Report\Invoice\Legacy\InvoiceList
 */
class LegacyInvoiceListReporter extends ReporterBase
{
    use ApplicationAccessCheckerCreateTrait;
    use AuctionBidderLoaderAwareTrait;
    use BidderNumPaddingAwareTrait;
    use CreditCardLoaderAwareTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use DataProviderCreateTrait;
    use DateHelperAwareTrait;
    use FilePathHelperAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterAwareTrait;
    use FilterCurrencyAwareTrait;
    use LegacyInvoiceCalculatorAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoicePaymentManagerAwareTrait;
    use LegacyInvoiceTaxCalculatorCreateTrait;
    use LotCustomFieldCsvRendererAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotCustomFieldsAwareTrait;
    use NumberFormatterAwareTrait;
    use PaymentRendererAwareTrait;
    use SortInfoAwareTrait;
    use UserCustomFieldCsvRendererAwareTrait;
    use UserCustomFieldLoaderAwareTrait;
    use UserCustomFieldsAwareTrait;

    private const CHUNK_SIZE = 200;

    /** @var DataLoader|null */
    protected ?DataLoader $dataLoader = null;

    protected ?int $editorUserId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function setEditorUserId(?int $editorUserId): static
    {
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $dateIso = $this->getCurrentDateUtc()->format('m-d-Y-His');
            $filename = "invoice_{$dateIso}.csv";
            $filename = $this->getFilePathHelper()->toFilename($filename);
            $this->outputFileName = $filename;
        }
        return $this->outputFileName;
    }

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new();
            $this->dataLoader
                ->enableAccountFiltering($this->isAccountFiltering())
                ->enableCustomFieldRender($this->isCustomFieldRender())
                ->enableMultipleSaleInvoice($this->isMultipleSaleInvoice())
                ->filterAccountId($this->getFilterAccountId())
                ->filterAuctionId($this->getFilterAuctionId())
                ->filterWinningUserId($this->getWinningUserId())
                ->filterWinningUserSearchKey($this->getWinningUserSearchKey())
                ->filterCurrencySign($this->getFilterCurrencySign())
                ->filterInvoiceNo($this->getInvoiceNo())
                ->filterInvoiceStatus($this->getInvoiceStatus())
                ->setPrimarySort($this->getPrimarySort())
                ->filterSearchKey($this->getSearchKey())
                ->setSecondarySort($this->getSecondarySort())
                ->setChunkSize(self::CHUNK_SIZE)
                ->setLotCustomFields($this->getLotCustomFields())
                ->setSortColumnIndex($this->getSortColumnIndex())
                ->setSortDirection($this->getSortDirection())
                ->setUserCustomFields($this->getUserCustomFields());
        }

        return $this->dataLoader;
    }

    /**
     * @param DataLoader $dataLoader
     * @return static
     */
    public function setDataLoader(DataLoader $dataLoader): static
    {
        $this->dataLoader = $dataLoader;
        return $this;
    }

    /**
     * @param array $row
     * @return string
     */
    protected function buildBodyLine(array $row): string
    {
        $saleNo = '';
        $saleName = '';
        $saleDesc = '';
        $this->getNumberFormatter()->construct($this->getSystemAccountId());
        $invoiceCalculator = $this->getLegacyInvoiceCalculator();
        $invoiceTaxCalculator = $this->createLegacyInvoiceTaxCalculator();
        $invoiceId = (int)$row['id'];
        if (!$this->isMultipleSaleInvoice()) {
            /** Convert UTF-8 encoding to the set encoding for export in settings **/
            $saleNo = $row['sale_no'];
            $saleName = $row['sale_name'];
            $saleDesc = $row['sale_desc'];
        }
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign((int)$row['sale_id'], true);

        $dateIso = $row['invoice_date'] ?: $row['created_on'];
        $date = $this->getDateHelper()->convertUtcToSysByDateIso($dateIso);
        $invoiceDate = $date ? $date->format(Constants\Date::US_DATE) : '';
        $invoiceNo = $row['invoice_no'];
        $internalNote = $row['internal_note'];
        if ($row['first_sent_on']) {
            $sentDate = $this->getDateHelper()->convertUtcToSysByDateIso($row['first_sent_on']);
            $sentDateFormatted = $sentDate ? $sentDate->format(Constants\Date::US_DATE) : '';
        } else {
            $sentDateFormatted = '';
        }
        $bidderNum = isset($row['bidder_num'])
            ? $this->getBidderNumberPadding()->clear($row['bidder_num']) : '';
        $username = $row['username'];
        $customerID = $row['customer_no'];
        $email = $row['email'];
        $firstName = $row['first_name'];
        $lastName = $row['last_name'];
        $phone = $row['iphone'];
        $referrer = $row['referrer'];
        $referrerHost = $row['referrer_host'];
        $state = $row['state'];
        $zip = $row['zip'];
        $buyerSalesTax = $row['sales_tax'];
        $billCompany = $row['bcompany_name'];
        $billFirstName = $row['bfirst_name'];
        $billLastName = $row['blast_name'];
        $billPhone = $row['bphone'];
        $billStreet = $row['baddress'];
        $billStreet2 = $row['baddress2'];
        $billStreet3 = $row['baddress3'];
        $billCity = $row['bcity'];
        $billState = $row['bstate'];
        $billZip = $row['bzip'];
        $billCountry = AddressRenderer::new()->countryName((string)$row['bcountry']);
        $shipCompany = $row['scompany_name'];
        $shipFirstName = $row['sfirst_name'];
        $shipLastName = $row['slast_name'];
        $shipPhone = $row['sphone'];
        $shipStreet = $row['saddress'];
        $shipStreet2 = $row['saddress2'];
        $shipStreet3 = $row['saddress3'];
        $shipCity = $row['scity'];
        $shipState = $row['sstate'];
        $shipZip = $row['szip'];
        $shipCountry = AddressRenderer::new()->countryName((string)$row['scountry']);
        $nonTaxable = $row['non_taxable'];
        $taxable = $row['taxable'];
        $export = $row['export'];
        $premiumTotal = $invoiceCalculator->calcTotalBuyersPremium($invoiceId, true);
        $grandTotal = $invoiceCalculator->calcGrandTotal($invoiceId, true);
        $balanceDue = $invoiceCalculator->calcRoundedBalanceDue($invoiceId, 2, true);
        $shipCharge = $row['shipping'];
        $extraFees = $invoiceCalculator->calcTotalAdditionalCharges($invoiceId, true);
        $totalPayments = $invoiceCalculator->calcTotalPayments($invoiceId, true);
        $lineItemsCell = $this->buildLineItemsCell($invoiceId);

        $paymentsString = '';
        $payments = $this->getInvoicePaymentManager()->loadForInvoice($invoiceId, true);
        foreach ($payments as $payment) {
            $paymentMethodName = $this->getPaymentRenderer()->makePaymentMethodTranslated($payment->PaymentMethodId);
            $amount = $payment->Amount;
            if ($payment->CreditCardId) {
                $creditCardSeparator = " - ";
                $creditCard = $this->getCreditCardLoader()->load($payment->CreditCardId, true);
                $creditCardName = $creditCard->Name ?? '';
                $paymentMethodName .= $creditCardSeparator . $creditCardName;
            }
            $note = $payment->Note ?? 'null';
            $date = $payment->PaidOn ?: new DateTime($payment->CreatedOn);
            $date = $this->getDateHelper()->convertUtcToSys($date);
            $dateFormatted = $date->format('m/d/Y h:i a');
            $paymentsString .= "$amount;$paymentMethodName;$dateFormatted;$note|";
        }

        $nonTaxableFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($nonTaxable);
        $taxableFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($taxable);
        $exportFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($export);
        $premiumFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($premiumTotal);
        $extraFeesFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($extraFees);
        $shipChargeFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($shipCharge);
        $paymentFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($totalPayments);
        $grandTotalFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($grandTotal);
        $balanceFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($balanceDue);
        $paymentStatus = Constants\Invoice::$invoiceStatusNames[(int)$row['invoice_status_id']];
        $buyerTaxRate = $buyerSalesTax;

        $auctionId = $this->getInvoiceItemLoader()->findFirstInvoicedAuctionId($invoiceId);
        $auctionBidder = $this->getAuctionBidderLoader()->load((int)$row['bidder_id'], $auctionId, true);
        $resellerStatus = 'No';
        if ($auctionBidder) {
            if ($auctionBidder->IsReseller) {
                $resellerStatus = 'Pending';
                if ($auctionBidder->ResellerApproved) {
                    $resellerStatus = 'Yes';
                }
            }
        }
        $salesTaxAmount = $invoiceTaxCalculator->calcTotalSalesTaxApplied($invoiceId);
        $salesTaxFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($salesTaxAmount);

        /*-------------------------------------
         * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
         * This might include adding, changing, or moving columns,
         * modifying header names,
         * modifying data or data format(s)
         *-------------------------------------*/
        $bodyRow = [
            $invoiceNo,
            $invoiceDate,
            $sentDateFormatted,
            $saleName,
            $bidderNum,
            $username,
            $state,
            $zip,
            $nonTaxableFormatted,
            $taxableFormatted,
            $exportFormatted,
            $premiumFormatted,
            $salesTaxFormatted,
            $shipChargeFormatted,
            $extraFeesFormatted,
            $grandTotalFormatted,
            $paymentFormatted,
            $balanceFormatted,
            $paymentStatus,
            $internalNote,
            $saleNo,
            $saleDesc,
            $customerID,
            $firstName,
            $lastName,
            $email,
            $phone,
            $billCompany,
            $billFirstName,
            $billLastName,
            $billPhone,
            $billStreet,
            $billStreet2,
            $billStreet3,
            $billCity,
            $billState,
            $billZip,
            $billCountry,
            $shipCompany,
            $shipFirstName,
            $shipLastName,
            $shipPhone,
            $shipStreet,
            $shipStreet2,
            $shipStreet3,
            $shipCity,
            $shipState,
            $shipZip,
            $shipCountry,
            $lineItemsCell,
            $shipChargeFormatted,
            $paymentsString,
            $buyerTaxRate,
            $resellerStatus,
            $referrer,
            $referrerHost,
        ];

        if ($this->isCustomFieldRender()) {
            $dbTransformer = DbTextTransformer::new();
            $userCustomFieldsData = [];
            foreach ($this->getUserCustomFields() as $userCustomField) {
                $alias = sprintf('ucf%s', $dbTransformer->toDbColumn($userCustomField->Name));
                $userCustomFieldsData[] = $this->getUserCustomFieldCsvRenderer()
                    ->renderByValue($userCustomField->Type, $row[$alias], $userCustomField->Parameters);
            }
            $bodyRow = array_merge($bodyRow, $userCustomFieldsData);
        }

        $bodyLine = $this->makeLine($bodyRow);

        return $bodyLine;
    }

    protected function buildLineItemsCell(int $invoiceId): string
    {
        $dataProvider = $this->createDataProvider();
        $lotCustomFieldIds = ArrayHelper::toArrayByProperty($this->getLotCustomFields(), 'Id');
        $lotCustomFieldCsvRenderer = $this->getLotCustomFieldCsvRenderer();

        $lineItems = [];
        $invoiceItems = $dataProvider->loadLotsRows($invoiceId, true);
        foreach ($invoiceItems as $invoiceItem) {
            /**
             * Collect invoice lot item values
             */
            $lotNo = $invoiceItem->makeLotNo();
            $itemNo = $invoiceItem->makeItemNo();
            $hammer = $invoiceItem->hammerPrice;
            $premium = $invoiceItem->buyersPremium;
            $subTotal = $invoiceItem->calcSubTotal();
            $saleTax = $subTotal - ($hammer + $premium);
            $lotFields = [$lotNo, $itemNo, $hammer, $premium, $saleTax];

            /**
             * Collect lot custom data values
             */
            $lotCustomFieldValues = [];
            if ($this->isCustomFieldRender()) {
                $lotCustomDatas = $dataProvider->loadLotCustomFields($lotCustomFieldIds, $invoiceItem->lotItemId, true);
                foreach ($this->getLotCustomFields() as $lotCustomField) {
                    $lotCustomData = $lotCustomDatas[$lotCustomField->Id] ?? null;
                    if (!$lotCustomData) {
                        $message = 'Lot item custom data record not found for lot item custom field id'
                            . composeSuffix(['licf' => $lotCustomField->Id]);
                        log_error($message);
                        continue;
                    }
                    $value = $lotCustomField->isNumeric()
                        ? $lotCustomData->Numeric
                        : $lotCustomData->Text;
                    $lotCustomFieldValues[] = $lotCustomFieldCsvRenderer->renderByValue(
                        $lotCustomField->Type,
                        $value,
                        $lotCustomField->Parameters
                    );
                }
            }

            $lineItems[] = array_merge($lotFields, $lotCustomFieldValues);
        }
        $lineItemsCell = NestedArrayBuilder::new()->build($lineItems);
        return $lineItemsCell;
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $output = '';
        while ($rows = $this->getDataLoader()->loadNextChunk()) {
            foreach ($rows as $row) {
                $bodyLine = $this->buildBodyLine($row);
                $output .= $this->processOutput($bodyLine);
            }
        }
        return $output;
    }

    /**
     * Output CSV header Titles
     * @return string
     */
    protected function outputTitles(): string
    {
        $headerRow = [
            'Inv. #',
            'Issued',
            'Sent',
            'Sale',
            'B#',
            'User',
            'St.',
            'ZIP',
            'Non Taxable',
            'Taxable',
            'Export',
            'Premium',
            'Tax',
            'Shipping',
            'Extra Charges',
            'Total',
            'Payment',
            'Balance',
            'Status',
            'Internal notes',
            'Sale #',
            'Sale Description',
            'SWB Customer ID',
            'Main First Name',
            'Main Last Name',
            'Email',
            'Main Phone #',
            'Bill Company',
            'Bill First Name',
            'Bill Last Name',
            'Bill Phone #',
            'Bill Street',
            'Bill Street 2',
            'Bill Street 3',
            'Bill City',
            'Bill Region/State',
            'Bill Zip /Postal Code',
            'Bill Country',
            'Ship Company',
            'Ship First Name',
            'Ship Last Name',
            'Ship Phone #',
            'Ship Street',
            'Ship Street 2',
            'Ship Street 3',
            'Ship City',
            'Ship Region/State',
            'Ship Zip /Postal Code',
            'Ship Country',
            'Line Items',
            'Shipping Charge',
            'Payments',
            'Buyer Tax Rate',
            'Reseller Status',
            'Referrer',
            'Referrer Host',
        ];

        if ($this->isCustomFieldRender()) {
            foreach ($this->getUserCustomFields() ?: [] as $userCustomField) {
                $headerRow[] = $userCustomField->Name;
            }
        }

        $headerLine = $this->makeLine($headerRow);

        return $this->processOutput($headerLine);
    }

    /**
     * Overload LotCustomFieldsAwareTrait::getLotCustomFields()
     * @return LotItemCustField[]
     */
    protected function getLotCustomFields(): array
    {
        if ($this->lotCustomFields === null) {
            $this->lotCustomFields = $this->createLotCustomFieldLoader()->loadInInvoices(true);
        }
        return $this->lotCustomFields;
    }

    /**
     * Overload UserCustomFieldsAwareTrait::getUserCustomFields()
     * @return UserCustField[]
     */
    protected function getUserCustomFields(): array
    {
        if ($this->userCustomFields === null) {
            $this->userCustomFields = $this->getUserCustomFieldLoader()->loadInInvoices(true);
        }
        return $this->userCustomFields;
    }

    /**
     * @return bool
     */
    public function isAccountFiltering(): bool
    {
        return $this->createApplicationAccessChecker()->isCrossDomainAdminOnMainAccountForMultipleTenant(
            $this->editorUserId,
            $this->getSystemAccountId(),
            true
        );
    }
}
