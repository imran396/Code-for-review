<?php
/**
 * SAM-5843: System Parameters management by CLI script
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Dev\Cli;

use Sam\Core\Service\CustomizableClass;
use Sam\Settings\Dev\Cli\Command;
use Symfony\Component\Console\Application;

/**
 * Class SettingsCliApplication
 * @package Sam\Settings\Dev\Cli
 */
class SettingsCliApplication extends CustomizableClass
{
    private array $commands = [
        Command\BuildSettingConstantClassCommand::class,
        Command\GeneratePropertyAnnotationCommand::class
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
                throw new \RuntimeException($errstr, $errno);
            }
        );
        $application = new Application();
        foreach ($this->commands as $command) {
            $application->add(new $command());
        }

        return $application->run();
    }
}
