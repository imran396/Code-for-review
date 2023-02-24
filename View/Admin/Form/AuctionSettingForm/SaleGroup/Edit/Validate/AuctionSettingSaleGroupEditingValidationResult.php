<?php
/**
 * SAM-11530 : Auction Sales group : Groups are getting unlinked if user creates new group with same name
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionSettingForm\SaleGroup\Edit\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;

/**
 * Class AuctionSettingSaleGroupEditingValidationResult
 * @package Sam\View\Admin\Form\AuctionSettingForm\SaleGroup\Edit\Validate
 */
class AuctionSettingSaleGroupEditingValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_FOUND = 1;
    public const OK_NOT_FOUND = 2;

    protected const SUCCESS_MESSAGES = [
        self::OK_NOT_FOUND => 'OK'
    ];

    protected const ERROR_MESSAGES = [
        self::ERR_FOUND => 'Should be unique',
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

    // --- Mutate ---

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

    // --- Query error ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    /**
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(string $glue = "\n"): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    // --- Query success ---

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    /**
     * @return int[]
     */
    public function successCodes(): array
    {
        return $this->getResultStatusCollector()->getSuccessCodes();
    }

    public function successMessage(string $glue = "\n"): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage($glue);
    }
}
