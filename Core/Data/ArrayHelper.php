<?php
/**
 * Helper class for array related functionality
 *
 * SAM-4825: Strict type related adjustments
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           7 Jun, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Data;

use Exception;
use Generator;
use InvalidArgumentException;

/**
 * Class ArrayHelper
 * @package Sam\Core\Data
 */
class ArrayHelper
{
    /**
     * Simple way to make two dimensional array to one dimensional without saving keys
     * E.g. [0 => ['name' => 'abc', 'age' => 10], 1 => ['name' => 'xzy', 'age' => 20]] is converted to [0 => 'abc', 1 => 10, 2 => 'xzy', 3 => 20]
     * NOTE: You should use array_column($rows, 'name'), if it is possible
     * @param array $rows
     * @return array
     */
    public static function flattenArray(array $rows): array
    {
        $result = [];
        foreach ($rows as $value) {
            if (is_array($value)) {
                $result = array_merge($result, self::flattenArray($value));
            } else {
                $result[] = $value;
            }
        }
        return $result;
    }

    /**
     * Keep in array only values from first dimension, all sub-arrays are dropped.
     * @param array $rows
     * @return array
     */
    public static function toFirstDimension(array $rows): array
    {
        $result = [];
        foreach ($rows as $i => $row) {
            if (!is_array($row)) {
                $result[$i] = $row;
            }
        }
        return $result;
    }

    /**
     * Search if $needle exists in multiple array $haystack
     * @param string $needle
     * @param array $haystack
     * @param bool $strict - check the types of the $needle in $haystack
     * @return bool
     * http://www.phpf1.com/tutorial/php-in_array.html
     */
    public static function inMultiArray(string $needle, array $haystack, bool $strict = false): bool
    {
        if (in_array($needle, $haystack, $strict)) {
            return true;
        }
        foreach ($haystack as $element) {
            if (
                is_array($element)
                && self::inMultiArray($needle, $element)
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Index $sourceRows array by value of $indexKey, store all values or only selected by $valueKeys
     * @param array $sourceRows
     * @param string $indexKey
     * @param string[]|null $valueKeys
     * @return array
     */
    public static function produceIndexedArray(array $sourceRows, string $indexKey, ?array $valueKeys = null): array
    {
        $indexedArray = [];
        foreach ($sourceRows as $sourceRow) {
            if ($valueKeys !== null) {
                $resultRow = [];
                foreach ($valueKeys as $valueKey) {
                    $resultRow[$valueKey] = $sourceRow[$valueKey];
                }
            } else {
                $resultRow = $sourceRow;
            }
            $indexedArray[$sourceRow[$indexKey]] = $resultRow;
        }
        return $indexedArray;
    }

    /**
     * Extract specific columns from 2-dimension array
     * @param array $rows
     * @param array $keys
     * @return array
     */
    public static function arrayColumns(array $rows, array $keys): array
    {
        $results = [];
        $columnKeys = array_flip($keys);
        foreach ($rows as $key => $row) {
            $results[$key] = array_intersect_key($row, $columnKeys);
        }
        return $results;
    }

    /**
     * Order elements of $entities array according their $property value position in $orderedValues array.
     * It returns unchanged $entities array, when there are mismatch of elements count in arrays.
     * It also considers case, when the same values among ordered values are passed,
     * it searches for appropriate entity for each of them. See, unit test.
     * @param array $entities - non ordered entity records
     * @param string $property - compared property of entity
     * @param array $orderedValues - property values in correct order
     * @return array
     */
    public static function orderEntities(array $entities, string $property, array $orderedValues): array
    {
        if (!$orderedValues) {
            log_error("Ordered values array cannot be empty");
            return $entities;
        }

        $entityCount = count($entities);
        $orderedValueCount = count($orderedValues);
        if ($entityCount !== $orderedValueCount) {
            log_error("Ordered values count ({$orderedValueCount}) cannot differ to entities count ({$entityCount})");
            return $entities;
        }

        $orderedEntities = [];
        for ($i = 0; $i < $entityCount; $i++) {
            $orderedValue = $orderedValues[$i];
            foreach ($entities as $j => $entity) {
                if (
                    $entity
                    && $orderedValue === $entity->{$property}
                ) {
                    $orderedEntities[$i] = $entity;
                    $entities[$j] = null;
                    continue 2;
                }
            }
        }
        return $orderedEntities;
    }

    /**
     * @param array $entities
     * @param string $property
     * @return array
     */
    public static function uniqueEntities(array $entities, string $property): array
    {
        $filtered = [];

        foreach ($entities as $entity) {
            if (!is_object($entity)) {
                continue;
            }

            if (self::propertyExists($entity, $property)) {
                $uniqueValue = $entity->{$property};
            } else {
                $message = 'Filtering property does not exist' . composeSuffix(['property' => $property]);
                log_error($message);
                throw new InvalidArgumentException($message);
            }
            if (!array_key_exists($uniqueValue, $filtered)) {
                $filtered[$uniqueValue] = $entity;
            }
        }

        $filtered = array_values($filtered);
        return $filtered;
    }

    /**
     * Place objects in array by index defined by some object's property value
     * @template T
     * @param array<T> $entities
     * @param string $property
     * @return array<T> [value1, value2, ... ]
     */
    public static function indexEntities(array $entities, string $property): array
    {
        $indexed = [];
        foreach ($entities as $entity) {
            if (self::propertyExists($entity, $property)) {
                $index = $entity->{$property};
            } else {
                $message = 'Indexing property does not exist' . composeSuffix(['property' => $property]);
                log_error($message);
                throw new InvalidArgumentException($message);
            }
            if (!array_key_exists($index, $indexed)) {
                $indexed[$index] = $entity;
            }
        }
        return $indexed;
    }

    /**
     * Place rows in array by index defined by some row's field value
     * @param array $rows
     * @param string $field
     * @return array [value1, value2, ... ]
     */
    public static function indexRows(array $rows, string $field): array
    {
        $indexed = [];
        foreach ($rows as $row) {
            if (array_key_exists($field, $row)) {
                $index = $row[$field];
            } else {
                $message = 'Indexing property does not exist' . composeSuffix(['property' => $field]);
                log_error($message);
                throw new InvalidArgumentException($message);
            }
            if (!array_key_exists($index, $indexed)) {
                $indexed[$index] = $row;
            }
        }
        return $indexed;
    }

    /**
     * Result with single dimension array, that contains values extracted from entities for definite property.
     * @param array $entities
     * @param string $property
     * @return array [[property1 => value, property2 => value], ... ]
     */
    public static function toArrayByProperty(array $entities, string $property): array
    {
        $results = [];
        foreach ($entities as $entity) {
            if (self::propertyExists($entity, $property)) {
                $results[] = $entity->{$property};
            } else {
                $message = 'Searching property does not exist' . composeSuffix(['property' => $property]);
                log_error($message);
                throw new InvalidArgumentException($message);
            }
        }
        return $results;
    }

    /**
     * Result with multiple dimension array, that contains values extracted from entities for defined properties
     * and associated by property name as key.
     * @param array $entities
     * @param string[] $properties
     * @return array
     */
    public static function toMultiArrayByProperties(array $entities, array $properties): array
    {
        $results = [];
        foreach ($entities as $entity) {
            $entry = [];
            foreach ($properties as $property) {
                if (self::propertyExists($entity, $property)) {
                    $entry[$property] = $entity->{$property};
                } else {
                    $message = 'Searching property does not exist' . composeSuffix(['property' => $property]);
                    log_error($message);
                    throw new InvalidArgumentException($message);
                }
            }
            $results[] = $entry;
        }
        return $results;
    }

    /**
     * @param array $array
     * @return Generator
     */
    public static function arrayAsGenerator(array $array): ?Generator
    {
        yield from $array;
    }

    /**
     * Create array of filled objects with help of named constructors
     * @param array $inputs
     * @param $cleanInstance
     * @param string $namedConstructor
     * @return array
     */
    public static function toArrayByNamedConstructor(array $inputs, $cleanInstance, string $namedConstructor): array
    {
        if (!method_exists($cleanInstance, $namedConstructor)) {
            $logData = [
                'class' => get_class($cleanInstance),
                'constructor' => $namedConstructor,
            ];
            $message = 'Named constructor method does not exist in provided instance' . composeSuffix($logData);
            log_error($message);
            throw new InvalidArgumentException($message);
        }
        $results = [];
        foreach ($inputs as $input) {
            $instance = clone $cleanInstance;
            $results[] = $instance->$namedConstructor($input);
        }
        return $results;
    }

    /**
     * Assign a value to specified property of entities in array.
     * @param array $entities
     * @param string $property
     * @param $value
     * @return array
     */
    public static function assignProperty(array $entities, string $property, $value): array
    {
        $results = [];
        foreach ($entities as $key => $entity) {
            if (self::propertyExists($entity, $property)) {
                $entity->$property = $value;
                $results[$key] = $entity;
            } else {
                $message = 'Searching property does not exist' . composeSuffix(['property' => $property]);
                log_error($message);
                throw new InvalidArgumentException($message);
            }
        }
        return $results;
    }

    /**
     * Transform full array or its slice to string.
     * @param array $array
     * @param int $startPos
     * @param int|null $length
     * @return string
     */
    public static function arrayToString(array $array, int $startPos = 0, ?int $length = null): string
    {
        $arraySlice = array_slice($array, $startPos, $length);
        $string = implode('', $arraySlice);
        return $string;
    }

    /**
     * Check that class property exists (even if it's magic property)
     *
     * @param $class
     * @param $property
     * @return bool
     */
    protected static function propertyExists($class, $property): bool
    {
        // if there are no magic __get method - use property_exists()
        if (!method_exists($class, '__get')) {
            return property_exists($class, $property);
        }

        try {
            /** @noinspection PhpUnusedLocalVariableInspection */
            $void = $class->{$property};
        } catch (Exception $e) { // @phpstan-ignore-line
            return false;
        }
        return true;
    }
}
