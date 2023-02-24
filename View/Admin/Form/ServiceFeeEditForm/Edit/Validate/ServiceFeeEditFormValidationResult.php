<?php
/**
 * SAM-11110: Stacked Tax. New Invoice Edit page: Service Fee Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Validate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ServiceFeeEditFormValidationResult
 * @package Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Validate
 */
class ServiceFeeEditFormValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_AMOUNT_INVALID = 1;
    public const ERR_AMOUNT_REQUIRED = 2;
    public const ERR_NAME_REQUIRED = 3;
    public const ERR_TAX_SCHEMA_INVALID = 4;
    public const ERR_TYPE_REQUIRED = 5;

    /** @var string[] */
    public const ERROR_MESSAGES = [
        self::ERR_AMOUNT_INVALID => 'Additional charge should be numeric',
        self::ERR_AMOUNT_REQUIRED => 'Additional charge is required',
        self::ERR_NAME_REQUIRED => 'Name is required',
        self::ERR_TAX_SCHEMA_INVALID => 'Tax schema invalid',
        self::ERR_TYPE_REQUIRED => 'Type required',
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

    /**
     * @param int $code
     * @return $this
     */
    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    /**
     * @return array
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    /**
     * @return ResultStatus[]
     */
    public function getErrors(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }
}
