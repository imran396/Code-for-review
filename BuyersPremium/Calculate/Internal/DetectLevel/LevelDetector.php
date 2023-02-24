<?php
/**
 * SAM-10463: Refactor BP calculator for v3-7 and cover with unit tests
 * SAM-3382: Multiple Buyers Premium rates
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

namespace Sam\BuyersPremium\Calculate\Internal\DetectLevel;

use Auction;
use Bidder;
use BuyersPremium;
use LotItem;
use Sam\BuyersPremium\Calculate\Internal\DetectLevel\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\BuyersPremium\Calculate\Internal\DetectLevel\LevelDetectionResult as Result;

/**
 * Class Calculator
 * @package Sam\BuyersPremium
 */
class LevelDetector extends CustomizableClass
{
    use DataProviderCreateTrait;

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
     * @param int|null $winningBidderUserId
     * @param int|null $accountId account.id
     * @param bool $isReadOnlyDb
     * @return Result
     */
    public function detect(
        int $lotItemId,
        ?int $auctionId,
        ?int $winningBidderUserId,
        ?int $accountId = null,
        bool $isReadOnlyDb = false,
    ): Result {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();
        $lotItem = $dataProvider->loadLotItem($lotItemId, $isReadOnlyDb);
        if (!$lotItem) {
            return $result->addErrorWithAppendedMessage(
                Result::ERR_CANNOT_LOAD_LOT_ITEM,
                composeSuffix(['li' => $lotItemId, 'a' => $auctionId])
            );
        }

        $auction = $dataProvider->loadAuction($auctionId, $isReadOnlyDb);
        if (!$auction) {
            return $result->addErrorWithAppendedMessage(
                Result::ERR_CANNOT_LOAD_AUCTION,
                composeSuffix(['li' => $lotItemId, 'a' => $auctionId])
            );
        }

        $bidder = $winningBidderUserId
            ? $dataProvider->loadBidder($winningBidderUserId, $isReadOnlyDb)
            : null;
        $userDirectAccountId = $bidder
            ? $dataProvider->loadUserDirectAccountId($bidder->UserId, $isReadOnlyDb)
            : null;

        $result
            ->setLotItemId($lotItem->Id)
            ->setHammerPrice($lotItem->HammerPrice)
            ->setAuctionId($auction->Id)
            ->setAuctionType($auction->AuctionType)
            ->setAuctionAccountId($auction->AccountId)
            ->setWinningBidderUserId($bidder->UserId ?? null)
            ->setUserAccountId($userDirectAccountId);

        $isLiveOrHybrid = $auction->isLiveOrHybrid();

        /**
         * Lot item named custom rule.
         */
        if ($lotItem->hasNamedBuyersPremium()) {
            $bp = $dataProvider->loadBuyersPremiumForLot($lotItem->BuyersPremiumId, $isReadOnlyDb);
            if ($bp) {
                return $this
                    ->fillNamedRuleResult($bp, $lotItem, $result)
                    ->addSuccess(Result::OK_LOT_NAMED_RULE);
            }
            return $result->addError(Result::ERR_CANNOT_LOAD_LOT_NAMED_RULE);
        }

        /**
         * Lot item's individual ranges table.
         */
        $hasLotIndividualBp = $dataProvider->hasIndividualBpForLot($lotItem, $isLiveOrHybrid, $isReadOnlyDb);
        if ($hasLotIndividualBp) {
            return $this
                ->fillIndividualForLot($lotItem, $result)
                ->addSuccess(Result::OK_LOT_INDIVIDUAL_RULE);
        }

        /**
         * User custom named rule.
         */
        if (
            $bidder
            && $bidder->hasNamedBuyersPremium()
        ) {
            $bp = $dataProvider->loadBuyersPremiumForUser($bidder->BuyersPremiumId, $isReadOnlyDb);
            if ($bp) {
                return $this
                    ->fillNamedRuleResult($bp, $lotItem, $result)
                    ->addSuccess(Result::OK_USER_NAMED_RULE);
            }
            return $result->addError(Result::ERR_CANNOT_LOAD_USER_NAMED_RULE);
        }

        /**
         * User's individual ranges table.
         */
        $hasUserIndividualBp = $dataProvider->hasIndividualBpForUser($bidder, $userDirectAccountId, $auction->AuctionType, $isReadOnlyDb);
        if (
            $bidder
            && $hasUserIndividualBp
        ) {
            return $this
                ->fillIndividualForUser($lotItem, $auction, $bidder, $result)
                ->addSuccess(Result::OK_USER_INDIVIDUAL_RULE);
        }

        /**
         * Auction custom named rule.
         */
        if ($auction->hasNamedBuyersPremium()) {
            $bp = $dataProvider->loadBuyersPremiumForAuction($auction->BuyersPremiumId, $isReadOnlyDb);
            if ($bp) {
                return $this
                    ->fillNamedRuleResult($bp, $lotItem, $result)
                    ->addSuccess(Result::OK_AUCTION_NAMED_RULE);
            }
            return $result->addError(Result::ERR_CANNOT_LOAD_AUCTION_NAMED_RULE);
        }

        /**
         * Auction's individual ranges table.
         */
        $hasAuctionIndividualBp = $dataProvider->hasIndividualBpForAuction($auction, $isReadOnlyDb);
        if ($hasAuctionIndividualBp) {
            return $this
                ->fillIndividualForAuction($lotItem, $auction, $result)
                ->addSuccess(Result::OK_AUCTION_INDIVIDUAL_RULE);
        }

        /**
         * Per account and auction type ranges table.
         */
        $bp = $dataProvider->loadBuyersPremiumForAuctionType(
            $auction->AuctionType,
            $accountId ?: $lotItem->AccountId,
            $isReadOnlyDb
        );
        if ($bp) {
            return $this
                ->fillNamedRuleResult($bp, $lotItem, $result)
                ->addSuccess(Result::OK_ACCOUNT_AUCTION_TYPE_RULE);
        }

        return $result->addError(Result::ERR_NOT_FOUND_ANY_RULE);
    }

    /**
     * Add BuyersPremium, range calculation, additional percent.
     */
    protected function fillNamedRuleResult(BuyersPremium $bp, LotItem $lotItem, Result $result): Result
    {
        $addPercent = $lotItem->InternetBid ? (float)$bp->AdditionalBpInternet : 0.;
        $rangeCalculation = $bp->RangeCalculation;
        return $result
            ->setAddPercent($addPercent)
            ->setBpId($bp->Id)
            ->setBpName($bp->Name)
            ->setRangeCalculation($rangeCalculation);
    }

    /**
     * Add range calculation, additional percent.
     */
    protected function fillIndividualForLot(
        LotItem $lotItem,
        Result $result
    ): Result {
        $addPercent = $lotItem->InternetBid ? (float)$lotItem->AdditionalBpInternet : 0.;
        $rangeCalculation = $lotItem->BpRangeCalculation;
        return $result
            ->setAddPercent($addPercent)
            ->setRangeCalculation($rangeCalculation);
    }

    /**
     * Add range calculation, additional percent, user's direct account id.
     */
    protected function fillIndividualForUser(
        LotItem $lotItem,
        Auction $auction,
        Bidder $bidder,
        Result $result
    ): Result {
        $rangeCalculation = BuyersPremium::RANGE_CALCULATION_DEFAULT;
        $addPercent = 0.;
        if ($auction->isLive()) {
            $rangeCalculation = $bidder->BpRangeCalculationLive;
            $addPercent = $lotItem->InternetBid ? (float)$bidder->AdditionalBpInternetLive : 0.;
        } elseif ($auction->isTimed()) {
            $rangeCalculation = $bidder->BpRangeCalculationTimed;
        } elseif ($auction->isHybrid()) {
            $rangeCalculation = $bidder->BpRangeCalculationHybrid;
            $addPercent = $lotItem->InternetBid ? (float)$bidder->AdditionalBpInternetHybrid : 0.;
        }
        return $result
            ->setAddPercent($addPercent)
            ->setRangeCalculation($rangeCalculation);
    }

    /**
     * Add range calculation, additional percent.
     */
    protected function fillIndividualForAuction(
        LotItem $lotItem,
        Auction $auction,
        Result $result
    ): Result {
        $rangeCalculation = $auction->BpRangeCalculation;
        $addPercent = $lotItem->InternetBid ? (float)$auction->AdditionalBpInternet : 0.;
        return $result
            ->setAddPercent($addPercent)
            ->setRangeCalculation($rangeCalculation);
    }
}
