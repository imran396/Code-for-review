<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Csv;

use ConsignorCommissionFeeRange;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Data\Range;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * This class contains methods to convert consignor commission fee ranges to csv string and vice versa
 *
 * Class ConsignorCommissionRangesToCsvStringTransformer
 * @package Sam\Consignor\Commission\Csv
 */
class ConsignorCommissionRangeCsvTransformer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use NumberFormatterAwareTrait;

    public const OP_CSV_CLEAR_VALUE = 'csvClearValue';
    public const OP_CSV_DEFAULT_VALUE = 'csvDefaultValue';

    protected const RANGES_DELIMITER = '|';
    protected const AMOUNT_DELIMITER = ':';
    protected const SET_DELIMITER = '-';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Convert consignor commission fee ranges to csv string
     *
     * @param ConsignorCommissionFeeRange[] $ranges
     * @return string
     */
    public function transformEntitiesToCsvString(array $ranges): string
    {
        $rangesAsString = [];
        foreach ($ranges as $range) {
            $rangesAsString[] =
                $this->getNumberFormatter()->formatMoneyNto($range->Amount) . self:: AMOUNT_DELIMITER .
                $this->getNumberFormatter()->formatMoneyNto($range->Fixed) . self::SET_DELIMITER .
                $this->getNumberFormatter()->formatPercent($range->Percent) . self::SET_DELIMITER .
                Constants\ConsignorCommissionFee::RANGE_MODE_NAMES[$range->Mode];
        }
        return implode(self::RANGES_DELIMITER, $rangesAsString);
    }

    /**
     * Parse CSV string into array of entity maker range dto
     *
     * @param string $csvString
     * @param array $optionals = [
     *      OP_CSV_CLEAR_VALUE => (string),
     *      OP_CSV_DEFAULT_VALUE => (string)
     * ]
     * @return array<Range|string>
     */
    public function transformCsvStringToDtos(string $csvString, array $optionals = []): array
    {
        if (!$csvString) {
            return [];
        }
        if (in_array($csvString, [$this->fetchOptionalCsvClearValue($optionals), $this->fetchOptionalCsvDefaultValue($optionals)], true)) {
            return [$csvString];
        }
        $result = [];
        foreach (explode(self::RANGES_DELIMITER, $csvString) as $key => $pair) {
            $range = new Range();
            $pair = explode(self::AMOUNT_DELIMITER, $pair);
            if ($key === 0 && count($pair) === 1) {
                $amount = 0;
                $setString = $pair[0];
            } else {
                [$amount, $setString] = $pair;
            }
            $range->amount = (float)$amount;
            [$range->fixed, $range->percent, $range->mode] = $this->parseSet($setString);
            $result[] = $range;
        }
        return $result;
    }

    protected function fetchOptionalCsvClearValue(array $optionals): string
    {
        return $optionals[self::OP_CSV_CLEAR_VALUE] ?? $this->cfg()->get('core->csv->clearValue');
    }

    protected function fetchOptionalCsvDefaultValue(array $optionals): string
    {
        return $optionals[self::OP_CSV_DEFAULT_VALUE] ?? $this->cfg()->get('core->csv->defaultValue');
    }

    /**
     * @param string $set
     * @return array
     */
    protected function parseSet(string $set): array
    {
        $set = explode(self::SET_DELIMITER, $set);
        $fixed = $set[0] ?? 0.;
        $percent = $set[1] ?? 0.;
        $mode = $set[2] ?? Constants\ConsignorCommissionFee::RANGE_MODE_GREATER_NAME;
        return [$fixed, $percent, $mode];
    }
}
