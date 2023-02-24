<?php
/**
 * SAM-4557: Settlement management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/13/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Save;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Settlement\Calculate\SettlementCalculatorAwareTrait;
use Sam\Settlement\Commission\SettlementItemConsignorCommissionFeeCalculatorCreateTrait;
use Sam\Settlement\Load\SettlementLoaderAwareTrait;
use Sam\Settlement\Save\Internal\Load\UnsoldItemAuctionIdDetectorCreateTrait;
use Sam\Settlement\SettlementNo\SettlementNoAdviserAwareTrait;
use Sam\Storage\WriteRepository\Entity\Settlement\SettlementWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\SettlementItem\SettlementItemWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Settlement;
use SettlementItem;

/**
 * Class SettlementProducer
 * @package Sam\Settlement\Save
 */
class SettlementProducer extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use SettlementCalculatorAwareTrait;
    use SettlementItemConsignorCommissionFeeCalculatorCreateTrait;
    use SettlementItemWriteRepositoryAwareTrait;
    use SettlementLoaderAwareTrait;
    use SettlementNoAdviserAwareTrait;
    use SettlementWriteRepositoryAwareTrait;
    use UnsoldItemAuctionIdDetectorCreateTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $consignorUserId
     * @param int $accountId
     * @param float|null $commissionPercent null means that there is no commission percent
     * @param int $editorUserId
     * @param int|null $auctionId null means that this is not generated per-sale
     * @return Settlement
     */
    public function create(
        int $consignorUserId,
        int $accountId,
        ?float $commissionPercent,
        int $editorUserId,
        ?int $auctionId = null
    ): Settlement {
        $settlement = $this->createEntityFactory()->settlement();
        $settlement->AccountId = $accountId;
        $settlement->ConsignmentCommission = $commissionPercent;
        $settlement->ConsignorId = $consignorUserId;
        $settlement->SettlementNo = $this->getSettlementNoAdviser()->suggest($accountId);
        $settlement->toOpen();

        $consignor = $this->getUserLoader()->loadConsignor($consignorUserId, true);
        if ($consignor) {
            $settlement->AuctionId = $auctionId;  //add the auction id if this is generated per-sale
            $settlement->ConsignorTax = $consignor->ConsignorTax;
            $settlement->ConsignorTaxComm = $consignor->ConsignorTaxComm;
            $settlement->ConsignorTaxHp = $consignor->ConsignorTaxHp;
            $settlement->ConsignorTaxHpType = $consignor->ConsignorTaxHpType;
            $settlement->ConsignorTaxServices = $consignor->ConsignorTaxServices;
        }
        $this->getSettlementWriteRepository()->saveWithModifier($settlement, $editorUserId);
        return $settlement;
    }

    /**
     * @param int $settlementId
     * @param int $lotItemId
     * @param int $editorUserId
     * @return SettlementItem|null
     */
    public function createItem(int $settlementId, int $lotItemId, int $editorUserId): ?SettlementItem
    {
        $settlement = $this->getSettlementLoader()->load($settlementId);
        if (!$settlement) {
            log_error(
                "Available settlement not found in settlement item producer"
                . composeSuffix(['s' => $settlementId])
            );
            return null;
        }

        // Tag item to settlement
        $settlementItem = $this->createEntityFactory()->settlementItem();
        $settlementItem->SettlementId = $settlement->Id;
        $settlementItem->LotItemId = $lotItemId;
        $settlementItem->AuctionId = $settlement->AuctionId;
        $settlementItem->Active = true;
        $this->fillItem($settlementItem);
        $this->getSettlementItemWriteRepository()->saveWithModifier($settlementItem, $editorUserId);
        return $settlementItem;
    }

    public function fillItem(SettlementItem $settlementItem): void
    {
        $lotItem = $this->getLotItemLoader()->load($settlementItem->LotItemId);
        if (!$lotItem) {
            log_error(
                "Available lot item not found in settlement item producer"
                . composeSuffix(['li' => $settlementItem->LotItemId])
            );
            return;
        }

        $commissionFeeCalculator = $this->createSettlementItemConsignorCommissionFeeCalculator();
        if ($lotItem->hasHammerPrice()) {
            $auctionId = $lotItem->AuctionId;
            $consignorCommissionFee = $commissionFeeCalculator->calcForSoldItem($lotItem);
        } else {
            $auctionId = $settlementItem->AuctionId ?? $this->createUnsoldItemAuctionIdDetector()->detect($settlementItem->LotItemId);
            $consignorCommissionFee = $commissionFeeCalculator->calcForUnsoldItem($lotItem, $auctionId);
        }
        if (!$auctionId) {
            log_debug(
                "Can't detect lot item auction id"
                . composeSuffix(['li' => $settlementItem->LotItemId])
            );
        }

        $auction = $this->getAuctionLoader()->load($auctionId);
        $isTestAuction = $auction->TestAuction ?? false;

        $settlementItem->HammerPrice = $lotItem->HammerPrice;
        $settlementItem->Commission = $consignorCommissionFee->commissionAmount;
        $settlementItem->CommissionId = $consignorCommissionFee->commissionId;
        $settlementItem->Fee = $consignorCommissionFee->feeAmount;
        $settlementItem->FeeId = $consignorCommissionFee->feeId;
        $settlementItem->Subtotal = $this->getSettlementCalculator()->calcSubtotal($settlementItem);
        $settlementItem->AuctionId = $auctionId;
        $settlementItem->LotName = $this->getLotRenderer()->makeName($lotItem->Name, $isTestAuction);
    }
}
