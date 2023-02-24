<?php
/**
 * SAM-5883: Develop the architecture for the admin side translation
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Translation\Compiler;

use Sam\Core\Service\CustomizableClass;
use Sam\Translation\Compiler\FileLoader\TranslationFileLoaderAwareTrait;
use Sam\Translation\TranslationDirectoryProviderAwareTrait;

/**
 * Class TranslationCompiler
 * @package Sam\Translation
 */
class TranslationCompiler extends CustomizableClass
{
    use TranslationDirectoryProviderAwareTrait;
    use TranslationFileLoaderAwareTrait;

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
     * @param string $domain
     * @return CompiledTranslation|null
     */
    public function compile(string $language, string $domain): ?CompiledTranslation
    {
        if (!$this->isLanguageExist($language)) {
            throw new \RuntimeException(sprintf('Language "%s" does not exist', $language));
        }
        /** @var CompiledTranslation|null $compiled */
        $compiled = null;
        $translationSourceDirs = $this->getTranslationDirectoryProvider()->getExistingDirs($language);
        foreach ($translationSourceDirs as $dir) {
            $translationFile = $this->getTranslationFileLoader()->load($dir, $language, $domain);
            if ($translationFile === null) {
                continue;
            }
            if (!$compiled) {
                $compiled = CompiledTranslation::createFromTranslationFile($translationFile);
            } else {
                $compiled = $compiled->translationFileApplied($translationFile);
            }
        }
        return $compiled;
    }

    /**
     * @param string $language
     * @return bool
     */
    private function isLanguageExist(string $language): bool
    {
        return count($this->getTranslationDirectoryProvider()->getExistingDirs($language)) > 0;
    }
}
