<?php
/**
 * SAM-5181: Implement JWT (Json Web Tokens) for authorization
 * SAM-10709: Implement the Bearer authorization method for GraphQL endpoint
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth\Identity\Jwt\Validate;

use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Log\Support\Internal\Content\ContentBuilder;
use Sam\Log\Support\SupportLoggerAwareTrait;
use Sam\User\Auth\Identity\Jwt\Internal\Config\JwtConfigFactoryCreateTrait;
use Sam\User\Auth\Identity\Jwt\Validate\AuthIdentityJwtValidationResult as Result;

/**
 * Class AuthIdentityJwtValidator
 * @package Sam\User\Auth\Identity\Jwt\Validate
 */
class AuthIdentityJwtValidator extends CustomizableClass
{
    use CurrentDateTrait;
    use JwtConfigFactoryCreateTrait;
    use SupportLoggerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(string $tokenString): Result
    {
        $result = Result::new()->construct();
        $config = $this->createJwtConfigFactory()->create();
        try {
            /** @var JWT\UnencryptedToken $token */
            $token = $config->parser()->parse($tokenString);
        } catch (JWT\Exception $exception) {
            $this->log($exception->getMessage(), $tokenString);
            return $result->addError(Result::INVALID_TOKEN_STRUCTURE);
        }

        try {
            $signedWithConstraint = new JWT\Validation\Constraint\SignedWith($config->signer(), $config->verificationKey());
            $signedWithConstraint->assert($token);
        } catch (JWT\Validation\ConstraintViolation $violation) {
            $this->log($violation->getMessage(), $tokenString, $token->claims()->all());
            return $result->addError(Result::ERR_INVALID_SIGNATURE);
        }

        try {
            $signedWithConstraint = new JWT\Validation\Constraint\StrictValidAt(new FrozenClock($this->getCurrentDateUtcImmutable()));
            $signedWithConstraint->assert($token);
        } catch (JWT\Validation\ConstraintViolation $violation) {
            $this->log($violation->getMessage(), $tokenString, $token->claims()->all());
            return $result->addError(Result::ERR_TOKEN_EXPIRED);
        }

        $this->log('Token is valid', $tokenString, $token->claims()->all());
        return $result;
    }

    protected function log(string $message, string $token, array $claims = []): void
    {
        $this->getSupportLogger()->trace(
            message: static fn() => $message . " " . composeLogData(
                    [
                        'Token' => $token,
                        'Claims' => $claims,
                    ],

                ),
            deep: 2,
            optionals: [ContentBuilder::OP_EDITOR_USER_INFO => 0]
        );
    }
}
