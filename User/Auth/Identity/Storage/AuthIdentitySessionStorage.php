<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/19/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth\Identity\Storage;

use Sam\Core\Service\CustomizableClass;
use Sam\User\Auth\Identity\Dto\AuthIdentityDto;

/**
 * Class AuthIdentitySessionStorage
 * @package
 */
class AuthIdentitySessionStorage extends CustomizableClass implements AuthIdentityStorageInterface
{
    /** @var string */
    public const SESSION_KEY = 'Identity';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function clearIdentity(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            unset($_SESSION[self::SESSION_KEY]);
        }
    }

    public function hasIdentityStored(): bool
    {
        return $this->readIdentity() !== null;
    }

    /**
     * @return AuthIdentityDto|null
     */
    public function readIdentity(): ?AuthIdentityDto
    {
        $dto = null;
        if (isset($_SESSION[self::SESSION_KEY])) {
            $dto = @unserialize($_SESSION[self::SESSION_KEY], [AuthIdentityDto::class]);
            if ($dto === false) {
                $dto = null;
            }
        }
        return $dto;
    }

    /**
     * @return AuthIdentityDto
     */
    public function readIdentityOrCreate(): AuthIdentityDto
    {
        $dto = $this->readIdentity();
        if (!$dto) {
            $dto = new AuthIdentityDto();
        }
        return $dto;
    }

    /**
     * @param AuthIdentityDto|null $dto
     */
    public function writeIdentity(?AuthIdentityDto $dto): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION[self::SESSION_KEY] = serialize($dto);
        }
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(): bool
    {
        return PHP_SAPI !== 'cli'
            && (session_status() === PHP_SESSION_ACTIVE
                || isset($_SESSION[self::SESSION_KEY]));
    }
}
