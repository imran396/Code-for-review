<?php
/**
 * SAM-4613: Settlements merging class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-11-25
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Save;

use InvalidArgumentException;
use Sam\Billing\Payment\Load\PaymentLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Delete\SettlementDeleterAwareTrait;
use Sam\Settlement\Load\SettlementAdditionalLoaderAwareTrait;
use Sam\Settlement\Load\SettlementItemLoaderAwareTrait;
use Sam\Settlement\Load\SettlementLoaderAwareTrait;
use Sam\Settlement\SettlementNo\SettlementNoAdviserAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\WriteRepository\Entity\Payment\PaymentWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\Settlement\SettlementWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\SettlementAdditional\SettlementAdditionalWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\SettlementItem\SettlementItemWriteRepositoryAwareTrait;
use Settlement;

/**
 * Class SettlementMerger
 * @package Sam\Settlement\Save
 */
class SettlementMerger extends CustomizableClass
{
    use EditorUserAwareTrait;
    use EntityFactoryCreateTrait;
    use PaymentLoaderAwareTrait;
    use PaymentWriteRepositoryAwareTrait;
    use SettlementAdditionalLoaderAwareTrait;
    use SettlementAdditionalWriteRepositoryAwareTrait;
    use SettlementDeleterAwareTrait;
    use SettlementItemLoaderAwareTrait;
    use SettlementItemWriteRepositoryAwareTrait;
    use SettlementLoaderAwareTrait;
    use SettlementNoAdviserAwareTrait;
    use SettlementWriteRepositoryAwareTrait;
    use SystemAccountAwareTrait;

    protected ?string $errorMessage = null;
    protected ?string $successMessage = null;
    /** @var int[]|null */
    protected ?array $settlementIds = null;
    /** @var Settlement[]|null */
    protected ?array $settlements = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Merge settlements
     */
    public function merge(): void
    {
        $this->successMessage = null;
        $systemAccountId = $this->getSystemAccountId();
        $newSettlement = $this->createEntityFactory()->settlement();
        $newSettlement->toOpen();
        $newSettlement->AccountId = $systemAccountId;
        $settlements = $this->getSettlements();
        $newSettlement->ConsignorId = isset($settlements[0]) ? $settlements[0]->ConsignorId : null; // JIC, already checked in validate()
        $newSettlement->SettlementNo = $this->getSettlementNoAdviser()->suggest($systemAccountId);

        $settlementItems = [];
        $extraCharges = [];
        $payments = [];
        $settlementNos = [];

        $settlements = $this->getSettlements();
        foreach ($settlements as $settlement) {
            $settlementId = $settlement->Id;
            $newSettlement->Note .= $settlement->Note . "\n";

            $tempSettlementItems = $this->getSettlementItemLoader()->loadBySettlementId($settlementId, true);
            foreach ($tempSettlementItems as $settlementItem) {
                $settlementItems[] = $settlementItem;
            }

            $tempArrExtraCharges = $this->getSettlementAdditionalLoader()->load($settlementId, true);
            foreach ($tempArrExtraCharges as $settlementExtraCharges) {
                $extraCharges[] = $settlementExtraCharges;
            }

            $tempArrPayments = $this->getPaymentLoader()
                ->loadByTranIdAndTranType($settlementId, Constants\Payment::TT_SETTLEMENT, true);
            foreach ($tempArrPayments as $payment) {
                $payments[] = $payment;
            }

            $this->getSettlementDeleter()
                ->enableItemsDeleting(false)
                ->delete($settlement, $this->getEditorUserId());
            $settlementNos[] = $settlement->SettlementNo;
        }

        $this->successMessage = "Settlements [" . implode(",", $settlementNos) . "] have been merged";

        $this->getSettlementWriteRepository()->saveWithModifier($newSettlement, $this->getEditorUserId());
        $newSettlementId = $newSettlement->Id;

        foreach ($extraCharges as $extraCharge) {
            $extraCharge->SettlementId = $newSettlementId;
            $this->getSettlementAdditionalWriteRepository()->saveWithModifier($extraCharge, $this->getEditorUserId());
        }

        foreach ($settlementItems as $settlementItem) {
            $settlementItem->SettlementId = $newSettlementId;
            $this->getSettlementItemWriteRepository()->saveWithModifier($settlementItem, $this->getEditorUserId());
        }

        foreach ($payments as $payment) {
            $payment->TranId = $newSettlementId;
            $this->getPaymentWriteRepository()->saveWithModifier($payment, $this->getEditorUserId());
        }
    }

    /**
     * Validate settlements to be merged
     * @return bool
     */
    public function validate(): bool
    {
        $this->errorMessage = null;
        $tempConsignorId = null;
        $settlementIds = $this->getSettlementIds();
        foreach ($settlementIds as $settlementId) {
            $settlement = $this->getSettlementLoader()->load($settlementId, true);
            if (!$settlement) {
                continue;
            }
            if (
                isset($tempConsignorId)
                && $settlement->ConsignorId !== $tempConsignorId
            ) {
                $this->errorMessage = 'Selected settlements has different consignors, merging can not be continue';
                return false;
            }
            $tempConsignorId = $settlement->ConsignorId;
        }
        $countSettlements = count($this->getSettlements());
        if ($countSettlements === 0) {
            $this->errorMessage = 'No settlement has been checked to merge';
        } elseif ($countSettlements === 1) {
            $this->errorMessage = 'There is no second settlement to merge with';
        }
        $success = $this->errorMessage === null;
        return $success;
    }

    /**
     * Get Error Message while merging
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * Get Success Message after success merge
     * @return string|null
     */

    public function getSuccessMessage(): ?string
    {
        return $this->successMessage;
    }

    /**
     * @return Settlement[]
     */
    protected function getSettlements(): array
    {
        if ($this->settlements === null) {
            $this->settlements = $this->getSettlementLoader()->loadEntities($this->getSettlementIds());
        }
        return $this->settlements;
    }

    /**
     * @param Settlement[] $settlements
     * @return static
     */
    public function setSettlements(array $settlements): static
    {
        $this->settlements = $settlements;
        return $this;
    }

    /**
     * @return int[]
     */
    protected function getSettlementIds(): ?array
    {
        if ($this->settlementIds === null) {
            throw new InvalidArgumentException("Settlement ids not defined");
        }
        return $this->settlementIds;
    }

    /**
     * Set Settlement Ids to be merged
     * @param int[] $settlementIds
     * @return static
     */
    public function setSettlementIds(array $settlementIds): static
    {
        $this->settlementIds = ArrayCast::castInt($settlementIds);
        return $this;
    }
}
