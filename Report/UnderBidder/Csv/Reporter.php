<?php
/**
 * SAM-4636: Refactor under bidders report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-04-19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\UnderBidder\Csv;


use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class Reporter
 */
class Reporter extends ReporterBase
{
    use BidderNumPaddingAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use LimitInfoAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use SortInfoAwareTrait;

    /** @var int */
    private const CHUNK_SIZE = 200;

    protected ?int $threshold = null;
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
     * @param int|null $threshold null leads to threshold id not found error
     * @return static
     */
    public function setThreshold(?int $threshold): static
    {
        $this->threshold = $threshold;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getThreshold(): ?int
    {
        return $this->threshold;
    }

    /**
     * Get Output file name
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $currentDateUtc = $this->getCurrentDateUtc();
            $this->outputFileName = 'under-bidders-report-' . $currentDateUtc->format('m-d-Y-His') . ".csv";
        }
        return $this->outputFileName;
    }

    /**
     * Get DataLoader
     * @return DataLoader
     */
    protected function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new()
                ->filterAuctionId($this->getFilterAuctionId())
                ->filterAccountId($this->getFilterAccountId())
                ->setSortColumn($this->getSortColumn())
                ->enableAscendingOrder($this->isAscendingOrder())
                ->setChunkSize(self::CHUNK_SIZE)
                ->setThreshold($this->getThreshold());
        }
        return $this->dataLoader;
    }

    /**
     * @param array $row
     * @return string
     */
    public function buildBodyLine(array $row): string
    {
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign();
        $lotName = $row['li_name'];
        $hammerPrice = Cast::toFloat($row['li_hp']);
        $hammerPriceFormatted = LotSellInfoPureChecker::new()->isHammerPrice($hammerPrice)
            ? $currencySign . $this->getNumberFormatter()->formatMoney($hammerPrice)
            : '';
        $bidderNum = $this->getBidderNumberPadding()->clear($row['wb_bidder_num']);
        $winningBidder = $row['wb_username'];
        $auctionBidder = $this->getBidderNumberPadding()->clear($row['ub_bidder_num']);
        $underBidder = $row['ub_username'];
        $amount = $this->getNumberFormatter()->formatMoney($row['amount']);

        $lotNum = $row['lot_num'];
        $lotExt = $row['lot_num_ext'];
        $lotPrefix = $row['lot_num_prefix'];

        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $lotNo = [$this->getLotRenderer()->makeLotNo($lotNum, $lotExt, $lotPrefix)];
        } else {
            $lotNo = [
                $lotPrefix,
                $lotNum,
                $lotExt,
            ];
        }
        $bodyRow = [
            $lotName,
            $hammerPriceFormatted,
            $bidderNum,
            $winningBidder,
            $auctionBidder,
            $underBidder,
            $amount,
        ];
        $bodyRow = array_merge($lotNo, $bodyRow);

        return $this->makeLine($bodyRow);
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
        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $lotNoHeader = ["LotFull#"];
        } else {
            $lotNoHeader = [
                "Lot# Prefix",
                "Lot#",
                "Lot# Ext."
            ];
        }
        $headerRow = [
            "Lot name",
            "Hammer",
            "Winning Bidder #",
            "Winning Bidder",
            "Under bidder #",
            "Under bidder",
            "Amount",
        ];

        $headerRow = array_merge($lotNoHeader, $headerRow);
        $headerLine = $this->makeLine($headerRow);

        return $this->processOutput($headerLine);
    }

    /**
     * @return bool
     */
    protected function validate(): bool
    {
        $errorMessages = [];
        $filterAuctionId = $this->getFilterAuctionId();
        if (is_array($filterAuctionId)) {
            $filterAuctionId = reset($filterAuctionId);
        }
        if (
            !$filterAuctionId
            || $filterAuctionId <= 0
        ) {
            $errorMessages[] = "Auction Id not defined";
        }
        if (
            !$this->getThreshold()
            || $this->getThreshold() < 0
        ) {
            $errorMessages[] = "Threshold Id not defined";
        }
        $this->errorMessage = implode('; ', $errorMessages);
        return count($errorMessages) === 0;
    }
}
