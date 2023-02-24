<?php
/**
 * SAM-9363: Write repository generator
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\Application;

use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Generate\EntityGenerator\DeleteRepository\Cli\Command\BuildDeleteRepositoryCommand;
use Sam\Infrastructure\Generate\EntityGenerator\EntityFactory\Cli\Command\BuildEntityFactoryCommand;
use Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Cli\Command\BuildReadRepositoryCommand;
use Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Cli\Command\BuildWriteRepositoryCommand;
use Symfony\Component\Console\Application;

/**
 * CLI application for generating read / write / delete repositories and entity factories
 * Entry point: bin/generate/entity-generator.php
 *
 * Class WriteRepositoryGenerateCliApplication
 * @package Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Cli
 */
class EntityGeneratorCliApplication extends CustomizableClass
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
     */
    public function run(): int
    {
        $application = new Application();
        $application->add(new BuildDeleteRepositoryCommand());
        $application->add(new BuildEntityFactoryCommand());
        $application->add(new BuildReadRepositoryCommand());
        $application->add(new BuildWriteRepositoryCommand());
        return $application->run();
    }
}
