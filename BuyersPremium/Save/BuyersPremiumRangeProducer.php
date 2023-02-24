<?php
/**
 * SAM-10464: Separate BP manager to services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Save;

use BuyersPremiumRange;
use Sam\BuyersPremium\Load\BuyersPremiumRangeLoaderCreateTrait;
use Sam\BuyersPremium\Validate\BuyersPremiumRangeExistenceCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\BuyersPremiumRange\BuyersPremiumRangeWriteRepositoryAwareTrait;

/**
 * Class BuyersPremiumProducer
 * @package Sam\BuyersPremium\Save
 */
class BuyersPremiumRangeProducer extends CustomizableClass
{
    use BuyersPremiumRangeExistenceCheckerCreateTrait;
    use BuyersPremiumRangeLoaderCreateTrait;
    use BuyersPremiumRangeWriteRepositoryAwareTrait;
    use EntityFactoryCreateTrait;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * Create auction's, lotItem's or user's BuyersPremiumRange.
     * User's BuyersPremiumRange is defined as $accountId + $auctionType + $userId
     * @param array $buyersPremiums [[amount, fixed, percent, mode]]
     * @param int $creatorId
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @param int|null $accountId
     * @param string|null $auctionType
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     */
    public function create(
        array $buyersPremiums,
        int $creatorId,
        ?int $auctionId = null,
        ?int $lotItemId = null,
        ?int $accountId = null,
        ?string $auctionType = null,
        ?int $userId = null,
        bool $isReadOnlyDb = false
    ): void {
        $params = [];
        if ($accountId) {
            $params['AccountId'] = $accountId;
        }
        if ($auctionId) {
            $params['AuctionId'] = $auctionId;
        }
        if ($auctionType) {
            $params['AuctionType'] = $auctionType;
        }
        if ($lotItemId) {
            $params['LotItemId'] = $lotItemId;
        }
        if ($userId) {
            $params['UserId'] = $userId;
        }

        if ($buyersPremiums) {
            $bpRangeExistenceChecker = $this->createBuyersPremiumRangeExistenceChecker();
            foreach ($buyersPremiums as $buyersPremium) {
                if (!$bpRangeExistenceChecker->existAmount((float)$buyersPremium[0], $params, $isReadOnlyDb)) {
                    $mode = $buyersPremium[3];
                    if (in_array($mode, Constants\BuyersPremium::$rangeModeNames, true)) {
                        $mode = $this->transformRangeModeNameToValue($mode);
                    }
                    $this->addRange(
                        (float)$buyersPremium[0],
                        (float)$buyersPremium[1],
                        (float)$buyersPremium[2],
                        $mode,
                        $creatorId,
                        $params
                    );
                }
            }
        }
    }

    /**
     * Update auction's or lotItem's BuyersPremiumRange
     * Works in 3 steps: update modified records, remove unused records, add new records.
     * It's faster than just 2 steps algorithm: remove old records, add new records.
     * @param array $newBuyersPremiums [[amount, fixed, percent, modeName]]
     * @param int $editorUserId
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @param int|null $accountId
     * @param string|null $auctionType
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     */
    public function update(
        array $newBuyersPremiums,
        int $editorUserId,
        ?int $auctionId = null,
        ?int $lotItemId = null,
        ?int $accountId = null,
        ?string $auctionType = null,
        ?int $userId = null,
        bool $isReadOnlyDb = false
    ): void {
        $oldBuyersPremiums = $this->createBuyersPremiumRangeLoader()->load(
            $auctionId,
            $lotItemId,
            $accountId,
            $auctionType,
            $userId,
            $isReadOnlyDb
        );

        // Exclude duplicate records
        foreach ($oldBuyersPremiums as $oldKey => $oldBuyersPremium) {
            foreach ($newBuyersPremiums as $newKey => $newBuyersPremium) {
                [$amount, $fixed, $percent, $mode] = $this->normalizeBpRow($newBuyersPremium);
                if (in_array($mode, Constants\BuyersPremium::$rangeModeNames, true)) {
                    $mode = $this->transformRangeModeNameToValue($mode);
                }

                $isAuctionBp = $auctionId && $oldBuyersPremium->AuctionId;
                $isLotItemBp = $lotItemId && $oldBuyersPremium->LotItemId;
                $isUserBp = $userId && $oldBuyersPremium->UserId;

                if (
                    (
                        $isAuctionBp
                        || $isLotItemBp
                        || $isUserBp
                    )
                    && $oldBuyersPremium->Mode === $mode
                    && Floating::eq($oldBuyersPremium->Amount, $amount)
                    && Floating::eq($oldBuyersPremium->Fixed, $fixed)
                    && Floating::eq($oldBuyersPremium->Percent, $percent)
                ) {
                    unset($oldBuyersPremiums[$oldKey], $newBuyersPremiums[$newKey]);
                }
                if (!$newBuyersPremium) {
                    unset($newBuyersPremiums[$newKey]);
                }
            }
        }

        /**
         * Order keys ascending
         * @var BuyersPremiumRange[] $oldBuyersPremiums
         */
        $oldBuyersPremiums = array_values($oldBuyersPremiums);

        // Update modified records
        $counter = 0;
        foreach ($newBuyersPremiums as $key => $newBuyersPremium) {
            if (isset($oldBuyersPremiums[$counter])) {
                [$amount, $fixed, $percent, $mode] = $this->normalizeBpRow($newBuyersPremium);
                if (in_array($mode, Constants\BuyersPremium::$rangeModeNames, true)) {
                    $mode = $this->transformRangeModeNameToValue($mode);
                }
                $oldBuyersPremiums[$counter]->AuctionId = $auctionId;
                $oldBuyersPremiums[$counter]->LotItemId = $lotItemId;
                $oldBuyersPremiums[$counter]->UserId = $userId;
                $oldBuyersPremiums[$counter]->AuctionType = (string)$auctionType;
                $oldBuyersPremiums[$counter]->AccountId = $accountId;
                $oldBuyersPremiums[$counter]->Amount = $amount;
                $oldBuyersPremiums[$counter]->Fixed = $fixed;
                $oldBuyersPremiums[$counter]->Percent = $percent;
                $oldBuyersPremiums[$counter]->Mode = $mode;
                $this->getBuyersPremiumRangeWriteRepository()->saveWithModifier($oldBuyersPremiums[$counter], $editorUserId);
                unset($newBuyersPremiums[$key], $oldBuyersPremiums[$counter]);
                $counter++;
            } else {
                break;
            }
        }

        // Remove unused records
        foreach ($oldBuyersPremiums as $oldBuyersPremium) {
            $this->getBuyersPremiumRangeWriteRepository()->deleteWithModifier($oldBuyersPremium, $editorUserId);
        }

        $this->create(
            $newBuyersPremiums,
            $editorUserId,
            $auctionId,
            $lotItemId,
            $accountId,
            $auctionType,
            $userId,
            $isReadOnlyDb
        );
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
        $mode = trim($bpRow[3]);
        return [$amount, $fixed, $percent, $mode];
    }

    /**
     * @param float $amount
     * @param float $fixed
     * @param float $percent
     * @param string $mode
     * @param int $creatorId user.id
     * @param int $lotItemId lot_item.id
     * @return BuyersPremiumRange $bpr
     */
    public function addInLotItemBp(
        float $amount,
        float $fixed,
        float $percent,
        string $mode,
        int $creatorId,
        int $lotItemId
    ): BuyersPremiumRange {
        return $this->addRange(
            $amount,
            $fixed,
            $percent,
            $mode,
            $creatorId,
            ['LotItemId' => $lotItemId]
        );
    }

    /**
     * @param float $amount
     * @param float $fixed
     * @param float $percent
     * @param string $mode
     * @param int $creatorId user.id
     * @param int $auctionId auction.id
     * @return BuyersPremiumRange $bpr
     */
    public function addInAuctionBp(
        float $amount,
        float $fixed,
        float $percent,
        string $mode,
        int $creatorId,
        int $auctionId
    ): BuyersPremiumRange {
        return $this->addRange(
            $amount,
            $fixed,
            $percent,
            $mode,
            $creatorId,
            ['AuctionId' => $auctionId]
        );
    }

    /**
     * @param float $amount
     * @param float $fixed
     * @param float $percent
     * @param string $mode
     * @param int $editorUserId user.id
     * @return BuyersPremiumRange
     */
    protected function addRange(
        float $amount,
        float $fixed,
        float $percent,
        string $mode,
        int $editorUserId
    ): BuyersPremiumRange {
        if (func_num_args() > 5) {
            $params = func_get_args();
        } else {
            $params = [];
        }

        $paramArray = $params[5] ?? [];

        $bpr = $this->createEntityFactory()->buyersPremiumRange();
        $bpr->Amount = $amount;
        $bpr->Fixed = $fixed;
        $bpr->Percent = $percent;
        $bpr->Mode = $mode;

        if (isset($paramArray['AccountId'])) {
            $bpr->AccountId = $paramArray['AccountId'];
        }
        if (isset($paramArray['AuctionType'])) {
            $bpr->AuctionType = (string)$paramArray['AuctionType'];
        }
        if (isset($paramArray['BpId'])) {
            $bpr->BuyersPremiumId = $paramArray['BpId'];
        }
        if (isset($paramArray['LotItemId'])) {
            $bpr->LotItemId = $paramArray['LotItemId'];
        }
        if (isset($paramArray['AuctionId'])) {
            $bpr->AuctionId = $paramArray['AuctionId'];
        }
        if (isset($paramArray['UserId'])) {
            $bpr->UserId = $paramArray['UserId'];
        }
        $this->getBuyersPremiumRangeWriteRepository()->saveWithModifier($bpr, $editorUserId);
        return $bpr;
    }

    /**
     * Get range mode value by name
     * @param string $name
     * @return string
     */
    protected function transformRangeModeNameToValue(string $name): string
    {
        return array_search($name, Constants\BuyersPremium::$rangeModeNames);
    }
}
