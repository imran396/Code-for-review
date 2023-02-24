<?php
/**
 * SAM-4600: SAM to Bidmaster Winning bidder information CSV
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Boanerge Regidor
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           09/02/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\BidMaster\WinningBidder;

use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Report\Base\Tab\ReporterBase;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Core\Constants;

/**
 * Class WinningBidMasterReporter
 * @package Sam\Report\Auction\BidMaster
 */
class WinningBidderReporter extends ReporterBase
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
            $filename = "bm_bidders_{$dateIso}_{$saleNo}.tab";
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
        $id = $paddle = $row['bidder_num'];
        $title = '';
        $firstName = $this->normalizeCellValue($row['first_name']);
        $lastName = $this->normalizeCellValue($row['last_name']);
        $email = $row['email'];
        $company = $row['company_name'];
        $address1 = $this->normalizeCellValue($row['bill_address']);
        $address2 = $this->normalizeCellValue($row['bill_address2']);
        $address3 = $this->normalizeCellValue($row['bill_address3']);
        $city = $row['bill_city'];
        $state = AddressRenderer::new()->stateName((string)$row['bill_state'], (string)$row['bill_country']);
        $postalCode = $row['bill_zip'];
        $country = $row['bill_country'];
        $tel = $mob = '';
        switch ((int)$row['phone_type']) {
            case Constants\User::PT_WORK:
            case Constants\User::PT_HOME:
                $tel = $this->normalizePhoneNumber((string)$row['phone']);
                break;
            case Constants\User::PT_MOBILE:
                $mob = $this->normalizePhoneNumber((string)$row['phone']);
                break;
        }
        $fax = $this->normalizePhoneNumber((string)$row['bill_fax']);

        $bodyRow = [
            $id,
            $paddle,
            $title,
            $firstName,
            $lastName,
            $email,
            $company,
            $address1,
            $address2,
            $address3,
            $city,
            $state,
            $postalCode,
            $country,
            $tel,
            $mob,
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
        $rows = $this->getDataLoader()->load();
        foreach ($rows as $row) {
            $bodyLine = $this->buildBodyLine($row);
            $output .= $this->processOutput($bodyLine);
        }
        return $output;
    }

    /**
     * Cut string according to max length
     * @param string $value
     * @return string
     */
    protected function normalizeCellValue(string $value): string
    {
        $maxLength = 35;
        $output = substr($value, 0, $maxLength);
        return $output;
    }

    /**
     * Removed unnecessary symbols from phone number
     * @param string $value
     * @return string
     */
    protected function normalizePhoneNumber(string $value): string
    {
        $output = preg_replace('/[-+,\s]/', '', $value);
        return $output;
    }

    /**
     * Output titles for csv header (string or echo)
     * @return string
     */
    protected function outputTitles(): string
    {
        $headerTitles = [
            "id",
            "paddle",
            "title",
            "first",
            "last",
            "email",
            "company",
            "ad1",
            "ad2",
            "ad3",
            "city",
            "state-province",
            "p-z code",
            "country",
            "tel",
            "mob",
            "fax",
            "vat"
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
