<?php
/**
 * SAM-5708: Local configuration management by CLI script
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Cli;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Cli\Command\ArrayAddCommand;
use Sam\Installation\Config\Edit\Cli\Command\ArrayRemoveCommand;
use Sam\Installation\Config\Edit\Cli\Command\GetCommand;
use Sam\Installation\Config\Edit\Cli\Command\RemoveCommand;
use Sam\Installation\Config\Edit\Cli\Command\SetCommand;
use Symfony\Component\Console\Application;

/**
 * Main entry point of a config modification console application.
 * This class is the container for a collection of config modification commands.
 *
 * Class ConfigCliApplication
 * @package Sam\Installation\Config
 */
class ConfigCliApplication extends CustomizableClass
{
    /** @var array */
    private const COMMANDS = [
        GetCommand::class,
        RemoveCommand::class,
        SetCommand::class,
        ArrayAddCommand::class,
        ArrayRemoveCommand::class
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function run(): int
    {
        $application = new Application();
        foreach (self::COMMANDS as $command) {
            $application->add(new $command());
        }
        return $application->run();
    }
}
