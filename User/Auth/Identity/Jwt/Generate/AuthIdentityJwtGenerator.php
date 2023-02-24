<?php
/**
 * SAM-5181: Implement JWT (Json Web Tokens) for authorization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 26, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth\Identity\Jwt\Generate;

use DateInterval;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\User\Auth\Identity\Dto\AuthIdentityDto;
use Sam\User\Auth\Identity\Jwt\Internal\Config\JwtConfigFactoryCreateTrait;

/**
 * Class AuthIdentityJwtGenerator
 * @package Sam\User\Auth\Identity\Jwt\Generate
 * @internal
 */
class AuthIdentityJwtGenerator extends CustomizableClass
{
    use CurrentDateTrait;
    use JwtConfigFactoryCreateTrait;
    use OptionalsTrait;

    public const OP_TOKEN_EXPIRES = 'expires';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals = [
     *     self::OP_TOKEN_EXPIRES => int
     * ]
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    public function generate(AuthIdentityDto $identityDto): string
    {
        $config = $this->createJwtConfigFactory()->create();
        $issuedAt = $this->getCurrentDateUtcImmutable();
        $token = $config->builder()
            ->issuedAt($issuedAt)
            ->expiresAt($issuedAt->add($this->makeExpiresDateInterval()))
            ->canOnlyBeUsedAfter($issuedAt)
            ->withClaim(Constants\Jwt::CLAIM_UID, $identityDto->userId)
            ->withClaim(Constants\Jwt::CLAIM_PASSWORD_CHANGE_REQUIRED, $identityDto->passwordChangeRequired)
            ->getToken($config->signer(), $config->signingKey());
        return $token->toString();
    }

    protected function makeExpiresDateInterval(): DateInterval
    {
        $expiresSeconds = $this->fetchOptional(self::OP_TOKEN_EXPIRES);
        return new DateInterval("PT{$expiresSeconds}S");
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_TOKEN_EXPIRES] = $optionals[self::OP_TOKEN_EXPIRES]
            ?? static function () {
                return ConfigRepository::getInstance()->get('core->jwt->authToken->expires');
            };
        $this->setOptionals($optionals);
    }
}
