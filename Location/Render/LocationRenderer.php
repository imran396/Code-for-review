<?php
/**
 * Helping methods for Location fields rendering
 *
 * SAM-4283: Location fields renderer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 05, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Location\Render;

use Sam\Tax\StackedTax\GeoType\Config\StackedTaxGeoTypeConfigProvider;
use Location;
use Sam\Application\Url\Build\Config\Image\LocationImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Html\HtmlRenderer;
use Sam\Date\CurrentDateTrait;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class LocationRenderer
 * @package Sam\Location\Render
 */
class LocationRenderer extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use CurrentDateTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Render "Geo Type" field name defined by system domain (Can be used in SOAP, CLI).
     * @param int|null $geoType
     * @return string
     */
    public function makeGeoTypeName(?int $geoType): string
    {
        return Constants\StackedTax::GEO_TYPE_NAMES[$geoType] ?? '';
    }

    /**
     * Render "Geo Type" field name with help of default admin-translations
     * @param string $country
     * @param int|null $geoType
     * @param string|null $locale
     * @return string
     */
    public function makeGeoTypeNameTranslated(string $country, ?int $geoType, string $locale = null): string
    {
        $translationKey = StackedTaxGeoTypeConfigProvider::new()->getAdminGeoTypeTranslationKey($country, $geoType);
        return $this->getAdminTranslator()->trans($translationKey, [], 'admin_stacked_tax', $locale);
    }

    /**
     * @param Location $location
     * @return string
     */
    public function renderLogoTag(Location $location): string
    {
        $output = $this->makeLogoTag($location->Id, $location->AccountId);
        return $output;
    }

    /**
     * Return <img> tag for location image
     * @param int $locationId
     * @param int $accountId
     * @return string
     */
    public function makeLogoTag(int $locationId, int $accountId): string
    {
        $output = '';
        if ($locationId) {
            $url = $this->makeLogoUrl($locationId, $accountId);
            $output = HtmlRenderer::new()->makeImgHtmlTag('img', ['src' => $url]);
        }
        return $output;
    }

    /**
     * Return url for location image
     * @param int $locationId
     * @param int $accountId
     * @return string
     */
    public function makeLogoUrl(int $locationId, int $accountId): string
    {
        $output = '';
        if ($locationId) {
            $output = $this->getUrlBuilder()->build(
                LocationImageUrlConfig::new()->construct($locationId, $accountId)
            );
        }
        return $output;
    }
}
