<?php
/**
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

namespace Sam\Api\GraphQL\Auth;

use Sam\Api\GraphQL\Exception\AuthorizationError;
use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Auth\Identity\Dto\AuthIdentityDto;
use Sam\User\Auth\Identity\Jwt\Parse\AuthIdentityJwtParserCreateTrait;
use Sam\User\Auth\Identity\Jwt\Validate\AuthIdentityJwtValidatorCreateTrait;

/**
 * Class JwtAuthorizer
 * @package Sam\Api\GraphQL\Auth
 */
class JwtAuthorizer extends CustomizableClass
{
    use AuthIdentityJwtParserCreateTrait;
    use AuthIdentityJwtValidatorCreateTrait;
    use CookieHelperCreateTrait;
    use ServerRequestReaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @throws AuthorizationError
     */
    public function authorize(): ?AuthIdentityDto
    {
        if (!$this->hasToken()) {
            return null;
        }
        $jwt = $this->readToken();
        if (!$jwt) {
            return null;
        }

        $validationResult = $this->createAuthIdentityJwtValidator()->validate($jwt);
        if ($validationResult->hasError()) {
            $error = $validationResult->errors()[0];
            throw new AuthorizationError($error->getMessage(), $error->getCode());
        }
        $dto = $this->createAuthIdentityJwtParser()->parse($jwt);
        return $dto;
    }

    public function hasToken(): bool
    {
        return $this->readToken() !== '';
    }

    protected function readToken(): string
    {
        $token = $this->readFromAuthorizationHeader() ?: $this->readFromCookie();
        return $token;
    }

    protected function readFromAuthorizationHeader(): string
    {
        $authHeader = $this->getServerRequestReader()->httpAuthorization();
        $token = str_starts_with($authHeader, 'Bearer')
            ? trim(substr($authHeader, strlen('Bearer')))
            : '';
        return $token;
    }

    protected function readFromCookie(): string
    {
        $token = $this->createCookieHelper()->getJwtAuthIdentityToken();
        return $token;
    }
}
