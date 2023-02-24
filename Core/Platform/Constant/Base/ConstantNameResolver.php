<?php
/**
 * This service tries to resolve constant name by value.
 * It is pure, although implementation is based on reflection mechanism.
 *
 * SAM-6862: Constants name resolving for better logs
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 31, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Platform\Constant\Base;

use ReflectionClass;
use ReflectionException;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ConstantNameResolver
 * @package Sam\Core\Platform\Constant\Base
 */
class ConstantNameResolver extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        return $this;
    }

    /**
     * Return constant name by value, that found in class.
     * (!) Be aware of possible value duplication among class constants.
     * @param mixed $value
     * @param string $class
     * @return string|null
     */
    public function resolveFromClass(mixed $value, string $class): ?string
    {
        [$foundNamesToCodes, $notFoundCodes] = $this->resolveManyFromClass([$value], $class);
        $tuple = array_pop($foundNamesToCodes);
        if ($tuple) {
            return $tuple[1];
        }

        return $notFoundCodes[0] ?? null;
    }

    /**
     * Search for constants in class by values, and return array of found items.
     * (!) Be aware of possible value duplication among class constants.
     * @param array $values
     * @param string $class
     * @return array[] of tuples: [<value>, <constant name>]
     */
    public function resolveManyFromClass(array $values, string $class): array
    {
        try {
            $reflectionClass = new ReflectionClass($class);
        } catch (ReflectionException) {
            log_error('Cannot find class for resolving constant names' . composeSuffix(['class' => $class]));
            return [];
        }
        $allNamesToCodes = $reflectionClass->getConstants();
        $foundNamesToCodes = $notFoundCodes = [];
        foreach ($values as $value) {
            $tuple = $this->findValueKeyTuple($value, $allNamesToCodes);
            if ($tuple) {
                $foundNamesToCodes[] = $tuple;
            } else {
                $notFoundCodes[] = $value;
            }
        }
        return [$foundNamesToCodes, $notFoundCodes];
    }

    /**
     * @param mixed $needle
     * @param array $haystack
     * @return array
     */
    private function findValueKeyTuple(mixed $needle, array $haystack): array
    {
        foreach ($haystack as $key => $value) {
            if ($needle === $value) {
                return [$value, $key];
            }
        }
        return [];
    }
}
