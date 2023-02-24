<?php
/**
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Edit\Normalize;


use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class PropertyMetadataProvider
 * @package Sam\Settings\Edit\Validate
 */
class PropertyMetadataProvider extends CustomizableClass
{
    private const CUSTOM_INPUT_TYPE = [
        Constants\Setting::TIMEZONE_ID => Constants\Type::T_STRING,
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
     * @param string $property
     * @return string
     */
    public function getType(string $property): string
    {
        $propertyDefinition = $this->getPropertyDefinition($property);
        return $propertyDefinition['type'];
    }

    /**
     * @param string $property
     * @return array
     */
    public function getKnownSet(string $property): array
    {
        $propertyDefinition = $this->getPropertyDefinition($property);
        return $propertyDefinition['knownSet'] ?? [];
    }

    /**
     * @param string $property
     * @return bool
     */
    public function isNullable(string $property): bool
    {
        $propertyDefinition = $this->getPropertyDefinition($property);
        return isset($propertyDefinition['nullable'])
            && $propertyDefinition['nullable'];
    }

    /**
     * @param string $property
     * @return array
     */
    private function getPropertyDefinition(string $property): array
    {
        if (!array_key_exists($property, Constants\Setting::$typeMap)) {
            throw new \RuntimeException("Property with name {$property} doesn't exist");
        }
        $propertyDefinition = Constants\Setting::$typeMap[$property];

        if (array_key_exists($property, self::CUSTOM_INPUT_TYPE)) {
            $propertyDefinition['type'] = self::CUSTOM_INPUT_TYPE[$property];
        }
        return $propertyDefinition;
    }
}
