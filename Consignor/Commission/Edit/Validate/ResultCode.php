<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Validate;

/**
 * Class ResultCode
 * @package Sam\Consignor\Commission\Edit
 */
class ResultCode
{
    public const ERR_CALCULATION_METHOD_INVALID = 4;
    public const ERR_CALCULATION_METHOD_REQUIRED = 3;
    public const ERR_FEE_REFERENCE_INVALID = 5;
    public const ERR_NAME_NOT_UNIQUE = 2;
    public const ERR_NAME_REQUIRED = 1;
    public const ERR_RANGE_AMOUNT_INVALID = 7;
    public const ERR_RANGE_AMOUNT_REQUIRED = 6;
    public const ERR_RANGE_EXIST = 8;
    public const ERR_RANGE_FIRST_AMOUNT_MUST_BE_ZERO = 17;
    public const ERR_RANGE_FIXED_INVALID = 9;
    public const ERR_RANGE_FIXED_REQUIRED = 15;
    public const ERR_RANGE_MODE_INVALID = 13;
    public const ERR_RANGE_MODE_REQUIRED = 12;
    public const ERR_RANGE_PERCENT_INVALID = 10;
    public const ERR_RANGE_PERCENT_REQUIRED = 16;
    public const ERR_RELATED_ENTITY_ID_REQUIRED = 14;
}
