<?php
/**
 * SAM-8107: Issues related to Validation and Values of Buyer's Premium
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Validate\Internal\BuyersPremium\Internal\Validate;

use Sam\Core\RangeTable\BuyersPremium\Validate\BuyersPremiumRangesValidationResult;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BuyersPremiumValidationResult
 * @package
 */
class BuyersPremiumValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_WRONG_SYNTAX = 1;
    public const ERR_WRONG_RANGES = 2;

    protected const ERROR_MESSAGES = [
        self::ERR_WRONG_SYNTAX => 'Wrong syntax',
        self::ERR_WRONG_RANGES => 'Wrong ranges',
    ];

    public ?BuyersPremiumRangesValidationResult $rangesValidationResult = null;

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

    // --- Mutation logic ---

    public function addError(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addError($code, $message);
        return $this;
    }

    public function addRangesError(BuyersPremiumRangesValidationResult $rangesValidationResult): static
    {
        $this->addError(self::ERR_WRONG_RANGES);
        $this->rangesValidationResult = $rangesValidationResult;
        return $this;
    }

    // --- Query logic ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasSyntaxError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_WRONG_SYNTAX]);
    }

    public function hasRangesError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_WRONG_RANGES]);
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
