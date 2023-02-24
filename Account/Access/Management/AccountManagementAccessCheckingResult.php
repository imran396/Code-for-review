<?php
/**
 * SAM-9594: Account management access checking
 * SAM-7650 : Route and menu adjustments of Settings / System Parameters section
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Access\Management;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AccountManagementAccessCheckingResult
 * @package Sam\Account\Access\Management
 */
class AccountManagementAccessCheckingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_ACCOUNT_ID_NOT_DEFINED = 1;
    public const ERR_ACCOUNT_NOT_FOUND_BY_ID = 2;
    public const ERR_DENIED_FOR_SINGLE_TENANT_INSTALLATION = 3;
    public const ERR_DENIED_FOR_PORTAL_SYSTEM_ACCOUNT = 4;
    public const ERR_ABSENT_MANAGE_SETTINGS_PRIVILEGE = 5;

    protected const ERROR_MESSAGES = [
        self::ERR_ACCOUNT_ID_NOT_DEFINED => 'Account id is not defined',
        self::ERR_ACCOUNT_NOT_FOUND_BY_ID => 'Active account cannot be found by id',
        self::ERR_DENIED_FOR_SINGLE_TENANT_INSTALLATION => 'Denied for single-tenant installation',
        self::ERR_DENIED_FOR_PORTAL_SYSTEM_ACCOUNT => 'Denied for portal system account',
        self::ERR_ABSENT_MANAGE_SETTINGS_PRIVILEGE => 'Absent "Manage Settings" privilege',
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(string $glue = ', '): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }
}
