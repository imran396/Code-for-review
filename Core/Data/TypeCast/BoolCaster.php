<?php
/**
 * Boolean type cast helper for individual variables.
 * Call ArrayHelper's cast methods for type transformations of array values
 *
 * SAM-4944: Defensive development approach
 * SAM-4825: Strict type related adjustments
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/7/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Data\TypeCast;

use InvalidArgumentException;
use Sam\Core\Constants;

/**
 * Class for casting and normalizing of bool values
 * @package Sam\Core\Data\TypeCast
 */
class BoolCaster extends CasterBase
{
    /** @var string[] */
    protected array $acceptableFilters = [
        Constants\Type::F_BOOL,
        Constants\Type::F_DISABLED,
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function getDefaultFilter(): string
    {
        if ($this->defaultFilter === null) {
            $this->defaultFilter = (string)$this->cfg()->get('core->general->typeCast->toBool');
        }
        return $this->defaultFilter;
    }

    /**
     * Cast value to boolean type
     * @param mixed $input
     * @param string|array|null $filter null means apply default filter
     * @return bool|null
     */
    public function toBool(
        mixed $input,
        string|array|null $filter = null
    ): ?bool {
        if ($filter === null) {
            $filter = $this->getDefaultFilter();
        }

        if (!in_array($filter, [Constants\Type::F_BOOL, Constants\Type::F_DISABLED], true)) {
            throw new InvalidArgumentException(
                "Incorrect filtering option '{$filter}', when normalizing value to boolean type"
            );
        }

        $output = null;
        if ($filter === Constants\Type::F_DISABLED) {
            $output = (bool)$input;
        } elseif (self::isBoolable($input)) { // $filter === Constants\Type::F_BOOL
            $type = gettype($input);
            if ($type === Constants\Type::T_BOOL) {
                $output = $input;
            } else {
                $output = (bool)IntCaster::new()->toInt($input);
            }
        }

        if (
            $output === null
            && $input !== null
        ) {
            $suffix = ['input json-encoded' => $input, 'filter' => $filter];
            $this->log("Cannot normalize value by boolean type" . composeSuffix($suffix));
        }
        return $output;
    }

    /**
     * Any type value, that can be casted to integer 0, 1 can be considered as boolable.
     * It relies to integer, float, string types.
     * Boolean type values are boolable too.
     * We don't consider as boolable: null, array, object, resource type values.
     * @param mixed $value
     * @return bool
     */
    protected static function isBoolable(mixed $value): bool
    {
        $type = gettype($value);
        if ($type === Constants\Type::T_BOOL) {
            return true;
        }

        $boolConvertibleTypes = [
            Constants\Type::T_INTEGER,
            Constants\Type::T_FLOAT,
            Constants\Type::T_STRING,
        ];
        if (in_array($type, $boolConvertibleTypes, true)) {
            $valueInt = IntCaster::new()->toInt($value);
            $isBool = in_array($valueInt, [0, 1], true);
            return $isBool;
        }
        return false;
    }
}
