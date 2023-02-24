<?php
/**
 * SAM-9666: Retire Cross-domain privilege for portal admins
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\CrossDomainAdminPrivilegeConstraint;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Contains cross-domain admin privileges constraint validation errors
 *
 * Class CrossDomainAdminPrivilegeConstraintValidationResult
 * @package Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\CrossDomainAdminConstraint
 */
class CrossDomainAdminPrivilegeConstraintValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_CROSS_DOMAIN_ADMIN_AT_PORTAL_ACCOUNT = 1;

    protected const ERROR_MESSAGES = [
        self::ERR_CROSS_DOMAIN_ADMIN_AT_PORTAL_ACCOUNT => 'Cross domain access rights are not applicable for the portal admin',
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

    public function addError(int $errorCode): static
    {
        $this->getResultStatusCollector()->addError($errorCode);
        return $this;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }
}
