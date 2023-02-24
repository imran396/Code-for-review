<?php
/**
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/20/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Auth;

use InvalidArgumentException;
use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Storage\PhpSessionStorageCreateTrait;
use Sam\Sso\TokenLink\Build\TokenLinkDecrypterCreateTrait;
use Sam\Sso\TokenLink\Build\TokenLinkSignatureProducerCreateTrait;
use Sam\Sso\TokenLink\Cache\TokenLinkCacherFactoryCreateTrait;
use Sam\Sso\TokenLink\Config\TokenLinkConfiguratorAwareTrait;
use Sam\Sso\TokenLink\Load\TokenLinkDataLoaderCreateTrait;
use Sam\Sso\TokenLink\Validate\TokenLinkCheckerCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserLogin\UserLoginReadRepositoryCreateTrait;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;

/**
 * Class TokenLinkAuthenticator
 * @package
 */
class TokenLinkAuthenticator extends CustomizableClass
{
    use AuthIdentityManagerCreateTrait;
    use PhpSessionStorageCreateTrait;
    use TokenLinkAuthCallbackCreateTrait;
    use TokenLinkCacherFactoryCreateTrait;
    use TokenLinkCheckerCreateTrait;
    use TokenLinkConfiguratorAwareTrait;
    use TokenLinkDataLoaderCreateTrait;
    use TokenLinkDecrypterCreateTrait;
    use TokenLinkSignatureProducerCreateTrait;
    use UserLoginReadRepositoryCreateTrait;

    public const ERROR_IP_BLOCKED = 'IP blocked';
    public const ERROR_LINK_ALREADY_USED = 'Link has been used more than once';
    public const ERROR_SIGNATURE_NOT_MATCH = 'Signatures not matching';
    public const ERROR_SIGNATURE_WRONG = 'Signature is wrong';
    public const ERROR_TIMESTAMP_EXPIRED = 'Expired timestamp';
    public const ERROR_TIMESTAMP_MISSING = 'Missing timestamp';
    public const ERROR_TIMESTAMP_INVALID = 'Invalid timestamp';
    public const ERROR_USER_BLOCKED = 'User blocked';
    public const ERROR_USERNAME_MISSING = 'Missing username';

    protected string $remoteAddr = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $token
     * @throws RuntimeException
     */
    public function authenticate(string $token): void
    {
        [$encryptedString, $signature] = explode(Constants\TokenLink::TOKEN_SEPARATOR, $token);
        $this->checkLinkNotUsed($encryptedString, $signature);

        $data = $this->createTokenLinkDecrypter()->decrypt($encryptedString);
        $username = $data['username'];
        $timestamp = $data['timestamp'];

        $this->validateSignature($signature);
        $this->validateTimestamp($timestamp);
        $this->validateUsername($username);

        [$userId, $userFlag, $secret] = $this->createTokenLinkDataLoader()->loadUserData($username);
        $this->checkSignatureMatch($signature, $username, $timestamp, $secret);
        $this->checkUserBlocked($userId, $userFlag);

        // Login user
        $this->createAuthIdentityManager()->applyUser($userId);
        $this->saveLink($encryptedString, $signature);

        //Send auth data to callback endpoint
        $this->createTokenLinkAuthCallback()->call(
            $token,
            $this->createPhpSessionStorage()->getSessionId()
        );
    }

    /**
     * @return string
     */
    public function getRemoteAddr(): string
    {
        return $this->remoteAddr;
    }

    /**
     * @param string $remoteAddr
     * @return static
     */
    public function setRemoteAddr(string $remoteAddr): static
    {
        $this->remoteAddr = trim($remoteAddr);
        return $this;
    }

    /**
     * Check that link has not been used yet
     * @param string $encryptedString
     * @param string $signature
     * @throws RuntimeException
     */
    protected function checkLinkNotUsed(string $encryptedString, string $signature): void
    {
        $isUsed = $this->createTokenLinkChecker()->isAlreadyUsed($encryptedString, $signature);
        if ($isUsed) {
            log_error(self::ERROR_LINK_ALREADY_USED . ' for ' . $encryptedString . $this->getTokenLinkConfigurator()->getInternalSeparator() . $signature);
            throw new RuntimeException(self::ERROR_LINK_ALREADY_USED);
        }
    }

    /**
     * @param string $signature
     * @param string $username
     * @param int $timestamp
     * @param string $secret
     * @throws RuntimeException
     */
    protected function checkSignatureMatch(string $signature, string $username, int $timestamp, string $secret): void
    {
        $saltEncoded = explode($this->getTokenLinkConfigurator()->getInternalSeparator(), $signature)[0];
        $salt = $this->createTokenLinkDecrypter()->base64decode($saltEncoded);
        // IK, 2022-11: Shouldn't we handle $salt === '' case with self::ERROR_CANNOT_DECODE_SALT? (and adjust base64decode() to return '' instead of false)
        if ($signature !== $this->createTokenLinkSignatureProducer()->produceSignature($username, $timestamp, $secret, $salt)) {
            throw new RuntimeException(self::ERROR_SIGNATURE_NOT_MATCH);
        }
    }

    /**
     * @param int $userId
     * @param int $userFlag
     * @throws RuntimeException
     */
    protected function checkUserBlocked(int $userId, int $userFlag): void
    {
        // Check flag
        if ($userFlag === Constants\User::FLAG_BLOCK) {
            throw new RuntimeException(self::ERROR_USER_BLOCKED);
        }

        // Check IP
        $isBlocked = $this->createUserLoginReadRepository()
            ->filterUserId($userId)
            ->filterIpAddress($this->getRemoteAddr())
            ->filterBlocked(true)
            ->exist();
        if ($isBlocked) {
            throw new RuntimeException(self::ERROR_IP_BLOCKED);
        }
    }

    /**
     * @param string $encryptedString
     * @param string $signature
     * @throws InvalidArgumentException
     */
    protected function saveLink(string $encryptedString, string $signature): void
    {
        $cacher = $this->createTokenLinkCacherFactory()->create();
        $key = $cacher->makeKey($encryptedString, $signature);
        $cacher->set($key);
    }

    /**
     * @param string $signature
     * @throws RuntimeException
     */
    protected function validateSignature(string $signature): void
    {
        if (!str_contains($signature, $this->getTokenLinkConfigurator()->getInternalSeparator())) {
            throw new RuntimeException(self::ERROR_SIGNATURE_WRONG);
        }
    }

    /**
     * @param int $timestamp
     * @throws RuntimeException
     */
    protected function validateTimestamp(int $timestamp): void
    {
        if (!$timestamp) {
            throw new RuntimeException(self::ERROR_TIMESTAMP_MISSING);
        }
        if ($timestamp + $this->getTokenLinkConfigurator()->getExpiration() < time()) {
            throw new RuntimeException(self::ERROR_TIMESTAMP_EXPIRED);
        }
        if ($timestamp > time()) {
            throw new RuntimeException(self::ERROR_TIMESTAMP_INVALID);
        }
    }

    /**
     * @param string $username
     * @throws RuntimeException
     */
    protected function validateUsername(string $username): void
    {
        if (!$username) {
            throw new RuntimeException(self::ERROR_USERNAME_MISSING);
        }
    }
}
