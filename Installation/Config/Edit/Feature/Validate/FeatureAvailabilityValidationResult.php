<?php
/**
 * SAM-6522: Local configuration files management - Improve unit testing
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           08-29, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Feature\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class FeatureAvailabilityValidationResult
 * @package Sam\Installation\Config\Edit\Feature\Validate
 */
class FeatureAvailabilityValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_NO_PRIVILEGES = 1;
    public const ERR_NOT_VALID_IP = 2;

    public const WARN_INSTALLATION_MANAGE_DISABLED = 1;

    /**
     * All validations completed successfully
     */
    public const OK_SUCCESS_VALIDATION = 100;

    /** @var string[] */
    public const SUCCESS_MESSAGES = [
        self::OK_SUCCESS_VALIDATION => 'Successful validation',
    ];

    public const ERROR_MESSAGES = [
        self::ERR_NO_PRIVILEGES => 'Admin role absent to access Installation configuration management',
        self::ERR_NOT_VALID_IP => "You don't have access to this page for your IP address: %s",
    ];

    public const ERROR_MESSAGES_MULTITENANT = [
        self::ERR_NO_PRIVILEGES => 'Admin should have Superadmin privilege to access Installation configuration management',
        self::ERR_NOT_VALID_IP => self::ERROR_MESSAGES[self::ERR_NOT_VALID_IP]
    ];

    public const WARNING_MESSAGES = [
        self::WARN_INSTALLATION_MANAGE_DISABLED => 'Installation configuration management disabled, username or password not set',
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()
            ->construct(
                self::ERROR_MESSAGES,
                self::SUCCESS_MESSAGES,
                self::WARNING_MESSAGES
            );
        return $this;
    }

    /**
     * Constructor for multi-tenant installation
     * @return $this
     */
    public function constructForMultitenant(): static
    {
        $this->getResultStatusCollector()
            ->construct(
                self::ERROR_MESSAGES_MULTITENANT,
                self::SUCCESS_MESSAGES,
                self::WARNING_MESSAGES
            );
        return $this;
    }

    // --- Mutate state ---

    public function addError(int $code, string $requestRemoteIp = ''): static
    {
        $collector = $this->getResultStatusCollector();
        if ($code === self::ERR_NOT_VALID_IP) {
            $message = sprintf($collector->getErrorMessageByCodeAmongAll(self::ERR_NOT_VALID_IP), $requestRemoteIp);
            $collector->addError($code, $message);
        } else {
            $collector->addError($code);
        }
        return $this;
    }

    public function addWarning(int $code): static
    {
        $this->getResultStatusCollector()->addWarning($code);
        return $this;
    }

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
        return $this;
    }

    // --- Query state ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasWarning(): bool
    {
        return $this->getResultStatusCollector()->hasWarning();
    }

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    /**
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @return int[]
     */
    public function warningCodes(): array
    {
        return $this->getResultStatusCollector()->getWarningCodes();
    }

    /**
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @return string[]
     */
    public function warningMessages(): array
    {
        return $this->getResultStatusCollector()->getWarningMessages();
    }

    /**
     * @return string[]
     */
    public function successMessages(): array
    {
        return $this->getResultStatusCollector()->getSuccessMessages();
    }
}
