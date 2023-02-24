<?php
/**
 * SAM-10477: Reject assigning both BP rules on the same level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyersPremiumForm\Save;

use BuyersPremiumRange;
use Sam\AuditTrail\AuditTrailLoggerAwareTrait;
use Sam\BuyersPremium\Load\BuyersPremiumRangeLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\BuyersPremiumRange\BuyersPremiumRangeWriteRepositoryAwareTrait;

/**
 * Class BuyersPremiumRangeProducer
 * @package Sam\View\Admin\Form\BuyersPremiumForm\Save
 */
class BuyersPremiumRangeProducer extends CustomizableClass
{
    use AuditTrailLoggerAwareTrait;
    use BuyersPremiumRangeLoaderCreateTrait;
    use BuyersPremiumRangeWriteRepositoryAwareTrait;
    use EntityFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Update Global BuyersPremiumRange
     * Works in 3 steps: update modified records, remove unused records, add new records.
     *
     * @param array $newRanges [[amount, fixed, percent, modeName]]
     * @param int $buyersPremiumId
     * @param int $accountId
     * @param int $editorUserId
     */
    public function update(array $newRanges, int $buyersPremiumId, int $accountId, int $editorUserId): void
    {
        $existingRanges = $this->createBuyersPremiumRangeLoader()->loadBpRangeByBpId($buyersPremiumId);

        // Update modified records
        foreach ($newRanges as $newRangeIndex => $newRange) {
            [$amount, $fixed, $percent, $mode] = $this->normalizeBpRow($newRange);
            $existingRangeIndex = $this->findRangeIndexWithAmount($amount, $existingRanges);
            if ($existingRangeIndex !== null) {
                $existingRange = $existingRanges[$existingRangeIndex];
                $existingRange->Amount = $amount;
                $existingRange->Fixed = $fixed;
                $existingRange->Percent = $percent;
                $existingRange->Mode = $mode;
                if ($existingRange->__Modified) {
                    $this->getBuyersPremiumRangeWriteRepository()->saveWithModifier($existingRange, $editorUserId);
                    $this->logAuditTrail('Updated', $existingRange, $accountId, $editorUserId);
                }
                unset($newRanges[$newRangeIndex], $existingRanges[$existingRangeIndex]);
            }
        }

        // Remove unused records
        foreach ($existingRanges as $existingRange) {
            $this->getBuyersPremiumRangeWriteRepository()->deleteWithModifier($existingRange, $editorUserId);
            $this->logAuditTrail('Deleted', $existingRange, $accountId, $editorUserId);
        }

        // Create new records
        foreach ($newRanges as $newRange) {
            $bpRange = $this->createEntityFactory()->buyersPremiumRange();
            [$amount, $fixed, $percent, $mode] = $this->normalizeBpRow($newRange);
            $bpRange->Amount = $amount;
            $bpRange->Fixed = $fixed;
            $bpRange->Percent = $percent;
            $bpRange->Mode = $mode;
            $bpRange->AccountId = $accountId;
            $bpRange->BuyersPremiumId = $buyersPremiumId;
            $this->getBuyersPremiumRangeWriteRepository()->saveWithModifier($bpRange, $editorUserId);
            $this->logAuditTrail('Added', $bpRange, $accountId, $editorUserId);
        }
    }

    /**
     * @param float|null $amount
     * @param BuyersPremiumRange[] $ranges
     * @return int|null
     */
    protected function findRangeIndexWithAmount(?float $amount, array $ranges): ?int
    {
        foreach ($ranges as $index => $range) {
            if (Floating::eq($amount, $range->Amount)) {
                return $index;
            }
        }
        return null;
    }

    protected function logAuditTrail(string $eventType, BuyersPremiumRange $bpRange, int $accountId, int $editorUserId): void
    {
        $event = sprintf(
            '%s buyers premium range for BP with id %s. Start %s, fixed %s, percent %s, mode %s',
            $eventType,
            $bpRange->BuyersPremiumId,
            $bpRange->Amount,
            $bpRange->Fixed,
            $bpRange->Percent,
            $bpRange->Mode
        );
        $this->getAuditTrailLogger()
            ->setAccountId($accountId)
            ->setEditorUserId($editorUserId)
            ->setEvent($event)
            ->setSection(Constants\AdminRoute::C_MANAGE_BUYERS_PREMIUM)
            ->setUserId($editorUserId)
            ->log();
        log_debug($event);
    }

    /**
     * Type cast and normalize values of buyer's premium row and return row
     * @param array $bpRow
     * @return array [float, float, float, string]
     */
    protected function normalizeBpRow(array $bpRow): array
    {
        $amount = Cast::toFloat($bpRow[0], Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        $fixed = Cast::toFloat($bpRow[1], Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        $percent = Cast::toFloat($bpRow[2], Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        $mode = (string)$bpRow[3];
        return [$amount, $fixed, $percent, $mode];
    }
}
