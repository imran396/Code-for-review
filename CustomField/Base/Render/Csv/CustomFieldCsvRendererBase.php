<?php
/**
 * Base class for custom field data csv rendering
 *
 * SAM-4814: User Custom Field renderer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-22
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Base\Render\Csv;

use DateTime;
use Sam\Core\Constants;
use Sam\Core\CustomField\Decimal\CustomDataDecimalPureCalculator;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Report\Base\Csv\ReportToolAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class CustomFieldCsvRendererBase
 * @package Sam\CustomField\Base\Render\Csv
 */
abstract class CustomFieldCsvRendererBase extends CustomizableClass
{
    use NumberFormatterAwareTrait;
    use ReportToolAwareTrait;

    /**
     * @param int $type
     * @param int|bool|string|null $value null leads to empty results
     * @param string|null $param null means that precision equals to 1 for decimal render
     * @return string
     */
    public function renderByValue(int $type, int|bool|string|null $value, ?string $param = null): string
    {
        $result = match ($type) {
            Constants\CustomField::TYPE_INTEGER => $this->renderInteger(Cast::toInt($value)),
            Constants\CustomField::TYPE_DECIMAL => $this->renderDecimal(Cast::toInt($value), $param),
            Constants\CustomField::TYPE_DATE => $this->renderDate(Cast::toInt($value)),
            Constants\CustomField::TYPE_CHECKBOX => $this->renderBool(Cast::toBool($value)),
            Constants\CustomField::TYPE_TEXT,
            Constants\CustomField::TYPE_SELECT,
            Constants\CustomField::TYPE_FULLTEXT,
            Constants\CustomField::TYPE_PASSWORD,
            Constants\CustomField::TYPE_LABEL,
            Constants\CustomField::TYPE_FILE,
            Constants\CustomField::TYPE_POSTALCODE => $this->renderString((string)$value),
            default => '',
        };
        return $result;
    }

    /**
     * @param bool|null $value null leads to false
     * @return string
     */
    protected function renderBool(?bool $value): string
    {
        return $this->getReportTool()->renderBool($value);
    }

    /**
     * @param int|null $input null leads to empty result
     * @return string
     */
    protected function renderInteger(?int $input): string
    {
        return (string)$input;
    }

    /**
     * @param int|null $input null leads to empty result
     * @return string
     */
    protected function renderDate(?int $input): string
    {
        $input = Cast::toInt($input);
        $output = '';
        if ($input) {
            $output = (new DateTime())
                ->setTimestamp($input)
                ->format(Constants\Date::ISO);
        }
        return $output;
    }

    /**
     * @param int|null $input null leads to empty result
     * @param string|null $param null means that precision equals to 1
     * @return string
     */
    protected function renderDecimal(?int $input, ?string $param = null): string
    {
        if ($input === null) {
            return '';
        }

        $precision = (int)$param;
        $amount = CustomDataDecimalPureCalculator::new()->calcRealValue($input, $precision);
        $output = $this->getNumberFormatter()->formatNto($amount, $precision);
        return $output;
    }

    /**
     * @param string|null $input null leads to empty result
     * @return string
     */
    protected function renderString(?string $input): string
    {
        $output = trim((string)$input);
        return $output;
    }
}
