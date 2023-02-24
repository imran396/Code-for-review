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

namespace Sam\User\Auth\Identity\Jwt\Parse;

use Lcobucci\JWT\UnencryptedToken;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Auth\Identity\Dto\AuthIdentityDto;
use Sam\User\Auth\Identity\Jwt\Internal\Config\JwtConfigFactoryCreateTrait;

/**
 * Class AuthIdentityJwtParser
 * @package Sam\User\Auth\Identity\Jwt\Parse
 */
class AuthIdentityJwtParser extends CustomizableClass
{
    use JwtConfigFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function parse(string $tokenString): AuthIdentityDto
    {
        $config = $this->createJwtConfigFactory()->create();
        $token = $config->parser()->parse($tokenString);

        assert($token instanceof UnencryptedToken);

        $authIdentityDto = new AuthIdentityDto();
        $authIdentityDto->userId = $token->claims()->get(Constants\Jwt::CLAIM_UID);
        $authIdentityDto->passwordChangeRequired = $token->claims()->get(Constants\Jwt::CLAIM_PASSWORD_CHANGE_REQUIRED);
        return $authIdentityDto;
    }
}
