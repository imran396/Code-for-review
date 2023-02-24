<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           31/05/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Auth;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class CredentialsChecker
 * Check in main class method $this->verify() input credentials (password $this->password and username $this->username)
 * and Request Remote IP with expected credentials from $this->expectedPassword,
 * $this->expectedUsername, $this->expectedRemoteIp and store errors in $this->errorMessages array
 * (ResultStatusCollector).
 *
 * @package Sam\Installation\Config
 */
class CredentialsChecker extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use ResultStatusCollectorAwareTrait;

    // ResultStatusCollector errors codes constants
    public const ERR_WRONG_USERNAME = 1;
    public const ERR_WRONG_PASSWORD = 2;
    public const ERR_EMPTY_USERNAME_OR_PASSWORD = 3;

    /**
     * External input username. Can be null if we are not able fetch it from request data (if we store them in invalid request field).
     * Proper request field is: @see Constants\Url::P_PARAM_LOGIN
     * @var string
     */
    protected string $username = '';

    /**
     * External input password. Can be null if we are not able fetch it from request data (if we store them in invalid request field).
     * Proper request field is: @see Constants\Url::P_PARAM_PASSWORD
     * @var string
     */
    protected string $password = '';

    /**
     * valid username for successful login as string
     * @var string|null
     */
    protected ?string $expectedUsername = null;

    /**
     * valid password for successful login as string
     * @var string|null
     */
    protected ?string $expectedPassword = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Compare input username, password with expected username, password and return true when all data
     * are as expected.
     * @return bool
     */
    public function verify(): bool
    {
        $this->initResultStatusCollector();
        $collector = $this->getResultStatusCollector();
        $username = !empty($this->username) ? trim($this->username) : '';
        $password = !empty($this->password) ? trim($this->password) : '';
        $expectedUsername = $this->getExpectedUsername();
        $expectedPassword = $this->getExpectedPassword();
        if (empty($username) || empty($password)) {
            $collector->addError(self::ERR_EMPTY_USERNAME_OR_PASSWORD);
            return false;
        }
        if ($username !== $expectedUsername) {
            $collector->addError(self::ERR_WRONG_USERNAME);
            return false;
        }
        if ($password !== $expectedPassword) {
            $collector->addError(self::ERR_WRONG_PASSWORD);
            return false;
        }
        return true;
    }

    /**
     * Getting expected username. By default return string from cfg()->core->install->username;
     * @return string
     */
    public function getExpectedUsername(): string
    {
        if ($this->expectedUsername === null) {
            $this->expectedUsername = (string)$this->cfg()->get('core->install->username');
        }
        return $this->expectedUsername;
    }

    /**
     * Setup Expected username value
     * @param string $username
     * @return static
     * @noinspection PhpUnused
     */
    public function setExpectedUsername(string $username): static
    {
        $this->expectedUsername = trim($username);
        return $this;
    }

    /**
     * Getting expected password. By default return string from cfg()->core->install->password;
     * @return string
     */
    public function getExpectedPassword(): string
    {
        if ($this->expectedPassword === null) {
            $this->expectedPassword = (string)$this->cfg()->get('core->install->password');
        }
        return $this->expectedPassword;
    }

    /**
     * Setup Expected password value
     * @param string $password
     * @return static
     * @noinspection PhpUnused
     */
    public function setExpectedPassword(string $password): static
    {
        $this->expectedPassword = trim($password);
        return $this;
    }

    /**
     * Getting username
     * @return string|null Can be null if we are not able fetch it from request data (if we store them in invalid request field).
     * Proper request field is: @see Constants\Url::P_PARAM_LOGIN
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Setup username
     * @param string $username
     * @return static
     */
    public function setUsername(string $username): static
    {
        $this->username = trim($username);
        return $this;
    }

    /**
     * Getting password
     * @return string|null Can be null if we are not able fetch it from request data (if we store them in invalid request field).
     * Proper request field is: @see Constants\Url::P_PARAM_PASSWORD
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Setup password
     * @param string $password
     * @return static
     */
    public function setPassword(string $password): static
    {
        $this->password = trim($password);
        return $this;
    }

    /**
     * Getting error messages from ResultStatusCollector, generated in $this->verify()
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * Get list of error codes
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * Initialize ResultStatusCollector
     */
    protected function initResultStatusCollector(): void
    {
        $errorMessages = [
            self::ERR_WRONG_USERNAME => 'Incorrect username',
            self::ERR_WRONG_PASSWORD => 'Incorrect password',
            self::ERR_EMPTY_USERNAME_OR_PASSWORD => 'Fill username and password',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
    }
}
