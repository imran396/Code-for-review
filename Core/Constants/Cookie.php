<?php
/**
 * SAM-5608: Abort PHP NULL Session
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/22/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class Cookie
 * @package
 */
class Cookie
{
    // https://www.php.net/manual/ru/session.configuration.php#ini.session.cookie-samesite
    public const SS_NONE = 'None';
    public const SS_LAX = 'Lax';
    public const SS_STRICT = 'Strict';
    /** @var string[] */
    public static array $sameSiteValues = [self::SS_NONE, self::SS_LAX, self::SS_STRICT];
}
