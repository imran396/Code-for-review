<?php
/**
 * SAM-4500: Front-end breadcrumb
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class ResponsiveBreadcrumb
 * @package Sam\Core\Constants
 */
class ResponsiveBreadcrumb
{
    public const ATT_DATE = 'date';
    public const ATT_NAME = 'name';
    public const ATT_NUMBER = 'number';
    public const AUCTION_TITLE_TYPE = [self::ATT_DATE, self::ATT_NAME, self::ATT_NUMBER];

    public const LTT_NAME = 'name';
    public const LTT_NUMBER = 'number';
    public const LOT_TITLE_TYPE = [self::LTT_NAME, self::LTT_NUMBER];
}
