<?php
/**
 * SAM-4687: Refactor "Unsold Lots" report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/28/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
 * This might include adding, changing, or moving columns, modifying header names, modifying data or data format
 */

namespace Sam\Report\Lot\Unsold\Csv;

use Sam\Date\CurrentDateTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\ReportToolAwareTrait;
use Sam\Report\Base\Csv\RowBuilderBase;
use Sam\Transform\Number\NextNumberFormatterAwareTrait;

/**
 * Class CsvRenderer
 * @package Sam\Report\Lot\Unsold\Render
 */
class RowBuilder extends RowBuilderBase
{
    use CurrentDateTrait;
    use LotRendererAwareTrait;
    use NextNumberFormatterAwareTrait;
    use ReportToolAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $fields
     * @return string
     */
    public function buildHeaderLine(array $fields): string
    {
        $headerLine = $this->getReportTool()->rowToLine($fields);
        return $headerLine;
    }

    /**
     * @param array $row
     * @param array $fields
     * @return array
     */
    public function buildBodyRow(array $row, array $fields): array
    {
        $bodyRow = [];
        foreach ($fields as $fieldName) {
            $bodyRow[] = match ($fieldName) {
                'LotNum' => $this->getLotRenderer()->makeLotNo(
                    $row['lot_num'],
                    $row['lot_num_ext'],
                    $row['lot_num_prefix']
                ),
                'Quantity' => $this->getNextNumberFormatter()->formatNto($row['quantity'], (int)$row['quantity_scale']),
                'Name' => $row['name'],
                'ReservePrice' => $this->getNextNumberFormatter()->formatMoneyNto($row['reserve_price']),
                'LowEstimate' => $this->getNextNumberFormatter()->formatMoneyNto($row['low_estimate']),
                'HighEstimate' => $this->getNextNumberFormatter()->formatMoneyNto($row['high_estimate']),
                'Consignor' => $row['username'],
                'GeneralNote' => $row['general_note'],
                'NoteToClerk' => $row['note_to_clerk'],
                'ItemNum' => $this->getLotRenderer()->makeItemNo($row['item_num'], $row['item_num_ext']),
                'Description' => $row['description'],
                'StartingBid' => $this->getNextNumberFormatter()->formatMoneyNto($row['starting_bid']),
                'Cost' => $this->getNextNumberFormatter()->formatMoneyNto($row['cost']),
                'ReplacementPrice' => $this->getNextNumberFormatter()->formatMoneyNto($row['replacement_price']),
                'SalesTax' => $this->getNextNumberFormatter()->formatMoneyNto($row['sales_tax']),
                default => '',
            };
        }

        foreach ($bodyRow as $i => $value) {
            $bodyRow[$i] = $this->getReportTool()->prepareValue($value, $this->getEncoding());
        }

        return $bodyRow;
    }
}
