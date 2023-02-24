<?php
/**
 * SAM-4633:Refactor sales staff report
 * https://bidpath.atlassian.net/browse/SAM-4633
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/11/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SaleStaff\Csv;

use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants\Admin\SaleStaffReportFormConstants;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Locale\Formatter\DateTimeFormatterAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Report\SaleStaff\Calculate\Dto\SaleStaffPayoutCalculatorData;
use Sam\Report\SaleStaff\Calculate\SaleStaffPayoutCalculator;
use Sam\Report\SaleStaff\Common\FilterAwareTrait;
use Sam\Report\SaleStaff\StaffReporterHelper;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class Reporter
 * @package Sam\Report\SaleStaff\Csv
 */
class Reporter extends ReporterBase
{
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use DateTimeFormatterAwareTrait;
    use FilePathHelperAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAwareTrait;
    use FilterDatePeriodAwareTrait;
    use LimitInfoAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use SortInfoAwareTrait;

    private const CHUNK_SIZE = 200;

    protected ?DataLoader $dataLoader = null;
    protected ?StaffReporterHelper $helper = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return StaffReporterHelper
     */
    public function getHelper(): StaffReporterHelper
    {
        if ($this->helper === null) {
            $this->helper = StaffReporterHelper::new()
                ->setSortColumn($this->getSortColumn());
        }
        return $this->helper;
    }

    /**
     * @param StaffReporterHelper $helper
     * @return static
     */
    public function setHelper(StaffReporterHelper $helper): static
    {
        $this->helper = $helper;
        return $this;
    }

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new()
                ->filterAccountId($this->getFilterAccountId())
                ->setDateRangeType($this->getDateRangeType())
                ->setSalesStaff($this->getSalesStaff())
                ->filterStartDateSysIso($this->getFilterStartDateSysIso())
                ->filterEndDateSysIso($this->getFilterEndDateSysIso())
                ->setChunkSize(self::CHUNK_SIZE)
                ->setSortColumn($this->getSortColumn())
                ->enableAscendingOrder($this->isAscendingOrder());
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
     * @return string
     */
    protected function outputTitles(): string
    {
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign();
        $headerTitles = [
            "Sales Staff",
            "Office Location",
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
            "Consignor",
            "Consignor Company",
            "Buyer",
            "Buyer Company",
            "Item# ",
            "Auction",
            "Lot# ",
            "Lot name",
            "Invoice status",
            "Hammer[" . $currencySign . "]",
            "BP[" . $currencySign . "]",
            "Tax[" . $currencySign . "]",
            "Total[" . $currencySign . "]",
            "Payout[" . $currencySign . "]",
            "Date sold",
            "Invoice date",
            "Payment date",
            "Referrer",
            "Referrer host",
        ];

        $headerLine = $this->makeLine($headerTitles);
        return $this->processOutput($headerLine);
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $output = '';
        foreach ($this->aggregateRows() as $row) {
            $bodyLine = $this->buildBodyLine($row);
            $output .= $this->processOutput($bodyLine);
        }
        return $output;
    }

    /**
     * @return array
     */
    public function aggregateRows(): array
    {
        $aggregatedRows = [];
        while ($rows = $this->getDataLoader()->loadNextChunk()) {
            foreach ($rows as $row) {
                $row = $this->getHelper()->castRowData($row);
                if ($this->getConsolidationType() === SaleStaffReportFormConstants::CT_NONE) {
                    $aggregatedRows[] = $row;
                } elseif (in_array(
                    $this->getConsolidationType(),
                    [
                        SaleStaffReportFormConstants::CT_SALE_STAFF,
                        SaleStaffReportFormConstants::CT_LOCATION_OFFICE
                    ],
                    true
                )
                ) {
                    $consolidateType = ($this->getConsolidationType() === SaleStaffReportFormConstants::CT_SALE_STAFF)
                        ? 'sales_staff' : 'office_location';
                    $aggregatingRow = $aggregatedRows[$row[$consolidateType]] ?? $this->getHelper()->createAggregationRow();

                    //aggregating value by consolidate type
                    $aggregatingRow[$consolidateType] = $row[$consolidateType];
                    $aggregatingRow['hammer_price'] += $row['hammer_price'];
                    $aggregatingRow['buyers_premium'] += $row['buyers_premium'];
                    $aggregatingRow['sales_tax'] += $row['sales_tax'];
                    $aggregatingRow['total'] += $row['total'];
                    $aggregatingRow['hammer_price'] += SaleStaffPayoutCalculator::new()
                        ->construct(
                            $this->getPayoutType(),
                            $this->getPayoutApplyStatus(),
                            SaleStaffPayoutCalculatorData::new()->fromDbRow($row)
                        )
                        ->calculate();
                    $aggregatedRows[$row[$consolidateType]] = $aggregatingRow;
                }
            }
        }
        return $this->getHelper()->sortAggregatedRows($aggregatedRows);
    }

    /**
     * @param array $row
     * @return string
     */
    protected function buildBodyLine(array $row): string
    {
        $userBilling = $user = $userInfo = null;
        $consignor = $conCompany = $buyer = $buyCompany = $itemNo = $auctionName = $lotNo = $lotName
            = $invStatus = $invoiceDateFormatted = $soldDateFormatted = $payDateFormatted = '';

        if ($this->getConsolidationType() === SaleStaffReportFormConstants::CT_NONE) {
            $consignor = $row['consignor_username'];
            $conCompany = $row['consignor_company'];
            $buyer = $row['buyer_username'];
            $buyCompany = $row['buyer_company'];
            $auctionName = $row['auc_name'];
            $lotNo = $this->getLotRenderer()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']);
            $itemNo = $this->getLotRenderer()->makeItemNo($row['item_num'], $row['item_num_ext']);
            $lotName = $this->getLotRenderer()->makeName($row['lot_name'], (bool)$row['test_auction']);
            $invStatus = $row['inv_status_name'];

            if ($row['inv_date']) {
                $invoiceDateFormatted = $this->getDateTimeFormatter()->format($row['inv_date']);
            }

            if ($row['date_sold']) {
                $soldDateFormatted = $this->getDateTimeFormatter()->format($row['date_sold']);
            }

            if ($row['pay_date']) {
                $payDateFormatted = $this->getDateTimeFormatter()->format($row['pay_date']);
            } elseif ($row['pay_created']) {
                $payDateFormatted = $this->getDateTimeFormatter()->format($row['pay_created']);
            }
        }

        $salesStaff = (int)$row['sales_staff'];
        if ($salesStaff) {
            $user = $this->getUserLoader()->load($salesStaff, true);
            if ($user) {
                $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($user->Id, true);
                $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($user->Id, true);
            }
        }

        if ($userInfo) {
            $userInfoRow = [
                $userInfo->FirstName,
                $userInfo->LastName,
                '',
                $userInfo->Phone,
            ];
        } else {
            $userInfoRow = array_fill(0, 4, '');
        }

        if ($user) {
            $userInfoRow [2] = $user->Email;
        }

        if ($userBilling) {
            $userBillingRow = [
                $userBilling->CompanyName,
                $userBilling->FirstName,
                $userBilling->LastName,
                $userBilling->Phone,
                $userBilling->Address,
                $userBilling->Address2,
                $userBilling->Address3,
                $userBilling->City,
                $userBilling->State,
                $userBilling->Zip,
                AddressRenderer::new()->countryName($userBilling->Country),
            ];
        } else {
            $userBillingRow = array_fill(0, 11, '');
        }

        $payout = isset($row['payout'])
            ? (float)$row['payout']
            : SaleStaffPayoutCalculator::new()
                ->construct(
                    $this->getPayoutType(),
                    $this->getPayoutApplyStatus(),
                    SaleStaffPayoutCalculatorData::new()->fromDbRow($row)
                )->calculate();

        $bodyRow = array_merge(
            [
                $salesStaff,
                $row['office_location'],
                $user->CustomerNo ?? null,
            ],
            $userInfoRow,
            $userBillingRow,
            [
                $consignor,
                $conCompany,
                $buyer,
                $buyCompany,
                $itemNo,
                $auctionName,
                $lotNo,
                $lotName,
                $invStatus,
                $this->getNumberFormatter()->formatMoney((float)$row['hammer_price']),
                $this->getNumberFormatter()->formatMoney((float)$row['buyers_premium']),
                $this->getNumberFormatter()->formatMoney((float)$row['sales_tax']),
                $this->getNumberFormatter()->formatMoney((float)$row['total']),
                $this->getNumberFormatter()->formatMoney($payout),
                $soldDateFormatted,
                $invoiceDateFormatted,
                $payDateFormatted,
                $row['buyer_referrer'],
                $row['buyer_referrer_host'],
            ]
        );

        $bodyLine = $this->makeLine($bodyRow);

        return $bodyLine;
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $dateIso = $this->getCurrentDateUtc()->format('m-d-Y-His');
            $filename = "sale-report-{$dateIso}.csv";
            $filename = $this->getFilePathHelper()->toFilename($filename);
            $this->outputFileName = $filename;
        }
        return $this->outputFileName;
    }
}
