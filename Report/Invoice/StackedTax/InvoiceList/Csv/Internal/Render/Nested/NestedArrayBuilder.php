<?php
/**
 * SAM-9617: Values are Repeated without Column header in CSV downloaded when we clicked on CSV export with custom field
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Invoice\StackedTax\InvoiceList\Csv\Internal\Render\Nested;

use Sam\Core\Service\CustomizableClass;

/**
 * Class NestedStructureBuilder
 * @package Sam\Report\Invoice\Legacy\InvoiceList\Csv\Internal\Render\Nested
 */
class NestedArrayBuilder extends CustomizableClass
{
    private const FIELD_SEPARATOR = ';';
    private const LOT_SEPARATOR = '|';
    private const ESCAPE_CHAR = '\\';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $lotRows
     * @return string
     */
    public function build(array $lotRows): string
    {
        $lotLines = [];
        foreach ($lotRows as $lotRow) {
            foreach ($lotRow as $i => $field) {
                $lotRow[$i] = $this->escape($field);
            }
            $lotLines[] = implode(self::FIELD_SEPARATOR, $lotRow);
        }
        $output = implode(self::LOT_SEPARATOR, $lotLines);
        return $output;
    }

    protected function escape(string $value): string
    {
        $dlm = '/';
        $separators = [self::FIELD_SEPARATOR, self::LOT_SEPARATOR];
        foreach ($separators as $separator) {
            $value = preg_replace(
                $dlm . preg_quote($separator, $dlm) . $dlm,
                self::ESCAPE_CHAR . $separator,
                $value
            );
        }
        return $value;
    }
}
