<?php
/**
 * SAM-6791: Validations at controller layer for v3.5 - InventoryControllerValidator at admin site
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Inventory\Validate;

use Sam\Application\Controller\Admin\Inventory\Validate\InventoryControllerValidationResult as Result;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InventoryControllerValidationResult
 * @package Sam\Application\Controller\Inventory
 */
class InventoryControllerValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    // --- Outgoing values ---

    /**
     * Check inventory entity existence
     */
    public const ERR_INCORRECT_LOT_ITEM_ID = 1;
    /**
     * Check inventory availability (not deleted)
     */
    public const ERR_UNAVAILABLE_LOT_ITEM = 2;
    /**
     * Access denied. Admin has not enough privileges
     */
    public const ERR_USER_DOES_NOT_HAVE_PRIVILEGE_TO_MANAGE_INVENTORY = 3;
    /**
     * Access denied. Admin has not enough privileges to manage inventory of not system account
     */
    public const ERR_USER_DOES_NOT_HAVE_PRIVILEGE_TO_MANAGE_INVENTORY_OF_NOT_SYSTEM_ACCOUNT = 4;
    /**
     * Check of inventory account
     */
    public const ERR_LOT_ITEM_ACCOUNT_NOT_FOUND = 5;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_INCORRECT_LOT_ITEM_ID => 'Wrong lot item id',
        self::ERR_UNAVAILABLE_LOT_ITEM => 'Available lot item not found (not active)',
        self::ERR_USER_DOES_NOT_HAVE_PRIVILEGE_TO_MANAGE_INVENTORY => 'Access denied: admin has not enough privileges',
        self::ERR_USER_DOES_NOT_HAVE_PRIVILEGE_TO_MANAGE_INVENTORY_OF_NOT_SYSTEM_ACCOUNT => 'Access denied: admin does not have privilege to manage inventory of not system account',
        self::ERR_LOT_ITEM_ACCOUNT_NOT_FOUND => 'Access denied: lot item account not available',
    ];

    /**
     * WHen creating new user, no need in most of validations
     */
    public const OK_CREATE_NEW_LOT_ITEM = 10;
    /**
     * All validations completed successfully
     */
    public const OK_SUCCESS_VALIDATION = 11;

    /** @var string[] */
    public const SUCCESS_MESSAGES = [
        self::OK_CREATE_NEW_LOT_ITEM => 'Creating new lot item',
        self::OK_SUCCESS_VALIDATION => 'Successful validation',
    ];

    // --- Constructors ---

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
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }

    // --- Mutate state ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
        return $this;
    }

    // --- Outgoing results ---

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function successCodes(): array
    {
        return $this->getResultStatusCollector()->getSuccessCodes();
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    public function hasUnavailableError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(Result::ERR_UNAVAILABLE_LOT_ITEM);
    }

}
