<?php
/**
 * SAM-9365: Refactor BidIncrementCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\BidIncrement\Internal\Validate\Internal;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * This class contains validation errors of the increment ranges
 *
 * Class RangesValidationResult
 * @package Sam\Import\Csv\BidIncrement\Internal\Validate\Internal
 */
class RangesValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_ABSENT_ZERO_AMOUNT = 1;
    public const ERR_DUPLICATE_RANGE = 2;

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
        $this->getResultStatusCollector()->construct(
            [
                self::ERR_ABSENT_ZERO_AMOUNT => 'Zero amount absent',
                self::ERR_DUPLICATE_RANGE => 'Duplicate range',
            ]
        );
        return $this;
    }

    public function addError(int $errorCode): void
    {
        $this->getResultStatusCollector()->addError($errorCode);
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function getErrorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    public function getErrorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function getErrorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }
}
