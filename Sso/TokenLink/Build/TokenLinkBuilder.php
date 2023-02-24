<?php
/**
 * Class uses TokenLinkEncrypter to build token
 *
 * SAM-5397: Token Link
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Build;

use InvalidArgumentException;
use RuntimeException;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Sso\TokenLink\Load\TokenLinkDataLoaderCreateTrait;
use Sam\Sso\TokenLink\Validate\TokenLinkCheckerCreateTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;

/**
 * Class TokenLinkBuilder
 * @package
 */
class TokenLinkBuilder extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;
    use TokenLinkDataLoaderCreateTrait;
    use TokenLinkEncrypterCreateTrait;
    use TokenLinkSignatureProducerCreateTrait;
    use TokenLinkCheckerCreateTrait;
    use UserAwareTrait;

    public const ERR_SEPARATOR_IN_USERNAME = 1;
    public const ERR_SEPARATOR_IN_SECRET = 2;
    public const ERR_USER_NOT_FOUND = 3;

    protected ?string $secret = null;
    protected ?string $username = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * To initialize instance properties
     * @return static
     */
    public function initInstance(): static
    {
        $errorMessages = [
            self::ERR_SEPARATOR_IN_USERNAME => 'Username contains separator character',
            self::ERR_SEPARATOR_IN_SECRET => 'Secret contains separator character',
            self::ERR_USER_NOT_FOUND => 'User not found, when building SSO token link',
        ];
        $this->getResultStatusCollector()->initAllErrors($errorMessages);
        return $this;
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function buildToken(): string
    {
        $username = $this->getUsername();
        $secret = $this->getSecret();
        $time = time();
        $signature = $this->createTokenLinkSignatureProducer()->produceSignature($username, $time, $secret);
        $encryptedString = $this->createTokenLinkEncrypter()->encrypt($username, $time);
        $token = $encryptedString . Constants\TokenLink::TOKEN_SEPARATOR . $signature;
        return $token;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->createTokenLinkChecker()->checkUsername($this->getUsername())) {
            $this->getResultStatusCollector()->addError(self::ERR_SEPARATOR_IN_USERNAME);
        }
        if (!$this->createTokenLinkChecker()->checkSecret($this->getSecret())) {
            $this->getResultStatusCollector()->addError(self::ERR_SEPARATOR_IN_SECRET);
        }
        return !$this->getResultStatusCollector()->hasError();
    }

    /**
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @return string
     * @throws RuntimeException
     */
    public function getSecret(): string
    {
        if ($this->secret === null) {
            $this->secret = $this->createTokenLinkDataLoader()->loadSecret($this->getUsername());
        }
        return $this->secret;
    }

    /**
     * @param string $secret
     * @return static
     */
    public function setSecret(string $secret): static
    {
        $this->secret = trim($secret);
        return $this;
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function getUsername(): string
    {
        if ($this->username === null) {
            $user = $this->getUser();
            if (!$user) {
                log_error(self::ERR_USER_NOT_FOUND);
                throw new InvalidArgumentException((string)self::ERR_USER_NOT_FOUND);
            }
            $this->username = $user->Username;
        }
        return $this->username;
    }

    /**
     * @param string $username
     * @return static
     */
    public function setUsername(string $username): static
    {
        $this->username = trim($username);
        return $this;
    }
}
