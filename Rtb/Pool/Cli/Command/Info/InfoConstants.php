<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/7/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Cli\Command\Info;

/**
 * Class InfoConstants
 * @package Sam\Rtb\Pool\Cli
 */
class InfoConstants
{
    public const O_ALL = 'all';
    public const O_ALL_SHORT = 'a';

    /** @var array */
    public static array $optionDefinitions = [
        [self::O_ALL, self::O_ALL_SHORT, null, 'display all info'],
    ];
}
