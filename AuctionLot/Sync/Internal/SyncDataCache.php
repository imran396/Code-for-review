<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Internal;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * File cache for auction lot sync public data
 *
 * Class SyncDataCache
 * @package Sam\AuctionLot\Sync\Internal
 */
class SyncDataCache extends CustomizableClass
{
    use FilesystemCacheManagerAwareTrait;
    use OptionalsTrait;
    use ServerRequestReaderAwareTrait;

    public const OP_BINARY_PROTOCOL_ENABLED = 'protobufBinaryProtocolEnabled';

    /**
     * @var string
     */
    protected string $key = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $requestData
     * @param string $cacheNamespace
     * @param int $ttl
     * @param array $optionals
     * @return static
     */
    public function construct(array $requestData, string $cacheNamespace, int $ttl = 2, array $optionals = []): static
    {
        $this->key = $this->makeCacheKey($requestData);
        $this->getFilesystemCacheManager()
            ->setExtension('php')
            ->setNamespace($cacheNamespace)
            ->setDefaultTtl($ttl);
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @return string|null
     */
    public function get(): ?string
    {
        return $this->getFilesystemCacheManager()->get($this->key);
    }

    /**
     * @param string $data
     */
    public function set(string $data): void
    {
        $this->getFilesystemCacheManager()->set($this->key, $data);
    }

    /**
     * @param array $requestData
     * @return string
     */
    protected function makeCacheKey(array $requestData): string
    {
        return $this->key = md5(strtolower(trim(var_export($requestData, true))))
            . '_' . (int)$this->fetchOptional(self::OP_BINARY_PROTOCOL_ENABLED);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_BINARY_PROTOCOL_ENABLED] = $optionals[self::OP_BINARY_PROTOCOL_ENABLED]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->app->protobuf->binaryProtocol');
            };
        $this->setOptionals($optionals);
    }
}
