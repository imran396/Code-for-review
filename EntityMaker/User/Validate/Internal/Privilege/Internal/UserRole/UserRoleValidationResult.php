<?php
/**
 * SAM-9520: Important Security user privilege issue
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\UserRole;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Contains user role validation errors
 *
 * Class UserRoleValidationResult
 * @package Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\UserRole
 */
class UserRoleValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_ADMIN_AND_BIDDER_CONSIGNOR_PRIVILEGE_TOGETHER_NOT_ALLOWED = 1;
    public const ERR_ADMIN_PRIVILEGES_IS_NOT_EDITABLE = 2;
    public const ERR_BIDDER_AND_CONSIGNOR_PRIVILEGES_IS_NOT_EDITABLE = 3;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_ADMIN_AND_BIDDER_CONSIGNOR_PRIVILEGE_TOGETHER_NOT_ALLOWED => 'Denied assign of Admin role together with Bidder or Consignor role',
        self::ERR_ADMIN_PRIVILEGES_IS_NOT_EDITABLE => 'Admin privileges are not editable',
        self::ERR_BIDDER_AND_CONSIGNOR_PRIVILEGES_IS_NOT_EDITABLE => 'Bidder and consignor privileges are not editable',
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
