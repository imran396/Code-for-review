<?php
/**
 * Map fetched from DB values to respective placeholders in array,
 * produce some values on the base of fetched data.
 *
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build\Internal\Template\Internal\Placeholder;

use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Content\Build\Internal\Template\Internal\Config\PlaceholderConfig;
use Sam\Settlement\Check\Content\Common\Constants\PlaceholderConstants;

/**
 * Class PlaceholderRenderer
 * @package Sam\Settlement\Check
 */
class PlaceholderValueMapper extends CustomizableClass
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
     * Produce array where array of $placeholders is mapped to values provided by loaded data $row.
     * @param array $placeholders
     * @param array $row
     * @return array
     * #[Pure]
     */
    public function mapPlaceholdersToValues(array $placeholders, array $row): array
    {
        $placeholderToValueMap = [];
        foreach ($placeholders as $placeholder) {
            if ($placeholder === PlaceholderConstants::PL_USER_BILLING_COUNTRY) {
                $placeholderToValueMap[$placeholder] = AddressRenderer::new()->countryName((string)$row['ub_country']);
            } elseif ($placeholder === PlaceholderConstants::PL_USER_SHIPPING_COUNTRY) {
                $placeholderToValueMap[$placeholder] = AddressRenderer::new()->countryName((string)$row['us_country']);
            } elseif ($placeholder === PlaceholderConstants::PL_USER_BILLING_STATE) {
                $placeholderToValueMap[$placeholder] = AddressRenderer::new()->stateName((string)$row['ub_state'], (string)$row['ub_country']);
            } elseif ($placeholder === PlaceholderConstants::PL_USER_SHIPPING_STATE) {
                $placeholderToValueMap[$placeholder] = AddressRenderer::new()->stateName((string)$row['us_state'], (string)$row['us_country']);
            } else {
                $resultField = PlaceholderConfig::USER_PLACEHOLDERS_CONFIG[$placeholder]['select'][0];
                $placeholderToValueMap[$placeholder] = $row[$resultField];
            }
        }
        return $placeholderToValueMap;
    }
}
