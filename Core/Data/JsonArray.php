<?php
/**
 * JsonArray represents objects created by JSON in string
 * Object represents array, that's keys can be accessed via get/set/drop methods
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           16 Nov, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Data;

use RuntimeException;
use Laminas\Json\Json;

/**
 * Class JsonArray
 * @package Sam\Core\Data
 */
class JsonArray
{
    protected array $arr = [];

    /**
     * JsonArray constructor.
     * @param array|string|null $initData
     */
    public function __construct(array|string|null $initData = null)
    {
        if (is_null($initData)) {
            $this->arr = [];
        } elseif (is_array($initData)) {
            $this->arr = $initData;
        } else {
            $this->arr = $this->decodeToArray($initData);
        }
    }

    /**
     * @param int|string $key
     * @param mixed $value
     */
    public function set(int|string $key, mixed $value): void
    {
        $this->arr[$key] = $value;
    }

    /**
     * @param int|string $key
     * @return mixed
     */
    public function get(int|string $key): mixed
    {
        $value = $this->arr[$key] ?? null;
        return $value;
    }

    /**
     * @return string
     */
    public function getJson(): string
    {
        $json = Json::encode($this->arr);
        return $json;
    }

    /**
     * @return array
     */
    public function getArray(): array
    {
        return $this->arr;
    }

    /**
     * @param int|string $key
     */
    public function drop(int|string $key): void
    {
        unset($this->arr[$key]);
    }

    /**
     * @return mixed
     */
    public function shift(): mixed
    {
        return array_shift($this->arr);
    }

    /**
     * @param mixed $value
     */
    public function unshift(mixed $value): void
    {
        array_unshift($this->arr, $value);
    }

    /**
     * @return mixed
     */
    public function pop(): mixed
    {
        return array_pop($this->arr);
    }

    /**
     * @param mixed $value
     */
    public function push(mixed $value): void
    {
        $this->arr[] = $value;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->arr);
    }

    /**
     * @param string $json
     * @return array
     */
    protected function decodeToArray(string $json): array
    {
        if (trim($json) === '') {
            return [];
        }

        try {
            $arr = Json::decode($json, Json::TYPE_ARRAY);
            if (!is_array($arr)) {
                log_error('Decoded JSON expected to be array, return empty array');
                return [];
            }
            return $arr;
        } catch (RuntimeException $e) {
            log_error('JSON decoding failed, return empty array' . composeSuffix(['error' => $e->getMessage(), 'code' => $e->getCode()]));
            return [];
        }
    }
}
