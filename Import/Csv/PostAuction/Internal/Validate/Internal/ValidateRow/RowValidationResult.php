<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Validate\Internal\ValidateRow;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Container for post auction row validation errors
 *
 * Class RowValidationResult
 * @package Sam\Import\Csv\PostAuction\Internal\Validate\Internal\ValidateRow
 */
class RowValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_LOT_NUMBER_INVALID = 1;
    public const ERR_HAMMER_PRICE_INVALID = 2;
    public const ERR_HAMMER_PRICE_REQUIRED = 3;

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
                self::ERR_LOT_NUMBER_INVALID => 'Invalid lot number',
                self::ERR_HAMMER_PRICE_INVALID => 'Invalid hammer price',
                self::ERR_HAMMER_PRICE_REQUIRED => 'Hammer price required',
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

    public function getErrorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }
}
