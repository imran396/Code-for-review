<?php
/**
 * Create \Auction instance
 *
 * SAM-3381: Implement Sam\Auction\Factory
 * https://bidpath.atlassian.net/browse/SAM-3381
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 2, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Init;

use Auction;
use InvalidArgumentException;
use Sam\BuyersPremium\Load\BuyersPremiumLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settings\TermsAndConditionsManagerAwareTrait;

/**
 * Auction managing tools
 *
 * @package com.swb.sam2
 */
class AuctionMakerDefaultsInitializer extends CustomizableClass
{
    use BuyersPremiumLoaderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use SettingsManagerAwareTrait;
    use TermsAndConditionsManagerAwareTrait;

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
        return $this;
    }

    /**
     * @param int $accountId
     * @param string $auctionType '' - to create Auction entity without auction type (when uploading lots via inventory)
     * @return Auction
     */
    public function create(int $accountId, string $auctionType = ''): Auction
    {
        if (!$accountId) {
            throw new InvalidArgumentException("Account id is invalid");
        }

        $auction = $this->createEntityFactory()->auction();
        // AccountId and AuctionType must be applied first (temporal coupling)
        $auction->AccountId = $accountId;
        $auction = $this->applyAuctionType($auction, $auctionType);
        // Initialize default values
        $auction = $this->applyBasicFields($auction);
        $auction = $this->applyAuctionParameters($auction);
        $auction = $this->applyBpRangeCalculation($auction);
        $auction = $this->applyShippingInfo($auction);
        return $auction;
    }

    /**
     * @param Auction $auction
     * @param string $auctionType
     * @return Auction
     */
    protected function applyAuctionType(Auction $auction, string $auctionType): Auction
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isTimed($auctionType)) {
            $auction->toTimed();
        } elseif ($auctionStatusPureChecker->isLive($auctionType)) {
            $auction->toLive();
        } elseif ($auctionStatusPureChecker->isHybrid($auctionType)) {
            $auction->toHybrid();
        }
        return $auction;
    }

    /**
     * Apply to auction properties general default values
     * @param Auction $auction
     * @return Auction
     */
    protected function applyBasicFields(Auction $auction): Auction
    {
        $auction->AuthorizationAmount = null;
        $auction->AutoPopulateEmptyLotNum = true;
        $auction->AutoPopulateLotFromCategory = false;
        $auction->BiddingPaused = false;
        $auction->DateAssignmentStrategy = Auction::DATE_ASSIGNMENT_STRATEGY_DEFAULT;
        $auction->DefaultLotPeriod = 0;
        $auction->Email = '';
        $auction->EndDate = null;
        $auction->EventId = '';
        $auction->ExtendAll = false;
        $auction->ExtendTime = null;
        $auction->LotOrderPrimaryType = Constants\Auction::LOT_ORDER_BY_LOT_NUMBER;
        $auction->LotOrderQuaternaryType = Constants\Auction::LOT_ORDER_BY_NONE;
        $auction->LotOrderSecondaryType = Constants\Auction::LOT_ORDER_BY_NONE;
        $auction->LotOrderTertiaryType = Constants\Auction::LOT_ORDER_BY_NONE;
        $auction->LotStartGapTime = 0;
        $auction->LotsPerInterval = 1;
        $auction->NotShowUpcomingLots = false;
        $auction->NotifyXLots = 5;
        $auction->NotifyXMinutes = 10;
        $auction->OnlyOngoingLots = false;
        $auction->PaymentTrackingCode = '';
        $auction->RequireLotChangeConfirmation = false;
        $auction->Reverse = false;
        $auction->StaggerClosing = 0;
        $auction->StreamDisplay = Auction::STREAM_DISPLAY_DEFAULT;
        $auction->TextMsgNotification = Constants\Auction::SMS_NOTIFICATION_TEXT;
        $auction->TimezoneId = null;
        $auction->toActive();
        $auction = $this->applySimpleClerking($auction);
        return $auction;
    }

    protected function applySimpleClerking(Auction $auction): Auction
    {
        if ($auction->isLive()) {
            $clerkingStyleDefault = $this->cfg()->get('core->auction->clerkingStyle->default');
            $isSimpleClerking = AuctionStatusPureChecker::new()->isSimpleClerking($clerkingStyleDefault);
            $isSimpleClerking
                ? $auction->toSimpleClerking()
                : $auction->toAdvancedClerking();
        }
        if ($auction->isHybrid()) {
            $auction->toSimpleClerking();
        }
        return $auction;
    }

    /**
     * Apply to auction defaults from account level of AuctionParameters
     * @param Auction $auction
     * @return Auction
     */
    protected function applyAuctionParameters(Auction $auction): Auction
    {
        $accountId = $auction->AccountId;
        $sm = $this->getSettingsManager();
        $auction->AllowForceBid = (bool)$sm->get(Constants\Setting::ALLOW_FORCE_BID, $accountId);
        $auction->AuctionCatalogAccess = (string)$sm->get(Constants\Setting::AUCTION_CATALOG_ACCESS, $accountId);
        $auction->AuctionHeldIn = $sm->get(Constants\Setting::DEFAULT_COUNTRY, $accountId) ?: Constants\Country::C_USA;
        $auction->AuctionInfoAccess = (string)$sm->get(Constants\Setting::AUCTION_INFO_ACCESS, $accountId);
        $auction->AuctionVisibilityAccess = (string)$sm->get(Constants\Setting::AUCTION_VISIBILITY_ACCESS, $accountId);
        $auction->BlacklistPhrase = (string)$sm->get(Constants\Setting::BLACKLIST_PHRASE, $accountId);
        $auction->Currency = (int)$sm->get(Constants\Setting::PRIMARY_CURRENCY_ID, $accountId);
        $auction->HideUnsoldLots = (bool)$sm->get(Constants\Setting::HIDE_UNSOLD_LOTS, $accountId);
        $auction->InvoiceNotes = $sm->get(Constants\Setting::DEFAULT_INVOICE_NOTES, $accountId);
        $auction->LiveViewAccess = (string)$sm->get(Constants\Setting::LIVE_VIEW_ACCESS, $accountId);
        $auction->LotBiddingHistoryAccess = (string)$sm->get(Constants\Setting::LOT_BIDDING_HISTORY_ACCESS, $accountId);
        $auction->LotBiddingInfoAccess = (string)$sm->get(Constants\Setting::LOT_BIDDING_INFO_ACCESS, $accountId);
        $auction->LotDetailsAccess = (string)$sm->get(Constants\Setting::LOT_DETAILS_ACCESS, $accountId);
        $auction->LotStartingBidAccess = (string)$sm->get(Constants\Setting::LOT_STARTING_BID_ACCESS, $accountId);
        $auction->LotWinningBidAccess = (string)$sm->get(Constants\Setting::LOT_WINNING_BID_ACCESS, $accountId);
        $auction->NextBidButton = (bool)$sm->get(Constants\Setting::NEXT_BID_BUTTON, $accountId);
        $auction->PostAucImportPremium = $sm->get(Constants\Setting::DEFAULT_POST_AUC_IMPORT_PREMIUM, $accountId);
        $auction->ReserveNotMetNotice = (bool)$sm->get(Constants\Setting::RESERVE_NOT_MET_NOTICE, $accountId);
        $auction->ReserveMetNotice = (bool)$sm->get(Constants\Setting::RESERVE_MET_NOTICE, $accountId);
        $auction->TaxDefaultCountry = (string)$sm->get(Constants\Setting::SAM_TAX_DEFAULT_COUNTRY, $accountId);

        if ($auction->isTimed()) {
            $auction->TakeMaxBidsUnderReserve = (bool)$sm->get(Constants\Setting::TAKE_MAX_BIDS_UNDER_RESERVE, $accountId);
        }
        if ($auction->isLiveOrHybrid()) {
            $auction->AboveReserve = (bool)$sm->get(Constants\Setting::ABOVE_RESERVE, $accountId);
            $auction->AboveStartingBid = (bool)$sm->get(Constants\Setting::ABOVE_STARTING_BID, $accountId);
            $auction->AbsenteeBidsDisplay = $sm->get(Constants\Setting::ABSENTEE_BIDS_DISPLAY, $accountId);
            $auction->NoLowerMaxbid = (bool)$sm->get(Constants\Setting::NO_LOWER_MAXBID, $accountId);
            $auction->NotifyAbsenteeBidders = (bool)$sm->get(Constants\Setting::NOTIFY_ABSENTEE_BIDDERS, $accountId);
            $auction->SuggestedStartingBid = (bool)$sm->get(Constants\Setting::SUGGESTED_STARTING_BID, $accountId);
        }
        if ($auction->isHybrid()) {
            $auction->AllowBiddingDuringStartGap = (bool)$sm->get(Constants\Setting::ALLOW_BIDDING_DURING_START_GAP_HYBRID, $accountId);
            $auction->ExtendTime = (int)$sm->get(Constants\Setting::EXTEND_TIME_HYBRID, $accountId);
            $auction->LotStartGapTime = (int)$sm->get(Constants\Setting::LOT_START_GAP_TIME_HYBRID, $accountId);
        }
        return $auction;
    }

    /**
     * @param Auction $auction
     * @return Auction
     */
    protected function applyBpRangeCalculation(Auction $auction): Auction
    {
        $bpLoader = $this->createBuyersPremiumLoader();
        if ($auction->isTimed()) {
            $bpGlobal = $bpLoader->loadTimed($auction->AccountId);
        } elseif ($auction->isLive()) {
            $bpGlobal = $bpLoader->loadLive($auction->AccountId);
        } elseif ($auction->isHybrid()) {
            $bpGlobal = $bpLoader->loadHybrid($auction->AccountId);
        }
        $auction->BpRangeCalculation = $bpGlobal->RangeCalculation
            ?? Auction::BP_RANGE_CALCULATION_DEFAULT;
        return $auction;
    }

    /**
     * @param Auction $auction
     * @return Auction
     */
    protected function applyShippingInfo(Auction $auction): Auction
    {
        $auction->ShippingInfo = $this->getTermsAndConditionsManager()->loadContent(
            $auction->AccountId,
            Constants\TermsAndConditions::SHIPPING,
            true
        );
        return $auction;
    }
}
