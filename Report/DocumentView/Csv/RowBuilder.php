<?php
/**
 * SAM-4630: Refactor document view report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-05-07
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\DocumentView\Csv;

use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Transform\Csv\CsvTransformer;
use Sam\Locale\Formatter\DateTimeFormatterAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\ReportToolAwareTrait;
use Sam\Report\Base\Csv\RowBuilderBase;

/**
 * Class RowBuilder
 */
class RowBuilder extends RowBuilderBase
{
    use AuctionRendererAwareTrait;
    use DateTimeFormatterAwareTrait;
    use LotRendererAwareTrait;
    use ReportToolAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build Header Titles
     * @return string
     */
    public function buildHeaderLine(): string
    {
        return $this->getReportTool()->rowToLine($this->getFields());
    }

    /**
     * @param array $row
     * @return array
     */
    public function buildBodyRow(array $row): array
    {
        $encoding = $this->getSettingsManager()
            ->get(Constants\Setting::DEFAULT_EXPORT_ENCODING, $this->getSystemAccountId());
        $csvTransformer = CsvTransformer::new();

        $itemNo = $this->getLotRenderer()->makeItemNo($row['item_num'], $row['item_num_ext']);
        $itemNo = $csvTransformer->convertEncoding($itemNo, $encoding);
        $auctionName = $csvTransformer->convertEncoding($this->renderAuction($row), $encoding);
        $lotNo = $this->getLotRenderer()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']);
        $lotNo = $csvTransformer->convertEncoding($lotNo, $encoding);
        $lotName = $csvTransformer->convertEncoding($row['lot_name'], $encoding);
        $customFieldName = $csvTransformer->convertEncoding($row['custom_field'], $encoding);
        $documentName = $csvTransformer->convertEncoding($row['document_name'], $encoding);
        $username = $csvTransformer->convertEncoding($row['username'], $encoding);
        $firstName = $csvTransformer->convertEncoding($row['first_name'], $encoding);
        $lastName = $csvTransformer->convertEncoding($row['last_name'], $encoding);
        $address = $csvTransformer->convertEncoding($row['address'], $encoding);
        $city = $csvTransformer->convertEncoding($row['city'], $encoding);
        $state = $csvTransformer->convertEncoding($row['state'], $encoding);
        $zip = $csvTransformer->convertEncoding($row['zip'], $encoding);
        $phone = $csvTransformer->convertEncoding($row['phone'], $encoding);
        $dateViewedFormatted = $this->getDateTimeFormatter()->format($row['date']);

        $bodyRow = [
            $dateViewedFormatted,
            $itemNo,
            $auctionName,
            $lotNo,
            $lotName,
            $customFieldName,
            $documentName,
            $username,
            $firstName,
            $lastName,
            $address,
            $city,
            $state,
            $zip,
            $phone,
        ];

        return $bodyRow;
    }

    /**
     * Get CSV header titles
     * @return array
     */
    protected function getFields(): array
    {
        return [
            "Date & Time",
            "Item #",
            "Auction",
            "Lot",
            "Lot Name",
            "Custom Field",
            "Document Name",
            "Username",
            "First Name",
            "Last Name",
            "Address",
            "City",
            "State",
            "Zip",
            "Phone",
        ];
    }

    /**
     * @param array $row
     * @return string
     */
    protected function renderAuction(array $row): string
    {
        $output = $this->getAuctionRenderer()->makeName($row['auction_name'], (bool)$row['test_auction']);
        $saleNo = $this->getAuctionRenderer()->makeSaleNo($row['sale_num'], $row['sale_num_ext']);
        if ($saleNo) {
            $output .= " ({$saleNo})";
        }
        return $output;
    }
}
