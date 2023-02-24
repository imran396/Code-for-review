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

namespace Sam\Sso\OpenId\Signup;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

class OpenIdUserProductionResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_USER_LOCKING = 1;
    public const ERR_USER_INPUT_DTO = 2;

    protected const ERROR_MESSAGES = [
        self::ERR_USER_LOCKING => 'User is locked',
        self::ERR_USER_INPUT_DTO => 'User input dto is invalid',
    ];

    public string $redirectUrl = '';

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(
            self::ERROR_MESSAGES,
            [],
            [],
            []
        );
        return $this;
    }

    // --- Mutation logic ---

    public function addError(int $code, ?string $message = null, array $payload = []): static
    {
        $this->getResultStatusCollector()->addError($code, $message, $payload);
        return $this;
    }

    public function addErrorWithAppendedMessage(int $code, string $append): static
    {
        $this->getResultStatusCollector()->addErrorWithAppendedMessage($code, $append);
        return $this;
    }

    public function setRedirectUrl(string $redirectUrl): static
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    // --- Query logic ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function isSuccess(): bool
    {
        return !$this->hasError();
    }

    public function errorMessage(string $glue = ';'): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    public function errorCode(): int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }

}
