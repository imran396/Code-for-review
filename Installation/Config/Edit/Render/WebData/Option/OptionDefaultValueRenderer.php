<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           09-12, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\WebData\Option;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;

/**
 * Class BuilderHelper
 * @package Sam\Installation\Config
 */
class OptionDefaultValueRenderer extends CustomizableClass
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
     * Render config option value for display in web interface form for Local config values list and for
     * default values if config option exists and override in local config file.
     * @param bool|string|array|int|float|null $value
     * @param string $dataType
     * @return string
     */
    public function render(bool|string|array|int|float|null $value, string $dataType): string
    {
        $emptyVal = "''";
        $output = '';
        switch ($dataType) {
            case Constants\Type::T_BOOL:
                $output = $value ? 'true' : 'false';
                break;

            case Constants\Type::T_NULL:
                $validTypes = [Constants\Type::T_INTEGER, Constants\Type::T_FLOAT, Constants\Type::T_STRING];
                if ($value === null) {
                    $output = 'NULL';
                } elseif (empty($value)) {
                    $output = $emptyVal;
                } elseif (in_array(gettype($value), $validTypes, true)) {
                    $output = $value;
                } elseif (is_array($value)) {
                    $output = implode(', ', $value);
                }
                break;

            case Constants\Type::T_ARRAY:
                $msg = 'error: not an Array';
                if (is_array($value) && count($value)) {
                    $output = implode(', ', $value);
                } elseif (empty($value)) {
                    $output = $emptyVal;
                } else {
                    $output = $msg;
                }
                break;

            case Constants\Type::T_INTEGER:
                $output = $this->renderDefaultValueForNumeric($value, Constants\Type::T_INTEGER);
                break;

            case Constants\Type::T_FLOAT:
                $output = $this->renderDefaultValueForNumeric($value, Constants\Type::T_FLOAT);
                break;

            case Constants\Type::T_STRING:
                $string = trim($value);
                if ($string === '') {
                    $output = $emptyVal;
                } else {
                    $output = "'{$string}'";
                }
                break;
        }
        return (string)$output;
    }

    /**
     * @param bool|string|array|int|float|null $value
     * @param string $type
     * @return string
     */
    protected function renderDefaultValueForNumeric(bool|string|array|int|float|null $value, string $type): string
    {
        if (in_array($type, [Constants\Type::T_FLOAT, Constants\Type::T_INTEGER], true)) {
            $msg = $type === Constants\Type::T_FLOAT ? 'error: not a Float' : 'error: not a Integer';
            if ($value !== '') {
                $output = $type === Constants\Type::T_FLOAT
                    ? Cast::toFloat($value)
                    : Cast::toInt($value);
                return $output === null ? $msg : (string)$output;
            }
            return $msg;
        }
        return '';
    }
}
