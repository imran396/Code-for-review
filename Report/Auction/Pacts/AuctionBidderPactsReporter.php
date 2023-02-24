<?php
/**
 * SAM-4598: Pacts Buyers export from SAM to Pacts
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Boanerge Regidor
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/15/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\Pacts;

use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class AuctionBidderPactsReporter
 * @package Sam\Report\Auction\Pacts
 */
class AuctionBidderPactsReporter extends ReporterBase
{
    use AuctionRendererAwareTrait;
    use CurrentDateTrait;
    use FilePathHelperAwareTrait;
    use FilterAuctionAwareTrait;
    use NumberFormatterAwareTrait;

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
                ->filterAuctionId($this->getFilterAuctionId());
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
            $saleNo = $this->getAuctionRenderer()->renderSaleNo($this->getFilterAuction());
            $dateIso = $this->getCurrentDateUtc()->format('m-d-Y');
            $filename = "pacts_auction_bidder_{$dateIso}_{$saleNo}.csv";
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
        $bidderNum = $row['bidder_num'];
        $title = '';
        $email = $row['email'];
        $firstName = $this->normalizeCellValue($row['first_name']);
        $lastName = $this->normalizeCellValue($row['last_name']);
        $phone = $this->normalizePhoneNumber($row['phone']);
        $fax = $this->normalizePhoneNumber($row['bill_fax']);
        $house = $this->normalizeCellValue($row['bill_address']);
        $billingAddress2 = $this->normalizeCellValue($row['bill_address2']);
        $billingAddress3 = $this->normalizeCellValue($row['bill_address3']);
        $street = $this->makeCellByArray([$billingAddress2, $billingAddress3]);
        $streetOutput = $this->normalizeCellValue($street);
        $town = $row['bill_city'];
        $state = AddressRenderer::new()->stateName((string)$row['bill_state'], (string)$row['bill_country']);
        $postalCode = $row['bill_zip'];
        $bodyRow = [
            $bidderNum,
            $title,
            $firstName,
            $lastName,
            $house,
            $streetOutput,
            $town,
            $state,
            $postalCode,
            $phone,
            $email,
            $fax,
            '',
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
        $total = $this->getDataLoader()->count();
        if (!$total) {
            return '';
        }

        while ($rows = $this->getDataLoader()->load()) {
            foreach ($rows as $row) {
                $bodyLine = $this->buildBodyLine($row);
                $output .= $this->processOutput($bodyLine);
            }
        }
        return $output;
    }

    /**
     * Cut string according by max length
     * @param string|null $value
     * @return string
     */
    protected function normalizeCellValue(?string $value): string
    {
        $maxLength = 35;
        $output = substr((string)$value, 0, $maxLength);
        return $output;
    }

    /**
     * Removed unnecessary symbols from phone number
     * @param string|null $value
     * @return string
     */
    protected function normalizePhoneNumber(?string $value): string
    {
        $output = preg_replace('/[-+,\s]/', '', (string)$value);
        return $output;
    }

    /**
     * Filter empty and implode array values by comma
     * @param array $row
     * @return string
     */
    protected function makeCellByArray(array $row): string
    {
        $output = implode(', ', array_filter($row));
        return $output;
    }

    /**
     * Output titles for csv header (string or echo)
     * @return string
     */
    protected function outputTitles(): string
    {
        $headerTitles = [
            "Buyer number",
            "Title",
            "Initials",
            "Surname",
            "House",
            "Street",
            "Town",
            "County",
            "Postcode",
            "Telephone",
            "e-mail",
            "Fax",
            "VAT",
            "Comments"
        ];

        $headerLine = $this->makeLine($headerTitles);

        return $this->processOutput($headerLine);
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
}
