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

namespace Sam\Translation;

use Sam\Core\Service\CustomizableClass;
use Sam\Translation\Cache\Exception\CacheException;
use Sam\Translation\Cache\TranslationCacheManager;
use Sam\Translation\Compiler\TranslationCompiler;
use Sam\Translation\Exception\CouldNotFindTranslation;

/**
 * Class TranslationLoader
 * @package Sam\Translation
 */
class TranslationLoader extends CustomizableClass
{
    use TranslationLanguageProviderCreateTrait;

    private ?TranslationCacheManager $cacheManager = null;
    private ?TranslationCompiler $compiler = null;

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
     * @param string $language
     * @param array $domains
     * @return array
     */
    public function getTranslations(string $language, array $domains): array
    {
        $translations = [];
        foreach ($domains as $domain) {
            $translation = $this->loadTranslation($language, $domain);
            if ($translation) {
                $translations[$domain] = $translation;
            }
        }

        if (!$translations) {
            $fallbackLanguage = $this->createTranslationLanguageProvider()->fallbackLanguage();
            if (
                !$fallbackLanguage
                || $fallbackLanguage === $language
            ) {
                CouldNotFindTranslation::forDomain($language, $domains);
            }
            log_warning(sprintf('Fallback translation used for domains "%s" in language "%s"', implode(', ', $domains), $language));
            $translations = $this->getTranslations($fallbackLanguage, $domains);
        }

        return $translations;
    }

    /**
     * @param string $language
     * @param string $domain
     * @return array
     * @throws CouldNotFindTranslation
     */
    public function loadTranslation(string $language, string $domain): array
    {
        try {
            $translation = $this->getCacheManager()->get($language, $domain);
            if ($translation->isFresh()) {
                return $translation->getMessages();
            }
        } catch (CacheException $e) {
            log_trace($e->getMessage());
        }

        $compiledTranslation = $this->getCompiler()->compile($language, $domain);
        if (!$compiledTranslation) {
            return [];
        }
        $this->getCacheManager()->set($compiledTranslation);
        return $compiledTranslation->getMessages();
    }

    /**
     * @return TranslationCacheManager
     */
    private function getCacheManager(): TranslationCacheManager
    {
        if ($this->cacheManager === null) {
            $this->cacheManager = TranslationCacheManager::new();
        }
        return $this->cacheManager;
    }

    /**
     * @param TranslationCacheManager $cacheManager
     */
    public function setCacheManager(TranslationCacheManager $cacheManager): void
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @return TranslationCompiler
     */
    private function getCompiler(): TranslationCompiler
    {
        if ($this->compiler === null) {
            $this->compiler = TranslationCompiler::new();
        }
        return $this->compiler;
    }

    /**
     * @param TranslationCompiler $compiler
     */
    public function setCompiler(TranslationCompiler $compiler): void
    {
        $this->compiler = $compiler;
    }
}
