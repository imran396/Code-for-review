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

use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Auth\Identity\Dto\AuthIdentityDto;
use Sam\User\Auth\Identity\Jwt\Generate\AuthIdentityJwtGeneratorCreateTrait;
use Sam\User\Auth\Identity\Jwt\Parse\AuthIdentityJwtParserCreateTrait;
use Sam\User\Auth\Identity\Jwt\Validate\AuthIdentityJwtValidatorCreateTrait;

/**
 * Class AuthIdentityJwtCookieStorage
 * @package Sam\User\Auth\Identity\Storage
 */
class AuthIdentityJwtCookieStorage extends CustomizableClass implements AuthIdentityStorageInterface
{
    use AuthIdentityJwtGeneratorCreateTrait;
    use AuthIdentityJwtParserCreateTrait;
    use AuthIdentityJwtValidatorCreateTrait;
    use CookieHelperCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function clearIdentity(): void
    {
        $this->createCookieHelper()->deleteJwtAuthIdentityToken();
    }

    /**
     * @inheritDoc
     */
    public function hasIdentityStored(): bool
    {
        return $this->createCookieHelper()->hasJwtAuthIdentityToken();
    }

    /**
     * @inheritDoc
     */
    public function readIdentity(): ?AuthIdentityDto
    {
        if (!$this->hasIdentityStored()) {
            return null;
        }
        $jwt = $this->createCookieHelper()->getJwtAuthIdentityToken();
        if (!$jwt) {
            return null;
        }
        $validationResult = $this->createAuthIdentityJwtValidator()->validate($jwt);
        if ($validationResult->hasError()) {
            return null;
        }
        $dto = $this->createAuthIdentityJwtParser()->parse($jwt);
        return $dto;
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
        if (
            $dto !== null
            && !$this->isStoredIdentityEqualsTo($dto)
        ) {
            $jwt = $this->createAuthIdentityJwtGenerator()
                ->construct()
                ->generate($dto);
            $this->createCookieHelper()->setJwtAuthIdentityToken($jwt);
        }
    }

    /**
     * @param AuthIdentityDto|null $dto
     */
    public function refreshIdentityToken(?AuthIdentityDto $dto): void
    {
        if ($dto !== null) {
            $jwt = $this->createAuthIdentityJwtGenerator()
                ->construct()
                ->generate($dto);
            log_trace('JWT refreshed ' . composeLogData(['token' => $jwt]));
            $this->createCookieHelper()->setJwtAuthIdentityToken($jwt);
        }
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(): bool
    {
        return PHP_SAPI !== 'cli';
    }

    /**
     * @param AuthIdentityDto $dto
     * @return bool
     */
    protected function isStoredIdentityEqualsTo(AuthIdentityDto $dto): bool
    {
        $prevIdentityDto = $this->readIdentity();
        return $prevIdentityDto
            && $prevIdentityDto->userId === $dto->userId
            && $prevIdentityDto->passwordChangeRequired === $dto->passwordChangeRequired;
    }
}
