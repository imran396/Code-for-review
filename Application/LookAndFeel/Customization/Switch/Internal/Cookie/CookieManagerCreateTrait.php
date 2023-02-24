<?php
/**
 * SAM-11612: Tech support tool to easily and temporarily disable installation look and feel customizations
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\LookAndFeel\Customization\Switch\Internal\Cookie;

/**
 * Trait CookieManagerCreateTrait
 * @package Sam\Application\LookAndFeel\Customization\Switch\Internal\Cookie
 */
trait CookieManagerCreateTrait
{
    protected ?CookieManager $cookieManager = null;

    /**
     * @return CookieManager
     */
    protected function createCookieManager(): CookieManager
    {
        return $this->cookieManager ?: CookieManager::new();
    }

    /**
     * @param CookieManager $cookieManager
     * @return $this
     * @internal
     */
    public function setCookieManager(CookieManager $cookieManager): self
    {
        $this->cookieManager = $cookieManager;
        return $this;
    }
}
