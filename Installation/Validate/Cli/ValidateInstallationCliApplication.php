<?php
/**
 * SAM-5306: Local installation correctness check
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 27, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Validate\Cli;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Validate\Cli\Command\All\AllCommand;
use Sam\Installation\Validate\Cli\Command\Config\ConfigCommand;
use Sam\Installation\Validate\Cli\Command\Db\DbCommand;
use Sam\Installation\Validate\Cli\Command\Fs\FsCommand;
use Sam\Installation\Validate\Cli\Command\Translation\TranslationCommand;
use Symfony\Component\Console\Application;

/**
 * This class contains console commands for local installation correctness checking
 *
 * Class ValidateInstallationCliApplication
 * @package Sam\Installation\Cli
 */
class ValidateInstallationCliApplication extends CustomizableClass
{
    /**
     * These commands are called, when this CLI entry-point is called with "all" command.
     * They are located according to priority of call order.
     * @var string[]
     */
    public const COMMANDS_RUN_ALL = [
        FsCommand::NAME,
        ConfigCommand::NAME,
        DbCommand::NAME,
        TranslationCommand::NAME,
    ];

    /** @var array */
    private const COMMANDS = [
        ConfigCommand::class,
        DbCommand::class,
        FsCommand::class,
        AllCommand::class,
        TranslationCommand::class,
    ];

    /**
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
