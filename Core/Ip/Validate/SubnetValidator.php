<?php
/**
 * SAM-4026: Refactor IP CIDR checker
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           09-05, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Ip\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

/**
 * Class SubnetValidator
 * @package Sam\Core\Ip
 */
class SubnetValidator extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVALID_MASK = 1;
    public const ERR_INVALID_IP = 2;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $subnet
     * @return bool
     */
    public function validate(string $subnet): bool
    {
        $this->initResultStatusCollector();
        $collector = $this->getResultStatusCollector();

        $parts = explode('/', $subnet);
        $ip = $parts[0] ?? '';
        $mask = $parts[1] ?? '';
        $netBitCount = str_contains($ip, '.') ? 32 : 128;

        $isValidMask = $this->validateMask($netBitCount, $mask);
        $isValidIp = $this->validateIp($ip);

        if ($isValidMask === false) {
            $this->processError(self::ERR_INVALID_MASK, [$mask, $subnet]);
        }
        if ($isValidIp === false) {
            $this->processError(self::ERR_INVALID_IP, [$subnet]);
        }

        $success = !$collector->hasError();
        return $success;
    }

    /**
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @return array
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @param int $netBitCount
     * @param string $mask
     * @return bool
     */
    protected function validateMask(int $netBitCount, string $mask): bool
    {
        $is = true;
        if (
            !is_numeric($mask)
            || (string)(int)$mask !== (string)$mask
            || (int)$mask < 0
            || (int)$mask > $netBitCount
        ) {
            $is = false;
        }
        return $is;
    }

    /**
     * Validate ip for v4 and v6
     * @param string $ip
     * @return bool
     */
    protected function validateIp(string $ip): bool
    {
        $version = str_contains($ip, '.') ? Assert\Ip::V4 : Assert\Ip::V6;
        $constraint = new Assert\Ip(['version' => $version]);
        $validationErrors = Validation::createValidator()->validate($ip, $constraint);
        return count($validationErrors) === 0;
    }

    /**
     * @param int $errorCode
     * @param array $data
     * @return static
     */
    protected function processError(int $errorCode, array $data): static
    {
        $collector = $this->getResultStatusCollector();
        $collector->addErrorWithInjectedInMessageArguments($errorCode, $data);
        $this->writeErrorToLog($errorCode, $collector->lastAddedErrorMessage(), $data);
        return $this;
    }

    /**
     * @param int $errorCode
     * @param string $message
     * @param array $data
     * @return static
     */
    protected function writeErrorToLog(int $errorCode, string $message = '', array $data = []): static
    {
        $error = '';
        if ($errorCode === self::ERR_INVALID_IP) {
            $subnet = $data[0];
            $error = 'Invalid subnet IP' . composeSuffix(['ip' => $subnet]);
        }
        if ($errorCode === self::ERR_INVALID_MASK) {
            $error = $message;
        }
        log_error($error);
        return $this;
    }

    protected function initResultStatusCollector(): void
    {
        $errorMessages = [
            self::ERR_INVALID_MASK => 'Invalid CIDR mask: %s in subnet: %s',
            self::ERR_INVALID_IP => 'Invalid subnet IP: %s',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
    }
}
