<?php
/**
 * SAM-4624 : Refactor invoice item sold report
 * https://bidpath.atlassian.net/browse/SAM-4624
 *
 * @author        Imran Rahman
 * @version       SVN: $Id: $
 * @since         Dec 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 */

namespace Sam\Report\Invoice\Legacy\ItemSold;

use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Billing\Payment\Render\PaymentRendererAwareTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Invoice\Render\InvoicePureRenderer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Renderer\LotCategoryRendererAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class InvoiceItemSoldReporter
 * @package Sam\Report\Invoice\Legacy\ItemSold
 */
class LegacyInvoiceItemSoldReporter extends ReporterBase
{
    use ApplicationAccessCheckerCreateTrait;
    use AuctionRendererAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use FilePathHelperAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterDatePeriodAwareTrait;
    use LotCategoryRendererAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use PaymentRendererAwareTrait;
    use SortInfoAwareTrait;

    private const DATE_FORMAT = 'm/d/Y';

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
     * @param DataLoader $dataLoader
     * @return static
     */
    public function setDataLoader(DataLoader $dataLoader): static
    {
        $this->dataLoader = $dataLoader;
        return $this;
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
                ->filterAuctionId($this->getFilterAuctionId())
                ->filterAccountId($this->getFilterAccountId())
                ->filterEndDateSysIso($this->getFilterEndDateSysIso())
                ->filterStartDateSysIso($this->getFilterStartDateSysIso())
                ->setSortColumn($this->getSortColumn())
                ->setSystemAccountId($this->getSystemAccountId());
        }
        return $this->dataLoader;
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $dateIso = $this->getCurrentDateUtc()->format('m-d-Y-His');
            $filename = "items-sold_{$dateIso}.csv";
            $filename = $this->getFilePathHelper()->toFilename($filename);
            $this->outputFileName = $filename;
        }
        return $this->outputFileName;
    }

    /**
     * Output titles for csv header (string or echo)
     * @return string
     */
    protected function outputTitles(): string
    {
        $headerTitles = ["Invoice#"];
        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $headerTitles[] = "LotFull#";
        } else {
            array_push($headerTitles, "Lot# Prefix", "Lot#", "Lot# Ext.");
        }
        array_push($headerTitles, "Invoice Date", "Payment Date", "Sale Name");
        if ($this->cfg()->get('core->auction->saleNo->concatenated')) {
            $headerTitles[] = "SaleFull#";
        } else {
            array_push($headerTitles, "Sale#", "Sale# Ext.");
        }
        if ($this->cfg()->get('core->lot->itemNo->concatenated')) {
            $headerTitles[] = "ItemFull#";
        } else {
            array_push($headerTitles, "Item#", "Item# Ext.");
        }
        array_push($headerTitles, "Item Name", "Qty", "Category");
        foreach ($this->getDataLoader()->loadLotCustomFields() ?: [] as $lotCustomField) {
            $headerTitles[] = $lotCustomField->Name;
        }
        array_push(
            $headerTitles,
            "Hammer Price",
            "Buyers Premium",
            "Sales Tax",
            "Subtotal",
            "Invoice Total",
            "Invoice Status",
            /****** user csv *******/
            "Username",
            "SWB Customer ID",
            "Main First Name",
            "Main Last Name",
            "Email",
            "Main Phone#",
            "Bill Company",
            "Bill First Name",
            "Bill Last Name",
            "Bill Phone#",
            "Bill Street",
            "Bill Street 2",
            "Bill Street 3",
            "Bill City",
            "Bill Region/State",
            "Bill Zip /Postal Code",
            "Bill Country",
            "Ship Company",
            "Ship First Name",
            "Ship Last Name",
            "Ship Phone#",
            "Ship Street",
            "Ship Street 2",
            "Ship Street 3",
            "Ship City",
            "Ship Region/State",
            "Ship Zip /Postal Code",
            "Ship Country",
            "Referrer",
            "Referrer host"
        );
        foreach ($this->getDataLoader()->loadUserCustomFields() as $userCustomField) {
            $headerTitles[] = $userCustomField->Name;
        }

        $headerLine = $this->makeLine($headerTitles);

        return $this->processOutput($headerLine);
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $output = '';
        $rows = $this->getDataLoader()->load();
        foreach ($rows as $row) {
            $invoiceId = (int)$row['inv_id'];
            $billCountry = AddressRenderer::new()->countryName((string)$row['billing_country']);
            $shipCountry = AddressRenderer::new()->countryName((string)$row['shipping_country']);
            $recentPaymentDate = $this->getDataLoader()->getRecentPaymentDate($invoiceId);
            $invoiceItemRows = $this->getDataLoader()->loadInvoiceItem($invoiceId);

            foreach ($invoiceItemRows as $invoiceItemRow) {
                $currencySign = $this->getCurrencyLoader()->detectDefaultSign((int)$invoiceItemRow['auction_id']);
                $lotCustomFields = $this->getLotCustomFieldsData($invoiceItemRow);
                $userCustomFields = $this->getUserCustomFieldsData($row);
                $categories = $this->getLotCategoryRenderer()->getCategoriesText((int)$invoiceItemRow['lot_item_id']);
                $output .= $this->outputResult(
                    $row,
                    $invoiceItemRow,
                    $currencySign,
                    $recentPaymentDate,
                    $categories,
                    $lotCustomFields,
                    $billCountry,
                    $shipCountry,
                    $userCustomFields
                );
            }

            if (in_array((int)$this->getSortColumn(), [1, 2], true)) { // Invoice date/Payment date
                $currencySign = $this->getCurrencyLoader()->detectDefaultSign();
                $noOfLotCustomFields = count($this->getDataLoader()->loadLotCustomFields());
                $customLotEmptyFields = array_fill(0, $noOfLotCustomFields, '');
                $noOfUserCustomFields = count($this->getDataLoader()->loadUserCustomFields());
                $userCustomEmptyFields = array_fill(0, $noOfUserCustomFields, '');

                /* added shipping, extra charges and payment rows. */
                $invoiceItemRow['lot_num_prefix'] = '';
                $invoiceItemRow['lot_num'] = '';
                $invoiceItemRow['lot_num_ext'] = '';
                $invoiceItemRow['item_num'] = '';
                $invoiceItemRow['item_num_ext'] = '';
                $invoiceItemRow['quantity'] = '';
                $invoiceItemRow['hammer_price'] = '';
                $invoiceItemRow['buyers_premium'] = '';
                $invoiceItemRow['computed_sales_tax'] = '';
                $invoiceItemRow['sale_name'] = '';
                $invoiceItemRow['test_auction'] = '';
                $invoiceItemRow['sale_num'] = '';
                $invoiceItemRow['sale_num_ext'] = '';
                if ((float)$row['shipping_fee'] > 0) { // has shipping
                    $invoiceItemRow['subtotal'] = (float)$row['shipping_fee'];
                    $invoiceItemRow['lot_name'] = 'Shipping & Handling';

                    $output .= $this->outputResult(
                        $row,
                        $invoiceItemRow,
                        $currencySign,
                        $recentPaymentDate,
                        'Shipping',
                        $customLotEmptyFields,
                        $billCountry,
                        $shipCountry,
                        $userCustomEmptyFields
                    );
                }
                $invoiceAdditionalRows = $this->getDataLoader()->loadInvoiceAdditionalByInvoiceId($invoiceId);
                foreach ($invoiceAdditionalRows as $rowInvoiceAdditional) {
                    $invoiceItemRow['subtotal'] = (float)$rowInvoiceAdditional['amount'];
                    $invoiceItemRow['lot_name'] = $rowInvoiceAdditional['name'];

                    $output .= $this->outputResult(
                        $row,
                        $invoiceItemRow,
                        $currencySign,
                        $recentPaymentDate,
                        'Extra Charges',
                        $customLotEmptyFields,
                        $billCountry,
                        $shipCountry,
                        $userCustomEmptyFields
                    );
                }
                $rowPayments = $this->getDataLoader()->loadPayments($invoiceId);
                foreach ($rowPayments as $rowPayment) {
                    $invoiceItemRow['subtotal'] = (float)$rowPayment['amount'] > 0
                        ? ((float)$rowPayment['amount'] * -1) : abs((float)$rowPayment['amount']);

                    $invoiceItemRow['lot_name'] = $rowPayment['note'];
                    $categories = $this->getPaymentRenderer()->makePaymentMethodTranslated((int)$rowPayment['payment_method_id']);
                    if ((int)$rowPayment['payment_method_id'] !== Constants\Payment::PM_CREDIT_MEMO) {
                        $categories = 'Payment - ' . $categories;
                    }
                    $output .= $this->outputResult(
                        $row,
                        $invoiceItemRow,
                        $currencySign,
                        $recentPaymentDate,
                        $categories,
                        $customLotEmptyFields,
                        $billCountry,
                        $shipCountry,
                        $userCustomEmptyFields
                    );
                }
            }
        }
        return $output;
    }

    /**
     * Preparing output result
     *
     * @param array $row
     * @param array $itemRow
     * @param string $currencySign
     * @param string $recentPaymentDate
     * @param string $categories
     * @param array $lotCustomFieldsData
     * @param string $billCountry
     * @param string $shipCountry
     * @param array $userCustomFieldsData
     * @return string
     */
    protected function outputResult(
        array $row,
        array $itemRow,
        string $currencySign,
        string $recentPaymentDate,
        string $categories,
        array $lotCustomFieldsData,
        string $billCountry,
        string $shipCountry,
        array $userCustomFieldsData
    ): string {
        $saleName = $this->getAuctionRenderer()->makeName($itemRow['sale_name'], (bool)$itemRow['test_auction']);
        $lotName = $this->getLotRenderer()->makeName($itemRow['lot_name'], (bool)$itemRow['test_auction']);
        // We pass empty strings in cells for some rows
        $hammerPriceFormatted = (string)$itemRow['hammer_price'] !== ''
            ? $currencySign . $this->getNumberFormatter()->formatMoney($itemRow['hammer_price'])
            : '';
        $buyersPremiumFormatted = (string)$itemRow['buyers_premium'] !== ''
            ? $currencySign . $this->getNumberFormatter()->formatMoney($itemRow['buyers_premium'])
            : '';
        $computedSalesTaxFormatted = (string)$itemRow['computed_sales_tax'] !== ''
            ? $currencySign . $this->getNumberFormatter()->formatMoney($itemRow['computed_sales_tax'])
            : '';
        $subtotalFormatted = (string)$itemRow['subtotal'] !== ''
            ? $currencySign . $this->getNumberFormatter()->formatMoney($itemRow['subtotal'])
            : '';
        $grandTotal = $currencySign . $this->getNumberFormatter()->formatMoney($row['grand_total']);
        $status = InvoicePureRenderer::new()->makeInvoiceStatus((int)$row['invoice_status_id']);
        $bodyRow[] = $row['inv_no'];
        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $bodyRow[] = $this->getLotRenderer()->makeLotNo($itemRow['lot_num'], $itemRow['lot_num_ext'], $itemRow['lot_num_prefix']);
        } else {
            $bodyRow = array_merge(
                $bodyRow,
                [
                    $itemRow['lot_num_prefix'],
                    $itemRow['lot_num'],
                    $itemRow['lot_num_ext'],
                ]
            );
        }
        $invDate = $this->getDateHelper()->convertUtcToSysByDateIso($row['inv_date']);
        $bodyRow [] = $invDate ? $invDate->format(self::DATE_FORMAT) : '';
        $bodyRow [] = $recentPaymentDate;
        $bodyRow [] = $saleName;

        if ($this->cfg()->get('core->auction->saleNo->concatenated')) {
            $bodyRow[] = $this->getAuctionRenderer()->makeSaleNo($itemRow['sale_num'], $itemRow['sale_num_ext']);
        } else {
            $bodyRow = array_merge(
                $bodyRow,
                [
                    $itemRow['sale_num'],
                    $itemRow['sale_num_ext'],
                ]
            );
        }
        if ($this->cfg()->get('core->lot->itemNo->concatenated')) {
            $bodyRow[] = $this->getLotRenderer()->makeItemNo($itemRow['item_num'], $itemRow['item_num_ext']);
        } else {
            $bodyRow = array_merge(
                $bodyRow,
                [
                    $itemRow['item_num'],
                    $itemRow['item_num_ext'],
                ]
            );
        }
        $bodyRow[] = $lotName;
        $bodyRow[] = $itemRow['quantity']
            ? $this->getNumberFormatter()->formatNto((float)$itemRow['quantity'], (int)$itemRow['quantity_scale'])
            : '';
        $bodyRow[] = $categories;

        $bodyRow = array_merge(
            $bodyRow,
            $lotCustomFieldsData,
            [
                $hammerPriceFormatted,
                $buyersPremiumFormatted,
                $computedSalesTaxFormatted,
                $subtotalFormatted,
                $grandTotal,
                $status,
                $row['username'],
                $row['customer_no'],
                $row['first_name'],
                $row['last_name'],
                $row['email'],
                $row['phone'],
                $row['billing_company_name'],
                $row['billing_first_name'],
                $row['billing_last_name'],
                $row['billing_phone'],
                $row['billing_address'],
                $row['billing_address2'],
                $row['billing_address3'],
                $row['billing_city'],
                $row['billing_state'],
                $row['billing_zip'],
                $billCountry,
                $row['shipping_company_name'],
                $row['shipping_first_name'],
                $row['shipping_last_name'],
                $row['shipping_phone'],
                $row['shipping_address'],
                $row['shipping_address2'],
                $row['shipping_address3'],
                $row['shipping_city'],
                $row['shipping_state'],
                $row['shipping_zip'],
                $shipCountry,
                $row['referrer'],
                $row['referrer_host'],
            ],
            $userCustomFieldsData
        );

        $bodyLine = $this->makeLine($bodyRow);
        $output = $this->processOutput($bodyLine);

        return $output;
    }

    /**
     * @param array $rowItem
     * @return array
     */
    protected function getLotCustomFieldsData(array $rowItem): array
    {
        $lotCustomFieldsData = [];
        foreach ($this->getDataLoader()->loadLotCustomFields() as $lotCustomField) {
            $lotCustomData = $this->getDataLoader()->loadLotCustomData($lotCustomField->Id, (int)$rowItem['lot_item_id']);
            $value = '';
            if ($lotCustomData) { //numeric
                $value = in_array(
                    $lotCustomField->Type,
                    [Constants\CustomField::TYPE_INTEGER, Constants\CustomField::TYPE_DECIMAL],
                    true
                )
                    ? $lotCustomData->Numeric
                    : $lotCustomData->Text;
            }
            $lotCustomFieldsData[] = $value;
        }
        return $lotCustomFieldsData;
    }

    /**
     * @param array $row
     * @return array
     */
    protected function getUserCustomFieldsData(array $row): array
    {
        $userCustomFieldsData = [];
        foreach ($this->getDataLoader()->loadUserCustomFields() as $userCustomField) {
            $userCustomData = $this->getDataLoader()->loadUserCustomDataOrCreate($userCustomField, (int)$row['id']);
            if (in_array(
                $userCustomField->Type,
                [
                    Constants\CustomField::TYPE_INTEGER,
                    Constants\CustomField::TYPE_DECIMAL,
                    Constants\CustomField::TYPE_DATE,
                ],
                true
            )
            ) { //numeric
                if ($userCustomData->Numeric === null) {
                    $value = '';
                } elseif ($userCustomField->Type === Constants\CustomField::TYPE_DATE) {
                    $dateSys = $this->getDateHelper()->convertUtcToSysByTimestamp($userCustomData->Numeric);
                    $value = $this->getDateHelper()->formattedDate($dateSys);
                } elseif ($userCustomField->Type === Constants\CustomField::TYPE_DECIMAL) {
                    $precision = (int)$userCustomField->Parameters;
                    $realValue = $userCustomData->calcDecimalValue($precision);
                    $value = $this->getNumberFormatter()->format($realValue, $precision);
                } else {
                    $value = $userCustomData->Numeric;
                }
            } elseif ($userCustomField->Type === Constants\CustomField::TYPE_CHECKBOX) {
                $value = $this->getReportTool()->renderBool((bool)$userCustomData->Numeric);
            } elseif ($userCustomField->Type === Constants\CustomField::TYPE_LABEL) {
                $value = $userCustomField->Parameters;
            } else { //text
                $value = $userCustomData->Text;
            }
            $userCustomFieldsData[] = $value;
        }
        return $userCustomFieldsData;
    }

    /**
     * @return bool
     */
    protected function validate(): bool
    {
        $startDateSysIso = $this->getFilterStartDateSysIso();
        $endDateSysIso = $this->getFilterEndDateSysIso();
        $sortColumn = $this->getSortColumn();
        $accountId = $this->getFilterAccountId();
        $errorMessages = [];
        if (!strtotime($startDateSysIso)) {
            $errorMessages[] = 'Invalid start date';
        }
        if (!strtotime($endDateSysIso)) {
            $errorMessages[] = 'Invalid end date';
        }
        if (strtotime($startDateSysIso) > strtotime($endDateSysIso)) {
            $errorMessages[] = 'Start date is later than end date';
        }
        if (!is_numeric($sortColumn)) {
            $errorMessages[] = ' Sort column should be numeric';
        }
        if ($accountId && !is_numeric($sortColumn)) {
            $errorMessages[] = ' Account Id should be numeric';
        }
        $this->errorMessage = implode('; ', $errorMessages);
        $success = count($errorMessages) === 0;
        return $success;
    }

    /**
     * @return bool
     */
    protected function isAccountFiltering(): bool
    {
        return $this->createApplicationAccessChecker()->isCrossDomainAdminOnMainAccountForMultipleTenant(
            $this->editorUserId,
            $this->getSystemAccountId(),
            true
        );
    }
}
