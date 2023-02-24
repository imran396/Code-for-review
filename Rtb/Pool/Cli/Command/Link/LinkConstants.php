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

namespace Sam\Rtb\Pool\Cli\Command\Link;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class LinkConstants
 * @package Sam\Rtb\Pool\Cli
 */
class LinkConstants
{
    // Arguments
    public const A_RTBD = 'rtbd';

    // Options
    public const O_AUCTION = 'auction';
    public const O_AUCTION_SHORT = 'a';
    public const O_EDITOR = 'editor';
    public const O_EDITOR_SHORT = 'e';

    /** @var array */
    public static array $argumentDefinitions = [
        [self::A_RTBD, InputArgument::REQUIRED, 'define rtbd instance name'],
    ];

    /** @var array */
    public static array $optionDefinitions = [
        [self::O_AUCTION, self::O_AUCTION_SHORT, InputOption::VALUE_REQUIRED, 'define auction to link (comma separated ids)'],
        [self::O_EDITOR, self::O_EDITOR_SHORT, InputOption::VALUE_REQUIRED, 'define editor by username'],
    ];
}
