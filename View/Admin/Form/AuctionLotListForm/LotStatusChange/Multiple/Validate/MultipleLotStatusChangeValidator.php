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

use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureCheckerAwareTrait;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Load\LotDto;

/**
 * Class MultipleLotStatusChangeValidator
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Validate
 */
class MultipleLotStatusChangeValidator extends CustomizableClass
{
    use AuctionLotStatusPureCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $targetLotStatus
     * @param LotDto[] $lotDtos
     * @return MultipleLotStatusChangeValidationResultCollection
     */
    public function validate(int $targetLotStatus, array $lotDtos): MultipleLotStatusChangeValidationResultCollection
    {
        $validationResultCollection = MultipleLotStatusChangeValidationResultCollection::new();
        foreach ($lotDtos as $lotDto) {
            $validationResult = $this->validateLot($targetLotStatus, $lotDto);
            $validationResultCollection->addValidationResult($validationResult);
        }
        return $validationResultCollection;
    }

    protected function validateLot(int $targetLotStatus, LotDto $lotDto): LotStatusChangeValidationResult
    {
        $validationResult = LotStatusChangeValidationResult::new()->construct($lotDto);
        if (
            !LotSellInfoPureChecker::new()->isHammerPrice($lotDto->hammerPrice)
            && $this->getAuctionLotStatusPureChecker()->isAmongWonStatuses($targetLotStatus)
        ) {
            $validationResult->addError(LotStatusChangeValidationResult::ERR_ABSENT_HAMMER_PRICE);
        }
        return $validationResult;
    }
}
