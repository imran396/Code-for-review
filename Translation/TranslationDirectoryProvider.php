<?php
/**
 * SAM-5883: Develop the architecture for the admin side translation
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Translation;

use Sam\Core\Service\CustomizableClass;

/**
 * Class TranslationDirectoryProvider
 * @package Sam\Translation
 */
class TranslationDirectoryProvider extends CustomizableClass
{
    private const BASE_TRANSLATION_DIR = '%s/resources/translation/%s';
    private const VARIABLE_TRANSLATION_DIR = '%s/var/translation/%s';

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
     * @return array
     */
    public function getExistingDirs(string $language = ''): array
    {
        $dirs = [
            $this->getOriginTranslationDirPath($language),
            $this->getVariableTranslationDirPath($language),
            $this->getCustomTranslationDirPath($language)
        ];
        return array_filter(
            $dirs,
            static function (string $dirPath) {
                return is_dir($dirPath);
            }
        );
    }

    /**
     * @param string $language
     * @return string
     */
    public function getOriginTranslationDirPath(string $language = ''): string
    {
        $path = sprintf(self::BASE_TRANSLATION_DIR, path()->sysRoot(), $language);
        return rtrim($path, '/');
    }

    /**
     * @param string $language
     * @return string
     */
    public function getVariableTranslationDirPath(string $language = ''): string
    {
        $path = sprintf(self::VARIABLE_TRANSLATION_DIR, path()->sysRoot(), $language);
        return rtrim($path, '/');
    }

    /**
     * @param string $language
     * @return string
     */
    public function getCustomTranslationDirPath(string $language = ''): string
    {
        $path = sprintf(self::BASE_TRANSLATION_DIR, path()->sysRoot(true), $language);
        return rtrim($path, '/');
    }

}
