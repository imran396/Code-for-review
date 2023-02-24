<?php
/**
 * SAM-4625: Refactor settlement list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-12
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Settlement\SettlementList;

use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Date\DateHelperAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Settlement\Calculate\SettlementCalculatorAwareTrait;
use Sam\Settlement\Currency\SettlementCurrencyDetectorAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class SettlementListReporter
 * @package Sam\Report\Settlement\SettlementList
 */
class SettlementListReporter extends ReporterBase
{
    use ApplicationAccessCheckerCreateTrait;
    use DateHelperAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterAwareTrait;
    use NumberFormatterAwareTrait;
    use SettlementCalculatorAwareTrait;
    use SettlementCurrencyDetectorAwareTrait;
    use SortInfoAwareTrait;
    use TimezoneLoaderAwareTrait;

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
            $this->dataLoader = DataLoader::new()
                ->enableAccountFiltering($this->isAccountFiltering())
                ->filterAccountId($this->getFilterAccountId())
                ->filterAuctionId($this->getFilterAuctionId())
                ->filterConsignorUserId($this->getConsignorUserId())
                ->filterSettlementStatusId($this->getSettlementStatusId())
                ->setSystemAccountId($this->getSystemAccountId())
                ->setSortColumnIndex($this->getSortColumnIndex())
                ->enableAscendingOrder($this->isAscendingOrder());
        }
        return $this->dataLoader;
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $this->outputFileName = "settlements-" . date('m-d-Y-His') . ".csv";
        }
        return $this->outputFileName;
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $rows = $this->getDataLoader()->load();
        $output = '';
        foreach ($rows as $row) { //cycle through auction lots
            $bodyLine = $this->buildBodyLine($row);
            $output .= $this->processOutput($bodyLine);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    protected function buildBodyLine(array $row): string
    {
        $bodyRow = [];

        $date = $this->getDateHelper()->convertUtcToSysByDateIso($row['settlement_date']);
        $dateFormatted = $date ? $date->format(Constants\Date::US_DATE) : '';
        $settlementNo = $row['settlement_no'];
        $customerNo = $row['customer_no'];

        $fullName = UserPureRenderer::new()->makeFullName($row['first_name'], $row['last_name']);
        $fullName = trim($fullName) === '' ? '--' : $fullName;
        $userOutput = $row['username'] . ' ' . $fullName;

        $currencySign = $this->getSettlementCurrencyDetector()->detectSign((int)$row['id'], true);

        $costTotalFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($row['cost_total']);
        $taxableTotalFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($row['taxable_total']);
        $nonTaxableTotalFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($row['non_taxable_total']);
        $exportTotalFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($row['export_total']);
        $totalDue = $this->getSettlementCalculator()->calcTotalDue($row['id']);
        $totalDueFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($totalDue);
        $feesCommTotalFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($row['fees_comm_total']);
        $taxExclusiveFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($row['tax_exclusive']);
        $taxInclusiveFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($row['tax_inclusive']);
        $taxServicesFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($row['tax_services']);
        $balanceDue = $this->getSettlementCalculator()->calcRoundedBalanceDue($row['id']);
        $balanceDueFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($balanceDue);
        $paidTotalFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($row['paid_total']);
        $owedTotalFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($row['owed_total']);

        $settlementStatusName = Constants\Settlement::$settlementStatusNames[(int)$row['settlement_status_id']];

        $bodyRow = array_merge(
            $bodyRow,
            [
                $settlementNo,
                $customerNo,
                $dateFormatted,
                $userOutput,
                $costTotalFormatted,
                $taxableTotalFormatted,
                $nonTaxableTotalFormatted,
                $exportTotalFormatted,
                $totalDueFormatted,
                $feesCommTotalFormatted,
                $taxExclusiveFormatted,
                $taxInclusiveFormatted,
                $taxServicesFormatted,
                $balanceDueFormatted,
                $settlementStatusName,
                $owedTotalFormatted,
                $paidTotalFormatted,
            ]
        );

        $bodyLine = $this->makeLine($bodyRow);

        return $bodyLine;
    }

    /**
     * Output titles for csv header (string or echo)
     * @return string
     */
    protected function outputTitles(): string
    {
        $headerTitles = [
            "Settlement #",
            "Customer #",
            "Date",
            "User",
            "Cost",
            "Taxable",
            "Non-Taxable",
            "Export",
            "Total",
            "Fees & Comm",
            "Tax Excl.",
            "Tax Incl.",
            "Tax Services",
            "Balance",
            "Status",
            "Owed",
            "Paid",
        ];

        $headerLine = $this->makeLine($headerTitles);

        return $this->processOutput($headerLine);
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
