<?php
/**
 * SAM-6856: Soft-deleted Auction restore
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Entity\Restore\Cli;

use Exception;
use Sam\Core\Service\CustomizableClass;
use Sam\Entity\Restore\Cli\Command\RunCommand;
use Symfony\Component\Console\Application;

/**
 * Cli application for restoring soft-deleted entities with related data
 * Entry point: bin/entity/restore.php
 *
 * Class EntityRestoreCliApplication
 * @package Sam\Entity\Restore\Cli
 */
class EntityRestoreCliApplication extends CustomizableClass
{
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
     * @throws Exception
     */
    public function run(): int
    {
        $application = new Application();
        $application->add(new RunCommand());
        return $application->run();
    }
}
