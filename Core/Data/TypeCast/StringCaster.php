<?php
/**
 * String type cast helper for individual variables.
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
use Sam\Core\Url\UrlParser;

/**
 * Class for casting and normalizing of string values
 * @package Sam\Core\Data\TypeCast
 */
class StringCaster extends CasterBase
{
    /** @var string[] */
    protected array $acceptableFilters = [
        Constants\Type::F_STRING,
        Constants\Type::F_STRING_TRIM,
        Constants\Type::F_URL,
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
            $this->defaultFilter = (string)$this->cfg()->get('core->general->typeCast->toString');
        }
        return $this->defaultFilter;
    }

    /**
     * Cast value to string type.
     * We don't convert array to json string, array is not expected value for string normalization.
     * @param mixed $input
     * @param string|null $filter null means apply default filter
     * @param string[]|null $knownSet - pass array of known set for filtering by string acceptable values
     * @return string|null
     */
    public function toString(
        mixed $input,
        ?string $filter = null,
        ?array $knownSet = null
    ): ?string {
        if ($filter === null) {
            $filter = $this->getDefaultFilter();
        }

        if (!$this->validateFilterArgument($filter)) {
            throw new InvalidArgumentException(
                "Incorrect filtering option '{$filter}', when normalizing value to string type"
            );
        }

        $type = gettype($input);
        $stringConvertibleTypes = [
            Constants\Type::T_BOOL,
            Constants\Type::T_INTEGER,
            Constants\Type::T_FLOAT,
            Constants\Type::T_STRING,
            Constants\Type::T_NULL,
        ];
        $output = null;
        if (in_array($type, $stringConvertibleTypes, true)) {
            if ($filter === Constants\Type::F_DISABLED) {
                $output = (string)$input;
            } elseif ($type !== Constants\Type::T_NULL) {
                if ($type === Constants\Type::T_BOOL) {
                    $input = (int)$input;   // Let it be "0" or "1" in result.
                }
                if ($filter === Constants\Type::F_STRING) {
                    $output = (string)$input;
                } elseif ($filter === Constants\Type::F_STRING_TRIM) {
                    $output = trim($input);
                } elseif ($filter === Constants\Type::F_URL) {
                    $urlParser = UrlParser::new();
                    $url = trim($input);
                    $output = $urlParser->isUrl($url)
                        ? $urlParser->sanitize($url)
                        : null;
                }
            }
        }

        if ($knownSet) {
            $output = in_array($output, $knownSet, true) ? $output : null;
        }

        if (
            $output === null
            && $input !== null
        ) {
            $suffix = ['input json-encoded' => $input, 'filter' => $filter];
            if ($knownSet) {
                $suffix['filter set'] = $knownSet;
            }
            $this->log("Cannot normalize value by string type" . composeSuffix($suffix));
        }
        return $output;
    }
}
