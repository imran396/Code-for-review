<?php
/**
 * SAM-4679: Refactor auction phone bidder report
 * https://bidpath.atlassian.net/browse/SAM-4679
 *
 * @author        Vahagn Hovsepyan
 * @since         Dec 13, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Report\Auction\PhoneBidder;

use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Date\CurrentDateTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;

/**
 * Class AuctionPhoneBidderReporter
 */
class AuctionPhoneBidderReporter extends ReporterBase
{
    use BidderNumPaddingAwareTrait;
    use CurrentDateTrait;
    use FilePathHelperAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterAwareTrait;
    use LotRendererAwareTrait;

    protected ?DataLoader $dataLoader = null;

    /**
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
                ->filterBidderId($this->getBidderId())
                ->filterClerk($this->getClerk())
                ->filterMinLotNum($this->getMinLotNum())
                ->filterMaxLotNum($this->getMaxLotNum())
                ->enableUnassignedOnly($this->isUnassignedOnly())
                ->enableAllLots($this->isAllLots());
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
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $auction = $this->getFilterAuction();
            $header = 'auction-phone-bidders';
            $dateIso = $this->getCurrentDateUtc()->format('m-d-Y');
            $saleNo = $auction->SaleNum;
            $filename = "{$header}-{$dateIso}-{$saleNo}.csv";
            $filename = $this->getFilePathHelper()->toFilename($filename);
            $this->setOutputFileName($filename);
        }
        return $this->outputFileName;
    }

    /**
     * @return bool
     */
    protected function validate(): bool
    {
        $this->errorMessage = null;
        $auction = $this->getFilterAuction();
        if ($auction === null) {
            // Unknown auction situation already processed at controller layer in allow() method
            $this->errorMessage = "Auction not found" . composeSuffix(['a' => $this->getFilterAuctionId()]);
        } elseif ($auction->isDeleted()) {
            $this->errorMessage = "Auction already deleted" . composeSuffix(['a' => $this->getFilterAuctionId()]);
        }
        $success = $this->errorMessage === null;
        return $success;
    }

    /**
     * Output titles for csv header (string or echo)
     * @return string
     */
    protected function outputTitles(): string
    {
        $headerTitles = [
            "Lot",
            "Bidder",
            "Name",
            "Phone",
            "Clerk"
        ]; // Auction

        $headerLine = $this->makeLine($headerTitles);

        return $this->processOutput($headerLine);
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $rows = $this->getDataLoader()->load();
        if (!count($rows)) {
            echo "No phone bidders found!";
            return '';
        }

        $output = '';
        foreach ($rows as $row) { //cycle through phone bidders
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
        $phone = '';
        if ($row['iphone']) {
            $phone .= $row['iphone'] . '(U)';
        }
        if ($row['bphone']) {
            $phone .= (($phone ? "\n" : '') . $row['bphone'] . '(B)');
        }
        if ($row['sphone']) {
            $phone .= (($phone ? "\n" : '') . $row['sphone'] . '(S)');
        }

        $lotNo = $this->getLotRenderer()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']);
        $bidder = $this->getBidderNumberPadding()->clear($row['bidder_num']);
        $name = UserPureRenderer::new()->makeFullName($row['first_name'], $row['last_name']);
        $clerk = $row['assigned_clerk'];

        $bodyRow = [$lotNo, $bidder, $name, $phone, $clerk,]; // Auction

        $bodyLine = $this->makeLine($bodyRow);

        return $bodyLine;
    }

    /**
     * Echo output, return empty string
     * @return string
     */
    protected function outputError(): string
    {
        echo $this->errorMessage;
        return '';
    }
}
