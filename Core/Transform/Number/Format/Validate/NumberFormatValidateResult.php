<?php
/**
 * SAM-8683: Adjustments and fixes for number formatting
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Transform\Number\Format\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class NumberFormatValidateResult
 * @package Sam\Core\Transform\Number\Format\Validate
 */
class NumberFormatValidateResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVALID = 1;

    public const OK_VALID_WITH_THOUSAND_SEPARATOR = 11;
    public const OK_VALID_WITHOUT_THOUSAND_SEPARATOR = 12;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_INVALID => 'Invalid number format',
    ];

    /** @var string[] */
    protected const SUCCESS_MESSAGES = [
        self::OK_VALID_WITH_THOUSAND_SEPARATOR => 'Correctly formatted number with thousand separator',
        self::OK_VALID_WITHOUT_THOUSAND_SEPARATOR => 'Correctly formatted number without thousand separator'
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

    // --- Query ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function isValidNumberWithThousandSeparator(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteSuccess(self::OK_VALID_WITH_THOUSAND_SEPARATOR);
    }

    public function isValidNumberWithoutThousandSeparator(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteSuccess(self::OK_VALID_WITHOUT_THOUSAND_SEPARATOR);
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
}
