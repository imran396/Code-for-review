<?php
/**
 * SAM-9734: Fix email reminder behavior for the case when last run timestamps are missed
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Reminder\Cli;

use Sam\Core\Service\CustomizableClass;
use Sam\Reminder\Cli\Command\Run\RunCommand;
use Symfony\Component\Console\Application;

/**
 * Class ReminderCliApplication
 * @package Sam\Reminder\Cli
 */
class ReminderCliApplication extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int
     */
    public function run(): int
    {
        $application = new Application();
        $application->add(new RunCommand());
        $application->setDefaultCommand(RunCommand::NAME);
        return $application->run();
    }
}
