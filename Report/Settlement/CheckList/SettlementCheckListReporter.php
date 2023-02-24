<?php
/**
 * SAM-10138: Add the ability to check lists to export to CSV
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 29, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Settlement\CheckList;

use Sam\Core\Constants;
use Sam\Core\Entity\Model\SettlementCheck\Status\SettlementCheckStatusPureChecker;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Settlement\Currency\SettlementCurrencyDetectorAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class SettlementCheckListReporter
 * @package Sam\Report\Settlement\CheckList
 */
class SettlementCheckListReporter extends ReporterBase
{
    use DateHelperAwareTrait;
    use NumberFormatterAwareTrait;
    use SettlementCurrencyDetectorAwareTrait;
    use SortInfoAwareTrait;

    protected ?DataLoader $dataLoader = null;

    /** @var int[] */
    protected ?array $settlementCheckIds = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function setSettlementCheckIds(array $settlementCheckIds): static
    {
        $this->settlementCheckIds = $settlementCheckIds;
        return $this;
    }

    /**
     * @param DataLoader $dataLoader
     * @return $this
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
        }
        return $this->dataLoader;
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $this->outputFileName = "settlement-checks" . date('m-d-Y-His') . ".csv";
        }
        return $this->outputFileName;
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $rows = $this->getDataLoader()->load(
            $this->settlementCheckIds,
            $this->getSortColumn(),
            $this->isAscendingOrder()
        );
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
        $currencySign = $this->getSettlementCurrencyDetector()->detectSign((int)$row['settlement_id']);

        $settlementNo = (string)$row['settlement_no'];
        $checkNo = (string)$row['check_no'];
        $payee = (string)$row['payee'];
        $amount = $currencySign . $this->getNumberFormatter()->formatMoney($row['amount']);
        $amountSpelling = (string)$row['amount_spelling'];
        $memo = (string)$row['memo'];
        $note = (string)$row['note'];
        $address = (string)$row['address'];
        $appliedAsPayment = $row['payment_amount']
            ? $currencySign . $row['payment_amount']
            : '';

        $createdOnUtcIso = (string)$row['created_on'];
        $createdOnSysFormatted = $this->formatDate($createdOnUtcIso);

        $printedOnUtcIso = (string)$row['printed_on'];
        $printedOnSysFormatted = $this->formatDate($printedOnUtcIso);

        $postedOnUtcIso = (string)$row['posted_on'];
        $postedOnSysFormatted = $this->formatDate($postedOnUtcIso);

        $clearedOnUtcIso = (string)$row['cleared_on'];
        $clearedOnSysFormatted = $this->formatDate($clearedOnUtcIso);

        $voidedOnUtcIso = (string)$row['voided_on'];
        $voidedOnSysFormatted = $this->formatDate($voidedOnUtcIso);

        $status = SettlementCheckStatusPureChecker::new()->detectStatusByIso(
            $createdOnUtcIso,
            $printedOnUtcIso,
            $postedOnUtcIso,
            $clearedOnUtcIso,
            $voidedOnUtcIso
        );
        $status = Constants\SettlementCheck::STATUS_NAMES[$status];

        $bodyRow = array_merge(
            $bodyRow,
            [
                $settlementNo,
                $checkNo,
                $payee,
                $amount,
                $amountSpelling,
                $memo,
                $note,
                $address,
                $status,
                $appliedAsPayment,
                $createdOnSysFormatted,
                $printedOnSysFormatted,
                $postedOnSysFormatted,
                $clearedOnSysFormatted,
                $voidedOnSysFormatted
            ]
        );

        return $this->makeLine($bodyRow);
    }

    /**
     * @return string
     */
    protected function outputTitles(): string
    {
        $headerTitles = [
            'Settlement#',
            'Check#',
            'Payee',
            'Amount',
            'Amount spelling',
            'Memo',
            'Note',
            'Address',
            'Status',
            'Applied as Payment',
            'Created Date',
            'Printed Date',
            'Posted Date',
            'Cleared Date',
            'Voided Date'
        ];

        $headerLine = $this->makeLine($headerTitles);

        return $this->processOutput($headerLine);
    }

    protected function formatDate(string $dateUtcIso): string
    {
        $dateHelper = $this->getDateHelper();
        $dateSysIso = $dateHelper->convertUtcToSysByDateIso($dateUtcIso, $this->getSystemAccountId());
        $dateSysFormatted = $dateHelper->formattedDate($dateSysIso);
        return $dateSysFormatted;
    }
}
