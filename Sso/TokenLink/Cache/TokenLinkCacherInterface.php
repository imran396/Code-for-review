<?php
/**
 * Common interface for token link cacher classes, like TokenLinkFileCacher
 *
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 8, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Cache;

/**
 * Class SingleUseFS
 * @package Sam\Sso\TokenLink
 */
interface TokenLinkCacherInterface
{
    /**
     * Remove the PHP session ID string associated with the encrypted string and signature
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool;

    /**
     * Check key exists
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $encryptedString
     * @param string $signature
     * @return string
     */
    public function makeKey(string $encryptedString, string $signature): string;

    /**
     * Save the PHP session ID string associated with the encrypted string and signature (first use)
     * @param string $key
     * @return bool
     */
    public function set(string $key): bool;

    /**
     * @param string $key
     * @return string
     */
    public function get(string $key): string;
}
