<?php
/**
 * SAM-8004: Refactor \Util_Storage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Storage;

/**
 * Trait CookieStorageCreateTrait
 * @package Sam\Infrastructure\Storage
 */
trait CookieStorageCreateTrait
{
    /**
     * @var CookieStorage|null
     */
    protected ?CookieStorage $cookieStorage = null;

    /**
     * @return CookieStorage
     */
    protected function createCookieStorage(): CookieStorage
    {
        return $this->cookieStorage ?: CookieStorage::new();
    }

    /**
     * @param CookieStorage $cookieStorage
     * @return static
     * @internal
     */
    public function setCookieStorage(CookieStorage $cookieStorage): static
    {
        $this->cookieStorage = $cookieStorage;
        return $this;
    }
}
