<?php
/**
 * SAM-6658: Improve application and domain layer services
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Service\Optional;

use Sam\Core\Service\CustomizableClass;

/**
 * Class OptionalsCollection
 * @package Sam\Core\Service
 */
class OptionalsCollection extends CustomizableClass
{
    protected array $optionals;
    protected array $cache = [];

    public const NONE = 0;
    public const GET_FROM_CACHE = 1;
    public const GET_BASIC_VALUE = 2;
    public const GET_CALLABLE_VALUE = 3;
    public const ERR_KEY_NOT_FOUND = 11;

    private int $lastOperationStatus = self::NONE;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Optionals value may be initialized in lazy way with help of closure
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals): static
    {
        $this->optionals = $optionals;
        return $this;
    }

    public function extractOptionalsByKeys(array $keys = []): array
    {
        if ($keys) {
            return array_intersect_key($this->optionals, array_flip($keys));
        }
        return $this->optionals;
    }

    /**
     * @param string $key
     * @param array $arguments
     * @return mixed
     */
    public function fetchOptional(string $key, array $arguments = []): mixed
    {
        $cacheKey = $this->makeCacheKey($key, $arguments);
        if (array_key_exists($cacheKey, $this->cache)) {
            $this->lastOperationStatus = self::GET_FROM_CACHE;
            return $this->cache[$cacheKey];
        }

        if (!isset($this->optionals[$key])) {
            $this->lastOperationStatus = self::ERR_KEY_NOT_FOUND;
            return null;
        }

        $callableOrValue = $this->optionals[$key];
        /**
         * Check, if we store value ready for usage. It shouldn't be callable,
         * or can be string like 'date', that is callable internal php function
         */
        $isValue = !is_callable($callableOrValue)
            || is_string($callableOrValue);
        if ($isValue) {
            $this->set($key, $callableOrValue, $arguments);
            $this->lastOperationStatus = self::GET_BASIC_VALUE;
            return $callableOrValue;
        }

        /**
         * Execute deferred function to find result value
         */
        $this->set($key, $callableOrValue(...$arguments), $arguments);
        $this->lastOperationStatus = self::GET_CALLABLE_VALUE;
        return $this->cache[$cacheKey];
    }

    /**
     * @param string $key
     * @param $value
     * @param array $arguments
     */
    public function set(string $key, $value, array $arguments = []): void
    {
        $cacheKey = $this->makeCacheKey($key, $arguments);
        $this->cache[$cacheKey] = $value;
    }

    /**
     * @return int
     * @internal for unit testing
     */
    public function lastOperationStatus(): int
    {
        return $this->lastOperationStatus;
    }

    /**
     * @param string $key
     * @param array $arguments
     * @return string
     */
    private function makeCacheKey(string $key, array $arguments = []): string
    {
        $cacheKey = $key;
        if ($arguments) {
            $cacheKey .= '_' . md5(json_encode($arguments));
        }
        return $cacheKey;
    }
}
