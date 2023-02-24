<?php
/**
 * SAM-4635 : Refactor tax report
 * https://bidpath.atlassian.net/browse/SAM-4635
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           4/11/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Tax\Csv;

use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Invoice\Status\InvoiceStatusPureChecker;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
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
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Locale\Formatter\DateTimeFormatterAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class Reporter
 * @package Sam\Report\Tax\Csv
 */
class Reporter extends ReporterBase
{
    use AuctionRendererAwareTrait;
    use ConfigRepositoryAwareTrait;
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
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use SortInfoAwareTrait;

    /** @var int */
    private const CHUNK_SIZE = 200;

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
                ->filterAccountId($this->getFilterAccountId())
                ->filterAuctionId($this->getFilterAuctionId())
                ->filterCurrencyId($this->getFilterCurrencyId())
                ->filterLocationId($this->getFilterLocationId())
                ->filterUserId($this->getFilterUserId())
                ->enableFilterDatePeriod($this->isFilterDatePeriod())
                ->filterStartDateSysIso($this->getFilterStartDateSysIso())
                ->filterEndDateSysIso($this->getFilterEndDateSysIso())
                ->setChunkSize(self::CHUNK_SIZE)
                ->setSortColumn($this->getSortColumn())
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
            $dateIso = $this->getCurrentDateUtc()->format('m-d-Y-His');
            $filename = "tax-report{$dateIso}.csv";
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
        $this->getNumberFormatter()->construct($this->getSystemAccountId());
        $invoiceDate = $this->getDateTimeFormatter()->format($row['invoice_date']);
        $userName = $row['username'];
        $bidderNum = $row['bidder_num'];
        $saleNo = $this->getAuctionRenderer()->makeSaleNo($row['sale_num'], $row['sale_num_ext']);
        $invoiceNum = $row['invoice_no'];
        $lotNum = $row['lot_number'];
        $lotPrefix = $row['lot_number_prefix'];
        $lotExtension = $row['lot_number_ext'];
        $lotNo = [];
        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $lotNo[] = $this->getLotRenderer()->makeLotNo($lotNum, $lotExtension, $lotPrefix);
        } else {
            $lotNo[] = $lotPrefix;
            $lotNo[] = $lotNum;
            $lotNo[] = $lotExtension;
        }

        $currencySign = $row['currency_sign'];
        $lotName = $row['lot_name'];
        if (InvoiceStatusPureChecker::new()->isLegacyTaxDesignation((int)$row['tax_designation'])) {
            $salesTax = $this->getNumberFormatter()->formatPercent($row['sales_tax']);
        } else {
            $salesTax = 'N/A';
        }
        $hammerPrice = Cast::toFloat($row['hammer_price']);
        $hammerPriceFormatted = LotSellInfoPureChecker::new()->isHammerPrice($hammerPrice)
            ? $currencySign . $this->getNumberFormatter()->formatMoney($hammerPrice)
            : '';
        $buyersPremium = $this->getNumberFormatter()->formatMoney($row['premium_price']);
        $subTotal = $this->getNumberFormatter()->formatMoney($row['sub_total']);
        $tax = $this->getNumberFormatter()->formatMoney($row['tax']);
        $total = $this->getNumberFormatter()->formatMoney($row['total']);
        $bodyRow = [
            $invoiceDate,
            $userName,
            $bidderNum,
            $saleNo,
        ];
        $bodyRow = array_merge(
            $bodyRow,
            $lotNo,
            [
                $invoiceNum,
                $lotName,
                $salesTax,
                $hammerPriceFormatted,
                $currencySign . $buyersPremium,
                $currencySign . $subTotal,
                $currencySign . $tax,
                $currencySign . $total,
            ]
        );

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
        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $lotNoHeader = ["LotFull#"];
        } else {
            $lotNoHeader = ["Lot# Prefix", "Lot#", "Lot# Ext."];
        }
        $headerTitles = [
            "Invoice Date",
            "User",
            "B#",
            "Auction"
        ];
        $headerTitles = array_merge(
            $headerTitles,
            $lotNoHeader,
            [
                "Invoice",
                "Lot Name",
                "%",
                "Hammer",
                "Prem.",
                "SubTotal",
                "Tax",
                "Total"
            ]
        );

        $headerLine = $this->makeLine($headerTitles);

        return $this->processOutput($headerLine);
    }
}
