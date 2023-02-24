<?php
/**
 * SAM-10700: Implement GraphQL authentication endpoint
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Mutation\Resolve\JwtType;

use GraphQL\Error\UserError;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Auth\Credentials\Validate\CredentialsCheckerCreateTrait;
use Sam\User\Auth\FailedLogin\Delay\FailedLoginDelayerCreateTrait;
use Sam\User\Auth\FailedLogin\FailedLoginLockoutManager;
use Sam\User\Auth\Identity\Dto\AuthIdentityDto;
use Sam\User\Auth\Identity\Jwt\Generate\AuthIdentityJwtGeneratorCreateTrait;
use Sam\User\Auth\IpBlockerAwareTrait;

/**
 * Class CreateResolver
 * @package Sam\Api\GraphQL\Type\Mutation\Resolve\JwtType
 */
class CreateResolver extends CustomizableClass
{
    use AuthIdentityJwtGeneratorCreateTrait;
    use CredentialsCheckerCreateTrait;
    use FailedLoginDelayerCreateTrait;
    use IpBlockerAwareTrait;
    use ServerRequestReaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function resolve($rootValue, array $args): array
    {
        $identity = $this->authenticate($args['username'], $args['password']);
        return [
            'accessToken' => $this->createAuthIdentityJwtGenerator()->construct()->generate($identity)
        ];
    }

    protected function authenticate(string $username, string $password): AuthIdentityDto
    {
        $credentialsChecker = $this->createCredentialsChecker()->construct($username, $password);

        if (!$credentialsChecker->verify()) {
            $this->createFailedLoginDelayer()->delay();
            $userId = $credentialsChecker->getUserId();
            if ($userId) {
                FailedLoginLockoutManager::new()
                    ->construct($userId)
                    ->lockout();
            }
            throw new UserError($credentialsChecker->errorMessageForUser());
        }

        $userId = $credentialsChecker->getUserId();
        $isBlockedByIp = $this->getIpBlocker()
            ->setIp($this->getServerRequestReader()->remoteAddr())
            ->setUserId($userId)
            ->isBlocked();

        if ($isBlockedByIp) {
            $this->getIpBlocker()->block();
            throw new UserError('IP address is blocked');
        }

        $identity = new AuthIdentityDto();
        $identity->userId = $userId;
        $identity->passwordChangeRequired = $credentialsChecker->needRenewPassword();
        return $identity;
    }
}
