<?php
/**
 * SAM-8016: Add 'City' as an attribute of Location
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Address;

use CommerceGuys\Addressing\Address;
use CommerceGuys\Addressing\AddressFormat\AddressFormatRepository;
use CommerceGuys\Addressing\Country\CountryRepository;
use CommerceGuys\Addressing\Formatter\DefaultFormatter;
use CommerceGuys\Addressing\Formatter\FormatterInterface;
use CommerceGuys\Addressing\Subdivision\SubdivisionRepository;
use Exception;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AddressFormatter
 * @package Sam\Address
 */
class AddressFormatter extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return formatted address depending on the country.
     *
     * @param string $countryCode
     * @param string $state
     * @param string $city
     * @param string $zip
     * @param string $addressLine
     * @param bool $html
     * @return string
     */
    public function format(
        string $countryCode,
        string $state,
        string $city,
        string $zip,
        string $addressLine,
        bool $html = true
    ): string {
        $address = new Address();
        $address = $address
            ->withCountryCode($countryCode)
            ->withAdministrativeArea($state)
            ->withAddressLine1($addressLine)
            ->withLocality($city)
            ->withPostalCode($zip);
        try {
            $addressFormatted = $this->constructFormatter()->format($address, ['html' => $html]);
        } catch (Exception $e) {
            $addressFormatted = '';
            log_warning($e->getMessage());
        }
        return $addressFormatted;
    }

    /**
     * @return FormatterInterface
     */
    protected function constructFormatter(): FormatterInterface
    {
        $addressFormatRepository = new AddressFormatRepository();
        $countryRepository = new CountryRepository();
        $subdivisionRepository = new SubdivisionRepository();
        $formatter = new DefaultFormatter(
            $addressFormatRepository,
            $countryRepository,
            $subdivisionRepository,
            [
                'html_tag' => 'div',
                'html_attributes' => ['class' => 'address'],
            ]
        );
        return $formatter;
    }
}
