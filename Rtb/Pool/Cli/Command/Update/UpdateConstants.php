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

namespace Sam\Rtb\Pool\Cli\Command\Update;

use Symfony\Component\Console\Input\InputOption;

/**
 * Class UpdateConstants
 * @package Sam\Rtb\Pool\Cli
 */
class UpdateConstants
{
    public const O_AUCTION = 'auction';
    public const O_AUCTION_STATUS = 'auction-status';
    public const O_ACCOUNT = 'account';
    public const O_ALL = 'all';
    public const O_EDITOR = 'editor';
    public const O_EDITOR_SHORT = 'e';
    public const O_LINKED = 'linked';

    /** @var array */
    public static array $optionDefinitions = [
        [self::O_ALL, null, null, 'update all open auctions for all active accounts'],
        [self::O_ACCOUNT, null, InputOption::VALUE_REQUIRED, 'filter by account ids (comma separated list)'],
        [self::O_AUCTION, null, InputOption::VALUE_REQUIRED, 'filter by auction ids'],
        [self::O_AUCTION_STATUS, null, InputOption::VALUE_REQUIRED, 'filter auction auction status ids'],
        [self::O_EDITOR, self::O_EDITOR_SHORT, InputOption::VALUE_REQUIRED, 'define editor by username'],
        [self::O_LINKED, null, null, 'update auctions, that are already linked to rtbd instance, but without active connections'],
    ];
}

