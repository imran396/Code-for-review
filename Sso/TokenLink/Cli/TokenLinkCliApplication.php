<?php
/**
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Cli;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Infrastructure\OutputBuffer\OutputBufferCreateTrait;
use Sam\Sso\TokenLink\Validate\TokenLinkFeatureAvailabilityValidatorAwareTrait;
use Symfony\Component\Console\Application;

/**
 * Class TokenLinkCliTool
 * @package
 */
class TokenLinkCliApplication extends CustomizableClass
{
    use OutputBufferCreateTrait;
    use TokenLinkFeatureAvailabilityValidatorAwareTrait;

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
     * @throws \Exception
     */
    public function run(): int
    {
        $featureAvailabilityValidator = $this->getTokenLinkFeatureAvailabilityValidator();
        $isAvailable = $featureAvailabilityValidator->isAvailable();
        if (!$isAvailable) {
            $this->renderFeatureUnavailable();
            $this->output('Fix configuration errors before continue');
            return Constants\Cli::EXIT_GENERAL_ERROR;
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
        foreach ($this->getTokenLinkFeatureAvailabilityValidator()->errorMessages() as $errorMessage) {
            $messages[] = 'Error: ' . $errorMessage;
        }
        $this->output('SSO Token Link feature is not available. Reason:');
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
