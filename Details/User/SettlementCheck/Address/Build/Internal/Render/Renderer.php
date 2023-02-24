<?php
/**
 * SAM-10136: Implement conditional logic in print check template field Payee
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\User\SettlementCheck\Address\Build\Internal\Render;

use Sam\Core\Address\Render\AddressRenderer;

/**
 * Class Renderer
 * @package Sam\Details\User\SettlementCheck\Address\Internal\Render
 */
class Renderer extends \Sam\Details\Core\Render\Renderer
{
    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function renderCountry(array $row, string $key): string
    {
        $countryCode = $this->getSingleSelectedValue($row, $key);
        return AddressRenderer::new()->countryName($countryCode);
    }

    public function renderBillingState(array $row): string
    {
        return AddressRenderer::new()->stateName((string)$row['user_billing_state'], (string)$row['user_billing_country']);
    }

    public function renderShippingState(array $row): string
    {
        return AddressRenderer::new()->stateName((string)$row['user_shipping_state'], (string)$row['user_shipping_country']);
    }
}
