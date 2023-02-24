<?php
/**
 * SAM-6793: Validations at controller layer for v3.5 - LotImageControllerValidator at admin site
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\LotImage\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotImageControllerValidationResult
 * @package Sam\Application\Controller\Admin\LotImage\Validate
 */
class LotImageControllerValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    // --- Output values ---

    /**
     * Check auction entity existence
     */
    public const ERR_INCORRECT_AUCTION_ID = 1;
    /**
     * Check auction availability (not deleted)
     */
    public const ERR_UNAVAILABLE_AUCTION = 2;
    /**
     * Access denied. Admin has not enough privileges
     */
    public const ERR_LOT_IMAGE_ACCESS_DENIED = 3;
    /**
     * Check auction account
     */
    public const ERR_AUCTION_ACCOUNT_NOT_FOUND = 4;
    /**
     * Check access rights on portal
     */
    public const ERR_AUCTION_AND_PORTAL_ACCOUNTS_NOT_MATCH = 5;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_INCORRECT_AUCTION_ID => 'Wrong auction id',
        self::ERR_UNAVAILABLE_AUCTION => 'Available auction not found (deleted)',
        self::ERR_LOT_IMAGE_ACCESS_DENIED => ' Access denied: admin has not enough privileges',
        self::ERR_AUCTION_ACCOUNT_NOT_FOUND => 'Access denied: auction account not available',
        self::ERR_AUCTION_AND_PORTAL_ACCOUNTS_NOT_MATCH => 'Access denied: auction and portal accounts not match',
    ];

    /**
     * All validations completed successfully
     */
    public const OK_SUCCESS_VALIDATION = 6;

    /** @var string[] */
    protected const SUCCESS_MESSAGES = [
        self::OK_SUCCESS_VALIDATION => 'Successful validation'
    ];

    /**
     * Class instantiation method
     * @return static
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

    /**
     * @return bool
     */
    public function hasUnavailableError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(
            [self::ERR_UNAVAILABLE_AUCTION]
        );
    }
}
