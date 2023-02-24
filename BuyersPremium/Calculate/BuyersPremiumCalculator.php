<?php
/**
 * SAM-10463: Refactor BP calculator for v3-7 and cover with unit tests
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          boanerge
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/23/2017
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Calculate;

use Sam\BuyersPremium\Calculate\Internal\DetectLevel\LevelDetectorCreateTrait;
use Sam\BuyersPremium\Calculate\Internal\Sliding\SlidingBpPureCalculator;
use Sam\BuyersPremium\Calculate\Internal\Tiered\TieredBpPureCalculator;
use Sam\BuyersPremium\Calculate\Internal\DetectLevel\LevelDetectionResult;
use Sam\BuyersPremium\Calculate\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class Calculator
 * @package Sam\BuyersPremium
 */
class BuyersPremiumCalculator extends CustomizableClass
{
    use DataProviderCreateTrait;
    use LevelDetectorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $lotItemId lot_item.id
     * @param int|null $auctionId auction.id
     * @param int|null $winningUserId
     * @param int|null $accountId account.id
     * @param float|null $newHp
     * @param bool $isReadOnlyDb
     * @return float $premium
     */
    public function calculate(
        int $lotItemId,
        ?int $auctionId,
        ?int $winningUserId,
        ?int $accountId = null,
        ?float $newHp = null,
        bool $isReadOnlyDb = false
    ): float {
        $result = $this->createLevelDetector()->detect($lotItemId, $auctionId, $winningUserId, $accountId, $isReadOnlyDb);
        if ($result->hasError()) {
            log_error('BP level detection failed - ' . $result->errorMessage());
            return 0.;
        }

        if ($newHp) {
            // Adjust existing Hammer Price from LotItem
            $result->setHammerPrice($newHp);
        }

        /**
         * Lot item named custom rule.
         */
        if ($result->isLotNameRule()) {
            return $this->calculateNamedBp($result, $isReadOnlyDb);
        }

        /**
         * Lot item's individual ranges table.
         */
        if ($result->isLotIndividualRule()) {
            return $this->calculateLotBp($result, $isReadOnlyDb);
        }

        /**
         * User custom named rule.
         */
        if ($result->isUserNameRule()) {
            return $this->calculateNamedBp($result, $isReadOnlyDb);
        }

        /**
         * User's individual ranges table.
         */
        if ($result->isUserIndividualRule()) {
            return $this->calculateBidderBp($result, $isReadOnlyDb);
        }

        /**
         * Auction custom named rule.
         */
        if ($result->isAuctionNameRule()) {
            return $this->calculateNamedBp($result, $isReadOnlyDb);
        }

        /**
         * Auction's individual ranges table.
         */
        if ($result->isAuctionIndividualRule()) {
            return $this->calculateAuctionBp($result, $isReadOnlyDb);
        }

        /**
         * Per account and auction type ranges table.
         */
        if ($result->isAccountAuctionTypeRule()) {
            return $this->calculateNamedBp($result, $isReadOnlyDb);
        }

        // It should not be possible to reach this line
        return 0.;
    }

    /**
     * @param LevelDetectionResult $result
     * @param bool $isReadOnlyDb
     * @return float $premium
     */
    protected function calculateNamedBp(LevelDetectionResult $result, bool $isReadOnlyDb = false): float
    {
        $addPercent = $result->addPercent;
        $amount = (float)$result->hammerPrice;
        $buyersPremiumId = $result->bpId;
        $rangeCalculation = $result->rangeCalculation;

        $dataProvider = $this->createDataProvider();
        if ($rangeCalculation === Constants\BuyersPremium::RANGE_CALC_CUMULATIVE_TIERED) {
            $bpRanges = $dataProvider->loadAllBpRangesForNamedRule($buyersPremiumId, $amount, $isReadOnlyDb);
            $premium = TieredBpPureCalculator::new()->calculate($bpRanges, $amount, $addPercent);
            $logData = array_merge(['result' => $premium], $result->logData());
            log_debug($result->successMessage() . composeSuffix($logData));
            return $premium;
        }

        $bpRange = $dataProvider->loadFirstBpRangesForNamedRule($buyersPremiumId, $amount, $isReadOnlyDb);
        $premium = SlidingBpPureCalculator::new()->calculate($bpRange, $amount, $addPercent);
        $logData = array_merge(['result' => $premium], $result->logData());
        log_debug($result->successMessage() . composeSuffix($logData));
        return $premium;
    }

    /**
     * @param LevelDetectionResult $result
     * @param bool $isReadOnlyDb
     * @return float
     */
    protected function calculateLotBp(LevelDetectionResult $result, bool $isReadOnlyDb = false): float
    {
        $addPercent = $result->addPercent;
        $amount = (float)$result->hammerPrice;
        $lotItemId = $result->lotItemId;
        $rangeCalculation = $result->rangeCalculation;

        $dataProvider = $this->createDataProvider();
        if ($rangeCalculation === Constants\BuyersPremium::RANGE_CALC_CUMULATIVE_TIERED) {
            $bpRanges = $dataProvider->loadAllBpRangesForLot($lotItemId, $amount, $isReadOnlyDb);
            $premium = TieredBpPureCalculator::new()->calculate($bpRanges, $amount, $addPercent);
            $logData = array_merge(['result' => $premium], $result->logData());
            log_debug($result->successMessage() . composeSuffix($logData));
            return $premium;
        }

        $bpRange = $dataProvider->loadFirstBpRangeForLot($lotItemId, $amount, $isReadOnlyDb);
        $premium = SlidingBpPureCalculator::new()->calculate($bpRange, $amount, $addPercent);
        $logData = array_merge(['result' => $premium], $result->logData());
        log_debug($result->successMessage() . composeSuffix($logData));
        return $premium;
    }

    /**
     * @param LevelDetectionResult $result
     * @param bool $isReadOnlyDb
     * @return float
     */
    protected function calculateBidderBp(LevelDetectionResult $result, bool $isReadOnlyDb = false): float
    {
        $userAccountId = $result->userAccountId;
        $addPercent = $result->addPercent;
        $amount = (float)$result->hammerPrice;
        $auctionType = $result->auctionType;
        $rangeCalculation = $result->rangeCalculation;
        $userId = $result->winningBidderUserId;

        $dataProvider = $this->createDataProvider();
        if ($rangeCalculation === Constants\BuyersPremium::RANGE_CALC_CUMULATIVE_TIERED) {
            $bpRanges = $dataProvider->loadAllBpRangesForUser($userId, $userAccountId, $auctionType, $amount, $isReadOnlyDb);
            $premium = TieredBpPureCalculator::new()->calculate($bpRanges, $amount, $addPercent);
            $logData = array_merge(['result' => $premium], $result->logData());
            log_debug($result->successMessage() . composeSuffix($logData));
            return $premium;
        }

        $bpRange = $dataProvider->loadFirstBpRangeForUser($userId, $userAccountId, $auctionType, $amount, $isReadOnlyDb);
        $premium = SlidingBpPureCalculator::new()->calculate($bpRange, $amount, $addPercent);
        $logData = array_merge(['result' => $premium], $result->logData());
        log_debug($result->successMessage() . composeSuffix($logData));
        return $premium;
    }

    /**
     * @param LevelDetectionResult $result
     * @param bool $isReadOnlyDb
     * @return float
     */
    protected function calculateAuctionBp(LevelDetectionResult $result, bool $isReadOnlyDb = false): float
    {
        $addPercent = $result->addPercent;
        $amount = (float)$result->hammerPrice;
        $auctionId = $result->auctionId;
        $rangeCalculation = $result->rangeCalculation;

        $dataProvider = $this->createDataProvider();
        if ($rangeCalculation === Constants\BuyersPremium::RANGE_CALC_CUMULATIVE_TIERED) {
            $bpRanges = $dataProvider->loadAllBpRangesForAuction($auctionId, $amount, $isReadOnlyDb);
            $premium = TieredBpPureCalculator::new()->calculate($bpRanges, $amount, $addPercent);
            $logData = array_merge(['result' => $premium], $result->logData());
            log_debug($result->successMessage() . composeSuffix($logData));
            return $premium;
        }

        $bpRange = $dataProvider->loadFirstBpRangeForAuction($auctionId, $amount, $isReadOnlyDb);
        $premium = SlidingBpPureCalculator::new()->calculate($bpRange, $amount, $addPercent);
        $logData = array_merge(['result' => $premium], $result->logData());
        log_debug($result->successMessage() . composeSuffix($logData));
        return $premium;
    }

}
