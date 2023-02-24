<?php
/**
 * SAM-9370: Access checker for lot category delete operation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Access\Management;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotCategoryDeleteAccessCheckingResult
 * @package Sam\Lot\Category\Access\Management
 */
class LotCategoryManagementAccessCheckingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_DENIED_FOR_ANONYMOUS = 1;
    public const ERR_TARGET_LOT_CATEGORY_NOT_FOUND = 2;
    public const ERR_EDITOR_USER_NOT_FOUND = 3;
    public const ERR_ABSENT_MANAGE_SETTINGS_PRIVILEGE = 4;
    public const ERR_DENIED_FOR_NOT_CROSS_DOMAIN_ADMIN_ON_MAIN_ACCOUNT = 5;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_DENIED_FOR_ANONYMOUS => 'Denied for anonymous user',
        self::ERR_TARGET_LOT_CATEGORY_NOT_FOUND => 'Target lot category not found',
        self::ERR_EDITOR_USER_NOT_FOUND => 'Editor user not found',
        self::ERR_ABSENT_MANAGE_SETTINGS_PRIVILEGE => 'Denied because "Settings Manager" privilege absent',
        self::ERR_DENIED_FOR_NOT_CROSS_DOMAIN_ADMIN_ON_MAIN_ACCOUNT => 'Access denied for not cross-domain admin on main account',
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

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addSuccess(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addSuccess($code, $message);
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

    public function errorMessage(string $glue = ', '): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }
}
