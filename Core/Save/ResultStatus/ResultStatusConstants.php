<?php
/**
 * SAM-5845: Adjust ResultStatusCollector
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Save\ResultStatus;

class ResultStatusConstants
{
    public const TYPE_ERROR = 1;
    public const TYPE_SUCCESS = 2;
    public const TYPE_WARNING = 3;
    public const TYPE_INFO = 4;

    /** @var int[] */
    public const TYPES = [self::TYPE_ERROR, self::TYPE_SUCCESS, self::TYPE_WARNING, self::TYPE_INFO];

    /** @var string[] */
    public const TYPE_NAMES = [
        self::TYPE_ERROR => 'error',
        self::TYPE_SUCCESS => 'success',
        self::TYPE_WARNING => 'warning',
        self::TYPE_INFO => 'info',
    ];
}
