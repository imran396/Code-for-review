<?php
/**
 * SAM-4634:Refactor special terms report
 * https://bidpath.atlassian.net/browse/SAM-4634
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/8/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SpecialTerm\Csv;

use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Timezone\ApplicationTimezoneProviderAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class Reporter
 * @package Sam\Report\SpecialTerm\Csv
 */
class Reporter extends ReporterBase
{
    use ApplicationTimezoneProviderAwareTrait;
    use BidderNumPaddingAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use FilePathHelperAwareTrait;
    use FilterAuctionAwareTrait;
    use LimitInfoAwareTrait;
    use LotRendererAwareTrait;
    use SortInfoAwareTrait;
    use TimezoneLoaderAwareTrait;

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
                ->filterAuctionId($this->getFilterAuctionId())
                ->setSortColumn($this->getSortColumn())
                ->setLimit($this->getLimit())
                ->setOffset($this->getOffset())
                ->enableAscendingOrder($this->isAscendingOrder())
                ->setLimit(self::CHUNK_SIZE);
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
            $filename = "special-term-report{$dateIso}.csv";
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
        $accountTimeZone = $this->getApplicationTimezoneProvider()->getAccountTimezone($this->getFilterAuction()->AccountId);
        $agreedOnDate = new \DateTime($row['agreed_on']);
        $agreedOnDate->setTimezone(new \DateTimeZone($accountTimeZone->Location));
        $agreedOn = $agreedOnDate->format(Constants\Date::ISO);
        $isTestAuction = $this->getFilterAuction() ? $this->getFilterAuction()->TestAuction : false;

        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $lotNo[] = $this->getLotRenderer()->makeLotNo($row['lot_number'], $row['lot_number_ext'], $row['lot_number_prefix']);
        } else {
            $lotNo = [
                $row['lot_number'],
                $row['lot_number_prefix'],
                $row['lot_number_ext'],
            ];
        }
        if ($this->cfg()->get('core->lot->itemNo->concatenated')) {
            $itemNo[] = $this->getLotRenderer()->makeItemNo($row['item_num'], $row['item_num_ext']);
        } else {
            $itemNo = [
                $row['item_num'],
                $row['item_num_ext'],
            ];
        }
        $bodyRow = array_merge(
            $itemNo,
            $lotNo,
            [
                $this->getLotRenderer()->makeName($row['name'], $isTestAuction),
                $row['terms_and_conditions'],
                $this->getBidderNumberPadding()->clear($row['bidder_num']),
                $row['first_name'],
                $row['last_name'],
                $row['customer_no'],
                $row['username'],
                $row['email'],
                $agreedOn,
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
        if ($this->cfg()->get('core->lot->itemNo->concatenated')) {
            $headerTitles = ["ItemFull#"];
        } else {
            $headerTitles = [
                "Item#",
                "Item# Ext."
            ];
        }

        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $headerTitles[] = "LotFull#";
        } else {
            array_push(
                $headerTitles,
                "Lot# Prefix",
                "Lot#",
                "Lot# Ext."
            );
        }

        array_push(
            $headerTitles,
            "Name",
            "T&C",
            "Bidder #",
            "First Name",
            "Last  Name",
            "Customer #",
            "Username",
            "Email",
            "Agreed on"
        );

        $headerLine = $this->makeLine($headerTitles);

        return $this->processOutput($headerLine);
    }
}
