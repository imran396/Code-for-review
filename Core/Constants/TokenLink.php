<?php
/**
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class TokenLinkConstants
 * @package
 */
class TokenLink
{
    // Separates encrypted string and signature
    public const TOKEN_SEPARATOR = '.';
    public const INTERNAL_CHARSET = 'UTF-8';

    public const CACHE_NAMESPACE = 'sso';
    // Caching manager types
    public const CACHE_FILE = 1;
    // const CACHE_MEMORY = 2; // TB: If we ever load balance we may implement a memcached based storage for the used link
    /** @var int[] */
    public static array $cacheTypes = [self::CACHE_FILE];
    /** @var string[] */
    public static array $cacheTypeNames = [
        self::CACHE_FILE => 'file',
    ];
}
