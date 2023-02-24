<?php
/**
 * SAM-6499: Refactor Settlement Calculator module (2020 year)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Calculate\Internal\Load\Cache;


use RuntimeException;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementProcessingCache
 * @package Sam\Settlement\Calculate\Internal\Load
 * @internal
 */
class SettlementProcessingCache extends CustomizableClass
{
    protected array $cache = [];
    protected ?int $settlementId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $key
     * @param int $settlementId
     * @return bool
     * @internal
     */
    public function has(string $key, int $settlementId): bool
    {
        return $this->settlementId === $settlementId
            && array_key_exists($key, $this->cache);
    }

    /**
     * @param string $key
     * @param int $settlementId
     * @return mixed
     * @internal
     */
    public function get(string $key, int $settlementId): mixed
    {
        if (!$this->has($key, $settlementId)) {
            throw new RuntimeException('Cash doesn\'t exist');
        }
        return $this->cache[$key];
    }

    /**
     * @param string $key
     * @param int $settlementId
     * @param $data
     * @internal
     */
    public function set(string $key, int $settlementId, $data): void
    {
        if ($this->settlementId !== $settlementId) {
            $this->reset($settlementId);
        }
        $this->cache[$key] = $data;
    }

    /**
     * @param int $settlementId
     */
    protected function reset(int $settlementId): void
    {
        $this->settlementId = $settlementId;
        $this->cache = [];
    }
}
