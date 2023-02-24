<?php
/**
 * SAM-9809:  Refactor Action Queue Module
 * https://bidpath.atlassian.net/browse/SAM-9809
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\ActionQueue\Cli;

use Sam\ActionQueue\Cli\Command\Run\RunCommand;
use Sam\Core\Service\CustomizableClass;
use Symfony\Component\Console\Application;

/**
 * Class ActionQueueCliApplication
 * @package Sam\ActionQueue\Cli\Command
 */
class ActionQueueCliApplication extends CustomizableClass
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
