<?php
/**
 * Pure from side-effects and out-of-process dependencies address related checking service.
 *
 * SAM-6805: Country and State pure checker and renderer
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Address\Validate;

use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class AddressChecker
 * @package Sam\Core\Address
 */
class AddressChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if country is available in our business domain
     * @param string $countryCode
     * @return bool
     * #[Pure]
     */
    public function isAvailableCountry(string $countryCode): bool
    {
        return isset(Constants\Country::$names[$countryCode]);
    }

    /**
     * Check address country of entity
     * @param string $checking
     * @param string $expected
     * @return bool
     * #[Pure]
     */
    public function isCountry(string $checking, string $expected): bool
    {
        return $checking === $expected;
    }

    /**
     * Is address country located in USA
     * @param string $country
     * @return bool
     * #[Pure]
     */
    public function isUsa(string $country): bool
    {
        return $this->isCountry($country, Constants\Country::C_USA);
    }

    /**
     * Is address country located in Canada
     * @param string $country
     * @return bool
     * #[Pure]
     */
    public function isCanada(string $country): bool
    {
        return $this->isCountry($country, Constants\Country::C_CANADA);
    }

    /**
     * Is address country located in Usa or Canada
     * @param string $country
     * @return bool
     * #[Pure]
     */
    public function isUsaOrCanada(string $country): bool
    {
        return $this->isUsa($country) || $this->isCanada($country);
    }

    /**
     * Is address country located in Mexico
     * @param string $country
     * @return bool
     * #[Pure]
     */
    public function isMexico(string $country): bool
    {
        return $this->isCountry($country, Constants\Country::C_MEXICO);
    }

    /**
     * Check, if country has states in our business domain (USA, Canada, Mexico)
     * @param string $country
     * @return bool
     * #[Pure]
     */
    public function isCountryWithStates(string $country): bool
    {
        return $this->isUsa($country)
            || $this->isCanada($country)
            || $this->isMexico($country);
    }

    /**
     * Check if state is located in USA
     * @param string $state
     * @return bool
     * #[Pure]
     */
    public function isUsaState(string $state): bool
    {
        return isset(AddressRenderer::new()->allUsaStates()[$state]);
    }

    /**
     * Check if state is located in Canada
     * @param string $state
     * @return bool
     * #[Pure]
     */
    public function isCanadaState(string $state): bool
    {
        return isset(AddressRenderer::new()->allCanadaStates()[$state]);
    }

    /**
     * Check if state is located in Mexico
     * @param string $state
     * @return bool
     * #[Pure]
     */
    public function isMexicoState(string $state): bool
    {
        return isset(AddressRenderer::new()->allMexicoStates()[$state]);
    }

    /**
     * Check, if State is marked as 'Non-US'
     * @param string $state
     * @return bool
     * #[Pure]
     */
    public function isNonUsaState(string $state): bool
    {
        return $state === 'Non-US';
    }

    /**
     * Returns whether or not postal code is valid for country
     * Only US 5-digits ZIP supported
     *
     * @param string $postalCode
     * @param bool $ignoreSpaces
     * @return bool
     * #[Pure]
     */
    public function isPostalCode(string $postalCode, bool $ignoreSpaces = false): bool
    {
        $formats = [
            Constants\Country::C_USA => ['#####', '#####-####'],            # USA
            Constants\Country::C_CANADA => ['@#@ #@#'],                   # CANADA
            'GB' => ['@@## #@@', '@#@ #@@', '@@# #@@', '@@#@ #@@', '@## #@@', '@# #@@'], # UK
            'DE' => ['#####'],                     # GERMANY
            'FR' => ['#####'],                     # FRANCE
            'IT' => ['#####'],                     # ITALY
            'AU' => ['####'],                      # AUSTRALIA
            'NL' => ['#### @@'],                   # NETHERLANDS
            'ES' => ['#####'],                     # SPAIN
            'DK' => ['####'],                      # DENMARK
            'SE' => ['### ##'],                    # SWEDEN
            'BE' => ['####'],                      # BELGIUM
        ];

        foreach ($formats as $countryFormats) {
            foreach ($countryFormats as $format) {
                if (preg_match($this->getFormatPattern($format, $ignoreSpaces), $postalCode)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param string $format
     * @param bool $ignoreSpaces
     * @return string
     * #[Pure]
     */
    protected function getFormatPattern(string $format, bool $ignoreSpaces = false): string
    {
        $pattern = str_replace(['#', '@', '*'], ['\d', '[a-zA-Z]', '[a-zA-Z0-9]'], $format);
        if ($ignoreSpaces) {
            $pattern = str_replace(' ', ' ?', $pattern);
        }
        return '/^' . $pattern . '$/';
    }
}
