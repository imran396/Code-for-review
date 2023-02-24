<?php
/**
 * SAM-4475: Cookie helper https://bidpath.atlassian.net/browse/SAM-4475
 *
 * @author        Vahagn Hovsepyan
 * @version       SAM 2.0
 * @since         Oct 10, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 */

namespace Sam\Application\Cookie;

/**
 * Trait CookieHelperAwareTrait
 */
trait CookieHelperCreateTrait
{
    /**
     * @var CookieHelper|null
     */
    protected ?CookieHelper $cookieHelper = null;

    /**
     * @return CookieHelper
     */
    protected function createCookieHelper(): CookieHelper
    {
        return $this->cookieHelper ?: CookieHelper::new();
    }

    /**
     * @param CookieHelper $cookieHelper
     * @return static
     * @internal
     */
    public function setCookieHelper(CookieHelper $cookieHelper): static
    {
        $this->cookieHelper = $cookieHelper;
        return $this;
    }
}
