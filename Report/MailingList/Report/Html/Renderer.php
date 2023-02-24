<?php
/**
 *
 * SAM-4751: Refactor mailing list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-16
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\MailingList\Report\Html;

use InvalidArgumentException;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;

/**
 * Class Renderer
 * @package Sam\Report\MailingList\Report\Html
 */
class Renderer extends CustomizableClass
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
     * @param string $name
     * @param array|null $argument null throw an exception
     * @return string
     */
    public function render(string $name, ?array $argument): string
    {
        if ($argument === null) {
            throw new InvalidArgumentException("Unable to return value for column.");
        }
        $value = $argument[$name];
        switch ($name) {
            case "apply_tax" :
                return UserPureRenderer::new()->makeTaxApplication((int)$value);
            case "user_flag" :
                $flag = (int)$value;
                $userFlag = UserPureRenderer::new()->makeFlag($flag);
                return $userFlag;
            case "shipping_country" :
                return AddressRenderer::new()->countryName((string)$value);
            case "shipping_state" :
                $addressChecker = AddressChecker::new();
                $addressRenderer = AddressRenderer::new();
                $shippingState = '';
                $state = (string)$value;
                if ($addressChecker->isUsaState($state)) {
                    $shippingState = $addressRenderer->stateName($state, Constants\Country::C_USA);
                } elseif ($addressChecker->isCanadaState($state)) {
                    $shippingState = $addressRenderer->stateName($state, Constants\Country::C_CANADA);
                } elseif ($addressChecker->isMexicoState($state)) {
                    $shippingState = $addressRenderer->stateName($state, Constants\Country::C_MEXICO);
                }
                return $shippingState;
            case "phone_type":
                return UserPureRenderer::new()->makePhoneType((int)$value);
            case "username":
            case "shipping_company_name":
            case "shipping_first_name":
            case "shipping_last_name":
            case "first_name":
            case "last_name":
            case "shipping_address":
            case "shipping_address_ln2":
            case "shipping_address_ln3":
                return ee($value);
        }

        return $value ?? '';
    }

    /**
     * return user export column header based on filed index
     * @param string $key Name of field to return
     * @return string
     */
    public function renderColumnName(string $key): string
    {
        if (!str_contains($key, "_")) {
            $output = ucfirst($key);
        } else {
            $parts = explode("_", $key);
            $output = ucfirst($parts[0]) . " " . $parts[1];
            if (isset($parts[2])) {
                $output .= " " . $parts[2];
            }
        }
        return $output;
    }
}
