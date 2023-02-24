<?php
/**
 * SAM-5884: Admin side internationalization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 07, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Locale;

use Sam\Core\Service\CustomizableClass;
use Locale;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * This class contains methods for working with available locales
 *
 * Class LocaleProvider
 */
class LocaleProvider extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use ServerRequestReaderAwareTrait;

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
     * Returns available locales.
     * If the configuration value of available locales is empty - returns all system locales
     * @return array
     */
    public function loadAll(): array
    {
        if ($this->cfg()->get('core->app->locale->available') === null) {
            return \ResourceBundle::getLocales('');
        }
        return $this->cfg()->get('core->app->locale->available')->toArray();
    }

    /**
     * Returns list of locales sorted by relevance and name
     * @return array
     */
    public function loadAllOrdered(): array
    {
        $locales = $this->loadAll();
        $relevantLocale = $this->detectHttpAcceptLanguageLocale();
        [$lang] = explode('_', $relevantLocale);
        usort(
            $locales,
            static function ($leftLocale, $rightLocale) use ($relevantLocale, $lang) {
                if ($leftLocale === $relevantLocale) {
                    return -1;
                }
                if ($rightLocale === $relevantLocale) {
                    return 1;
                }
                if ($leftLocale === $lang) {
                    return -1;
                }
                if ($rightLocale === $lang) {
                    return 1;
                }
                if (str_starts_with($leftLocale, $lang)) {
                    return -1;
                }
                if (str_starts_with($rightLocale, $lang)) {
                    return 1;
                }

                return $leftLocale < $rightLocale ? -1 : 1;
            }
        );
        return $locales;
    }

    /**
     * Detects if string is valid locale
     * @param string $locale
     * @return bool
     */
    public function exist(string $locale): bool
    {
        return in_array($locale, $this->loadAll(), true);
    }

    /**
     * Detects locale display name
     * @param string $locale
     * @return string
     */
    public function detectDisplayName(string $locale): string
    {
        return Locale::getDisplayName($locale);
    }

    /**
     * Detect preferred locale from HTTP_ACCEPT_LANGUAGE header
     * @return string
     */
    private function detectHttpAcceptLanguageLocale(): string
    {
        $header = $this->getServerRequestReader()->readServerValueByKey('HTTP_ACCEPT_LANGUAGE');
        return Locale::acceptFromHttp($header) ?: '';
    }
}
