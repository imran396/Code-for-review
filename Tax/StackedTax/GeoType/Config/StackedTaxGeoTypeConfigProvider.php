<?php
/**
 * Configuration provider of Geo Type location properties defined in hard-coded configuration file.
 *
 * SAM-10822: Stacked Tax. Location authority chain configuration (Stage 2)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\GeoType\Config;

use Sam\Core\Service\CustomizableClass;
use Sam\Tax\StackedTax\GeoType\Config\StackedTaxGeoTypeDefaultConfig as Config;

class StackedTaxGeoTypeConfigProvider extends CustomizableClass
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
     * @param string|null $country null means default
     * @param int $geoType
     * @return bool
     */
    public function isRequired(?string $country, int $geoType): bool
    {
        $country = $this->toCountryKey($country);
        $config = Config::CONFIG;
        if (isset($config[$country])) {
            return $config[$country][$geoType]['required'] ?? false;
        }
        return $config[Config::DEFAULT][$geoType]['required'] ?? false;
    }

    /**
     * @param string|null $country null means default
     * @param int $geoType
     * @return bool
     */
    public function isAvailable(?string $country, int $geoType): bool
    {
        $country = $this->toCountryKey($country);
        $config = Config::CONFIG;
        if (isset($config[$country])) {
            return isset($config[$country][$geoType]);
        }
        return isset($config[Config::DEFAULT][$geoType]);
    }

    /**
     * @param string|null $country null means default
     * @param int $geoType
     * @return bool
     */
    public function inInvoice(?string $country, int $geoType): bool
    {
        $country = $this->toCountryKey($country);
        $config = Config::CONFIG;
        if (isset($config[$country])) {
            return $config[$country][$geoType]['in_invoice'] ?? false;
        }
        return $config[Config::DEFAULT][$geoType]['in_invoice'] ?? false;
    }

    /**
     * @param string|null $country null means default
     * @param int $geoType
     * @return string
     */
    public function getAdminGeoTypeTranslationKey(?string $country, int $geoType): string
    {
        return $this->getTranslationKey($country, $geoType, 'admin_geo_type');
    }

    /**
     * @param string|null $country null means default
     * @param int $geoType
     * @return string
     */
    public function getAdminInvoiceTaxTranslationKey(?string $country, int $geoType): string
    {
        return $this->getTranslationKey($country, $geoType, 'admin_invoice_tax');
    }

    /**
     * @param string|null $country null means default
     * @param int $geoType
     * @return string
     */
    public function getAdminInvoiceTaxOnHpTranslationKey(?string $country, int $geoType): string
    {
        return $this->getTranslationKey($country, $geoType, 'admin_invoice_tax_hp');
    }

    /**
     * @param string|null $country null means default
     * @param int $geoType
     * @return string
     */
    public function getAdminInvoiceTaxOnBpTranslationKey(?string $country, int $geoType): string
    {
        return $this->getTranslationKey($country, $geoType, 'admin_invoice_tax_bp');
    }

    /**
     * @param string|null $country null means default
     * @param int $geoType
     * @return string
     */
    public function getPublicInvoiceTaxTranslationKey(?string $country, int $geoType): string
    {
        return $this->getTranslationKey($country, $geoType, 'public_invoice_tax');
    }

    /**
     * @param string|null $country null means default
     * @param int $geoType
     * @return string
     */
    public function getPublicInvoiceTaxOnHpTranslationKey(?string $country, int $geoType): string
    {
        return $this->getTranslationKey($country, $geoType, 'public_invoice_tax_hp');
    }

    /**
     * @param string|null $country null means default
     * @param int $geoType
     * @return string
     */
    public function getPublicInvoiceTaxOnBpTranslationKey(?string $country, int $geoType): string
    {
        return $this->getTranslationKey($country, $geoType, 'public_invoice_tax_bp');
    }

    /**
     * @param string|null $country null means default
     * @param int $geoType
     * @param string $option
     * @return string
     */
    protected function getTranslationKey(?string $country, int $geoType, string $option): string
    {
        $country = $this->toCountryKey($country);
        $translations = Config::TRANSLATIONS;
        return $translations[$country][$geoType][$option] ?? $translations[Config::DEFAULT][$geoType][$option];
    }

    public function getAdminTranslationDomain(): string
    {
        return Config::ADMIN_TRANSLATION_DOMAIN;
    }

    public function getPublicTranslationDomain(): string
    {
        return Config::PUBLIC_TRANSLATION_DOMAIN;
    }

    protected function toCountryKey(?string $country): string
    {
        return $country ?? Config::DEFAULT;
    }
}
