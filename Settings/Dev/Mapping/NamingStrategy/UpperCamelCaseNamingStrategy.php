<?php
/**
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Dev\Mapping\NamingStrategy;

use Sam\Core\Service\CustomizableClass;

/**
 * Responsible for converting database column names strategy
 *
 * Class UpperCamelCaseNamingStrategy
 * @package Sam\Settings\Dev\Mapping
 */
class UpperCamelCaseNamingStrategy extends CustomizableClass implements NamingStrategyInterface
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
     * @inheritDoc
     */
    public function columnToConstantName(string $columnName): string
    {
        return strtoupper($columnName);
    }

    /**
     * @inheritDoc
     */
    public function columnToPropertyName(string $columnName): string
    {
        $nameParts = explode('_', $columnName);
        $nameParts = array_map(
            static function (string $namePart) {
                return ucfirst(strtolower($namePart));
            },
            $nameParts
        );
        return implode('', $nameParts);
    }
}
