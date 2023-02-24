<?php
/**
 * SAM-5883: Develop the architecture for the admin side translation
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Translation\Cache;

use Sam\Core\Service\CustomizableClass;
use Sam\Cache\File\FilesystemCacheManager;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Translation\Cache\Exception\CacheException;
use Sam\Translation\Compiler\CompiledTranslation;

/**
 * Class TranslationCacheManager
 * @package Sam\Translation\Cache
 */
class TranslationCacheManager extends CustomizableClass
{
    use FilesystemCacheManagerAwareTrait;

    private const CACHE_NAMESPACE = 'admin_translation';
    private const CACHE_SOURCE_FILES_KEY = 'sourceFiles';
    private const CACHE_CREATION_TIME_KEY = 'creationTime';
    private const CACHE_MESSAGES_KEY = 'messages';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function initInstance()
    {
        $this->getFilesystemCacheManager()
            ->setNamespace(self::CACHE_NAMESPACE)
            ->setExtension(FilesystemCacheManager::EXT_PHP);
        return parent::initInstance();
    }

    /**
     * @param string $language
     * @param string $domain
     * @return TranslationCache
     * @throws CacheException
     */
    public function get(string $language, string $domain): TranslationCache
    {
        $key = $this->makeCacheKey($language, $domain);
        $cacheData = $this->getFilesystemCacheManager()->get($key);

        if ($cacheData === null) {
            throw CacheException::cacheMissing($language, $domain);
        }

        return new TranslationCache(
            $cacheData[self::CACHE_CREATION_TIME_KEY],
            $cacheData[self::CACHE_SOURCE_FILES_KEY],
            $cacheData[self::CACHE_MESSAGES_KEY]
        );
    }

    /**
     * @param CompiledTranslation $translation
     */
    public function set(CompiledTranslation $translation): void
    {
        $key = $this->makeCacheKey($translation->getLanguage(), $translation->getDomain());
        $content = $this->prepareCacheContent($translation);
        $this->getFilesystemCacheManager()->set($key, $content);
    }

    /**
     * @param CompiledTranslation $translation
     * @return array
     */
    private function prepareCacheContent(CompiledTranslation $translation): array
    {
        return [
            self::CACHE_SOURCE_FILES_KEY => $translation->getSourceFiles(),
            self::CACHE_CREATION_TIME_KEY => time(),
            self::CACHE_MESSAGES_KEY => $translation->getMessages()
        ];
    }

    /**
     * @param string $language
     * @param string $domain
     * @return string
     */
    private function makeCacheKey(string $language, string $domain): string
    {
        return sprintf('%s_%s', $domain, $language);
    }
}
