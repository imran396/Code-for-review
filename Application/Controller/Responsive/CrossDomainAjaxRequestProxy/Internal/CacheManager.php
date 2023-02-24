<?php
/**
 * SAM-5788: Adjust proxy.php script
 * SAM-7980: Refactor proxy.php
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\CrossDomainAjaxRequestProxy\Internal;

use Psr\SimpleCache\CacheInterface;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\RequestParam\ParamFetcherForPostAwareTrait;
use Sam\Cache\File\FilesystemCacheManager;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Caching the result of an external resource request
 *
 * Class CacheManager
 * @package Sam\Application\Controller\Responsive\CrossDomainAjaxRequestProxy
 * @internal
 */
class CacheManager extends CustomizableClass
{
    use FilesystemCacheManagerAwareTrait;
    use OptionalsTrait;
    use ParamFetcherForGetAwareTrait;
    use ParamFetcherForPostAwareTrait;
    use ServerRequestReaderAwareTrait;

    public const OP_TTL = 'proxyCacheTtl';

    protected const CACHE_NAMESPACE = 'web-proxy';
    protected const CACHE_KEY_PREFIX = 'ajax';

    protected ?string $key = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @return string|null
     */
    public function get(): ?string
    {
        $key = $this->getKey();
        $data = $this->getCacheManager()->get($key);
        return $data;
    }

    /**
     * @param string $data
     */
    public function set(string $data): void
    {
        $ttl = $this->fetchOptional(self::OP_TTL);
        if ($ttl > 0) {
            $key = $this->getKey();
            $this->getCacheManager()->set($key, $data, $ttl);
        }
    }

    /**
     * @return CacheInterface
     */
    protected function getCacheManager(): CacheInterface
    {
        return $this->getFilesystemCacheManager()
            ->setNamespace(self::CACHE_NAMESPACE)
            ->setExtension(FilesystemCacheManager::EXT_TXT);
    }

    /**
     * @return string
     */
    protected function getKey(): string
    {
        if ($this->key === null) {
            $this->key = $this->makeKey();
        }
        return $this->key;
    }

    /**
     * Create a cache key using the GET and POST parameters
     *
     * @return string
     */
    protected function makeKey(): string
    {
        $key = self::CACHE_KEY_PREFIX;
        if ($this->getServerRequestReader()->isPost()) {
            $data = $this->getParamFetcherForPost()->getParameters();
            if ($data) {
                $key .= '; POST:' . var_export($data, true);
            }
        } else {
            $data = $this->getParamFetcherForGet()->getParameters();
            if ($data) {
                $key .= '; GET:' . var_export($data, true);
            }
        }
        return $key;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_TTL] = $optionals[self::OP_TTL]
            ?? static function (): int {
                return (int)ConfigRepository::getInstance()->get('core->vendor->samSharedService->httpProxy->cacheTimeout');
            };
        $this->setOptionals($optionals);
    }
}
