<?php
/**
 * Integer type cast helper for individual variables.
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

use Sam\Core\Constants;
use Sam\Core\Validate\Number\NumberValidator;

/**
 * Class for casting and normalizing of int values
 * @package Sam\Core\Data\TypeCast
 */
class IntCaster extends CasterBase
{
    /** @var string[] */
    protected array $acceptableFilters = [
        Constants\Type::F_INT,
        Constants\Type::F_INT_POSITIVE,
        Constants\Type::F_INT_POSITIVE_OR_ZERO,
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
            $this->defaultFilter = (string)$this->cfg()->get('core->general->typeCast->toInt');
        }
        return $this->defaultFilter;
    }

    /**
     * Cast value to integer type
     * @param mixed $input
     * @param string|null $filter null means apply default filter
     * @param int[]|null $knownSet - pass array of known set for filtering by integer acceptable values
     * @return int|null
     */
    public function toInt(
        mixed $input,
        ?string $filter = null,
        ?array $knownSet = null
    ): ?int {
        if ($filter === null) {
            $filter = $this->getDefaultFilter();
        }

        if (!$this->validateFilterArgument($filter)) {
            throw new \InvalidArgumentException(
                "Incorrect filtering option '{$filter}', when normalizing value to integer type"
            );
        }

        if (
            is_string($input)
            && $input === ''
        ) {
            // Empty string is expected and considered as null value
            $input = null;
        }

        if (is_bool($input)) {
            // Let it be 0 or 1 in result.
            $input = (int)$input;
        }

        if (
            ($filter === Constants\Type::F_DISABLED)
            || (
                $filter === Constants\Type::F_INT
                && NumberValidator::new()->isInt($input)
            ) || (
                $filter === Constants\Type::F_INT_POSITIVE
                && NumberValidator::new()->isIntPositive($input)
            ) || (
                $filter === Constants\Type::F_INT_POSITIVE_OR_ZERO
                && NumberValidator::new()->isIntPositiveOrZero($input)
            )
        ) {
            $output = (int)$input;
        } else {
            $output = null;
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
            $this->log("Cannot normalize value by integer type" . composeSuffix($suffix));
        }
        return $output;
    }
}
