<?php
/**
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Account\Validate\Constants;

use Sam\Core\Service\CustomizableClass;

/**
 * Class ResultCode
 * @package Sam\EntityMaker\Account\Validate\Constants
 */
class ResultCode extends CustomizableClass
{
    public const COUNTRY_UNKNOWN = 1;
    public const EMAIL_INVALID = 2;
    public const SYNC_KEY_EXIST = 3;
    public const NAME_EXIST = 4;
    public const NAME_INVALID = 5;
    public const NAME_REQUIRED = 6;
    public const SITE_URL_INVALID = 7;
    public const STATE_UNKNOWN = 11;
    public const URL_DOMAIN_INVALID = 8;
    public const URL_DOMAIN_EXIST = 9;
    public const URL_DOMAIN_REQUIRED = 10;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
