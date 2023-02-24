<?php
/**
 * SAM-5181: Implement JWT (Json Web Tokens) for authorization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 27, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth\Identity\Storage;

use Sam\Core\Service\CustomizableClass;
use Sam\User\Auth\Identity\Dto\AuthIdentityDto;

/**
 * Class AuthIdentityStoragePool
 * @package Sam\User\Auth\Identity\Storage
 */
class AuthIdentityStoragePool extends CustomizableClass
{
    /**
     * @var AuthIdentityStorageInterface[]
     */
    protected array $pool = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        // Primary storage must be first in the list
        $this->addToPool(AuthIdentitySessionStorage::new());
        $this->addToPool(AuthIdentityJwtCookieStorage::new());
        return $this;
    }

    /**
     * @param AuthIdentityStorageInterface $storage
     */
    protected function addToPool(AuthIdentityStorageInterface $storage): void
    {
        if ($storage->isApplicable()) {
            $this->pool[] = $storage;
        }
    }

    /**
     * @inheritDoc
     */
    public function clearIdentity(): void
    {
        foreach ($this->pool as $storage) {
            $storage->clearIdentity();
        }
    }

    /**
     * @inheritDoc
     */
    public function hasIdentityStored(): bool
    {
        $storage = $this->primaryStorage();
        return $storage && $storage->hasIdentityStored();
    }

    /**
     * @inheritDoc
     */
    public function readIdentity(): ?AuthIdentityDto
    {
        $primaryStorage = $this->primaryStorage();
        if ($primaryStorage) {
            $identity = $primaryStorage->readIdentity();
            if ($identity !== null) {
                return $identity;
            }
        }
        return null;
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function writeIdentity(?AuthIdentityDto $dto): void
    {
        foreach ($this->pool as $storage) {
            $storage->writeIdentity($dto);
        }
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(): bool
    {
        return count($this->pool) > 0;
    }

    /**
     * @return AuthIdentityStorageInterface|null
     */
    public function primaryStorage(): ?AuthIdentityStorageInterface
    {
        return reset($this->pool);
    }
}
