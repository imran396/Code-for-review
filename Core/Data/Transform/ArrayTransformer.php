<?php
/**
 * Transform array to string and vice-versa
 *
 * SAM-6788: Array to string transformer
 * SAM-4825: Strict type related adjustments
 *
 * This service can be applied in code, i.e. for drop-down custom field parameters operations.
 * Features:
 * [x] - define input and output delimiter
 * [x] - define characters that should be removed from start and end of every value
 * [ ] - define characters that should be prepended and appended for every value
 * [x] - define option for unique filtering
 * [x] - configure to keep or remove nulls
 * [x] - configure filtering type (Int/Float, Positive Int/Float, Positive or Zero Int/Float, String, Url, Bool)
 * [x] - unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 05, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Data\Transform;

use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class ArrayTransformer
 * @package Sam\Core\Data\Transform
 */
class ArrayTransformer extends CustomizableClass
{
    /**
     * Expected to see in input string for exploding to array, or desired for imploding to string separator.
     */
    private string $delimiter = ', ';
    /**
     * Do we want to result with unique values only.
     */
    private bool $isUnique = false;
    /**
     * Define character list, that should be used to trim values of array (for input and output).
     */
    private string $trimCharList = ' ';
    /**
     * Filter types are defined in Constants\Type
     */
    private ?string $filter = null;
    /**
     * Define character list, that should lead and trail
     */
    private string $wrapCharList = '';
    private bool $keepNulls = false;

    public const OP_DELIMITER = 'delimiter';
    public const OP_IS_UNIQUE = 'isUnique';
    public const OP_TRIM_CHAR_LIST = 'trimCharList';
    public const OP_FILTER = 'filter';
    public const OP_WRAP_CHAR_LIST = 'wrapCharList';
    public const OP_KEEP_NULLS = 'keepNulls';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Null values mean class default.
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->delimiter = $optionals[self::OP_DELIMITER] ?? $this->delimiter;
        $this->filter = $optionals[self::OP_FILTER] ?? $this->filter;
        $this->isUnique = $optionals[self::OP_IS_UNIQUE] ?? $this->isUnique;
        $this->keepNulls = $optionals[self::OP_KEEP_NULLS] ?? $this->keepNulls;
        $this->trimCharList = $optionals[self::OP_TRIM_CHAR_LIST] ?? $this->trimCharList;
        $this->wrapCharList = $optionals[self::OP_WRAP_CHAR_LIST] ?? $this->wrapCharList;
        return $this;
    }

    /**
     * Assemble string from values of array
     * @param array $array
     * @return string
     */
    public function arrayToString(array $array): string
    {
        $array = $this->filterArray($array);
        $array = $this->wrapValues($array);
        $string = implode($this->delimiter(), $array);
        return $string;
    }

    /**
     * Parse string to array with consideration of required operations of value adjustments
     * @param string $string
     * @return array
     */
    public function stringToArray(string $string): array
    {
        $array = $this->delimiter() ? explode($this->delimiter(), $string) : [];
        $array = $this->wrapValues($array);
        $array = $this->filterArray($array);
        return $array;
    }

    /**
     * Determine delimiter for gluing and splitting. BackSpace delimiter is allowed " ".
     * @return string
     */
    protected function delimiter(): string
    {
        return trim($this->delimiter) ?: $this->delimiter;
    }

    /**
     * Perform configured filtering
     * @param array $array
     * @return array
     */
    protected function filterArray(array $array): array
    {
        $array = $this->trimValues($array);
        $array = ArrayCast::makeTypedArray(
            $this->findType(),
            $array,
            $this->filter,
            [ArrayCast::OP_KEEP_NULLS => $this->keepNulls]
        );
        $array = $this->filterUnique($array);
        $array = array_values($array);
        return $array;
    }

    /**
     * Result with unique value array
     * @param array $array
     * @return array
     */
    protected function filterUnique(array $array): array
    {
        if ($this->isUnique) {
            $array = array_unique($array);
        }
        return $array;
    }

    /**
     * Clean specific characters from start and end of every value in array, when it is string
     * @param array $array
     * @return array
     */
    protected function trimValues(array $array): array
    {
        $trimCharList = $this->trimCharList;
        if ($trimCharList) {
            $array = array_map(
                static function ($value) use ($trimCharList) {
                    return is_string($value) ? trim($value, $trimCharList) : $value;
                },
                $array
            );
        }
        return $array;
    }

    /**
     * Detect type by filtering type, otherwise it is string
     * @return string|null null when type cannot be found
     */
    protected function findType(): ?string
    {
        $defaultType = Constants\Type::T_STRING;
        if (!$this->filter) {
            return $defaultType;
        }
        foreach (Constants\Type::TYPES_TO_FILTERS as $type => $filters) {
            if (in_array($this->filter, $filters, true)) {
                return $type;
            }
        }
        return $defaultType;
    }

    /**
     * Wrap every value with some characters at start and at end
     * @param array $array
     * @return array
     */
    protected function wrapValues(array $array): array
    {
        $wrapCharList = $this->wrapCharList;
        if ($wrapCharList) {
            $array = array_filter(
                array_map(
                    static function ($value) use ($wrapCharList) {
                        return $wrapCharList . $value . $wrapCharList;
                    },
                    $array
                )
            );
        }
        return $array;
    }
}
