<?php
/**
 * SAM-5843: System Parameters management by CLI script
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Edit\Cli;

use RuntimeException;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\Edit\Cli\Command;
use Symfony\Component\Console\Application;

/**
 * Class SettingsCliApplication
 * @package Sam\Settings\Edit\Cli
 */
class SettingsCliApplication extends CustomizableClass
{
    private array $commands = [
        Command\GetSettingsOptionCommand::class,
        Command\SetSettingsOptionCommand::class,
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
        set_error_handler(
            static function (int $errno, string $errstr) {
                throw new RuntimeException($errstr, $errno);
            }
        );
        $application = new Application();
        foreach ($this->commands as $command) {
            $application->add(new $command());
        }

        return $application->run();
    }
}
