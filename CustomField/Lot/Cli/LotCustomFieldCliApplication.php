<?php
/**
 * SAM-6308: Refactor custom field management to separate modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 27, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Cli;

use Sam\Core\Service\CustomizableClass;
use Symfony\Component\Console\Application;

/**
 * Cli application for working with lot custom field settings
 * Entry point: bin/entity/lot_custom_field.php
 *
 * Class LotCustomFieldCliApplication
 * @package Sam\CustomField\Lot\Cli
 */
class LotCustomFieldCliApplication extends CustomizableClass
{
    private array $commands = [
        Command\GetLotCustomFieldCommand::class,
        Command\SetLotCustomFieldCommand::class,
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
