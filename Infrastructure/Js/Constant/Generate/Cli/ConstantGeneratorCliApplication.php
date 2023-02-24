<?php
/**
 * SAM-8867: Modularize JS constants generation script
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Js\Constant\Generate\Cli;

use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Js\Constant\Generate\Cli\Command\Generate\ConstantsCommand;
use Symfony\Component\Console\Application;

/**
 * Class ConstantGeneratorCliApplication
 * @package Sam\Infrastructure\Js\Constant\Generate\Cli
 */
class ConstantGeneratorCliApplication extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function run(): int
    {
        $application = new Application();
        $application->add(new ConstantsCommand());
        $application->setDefaultCommand(ConstantsCommand::NAME);
        return $application->run();
    }
}
