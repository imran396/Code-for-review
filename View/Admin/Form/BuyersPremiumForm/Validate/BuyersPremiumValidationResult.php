<?php
/**
 * SAM-8107: Issues related to Validation and Values of Buyer's Premium
 * SAM-9727: Refactor \Qform_BuyersPremiumHelper
 * SAM-10477: Reject assigning both BP rules on the same level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyersPremiumForm\Validate;

use Sam\Core\RangeTable\BuyersPremium\Validate\BuyersPremiumRangesValidationResult;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BuyersPremiumValidationResult
 * @package Sam\View\Admin\Form\BuyersPremiumForm\Validate
 */
class BuyersPremiumValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_NAME_REQUIRED = 1;
    public const ERR_SHORT_NAME_REQUIRED = 2;
    public const ERR_SHORT_NAME_EXIST = 3;
    public const ERR_CALCULATION_METHOD_INVALID = 4;
    public const ERR_ADDITIONAL_BP_INTERNET_INVALID = 5;
    public const ERR_RANGES_INVALID = 6;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_NAME_REQUIRED => 'Required',
        self::ERR_SHORT_NAME_REQUIRED => 'Required',
        self::ERR_SHORT_NAME_EXIST => 'Already exist',
        self::ERR_CALCULATION_METHOD_INVALID => 'Invalid',
        self::ERR_ADDITIONAL_BP_INTERNET_INVALID => 'Invalid',
        self::ERR_RANGES_INVALID => 'Validation failed',
    ];

    protected ?BuyersPremiumRangesValidationResult $rangesValidationResult = null;

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
        $this->getResultStatusCollector()->addError($errorCode,);
        return $this;
    }

    public function setRangesValidationResult(BuyersPremiumRangesValidationResult $rangesValidationResult): static
    {
        $this->rangesValidationResult = $rangesValidationResult;
        if ($rangesValidationResult->hasError()) {
            $this->addError(self::ERR_RANGES_INVALID);
        }
        return $this;
    }

    public function isValid(): bool
    {
        return !$this->getResultStatusCollector()->hasError();
    }

    /**
     * @return ResultStatus[]
     */
    public function getNameErrors(): array
    {
        return $this->getResultStatusCollector()->findErrorResultStatusesByCodes([self::ERR_NAME_REQUIRED]);
    }

    /**
     * @return ResultStatus[]
     */
    public function getShortNameErrors(): array
    {
        return $this->getResultStatusCollector()->findErrorResultStatusesByCodes(
            [self::ERR_SHORT_NAME_REQUIRED, self::ERR_SHORT_NAME_EXIST]
        );
    }

    /**
     * @return ResultStatus[]
     */
    public function getCalculationMethodErrors(): array
    {
        return $this->getResultStatusCollector()->findErrorResultStatusesByCodes([self::ERR_CALCULATION_METHOD_INVALID]);
    }

    /**
     * @return ResultStatus[]
     */
    public function getAdditionalBpInternetErrors(): array
    {
        return $this->getResultStatusCollector()->findErrorResultStatusesByCodes([self::ERR_ADDITIONAL_BP_INTERNET_INVALID]);
    }

    public function getRangesValidationResult(): BuyersPremiumRangesValidationResult
    {
        if (!$this->rangesValidationResult) {
            throw new \RuntimeException('Ranges validation result does not set');
        }
        return $this->rangesValidationResult;
    }

    public function getErrorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @return ResultStatus[]
     */
    public function getErrorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }
}
