<?php
/**
 * SAM-10180: Extract logic of date and time assignment to auction lots collection from the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotDateTimeApplyingValidationResult
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Validate
 */
class LotDateTimeApplyingValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_DATE_ASSIGNMENT_STRATEGY_INVALID = 1;
    public const ERR_START_DATE_REQUIRED = 2;
    public const ERR_START_DATE_INVALID_FORMAT = 3;
    public const ERR_START_CLOSING_DATE_REQUIRED = 4;
    public const ERR_START_CLOSING_DATE_INVALID_FORMAT = 5;
    public const ERR_TIMEZONE_INVALID = 6;
    public const ERR_START_DATE_GREATER_THAN_START_CLOSING_DATE = 7;
    public const ERR_NOT_APPLICABLE_FOR_AUCTION = 8;

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
                self::ERR_DATE_ASSIGNMENT_STRATEGY_INVALID => 'Date assignment strategy should be ITEMS_TO_AUCTION',
                self::ERR_NOT_APPLICABLE_FOR_AUCTION => 'Not applicable for this auction',
                self::ERR_START_CLOSING_DATE_INVALID_FORMAT => 'Invalid start closing date format',
                self::ERR_START_CLOSING_DATE_REQUIRED => 'Start closing date is required',
                self::ERR_START_DATE_GREATER_THAN_START_CLOSING_DATE => 'Start date greater than start closing date',
                self::ERR_START_DATE_INVALID_FORMAT => 'Invalid start date format',
                self::ERR_START_DATE_REQUIRED => 'Start date is required',
                self::ERR_TIMEZONE_INVALID => 'Invalid timezone',
            ]
        );
        return $this;
    }

    public function addError(int $errorCode): static
    {
        $this->getResultStatusCollector()->addError($errorCode);
        return $this;
    }

    public function getErrorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }
}
