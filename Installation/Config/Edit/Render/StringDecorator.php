<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Июль 07, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class StringDecorator
 * @package Sam\Installation\Config
 */
class StringDecorator extends CustomizableClass
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
     * Add quotes symbols at begin and end of array values if value - is a string.
     * @param array $values
     * @return array
     */
    public function decorateQuotesInArrayValues(array $values): array
    {
        $decorated = [];
        foreach ($values as $value) {
            $decorated[] = $this->decorateQuotesValue($value);
        }
        return $decorated;
    }

    /**
     * Add quotes symbols at begin and end of input value if value is a string.
     * @param bool|string|float|int|array|null $value
     * @return string
     */
    protected function decorateQuotesValue(bool|string|float|int|array|null $value): string
    {
        $valueType = gettype($value);
        if (
            $valueType === Constants\Type::T_STRING
            && $value !== ''
        ) {
            return $this->quoteStringDecorator($value);
        }

        $validTypes = [
            Constants\Type::T_BOOL,
            Constants\Type::T_FLOAT,
            Constants\Type::T_INTEGER,
            Constants\Type::T_NULL,
        ];
        return in_array($valueType, $validTypes, true) ? (string)$value : '';
    }

    /**
     * Decorate string by single quotes.
     * @param string $value
     * @return string
     */
    protected function quoteStringDecorator(string $value): string
    {
        $normalizedValue = preg_replace(['/\'/'], ['\\\\\''], $value);
        $output = sprintf("'%s'", $normalizedValue);
        return $output;
    }
}
