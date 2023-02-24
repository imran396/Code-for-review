<?php
/**
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Cli;

use Sam\Sso\TokenLink\Cli\Command\Create\CreateCommand;
use Sam\Sso\TokenLink\Cli\Command\Create\CreateConstants;

/**
 * Class ToolConstants
 * @package
 */
class ApplicationConstants
{
    public const C_CREATE = 'create';

    /** @var string[] */
    public static array $commands = [
        self::C_CREATE,
    ];

    /** @var string[] */
    public static array $commandDescriptions = [
        self::C_CREATE => 'Create token link for user',
    ];

    /** @var string[] */
    public static array $commandConstantClasses = [
        self::C_CREATE => CreateConstants::class,
    ];

    /** @var string[] */
    public static array $commandHandlerClasses = [
        self::C_CREATE => CreateCommand::class,
    ];
}
