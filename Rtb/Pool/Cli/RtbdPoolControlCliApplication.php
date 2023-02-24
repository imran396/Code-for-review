<?php
/**
 * General controller for processing tool commands in CLI mode.
 * It detects required command and calls command.
 * It also checks, feature availability and renders help in case of problems with calling arguments.
 *
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/7/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Cli;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\OutputBuffer\OutputBufferCreateTrait;
use Sam\Rtb\Pool\Config\RtbdPoolConfigManagerAwareTrait;
use Sam\Rtb\Pool\Feature\RtbdPoolFeatureAvailabilityValidatorAwareTrait;
use Symfony\Component\Console\Application;

/**
 * Class RtbdPoolControlCliTool
 * @package
 */
class RtbdPoolControlCliApplication extends CustomizableClass
{
    use OutputBufferCreateTrait;
    use RtbdPoolConfigManagerAwareTrait;
    use RtbdPoolFeatureAvailabilityValidatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->createOutputBuffer()->completeEndFlush();
        return $this;
    }

    /**
     * @return int
     */
    public function run(): int
    {
        $isAvailable = $this->getRtbdPoolFeatureAvailabilityValidator()->isAvailable();
        if (!$isAvailable) {
            $this->renderFeatureUnavailable();
            if ($this->getRtbdPoolFeatureAvailabilityValidator()->hasError()) {
                $this->output('Fix configuration errors before continue');
                return Constants\Cli::EXIT_GENERAL_ERROR;
            }
        }

        $application = new Application();
        foreach (ApplicationConstants::$commandHandlerClasses as $commandClass) {
            $application->add(new $commandClass());
        }
        $result = $application->run();
        return $result;
    }

    /**
     * Show feature unavailable info
     */
    protected function renderFeatureUnavailable(): void
    {
        $messages = [];
        foreach ($this->getRtbdPoolFeatureAvailabilityValidator()->errorMessages() as $errorMessage) {
            $messages[] = 'Error: ' . $errorMessage;
        }
        $this->output('Rtbd pool feature is not available. Reason:');
        $this->output(implode(PHP_EOL, $messages));
    }

    /**
     * @param string $output
     */
    protected function output(string $output): void
    {
        echo $output . PHP_EOL;
    }
}
