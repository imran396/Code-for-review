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

namespace Sam\Rtb\Pool\Cli;

use Sam\Rtb\Pool\Cli\Command\Info\InfoCommand;
use Sam\Rtb\Pool\Cli\Command\Info\InfoConstants;
use Sam\Rtb\Pool\Cli\Command\Link\LinkCommand;
use Sam\Rtb\Pool\Cli\Command\Link\LinkConstants;
use Sam\Rtb\Pool\Cli\Command\Run\RunCommand;
use Sam\Rtb\Pool\Cli\Command\Run\RunConstants;
use Sam\Rtb\Pool\Cli\Command\Update\UpdateCommand;
use Sam\Rtb\Pool\Cli\Command\Update\UpdateConstants;

/**
 * Class ToolConstants
 * @package Sam\Rtb\Pool\Cli
 */
class ApplicationConstants
{
    public const C_INFO = 'Info';
    public const C_LINK = 'link';
    public const C_RUN = 'run';
    public const C_UPDATE = 'update';

    /** @var string[] */
    public static array $commands = [
        self::C_INFO,
        self::C_LINK,
        self::C_RUN,
        self::C_UPDATE,
    ];

    /** @var string[] */
    public static array $commandDescriptions = [
        self::C_INFO => 'Show pool configuration info',
        self::C_LINK => 'Link rtbd instance to auction',
        self::C_RUN => 'Run rtbd instance',
        self::C_UPDATE => 'Update auction binding with rtbd instances in pool',
    ];

    /** @var string[] */
    public static array $commandConstantClasses = [
        self::C_INFO => InfoConstants::class,
        self::C_LINK => LinkConstants::class,
        self::C_RUN => RunConstants::class,
        self::C_UPDATE => UpdateConstants::class,
    ];

    /** @var string[] */
    public static array $commandHandlerClasses = [
        self::C_INFO => InfoCommand::class,
        self::C_LINK => LinkCommand::class,
        self::C_RUN => RunCommand::class,
        self::C_UPDATE => UpdateCommand::class,
    ];
}
