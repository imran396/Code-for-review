<?php
/**
 * Useful methods for tab report generating
 *
 * SAM-4616: Reports code refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Boanerge Regidor
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/19/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Base\Tab;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Csv\CsvTransformer;

/**
 * Class CsvReportTool
 * @package Sam\Report\Base\Tab
 */
class ReportTool extends CustomizableClass
{

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Encode and csv-escape values, make and return one line of csv output with EOL
     * @param array $values
     * @param string $encoding
     * @return string
     */
    public function makeLine(array $values, string $encoding): string
    {
        $values = $this->prepareValues($values, $encoding);
        $line = $this->rowToLine($values);
        return $line;
    }

    /**
     * Convert UTF-8 encoding to the set encoding for export in settings
     * @param string|int|float|null $value
     * @param string $encoding
     * @return string
     */
    public function prepareValue(string|int|float|null $value, string $encoding): string
    {
        $value = CsvTransformer::new()->convertEncoding($value, $encoding);
        return $value;
    }

    /**
     * @param array $values
     * @param string $encoding
     * @return array
     */
    public function prepareValues(array $values, string $encoding): array
    {
        foreach ($values as $i => $value) {
            $values[$i] = $this->prepareValue($value, $encoding);
        }
        return $values;
    }

    /**
     * Convert array of csv values to escaped list of values as string
     * @param array $row
     * @return string
     */
    public function rowToLine(array $row): string
    {
        $output = implode("\t", $row) . "\r\n";
        return $output;
    }
}
