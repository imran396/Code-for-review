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

use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Load\LotDto;

/**
 * Class MultipleLotStatusChangeValidationResultCollection
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Validate
 */
class MultipleLotStatusChangeValidationResultCollection extends CustomizableClass
{
    /**
     * @var LotStatusChangeValidationResult[]
     */
    protected array $validationResults = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function addValidationResult(LotStatusChangeValidationResult $validationResult): static
    {
        $this->validationResults[] = $validationResult;
        return $this;
    }

    /**
     * @return LotStatusChangeValidationResult[]
     */
    public function validationResults(): array
    {
        return $this->validationResults;
    }

    public function hasError(): bool
    {
        foreach ($this->validationResults as $validationResult) {
            if ($validationResult->hasError()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param int $errorCode
     * @return LotDto[]
     */
    public function collectLotDtosWithError(int $errorCode): array
    {
        $lotDtos = [];
        foreach ($this->validationResults as $validationResult) {
            if ($validationResult->hasConcreteError($errorCode)) {
                $lotDtos[] = $validationResult->lotDto;
            }
        }
        return $lotDtos;
    }

    /**
     * @return LotDto[]
     */
    public function collectValidLotDtos(): array
    {
        $validLotDtos = [];
        foreach ($this->validationResults as $validationResult) {
            if (!$validationResult->hasError()) {
                $validLotDtos[] = $validationResult->lotDto;
            }
        }
        return $validLotDtos;
    }

    /**
     * @return int[]
     */
    public function collectValidLotItemIds(): array
    {
        $validLotItemIds = [];
        foreach ($this->validationResults as $validationResult) {
            if (!$validationResult->hasError()) {
                $validLotItemIds[] = $validationResult->lotDto->lotItemId;
            }
        }
        return $validLotItemIds;
    }
}
