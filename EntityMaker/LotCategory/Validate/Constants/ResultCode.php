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

namespace Sam\EntityMaker\LotCategory\Validate\Constants;

use Sam\Core\Service\CustomizableClass;

/**
 * Class ResultCode
 * @package Sam\EntityMaker\LotCategory\Validate\Constants
 */
class ResultCode extends CustomizableClass
{
    public const BUY_NOW_AMOUNT_NOT_POSITIVE_NUMBER = 1;
    public const CONSIGNMENT_COMMISSION_NOT_POSITIVE_OR_ZERO_NUMBER = 2;
    public const CUSTOM_FIELD_DECIMAL_ERROR = 3;
    public const CUSTOM_FIELD_INTEGER_ERROR = 4;
    public const CUSTOM_FIELD_SELECT_INVALID_OPTION_ERROR = 5;
    public const INVALID_IMAGE_EXTENSION = 6;
    public const INVALID_NESTED_LEVEL = 7;
    public const INVALID_PARENT = 8;
    public const INVALID_POSITION = 9;
    public const NAME_LENGTH_LIMIT = 10;
    public const NAME_NOT_UNIQUE = 11;
    public const NAME_REQUIRED = 12;
    public const PARENT_CATEGORY_AMONG_DESCENDANTS = 13;
    public const STARTING_BID_NOT_POSITIVE_NUMBER = 14;
    public const QUANTITY_DIGITS_INVALID = 15;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
