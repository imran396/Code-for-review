<?php
/**
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/14/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Cli\Command\Base;

use Sam\Sso\TokenLink\Cli\ApplicationConstants;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class CommandBase
 * @package
 */
class CommandBase extends Command
{
    protected function configure(): void
    {
        $commandName = static::$defaultName;
        $this->setDescription(ApplicationConstants::$commandDescriptions[$commandName]);
        $constantClasses = ApplicationConstants::$commandConstantClasses;
        if (isset($constantClasses[$commandName])) {
            $constantClass = $constantClasses[$commandName];
            $definitions = [];
            if (isset($constantClass::$optionDefinitions)) {
                $optionDefinitions = $constantClass::$optionDefinitions;
                foreach ($optionDefinitions ?: [] as $definition) {
                    $definitions[] = new InputOption(...$definition);
                }
            }
            if (isset($constantClass::$argumentDefinitions)) {
                $argumentDefinitions = $constantClass::$argumentDefinitions;
                foreach ($argumentDefinitions ?: [] as $definition) {
                    $definitions[] = new InputArgument(...$definition);
                }
            }
            $this->setDefinition(new InputDefinition($definitions));
        }
    }
}
