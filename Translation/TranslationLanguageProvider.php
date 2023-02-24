<?php
/**
 * SAM-5883: Develop the architecture for the admin side translation
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Translation;

use Sam\Core\Service\CustomizableClass;
use Locale;
use Sam\Core\Constants;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class TranslationLanguageProvider
 * @package Sam\Translation
 */
class TranslationLanguageProvider extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use SettingsManagerAwareTrait;
    use TranslationDirectoryProviderAwareTrait;

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
     * @param int $systemAccountId
     * @return string
     */
    public function detectLanguage(int $systemAccountId): string
    {
        return $this->getSettingsManager()->get(Constants\Setting::ADMIN_LANGUAGE, $systemAccountId)
            ?: $this->defaultLanguage();
    }

    /**
     * @return string
     */
    public function defaultLanguage(): string
    {
        return $this->cfg()->get('core->admin->translation->defaultLanguage');
    }

    /**
     * @return string
     */
    public function fallbackLanguage(): string
    {
        return $this->cfg()->get('core->admin->translation->fallbackLanguage') ?? '';
    }

    /**
     * @return array
     */
    public function detectAvailableLanguages(): array
    {
        $translationDirs = $this->getTranslationDirectoryProvider()->getExistingDirs();
        $availableLanguages = [];
        foreach ($translationDirs as $dir) {
            $langDirectories = glob("{$dir}/*", GLOB_ONLYDIR);
            $langDirectories = array_map('basename', $langDirectories);
            $availableLanguages[] = $langDirectories;
        }
        return array_unique(array_merge(...$availableLanguages));
    }

    /**
     * @param string $language
     * @return string
     */
    public function makeDisplayName(string $language): string
    {
        return Locale::getDisplayName($language);
    }
}
