<?php
/**
 * SAM-10417: Stacked Tax
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Render;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Location\Render\LocationRendererAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class StackedTaxRenderer
 * @package Sam\Tax\StackedTax\Render
 */
class StackedTaxRenderer extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use LocationRendererAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function makeGeoTypeName(?int $geoType): string
    {
        if (!in_array($geoType, Constants\StackedTax::TAX_AUTHORITY_GEO_TYPES, true)) {
            return ''; // throw exception?
        }

        return $this->getLocationRenderer()->makeGeoTypeName($geoType);
    }

    public function makeGeoTypeNameTranslated(?int $geoType, string $country = 'default', string $locale = null): string
    {
        if (!in_array($geoType, Constants\StackedTax::TAX_AUTHORITY_GEO_TYPES, true)) {
            return ''; // throw exception?
        }

        return $this->getLocationRenderer()->makeGeoTypeNameTranslated($country, $geoType, $locale);
    }

    public function makeTaxTypeName(?int $taxType): string
    {
        return Constants\StackedTax::TAX_TYPE_NAMES[$taxType] ?? '';
    }

    public function makeTaxTypeNameTranslated(?int $taxType, string $locale = null): string
    {
        $langTaxType = Constants\StackedTax::TAX_TYPE_NAME_TRANSLATIONS[$taxType] ?? '';
        return $this->getAdminTranslator()->trans($langTaxType, [], 'admin_stacked_tax', $locale);
    }
}
