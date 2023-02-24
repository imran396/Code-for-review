<?php
/**
 * SAM-11612: Tech support tool to easily and temporarily disable installation look and feel customizations
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 11, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\LookAndFeel\Customization\Switch\Internal\Cookie;

use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class CookieManager
 * @package Sam\Application\LookAndFeel\Customization\Switch\Internal\Cookie
 */
class CookieManager extends CustomizableClass
{
    use CookieHelperCreateTrait;

    protected const ENABLED = '1';
    protected const DISABLED = '0';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function enableLayoutCustomization(bool $enable): self
    {
        $isEnabled = $enable ? self::ENABLED : self::DISABLED;
        $this->createCookieHelper()->setCookie(Constants\CookieKey::COOKIE_LAYOUT_CUSTOMIZATION, $isEnabled);
        return $this;
    }

    public function isLayoutCustomizationEnabled(): bool
    {
        $cookieHelper = $this->createCookieHelper();
        $key = Constants\CookieKey::COOKIE_LAYOUT_CUSTOMIZATION;
        if (!$cookieHelper->has($key)) {
            return true;
        }
        return $cookieHelper->getString($key) === self::ENABLED;
    }

    public function deleteLayoutCustomization(): void
    {
        $this->createCookieHelper()->deleteCookie(Constants\CookieKey::COOKIE_LAYOUT_CUSTOMIZATION);
    }
}
