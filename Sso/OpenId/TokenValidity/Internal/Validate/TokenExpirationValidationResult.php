<?php
/**
 * SAM-10724: Login through SSO
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Georgi Nikolov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\OpenId\TokenValidity\Internal\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

class TokenExpirationValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const INFO_REFRESH_TOKEN_ABSENT_IN_SESSION = 21;
    public const INFO_TOKEN_EXPIRED = 22;

    protected const INFO_MESSAGES = [
        self::INFO_REFRESH_TOKEN_ABSENT_IN_SESSION => 'Refresh token absent in session',
        self::INFO_TOKEN_EXPIRED => 'Token expired',
    ];

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(
            [],
            [],
            [],
            self::INFO_MESSAGES
        );
        return $this;
    }

    // --- Mutation logic ---

    public function addInfo(int $code, ?string $message = null, array $payload = []): static
    {
        $this->getResultStatusCollector()->addInfo($code, $message, $payload);
        return $this;
    }

    // --- Query ----

    public function isTokenExpired(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteInfo(self::INFO_TOKEN_EXPIRED);
    }

    public function isRefreshTokenAbsentInSession(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteInfo(self::INFO_REFRESH_TOKEN_ABSENT_IN_SESSION);
    }

}
