<?php
/**
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 7, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Location\Validate\Constants;

use Sam\Core\Service\CustomizableClass;

/**
 * Class ResultCode
 * @package Sam\EntityMaker\Location\Validate\Constants
 */
class ResultCode extends CustomizableClass
{
    public const COUNTRY_UNKNOWN = 1;
    public const NAME_EXIST = 2;
    public const NAME_LENGTH_LIMIT = 6;
    public const NAME_REQUIRED = 5;
    public const STATE_UNKNOWN = 3;
    public const SYNC_KEY_EXIST = 4;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
