<?php
/**
 * Pure from side-effects and out-of-process dependencies service for address parts rendering purposes
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

namespace Sam\Core\Address\Render;

use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AddressRenderer
 * @package Sam\Core\Address
 */
class AddressRenderer extends CustomizableClass
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
     * Return array of all countries: <abbreviation code> => <country name>
     * @return array
     * #[Pure]
     */
    public function allCountries(): array
    {
        return Constants\Country::$names;
    }

    /**
     * Return country name by country code
     * @param string $countryCode
     * @return string
     * #[Pure]
     */
    public function countryName(string $countryCode): string
    {
        return Constants\Country::$names[$countryCode] ?? '';
    }

    /**
     * Search state name for specified country, or return it as is.
     * @param string $stateCode
     * @param string $countryCode
     * @return string
     * #[Pure]
     */
    public function stateName(string $stateCode, string $countryCode): string
    {
        $addressChecker = AddressChecker::new();
        if ($addressChecker->isUsa($countryCode)) {
            return $this->usaStateName($stateCode);
        }
        if ($addressChecker->isCanada($countryCode)) {
            return $this->canadaStateName($stateCode);
        }
        if ($addressChecker->isMexico($countryCode)) {
            return $this->mexicoStateName($stateCode);
        }
        return $stateCode;
    }

    /**
     * Return array of all USA states: <abbreviation code> => <state name>
     * @return array
     * #[Pure]
     */
    public function allUsaStates(): array
    {
        return Constants\State::$names[Constants\Country::C_USA];
    }

    /**
     * Return array of all Canada states: <abbreviation code> => <state name>
     * @return array
     * #[Pure]
     */
    public function allCanadaStates(): array
    {
        return Constants\State::$names[Constants\Country::C_CANADA];
    }

    /**
     * Return array of all Mexico states: <abbreviation code> => <state name>
     * @return array
     * #[Pure]
     */
    public function allMexicoStates(): array
    {
        return Constants\State::$names[Constants\Country::C_MEXICO];
    }

    /**
     * Return array of states (code => name) for country with states in our business-domain (USA, Canada, Mexico)
     * @param string $country
     * @return array
     * #[Pure]
     */
    public function allStatesByCountry(string $country): array
    {
        $addressChecker = AddressChecker::new();
        if (!$addressChecker->isCountryWithStates($country)) {
            return [];
        }
        if ($addressChecker->isUsa($country)) {
            return $this->allUsaStates();
        }
        if ($addressChecker->isCanada($country)) {
            return $this->allCanadaStates();
        }
        if ($addressChecker->isMexico($country)) {
            return $this->allMexicoStates();
        }
        return [];
    }

    /**
     * Find state name among USA states, or result with empty string
     * @param string $stateCode
     * @return string
     * #[Pure]
     */
    public function usaStateName(string $stateCode): string
    {
        return $this->allUsaStates()[$stateCode] ?? '';
    }

    /**
     * Find state name among Canada states, or result with empty string
     * @param string $stateCode
     * @return string
     * #[Pure]
     */
    public function canadaStateName(string $stateCode): string
    {
        return $this->allCanadaStates()[$stateCode] ?? '';
    }

    /**
     * Find state name among Mexico states, or result with empty string
     * @param string $stateCode
     * @return string
     * #[Pure]
     */
    public function mexicoStateName(string $stateCode): string
    {
        return $this->allMexicoStates()[$stateCode] ?? '';
    }

    /**
     * Get country name or country code and return country code in upper case
     * @param string|null $country null leads to empty string
     * @return string
     * #[Pure]
     */
    public function normalizeCountry(?string $country): string
    {
        if ((string)$country === '') {
            return '';
        }

        $countries = $this->allCountries();
        $countryUpper = strtoupper($country);
        if (array_key_exists($countryUpper, $countries)) {
            // Country code found
            return $countryUpper;
        }

        // Name provided
        $countryCode = array_search(
            $countryUpper,
            array_map(static fn($c) => strtoupper($c), $countries),
            true
        );
        if ($countryCode) {
            // Country code found by definite country name
            return $countryCode;
        }

        return '';
    }

    /**
     * Convert state name to state abbreviations for US, CS, MX countries, otherwise return state name as is.
     * Produce value expected by DB model.
     * @param string|null $country null leads to empty string
     * @param string|null $state null leads to empty string
     * @return string
     * #[Pure]
     */
    public function normalizeState(?string $country, ?string $state): string
    {
        if (
            (string)$country === ''
            || (string)$state === ''
        ) {
            return '';
        }

        $country = $this->normalizeCountry($country);
        if (!AddressChecker::new()->isCountryWithStates($country)) {
            // Return input state for country, where we don't define state code to name map
            return $state;
        }

        $states = $this->allStatesByCountry($country);
        $stateUpper = strtoupper($state);
        if (array_key_exists($stateUpper, $states)) {
            // State code found
            return $stateUpper;
        }

        $stateCode = array_search(
            $stateUpper,
            array_map(static fn($s) => strtoupper($s), $states),
            true
        );
        if ($stateCode) {
            // State code is found by definite state name
            return $stateCode;
        }

        return '';
    }
}
