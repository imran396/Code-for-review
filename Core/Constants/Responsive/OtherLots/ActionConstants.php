<?php
/**
 *
 * @see https://bidpath.atlassian.net/browse/SAM-3506
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 21, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Responsive\OtherLots;

/**
 * Class ActionConstants
 * Number representation of the available actions on the Other Lots Carousel
 */
class ActionConstants
{
    public const FIRST = 1;
    public const PREV = 2;
    public const NEXT = 3;
    public const LAST = 4;
    public const CURRENT = 5;
    /** @var int[] */
    public static array $all = [
        self::FIRST,
        self::PREV,
        self::NEXT,
        self::LAST,
        self::CURRENT,
    ];
}
