<?php
/**
 * SAM-9538: Decouple ACL checking logic from front controller
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Acl\Protect\Internal\Access;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AclControllerCheckingResult
 * @package Sam\Application\Acl\Protect\Internal\Access
 */
class AclControllerCheckingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_ACCESS_DENIED_TO_UNKNOWN_RESOURCE_FOR_ANONYMOUS_AND_REDIRECT_URL_UNDEFINED = 1;
    public const ERR_ACCESS_DENIED_TO_UNKNOWN_RESOURCE_FOR_ANONYMOUS_AND_REDIRECT_URL_DEFINED_BY_SYSTEM_PARAMETER = 2;
    public const ERR_ACCESS_DENIED_TO_KNOWN_RESOURCE_FOR_ANONYMOUS = 3;
    public const ERR_ACCESS_DENIED_FOR_AUTHORIZED_USER = 4;
    public const ERR_ACCESS_DENIED_TO_MULTIPLE_TENANT_INSTALL_FOR_ADMIN = 5;

    public const OK_ACCESS_ALLOWED_TO_OWN_ACCOUNT_FOR_ADMIN = 11;
    public const OK_ACCESS_ALLOWED_TO_OTHER_ACCOUNT_FOR_CROSS_DOMAIN_ADMIN = 12;
    public const OK_ACCESS_ALLOWED_TO_MAIN_ACCOUNT_FOR_REGULAR_ADMIN = 13; // TODO: ?
    public const OK_ACCESS_ALLOWED = 14;

    protected const ERROR_MESSAGES = [
        self::ERR_ACCESS_DENIED_TO_UNKNOWN_RESOURCE_FOR_ANONYMOUS_AND_REDIRECT_URL_UNDEFINED => 'Access denied to unknown resource for anonymous user and redirect url is undefined',
        self::ERR_ACCESS_DENIED_TO_UNKNOWN_RESOURCE_FOR_ANONYMOUS_AND_REDIRECT_URL_DEFINED_BY_SYSTEM_PARAMETER => 'Access denied to unknown resource for anonymous user and redirect url is defined by system parameter',
        self::ERR_ACCESS_DENIED_TO_KNOWN_RESOURCE_FOR_ANONYMOUS => 'Access denied to known resource for anonymous user',
        self::ERR_ACCESS_DENIED_FOR_AUTHORIZED_USER => 'Access denied for authorized user',
        self::ERR_ACCESS_DENIED_TO_MULTIPLE_TENANT_INSTALL_FOR_ADMIN => 'Access denied to multiple tenant installation for admin',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_ACCESS_ALLOWED_TO_OWN_ACCOUNT_FOR_ADMIN => 'Access allowed to own domain for admin',
        self::OK_ACCESS_ALLOWED_TO_OTHER_ACCOUNT_FOR_CROSS_DOMAIN_ADMIN => 'Access allowed to other account for cross-domain admin',
        self::OK_ACCESS_ALLOWED => 'Access allowed',
    ];

    public string $redirectUrl = '';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }

    // --- Mutate state ---

    public function addError(int $code, string $redirectUrl): static
    {
        $this->redirectUrl = $redirectUrl;
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
        return $this;
    }

    // --- Query state ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function successCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstSuccessCode();
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    public function statusCode(): ?int
    {
        if ($this->hasError()) {
            return $this->getResultStatusCollector()->getFirstErrorCode();
        }
        if ($this->hasSuccess()) {
            return $this->getResultStatusCollector()->getFirstSuccessCode();
        }
        return null;
    }

    public function logData(): array
    {
        $logData = $this->redirectUrl ? [
            'redirect' => $this->redirectUrl,
        ] : [];
        if ($this->hasError()) {
            $logData += [
                'error code' => $this->errorCode(),
                'error message' => $this->errorMessage()
            ];
        }
        if ($this->hasSuccess()) {
            $logData += [
                'success code' => $this->successCode(),
                'success message' => $this->successMessage()
            ];
        }
        return $logData;
    }
}
