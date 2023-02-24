<?php
/**
 * SAM-4632 : Refactor payment report
 * https://bidpath.atlassian.net/browse/SAM-4632
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/2/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Payment\Csv;

use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Billing\Payment\Render\PaymentRendererAwareTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Entity\FilterCurrencyAwareTrait;
use Sam\Core\Filter\Entity\FilterLocationAwareTrait;
use Sam\Core\Filter\Entity\FilterUserAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Locale\Formatter\DateTimeFormatterAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class Reporter
 * @package Sam\Report\Payment\Csv
 */
class Reporter extends ReporterBase
{
    use AuctionRendererAwareTrait;
    use CreditCardLoaderAwareTrait;
    use CurrentDateTrait;
    use DateTimeFormatterAwareTrait;
    use FilePathHelperAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterCurrencyAwareTrait;
    use FilterDatePeriodAwareTrait;
    use FilterLocationAwareTrait;
    use FilterUserAwareTrait;
    use LimitInfoAwareTrait;
    use NumberFormatterAwareTrait;
    use PaymentRendererAwareTrait;
    use SortInfoAwareTrait;

    private const CHUNK_SIZE = 200;

    /** @var DataLoader|null */
    protected ?DataLoader $dataLoader = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new()
                ->enableAscendingOrder($this->isAscendingOrder())
                ->enableFilterDatePeriod($this->isFilterDatePeriod())
                ->filterAccountId($this->getFilterAccountId())
                ->filterAuctionId($this->getFilterAuctionId())
                ->filterCurrencyId($this->getFilterCurrencyId())
                ->filterEndDateSysIso($this->getFilterEndDateSysIso())
                ->filterLocationId($this->getFilterLocationId())
                ->filterStartDateSysIso($this->getFilterStartDateSysIso())
                ->filterUserId($this->getFilterUserId())
                ->setChunkSize(self::CHUNK_SIZE)
                ->setSortColumn($this->getSortColumn());
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
            $filename = "payment-report-{$dateIso}.csv";
            $filename = $this->getFilePathHelper()->toFilename($filename);
            $this->outputFileName = $filename;
        }
        return $this->outputFileName;
    }

    /**
     * @param array $row
     * @return string
     */
    protected function buildBodyLine(array $row): string
    {
        $paymentDateIso = $this->getDateTimeFormatter()->format($row['payment_date']);
        $username = $row['username'];
        $referrer = $row['referrer'];
        $referrerHost = $row['referrer_host'];
        $bidderNum = $row['bidder_num'];
        $auctionNum = $this->getAuctionRenderer()->makeSaleNo($row['sale_num'], $row['sale_num_ext']);
        $invoiceNum = $row['invoice_no'];
        $paymentMethodName = $this->getPaymentRenderer()->makePaymentMethodTranslated((int)$row['payment_method_id']);
        $paymentOutput = $paymentMethodName;
        if ($row['credit_card_id']) {
            $creditCard = $this->getCreditCardLoader()->load((int)$row['credit_card_id']);
            $creditCardName = $creditCard->Name ?? '';
            $paymentOutput .= ' - ' . $creditCardName;
        }
        $note = $row['note'];
        $serviceAccountId = $this->getFilterAccountId() ?: $this->getSystemAccountId();
        $amount = $this->getNumberFormatter()
            ->construct($serviceAccountId)
            ->formatMoney((float)$row['amount']);
        $currencySign = $row['currency_sign'];

        $bodyRow = [
            $paymentDateIso,
            $username,
            $referrer,
            $referrerHost,
            $bidderNum,
            $auctionNum,
            $invoiceNum,
            $paymentOutput,
            $note,
            $currencySign . $amount,
        ];

        $bodyLine = $this->makeLine($bodyRow);

        return $bodyLine;
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
     * Output titles for csv header (string or echo)
     * @return string
     */
    protected function outputTitles(): string
    {
        $headerTitles = [
            "Payment Date",
            "User",
            "Referrer",
            "Referrer host",
            "B#",
            "Auction",
            "Invoice",
            "Type",
            "Notes",
            "Amount"
        ];

        $headerLine = $this->makeLine($headerTitles);

        return $this->processOutput($headerLine);
    }
}
