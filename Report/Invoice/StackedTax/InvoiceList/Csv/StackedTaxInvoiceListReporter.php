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

namespace Sam\Report\Invoice\StackedTax\InvoiceList\Csv;

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
use Sam\Invoice\StackedTax\View\Common\Goods\Load\InvoiceItemDataLoaderCreateTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Report\Invoice\StackedTax\InvoiceList\Csv\Internal\Load\DataProviderCreateTrait;
use Sam\Report\Invoice\StackedTax\InvoiceList\Csv\Internal\Render\Nested\NestedArrayBuilder;
use Sam\Storage\Entity\AwareTrait\LotCustomFieldsAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserCustomFieldsAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use UserCustField;

/**
 * Class InvoiceListReporter
 * @package Sam\Report\Invoice\StackedTax\InvoiceList
 */
class StackedTaxInvoiceListReporter extends ReporterBase
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
    use InvoiceItemDataLoaderCreateTrait;
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

    protected function buildInvoiceListRow(StackedTaxInvoiceListDto $dto): array
    {
        $saleNo = '';
        $saleName = '';
        $saleDesc = '';
        $this->getNumberFormatter()->construct($this->getSystemAccountId());
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign($dto->saleId, true);
        if (!$this->isMultipleSaleInvoice()) {
            /** Convert UTF-8 encoding to the set encoding for export in settings **/
            $saleNo = $dto->saleNo;
            $saleName = $dto->saleName;
            $saleDesc = $dto->saleDescription;
        }
        $dateIso = $dto->invoiceDate ?: $dto->createdDate;
        $date = $this->getDateHelper()->convertUtcToSysByDateIso($dateIso);
        $invoiceDate = $date ? $date->format(Constants\Date::US_DATE) : '';
        $bidderNum = $dto->bidderNumber ? $this->getBidderNumberPadding()->clear($dto->bidderNumber) : '';
        $quantity = $dto->quantity ? $this->getNumberFormatter()->formatNto($dto->quantity, $dto->quantityScale) : '';

        // calculated total with tax
        $totalHpTax = $dto->hammerPrice + $dto->hammerPriceTax;
        $totalBpTax = $dto->buyerPremium + $dto->buyerPremiumTax;
        $totalServicesTax = $dto->services + $dto->servicesTax;
        /*-------------------------------------
         * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
         * This might include adding, changing, or moving columns,
         * modifying header names,
         * modifying data or data format(s)
         *-------------------------------------*/
        return [
            $dto->invoiceNo,
            $dto->itemNo,
            $dto->lotNo,
            $quantity,
            $invoiceDate,
            $this->getFirstSentOn($dto->firstSentOn),
            $saleName,
            $bidderNum,
            $dto->username,
            $dto->state,
            $dto->zip,
            $this->renderFormattedMoney($currencySign, $dto->hammerPrice),
            $this->renderFormattedMoney($currencySign, $dto->hammerPriceTax),
            $this->renderFormattedMoney($currencySign, $totalHpTax),
            $this->renderFormattedMoney($currencySign, $dto->buyerPremium),
            $this->renderFormattedMoney($currencySign, $dto->buyerPremiumTax),
            $this->renderFormattedMoney($currencySign, $totalBpTax),
            $this->renderFormattedMoney($currencySign, $dto->services),
            $this->renderFormattedMoney($currencySign, $dto->servicesTax),
            $this->renderFormattedMoney($currencySign, $totalServicesTax),
            $this->renderFormattedMoney($currencySign, $dto->taxTotal),
            $this->renderFormattedMoney($currencySign, $dto->countryTaxTotal),
            $this->renderFormattedMoney($currencySign, $dto->stateTaxTotal),
            $this->renderFormattedMoney($currencySign, $dto->countyTaxTotal),
            $this->renderFormattedMoney($currencySign, $dto->cityTaxTotal),
            $this->renderFormattedMoney($currencySign, $dto->grandTotal),
            $this->renderFormattedMoney($currencySign, $dto->totalPayments),
            $this->renderFormattedMoney($currencySign, $dto->balanceDue),
            Constants\Invoice::$invoiceStatusNames[$dto->invoiceStatusId],
            $dto->internalNote,
            $saleNo,
            $saleDesc,
            $dto->customerNo,
            $dto->firstName,
            $dto->lastName,
            $dto->email,
            $dto->iphone,
            $dto->billingCompanyName,
            $dto->billingFirstName,
            $dto->billingLastName,
            $dto->billingPhoneNo,
            $dto->billingAddress,
            $dto->billingAddress2,
            $dto->billingAddress3,
            $dto->billingCity,
            $dto->billingState,
            $dto->billingZip,
            AddressRenderer::new()->countryName($dto->billingCountry),
            $dto->shippingCompanyName,
            $dto->shippingFirstName,
            $dto->shippingLastName,
            $dto->shippingPhoneNo,
            $dto->shippingAddress,
            $dto->shippingAddress2,
            $dto->shippingAddress3,
            $dto->shippingCity,
            $dto->shippingState,
            $dto->shippingZip,
            AddressRenderer::new()->countryName($dto->shippingCountry),
            $this->buildLineItemsCell($dto->invoiceId),
            $this->buildPaymentsString($dto->invoiceId),
            $this->getResellerStatus($dto->invoiceId, $dto->bidderId),
            $dto->referer,
            $dto->refererHost,
        ];
    }

    protected function buildCustomFieldBodyRow(array $row): array
    {
        $userCustomFieldsData = [];
        if ($this->isCustomFieldRender()) {
            $dbTransformer = DbTextTransformer::new();
            foreach ($this->getUserCustomFields() as $userCustomField) {
                $alias = sprintf('ucf%s', $dbTransformer->toDbColumn($userCustomField->Name));
                $userCustomFieldsData[] = $this->getUserCustomFieldCsvRenderer()
                    ->renderByValue($userCustomField->Type, $row[$alias], $userCustomField->Parameters);
            }
        }
        return $userCustomFieldsData;
    }

    protected function buildLineItemsCell(int $invoiceId): string
    {
        $dataProvider = $this->createDataProvider();
        $lotCustomFieldIds = ArrayHelper::toArrayByProperty($this->getLotCustomFields(), 'Id');
        $lotCustomFieldCsvRenderer = $this->getLotCustomFieldCsvRenderer();

        $lineItems = [];
        $invoiceItems = $this->createInvoiceItemDataLoader()->loadForInvoice($invoiceId, [], true);
        foreach ($invoiceItems as $invoiceItem) {
            /**
             * Collect invoice lot item values
             */
            $lotNo = $invoiceItem->lotNo;
            $itemNo = $invoiceItem->itemNo;
            $hammer = $invoiceItem->hp;
            $premium = $invoiceItem->bp;
            $saleTax = $invoiceItem->hpTaxAmount + $invoiceItem->bpTaxAmount;
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
                $stackedTaxInvoiceListDto = StackedTaxInvoiceListDto::new()->fromDbRow($row);
                $invoiceListRow = $this->buildInvoiceListRow($stackedTaxInvoiceListDto);
                $customFieldBodyRow = $this->buildCustomFieldBodyRow($row);
                $bodyRow = array_merge($invoiceListRow, $customFieldBodyRow);
                $bodyRowLine = $this->makeLine($bodyRow);
                $output .= $this->processOutput($bodyRowLine);
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
            'Item #',
            'Lot #',
            'Quantity',
            'Issued',
            'Sent',
            'Sale',
            'B#',
            'User',
            'State',
            'ZIP',
            'Total HP',
            'Total HP Tax',
            'Total HP+TAX',
            'Total BP',
            'Total BP Tax',
            'Total BP+TAX',
            'Total Services',
            'Total Service Tax',
            'Total Service + Tax',
            'Total Tax',
            'Country Tax',
            'State Tax',
            'County Tax',
            'City Tax',
            'Invoice total',
            'Payment total',
            'Balance due',
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
            'Payments',
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

    public function buildPaymentsString(int $invoiceId): string
    {
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
        return $paymentsString;
    }

    protected function renderFormattedMoney(string $currencySign, float $amount): string
    {
        return $currencySign . $this->getNumberFormatter()->formatMoney($amount);
    }

    protected function getFirstSentOn(string $firstSentOn): string
    {
        if ($firstSentOn) {
            $sentDate = $this->getDateHelper()->convertUtcToSysByDateIso($firstSentOn);
            $sentDateFormatted = $sentDate ? $sentDate->format(Constants\Date::US_DATE) : '';
        } else {
            $sentDateFormatted = '';
        }
        return $sentDateFormatted;
    }

    public function getResellerStatus(int $invoiceId, int $bidderId): string
    {
        $auctionId = $this->getInvoiceItemLoader()->findFirstInvoicedAuctionId($invoiceId);
        $auctionBidder = $this->getAuctionBidderLoader()->load($bidderId, $auctionId, true);
        $resellerStatus = 'No';
        if ($auctionBidder) {
            if ($auctionBidder->IsReseller) {
                $resellerStatus = 'Pending';
                if ($auctionBidder->ResellerApproved) {
                    $resellerStatus = 'Yes';
                }
            }
        }
        return $resellerStatus;
    }
}
