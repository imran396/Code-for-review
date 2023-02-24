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

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuthIdentityJwtValidationResult
 * @package Sam\User\Auth\Identity\Jwt\Validate
 */
class AuthIdentityJwtValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const INVALID_TOKEN_STRUCTURE = 1;
    public const ERR_INVALID_SIGNATURE = 2;
    public const ERR_TOKEN_EXPIRED = 3;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::INVALID_TOKEN_STRUCTURE => 'Token structure is invalid',
        self::ERR_INVALID_SIGNATURE => 'JWT signature mismatch',
        self::ERR_TOKEN_EXPIRED => 'The JWT is expired',
    ];

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
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    /**
     * @return ResultStatus[]
     */
    public function errors(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }
}
