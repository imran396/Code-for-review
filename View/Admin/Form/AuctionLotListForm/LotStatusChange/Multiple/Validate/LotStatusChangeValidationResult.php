<?php
/**
 * SAM-10177: Decouple the "Lot status change" function at the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Validate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Load\LotDto;

/**
 * Class LotStatusChangeValidationResult
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Validate
 */
class LotStatusChangeValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_ABSENT_HAMMER_PRICE = 1;

    public readonly LotDto $lotDto;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(LotDto $lotDto): static
    {
        $this->lotDto = $lotDto;
        $this->getResultStatusCollector()->construct(
            [
                self::ERR_ABSENT_HAMMER_PRICE => 'Hammer price is not set',
            ]
        );
        return $this;
    }

    public function addError(int $errorCode): static
    {
        $this->getResultStatusCollector()->addError($errorCode);
        return $this;
    }

    /**
     * @return ResultStatus[]
     */
    public function getErrors(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasConcreteError(int $errorCode): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($errorCode);
    }

    public function getErrorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }
}
