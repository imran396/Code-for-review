<?php

/**
 * All states that can change a view of a form.
 * @see https://bidpath.atlassian.net/browse/SAM-3241 Refactor Live and Timed Lot Details pages of responsive side
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Maxim Lyubetskiy
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       Jun 15, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform;

use DateTime;
use InvalidArgumentException;
use Sam\Auction\Validate\AuctionStatusCheckerAwareTrait;
use Sam\AuctionLot\Agreement\ChangesAgreement;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Bidder\BidderTerms\BidderTermsAgreementManagerAwareTrait;
use Sam\Core\Bidding\BuyNow\BuyNowAvailabilityLiveCheckerCreateTrait;
use Sam\Core\Bidding\BuyNow\BuyNowAvailabilityTimedCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\BuyerGroup\Access\LotBuyerGroupAccessHelperAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settings\TermsAndConditionsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class AvailableStateDetector
 */
class AvailableStateDetector extends CustomizableClass
{
    use AccountAwareTrait;
    use AuctionAwareTrait;
    use AuctionBidderCheckerAwareTrait;
    use AuctionStatusCheckerAwareTrait;
    use BidderTermsAgreementManagerAwareTrait;
    use BuyNowAvailabilityLiveCheckerCreateTrait;
    use BuyNowAvailabilityTimedCheckerCreateTrait;
    use EditorUserAwareTrait;
    use LotBuyerGroupAccessHelperAwareTrait;
    use SettingsManagerAwareTrait;
    use TermsAndConditionsManagerAwareTrait;
    use UserLoaderAwareTrait;

    public const SIGNIN = 1;
    public const EMAIL_VERIFICATION = 2;
    public const AUCTION_REGISTRATION = 3;
    public const AUCTION_APPROVAL = 4;
    public const BIDDING_PAUSED = 5;
    public const SPECIAL_TERMS_APPROVAL = 6;
    public const LOT_CHANGES_APPROVAL = 7;
    public const RESTRICTED_BUYER_GROUP = 8;
    public const AUCTION_CLOSED = 9;
    public const ABSENTEE_BID = 10;
    public const BUY_NOW = 11;
    public const BIDS_LIMIT_REACHED = 12;
    public const REGULAR_BID = 13;
    // const NEXT_BID = 9;
    // const NEXT_BID_ENABLED = 8;
    // const NEXT_BID_DISABLED = 9;

    private array $stateLabels = [
        self::SIGNIN => 'signin',
        self::EMAIL_VERIFICATION => 'email verification',
        self::AUCTION_REGISTRATION => 'auction registration',
        self::AUCTION_APPROVAL => 'auction approval',
        self::BIDDING_PAUSED => 'bidding paused',
        self::SPECIAL_TERMS_APPROVAL => 'special terms approval',
        self::LOT_CHANGES_APPROVAL => 'lot changes approval',
        self::RESTRICTED_BUYER_GROUP => 'restricted buyer group',
        self::AUCTION_CLOSED => 'auction closed',
        self::ABSENTEE_BID => 'absentee bid',
        self::BUY_NOW => 'buy now',
    ];

    /** @var int[] */
    private array $states = [];
    private ?ChangesAgreement $changesAgreementManager = null;
    private int $lotItemId = 0;

    /**
     * RtbCurrent->LotItemId if exists
     */
    private int $rtbCurrentLotId = 0;

    /**
     * \Auction->AuctionStatusId
     */
    private int $auctionStatusId = Constants\Auction::AS_NONE;

    /**
     * \AuctionLotItem->LotStatusId
     */
    private int $lotStatusId = Constants\Lot::LS_UNASSIGNED;

    private ?bool $isBuyNowAvailable = null;
    private string $auctionType = '';

    /**
     * \AuctionLotItem->BuyNowAmount
     */
    private ?float $buyNowAmount = null;

    /**
     * \AuctionLotItemCache->CurrentBid
     */
    private ?float $currentBid = null;

    /**
     * \Auction->StartDate
     */
    private ?DateTime $startDateUtc = null;

    /**
     * \Auction->EndDate
     */
    private ?DateTime $endDateUtc = null;

    /**
     * setting_auction.buy_now_unsold
     */
    private ?bool $isBuyNowUnsold = null;

    /**
     * setting_rtb.buy_now_restriction
     */
    private ?string $buyNowRestriction = null;

    /**
     * \Lot_Factory::GetCurrentBid result
     */
    private ?float $transactionCurrentBid = null;

    /**
     * \Auction->ListingOnly
     */
    private bool $isAuctionListingOnly = false;

    /**
     * \AuctionLotItem->ListingOnly
     */
    private bool $isAuctionLotListingOnly = false;

    /**
     * Result of \Sam\User\Flag\UserFlagging::getFlag()
     */
    private int $userFlag = Constants\User::FLAG_DEF;

    private string $lotChanges = '';
    private bool $isAuctionRequireLotChangeConfirmation = false;
    private ?bool $isUserEmailVerified = null;
    private bool $isEmailVerificationRequired = false;
    private ?bool $isAuctionRegistered = null;
    private ?bool $isAuctionApproved = null;
    private ?bool $isBiddingPaused = null;
    private ?bool $isSpecialTermsApprovalRequired = null;
    private ?bool $isChangesAgreementAcceptanceRequired = null;
    private ?bool $isRestrictedGroup = null;
    private ?bool $isAuctionClosed = null;
    private ?float $askingBid = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int[]
     */
    public function getStates(): array
    {
        $this->states = [];
        $isAuctionClosed = $this->isAuctionClosed();
        if ($isAuctionClosed) {
            $this->states[] = self::AUCTION_CLOSED;
        }
        if (!$this->getEditorUserId()) {
            $this->states[] = self::SIGNIN;
        } elseif ($this->detectEmailVerification()) {
            $this->states[] = self::EMAIL_VERIFICATION;
        } elseif (!$this->isAuctionRegistered()) {
            $this->states[] = self::AUCTION_REGISTRATION;
        } elseif (!$this->isAuctionApproved()) {
            $this->states[] = self::AUCTION_APPROVAL;
        } elseif ($this->detectBiddingPaused()) {
            $this->states[] = self::BIDDING_PAUSED;
        } elseif ($this->detectSpecialTermsApprovalRequired()) {
            $this->states[] = self::SPECIAL_TERMS_APPROVAL;
        } elseif ($this->detectChangesAgreementAcceptanceRequired()) {
            $this->states[] = self::LOT_CHANGES_APPROVAL;
        } elseif ($this->detectRestrictedGroup()) {
            $this->states[] = self::RESTRICTED_BUYER_GROUP;
        } else {
            $auctionStatusPureChecker = AuctionStatusPureChecker::new();
            if ($auctionStatusPureChecker->isTimed($this->getAuctionType())) {
                $this->setStateForTimedAuction();
            } elseif ($auctionStatusPureChecker->isLiveOrHybrid($this->getAuctionType())) {
                $this->setStateForLiveAuction();
            }
        }

        if ($this->detectBuyNow()) {
            $this->states[] = self::BUY_NOW;
        }

        log_trace(
            'Available states detected'
            . composeSuffix(['a' => $this->getAuctionId(), 'li' => $this->getLotItemId(), 'u' => $this->getEditorUserId()])
            . ' : ' . implode(', ', $this->getStateLabels())
        );

        return $this->states;
    }

    /**
     * @return array of states in readable view
     */
    public function getStateLabels(): array
    {
        $labels = array_intersect_key($this->stateLabels, array_flip($this->states));
        return $labels;
    }

    /**
     * @return static
     */
    protected function setStateForLiveAuction(): static
    {
        $this->states[] = self::ABSENTEE_BID;
        return $this;
    }

    /**
     * @return static
     */
    protected function setStateForTimedAuction(): static
    {
        $this->states[] = self::REGULAR_BID;
        return $this;
    }

    //<editor-fold desc="State Detectors">

    /**
     * @return bool
     */
    protected function detectEmailVerification(): bool
    {
        $isState = $this->isEmailVerificationRequired()
            && !$this->isUserEmailVerified();
        return $isState;
    }

    /**
     * @return bool
     */
    protected function detectBiddingPaused(): bool
    {
        return $this->isBiddingPaused();
    }

    /**
     * @return bool
     */
    protected function detectSpecialTermsApprovalRequired(): bool
    {
        return $this->isSpecialTermsApprovalRequired();
    }

    /**
     * @return bool
     */
    protected function detectChangesAgreementAcceptanceRequired(): bool
    {
        return $this->isChangesAgreementAcceptanceRequired();
    }

    /**
     * @return bool
     */
    protected function detectRestrictedGroup(): bool
    {
        return $this->isRestrictedGroup();
    }

    /**
     * @return bool
     */
    public function isBiddingPaused(): ?bool
    {
        if ($this->isBiddingPaused === null) {
            throw new InvalidArgumentException(__METHOD__ . " BiddingPaused is invalid. Null given");
        }
        return $this->isBiddingPaused;
    }

    /**
     * @param bool $biddingPaused
     * @return static
     */
    public function enableBiddingPaused(bool $biddingPaused): static
    {
        $this->isBiddingPaused = $biddingPaused;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAuctionApproved(): bool
    {
        if ($this->isAuctionApproved === null) {
            if (
                $this->getAuctionId()
                && $this->getEditorUserId()
            ) {
                $this->isAuctionApproved = $this->getAuctionBidderChecker()
                    ->isAuctionApproved($this->getEditorUserId(), $this->getAuctionId());
            } else {
                $this->isAuctionApproved = false;
            }
        }
        return $this->isAuctionApproved;
    }

    /**
     * @param bool $auctionApproved
     * @return static
     */
    public function enableAuctionApproved(bool $auctionApproved): static
    {
        $this->isAuctionApproved = $auctionApproved;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUserEmailVerified(): bool
    {
        if ($this->isUserEmailVerified === null) {
            $this->isUserEmailVerified = $this->getEditorUserAuthenticationOrCreate()->EmailVerified;
        }
        return $this->isUserEmailVerified;
    }

    /**
     * @param bool $userEmailVerified
     * @return static
     */
    public function enableUserEmailVerified(bool $userEmailVerified): static
    {
        $this->isUserEmailVerified = $userEmailVerified;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEmailVerificationRequired(): bool
    {
        return $this->isEmailVerificationRequired;
    }

    /**
     * @param bool $emailVerificationRequired
     * @return static
     */
    public function enableEmailVerificationRequired(bool $emailVerificationRequired): static
    {
        $this->isEmailVerificationRequired = $emailVerificationRequired;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAuctionRegistered(): bool
    {
        if ($this->isAuctionRegistered === null) {
            $this->isAuctionRegistered = $this->getAuctionBidderChecker()
                ->isAuctionRegistered($this->getEditorUserId(), $this->getAuctionId());
        }
        return $this->isAuctionRegistered;
    }

    /**
     * @param bool $auctionRegistered
     * @return static
     */
    public function enableAuctionRegistered(bool $auctionRegistered): static
    {
        $this->isAuctionRegistered = $auctionRegistered;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSpecialTermsApprovalRequired(): bool
    {
        if ($this->isSpecialTermsApprovalRequired === null) {
            $aucLotTerms = $this->getTermsAndConditionsManager()->loadForAuctionLot(
                $this->getLotItemId(),
                $this->getAuctionId()
            );

            $hasAgreement = $this->getBidderTermsAgreementManager()->has(
                $this->getEditorUserId(),
                $this->getLotItemId(),
                $this->getAuctionId()
            );
            $this->isSpecialTermsApprovalRequired = $aucLotTerms && !$hasAgreement;
        }

        return $this->isSpecialTermsApprovalRequired;
    }

    /**
     * @param bool $specialTermsApprovalRequired
     * @return static
     */
    public function enableSpecialTermsApprovalRequired(bool $specialTermsApprovalRequired): static
    {
        $this->isSpecialTermsApprovalRequired = $specialTermsApprovalRequired;
        return $this;
    }

    /**
     * @return bool
     */
    public function isChangesAgreementAcceptanceRequired(): bool
    {
        if ($this->isChangesAgreementAcceptanceRequired === null) {
            $isRequired = $this->getChangesAgreementManager()->isRequired(
                $this->isAuctionRequireLotChangeConfirmation(),
                $this->getLotChanges()
            );

            $isAccepted = $this->getChangesAgreementManager()->isAccepted(
                $this->getEditorUserId(),
                $this->getLotItemId(),
                $this->getAuctionId()
            );

            $this->isChangesAgreementAcceptanceRequired = $isRequired && !$isAccepted;
        }

        return $this->isChangesAgreementAcceptanceRequired;
    }

    /**
     * @param bool $changesAgreementAcceptanceRequired
     * @return static
     */
    public function enableChangesAgreementAcceptanceRequired(bool $changesAgreementAcceptanceRequired): static
    {
        $this->isChangesAgreementAcceptanceRequired = $changesAgreementAcceptanceRequired;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRestrictedGroup(): bool
    {
        if ($this->isRestrictedGroup === null) {
            $this->isRestrictedGroup = $this->getLotBuyerGroupAccessHelper()
                ->isRestrictedBuyerGroup($this->getEditorUserId(), $this->getLotItemId());
        }
        return $this->isRestrictedGroup;
    }

    /**
     * @param bool $restrictedGroup
     * @return static
     */
    public function enableRestrictedGroup(bool $restrictedGroup): static
    {
        $this->isRestrictedGroup = $restrictedGroup;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAuctionClosed(): bool
    {
        if ($this->isAuctionClosed === null) {
            $this->isAuctionClosed = $this->getAuctionStatusChecker()->isClosed($this->getAuctionId());
        }
        return $this->isAuctionClosed;
    }

    /**
     * @param bool $auctionClosed
     * @return static
     */
    public function enableAuctionClosed(bool $auctionClosed): static
    {
        $this->isAuctionClosed = $auctionClosed;
        return $this;
    }

    /**
     * @return ChangesAgreement
     */
    public function getChangesAgreementManager(): ChangesAgreement
    {
        if ($this->changesAgreementManager === null) {
            $this->changesAgreementManager = ChangesAgreement::new();
        }
        return $this->changesAgreementManager;
    }

    /**
     * @param ChangesAgreement $changesAgreementManager
     * @return static
     */
    public function setChangesAgreementManager(ChangesAgreement $changesAgreementManager): static
    {
        $this->changesAgreementManager = $changesAgreementManager;
        return $this;
    }

    /**
     * @return int
     */
    public function getLotItemId(): int
    {
        if (!$this->lotItemId) {
            throw new InvalidArgumentException("Lot Item id is invalid");
        }
        return $this->lotItemId;
    }

    /**
     * @param int $lotItemId
     * @return static
     */
    public function setLotItemId(int $lotItemId): static
    {
        $this->lotItemId = $lotItemId;
        return $this;
    }

    /**
     * @return string
     */
    public function getLotChanges(): string
    {
        return $this->lotChanges;
    }

    /**
     * @param string $lotChanges
     * @return static
     */
    public function setLotChanges(string $lotChanges): static
    {
        $this->lotChanges = $lotChanges;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAuctionRequireLotChangeConfirmation(): bool
    {
        return $this->isAuctionRequireLotChangeConfirmation;
    }

    /**
     * @param bool $auctionRequireLotChangeConfirmation
     * @return static
     */
    public function enableAuctionRequireLotChangeConfirmation(bool $auctionRequireLotChangeConfirmation): static
    {
        $this->isAuctionRequireLotChangeConfirmation = $auctionRequireLotChangeConfirmation;
        return $this;
    }

    /**
     * @return int
     */
    public function getRtbCurrentLotId(): int
    {
        return $this->rtbCurrentLotId;
    }

    /**
     * @param int $rtbCurrentLotId
     * @return static
     */
    public function setRtbCurrentLotId(int $rtbCurrentLotId): static
    {
        $this->rtbCurrentLotId = $rtbCurrentLotId;
        return $this;
    }

    /**
     * @return int
     */
    public function getAuctionStatusId(): int
    {
        return $this->auctionStatusId;
    }

    /**
     * @param int $auctionStatusId
     * @return static
     */
    public function setAuctionStatusId(int $auctionStatusId): static
    {
        $this->auctionStatusId = $auctionStatusId;
        return $this;
    }

    /**
     * @return int
     */
    public function getLotStatusId(): int
    {
        return $this->lotStatusId;
    }

    /**
     * @param int $lotStatusId
     * @return static
     */
    public function setLotStatusId(int $lotStatusId): static
    {
        $this->lotStatusId = $lotStatusId;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuctionType(): string
    {
        return $this->auctionType;
    }

    /**
     * @param string $auctionType
     * @return static
     */
    public function setAuctionType(string $auctionType): static
    {
        $this->auctionType = $auctionType;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getBuyNowAmount(): ?float
    {
        return $this->buyNowAmount;
    }

    /**
     * @param float|null $buyNowAmount
     * @return static
     */
    public function setBuyNowAmount(?float $buyNowAmount): static
    {
        $this->buyNowAmount = $buyNowAmount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getCurrentBid(): ?float
    {
        return $this->currentBid;
    }

    /**
     * @param float|null $currentBid
     * @return static
     */
    public function setCurrentBid(?float $currentBid): static
    {
        $this->currentBid = $currentBid;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getStartDateUtc(): ?DateTime
    {
        return $this->startDateUtc;
    }

    /**
     * @param DateTime|null $startDateUtc
     * @return static
     */
    public function setStartDateUtc(?DateTime $startDateUtc): static
    {
        $this->startDateUtc = $startDateUtc;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getEndDateUtc(): ?DateTime
    {
        return $this->endDateUtc;
    }

    /**
     * @param DateTime|null $endDateUtc
     * @return static
     */
    public function setEndDateUtc(?DateTime $endDateUtc): static
    {
        $this->endDateUtc = $endDateUtc;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBuyNowUnsold(): bool
    {
        if ($this->isBuyNowUnsold === null) {
            $this->isBuyNowUnsold = (bool)$this->getSettingsManager()->get(
                Constants\Setting::BUY_NOW_UNSOLD,
                $this->getAccountId()
            );
        }
        return $this->isBuyNowUnsold;
    }

    /**
     * @param bool $buyNowUnsold
     * @return static
     */
    public function enableBuyNowUnsold(bool $buyNowUnsold): static
    {
        $this->isBuyNowUnsold = $buyNowUnsold;
        return $this;
    }

    /**
     * @return string
     */
    public function getBuyNowRestriction(): string
    {
        if ($this->buyNowRestriction === null) {
            $this->buyNowRestriction = $this->getSettingsManager()->get(Constants\Setting::BUY_NOW_RESTRICTION, $this->getAccountId());
        }
        return $this->buyNowRestriction;
    }

    /**
     * @param string $buyNowRestriction
     * @return static
     */
    public function setBuyNowRestriction(string $buyNowRestriction): static
    {
        $this->buyNowRestriction = $buyNowRestriction;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTransactionCurrentBid(): ?float
    {
        return $this->transactionCurrentBid;
    }

    /**
     * @param float|null $transactionCurrentBid
     * @return static
     */
    public function setTransactionCurrentBid(?float $transactionCurrentBid): static
    {
        $this->transactionCurrentBid = $transactionCurrentBid;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAuctionListingOnly(): bool
    {
        return $this->isAuctionListingOnly;
    }

    /**
     * @param bool $auctionListingOnly
     * @return static
     */
    public function enableAuctionListingOnly(bool $auctionListingOnly): static
    {
        $this->isAuctionListingOnly = $auctionListingOnly;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAuctionLotListingOnly(): bool
    {
        return $this->isAuctionLotListingOnly;
    }

    /**
     * @param bool $isAuctionLotListingOnly
     * @return static
     */
    public function enableAuctionLotListingOnly(bool $isAuctionLotListingOnly): static
    {
        $this->isAuctionLotListingOnly = $isAuctionLotListingOnly;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserFlag(): int
    {
        return $this->userFlag;
    }

    /**
     * @param int $userFlag
     * @return static
     */
    public function setUserFlag(int $userFlag): static
    {
        $this->userFlag = $userFlag;
        return $this;
    }

    /**
     * @return bool
     */
    protected function detectBuyNow(): bool
    {
        $isState = $this->isBuyNowAvailable();
        return $isState;
    }

    /**
     * @return bool
     */
    public function isBuyNowAvailable(): bool
    {
        if ($this->isBuyNowAvailable === null) {
            $auctionStatusPureChecker = AuctionStatusPureChecker::new();
            if ($auctionStatusPureChecker->isTimed($this->getAuctionType())) {
                $checker = $this->createBuyNowAvailabilityTimedChecker();
                if ($this->getEditorUserId()) {
                    $checker
                        ->enableApprovedBidder($this->isAuctionApproved())
                        ->setUserFlag($this->getUserFlag());
                }
                $this->isBuyNowAvailable = $checker
                    // ->enableAllowedForUnsold($this->isBuyNowUnsold())
                    ->enableAuctionListingOnly($this->isAuctionListingOnly())
                    ->enableAuctionLotListingOnly($this->isAuctionLotListingOnly())
                    ->enableBiddingPaused($this->isBiddingPaused())
                    ->setBuyNowAmount($this->getBuyNowAmount())
                    ->setCurrentBid($this->getCurrentBid())
                    ->setEndDateUtc($this->getEndDateUtc())
                    ->setLotStatus($this->getLotStatusId())
                    ->setStartDateUtc($this->getStartDateUtc())
                    ->setUserFlag($this->getUserFlag())
                    ->isAvailable();
            } else {
                $checker = $this->createBuyNowAvailabilityLiveChecker();
                if ($this->getEditorUserId()) {
                    $checker
                        ->enableApprovedBidder($this->isAuctionApproved())
                        ->setUserFlag($this->getUserFlag());
                }
                $this->isBuyNowAvailable = $checker
                    ->enableAllowedForUnsold($this->isBuyNowUnsold())
                    ->enableAuctionListingOnly($this->isAuctionListingOnly())
                    ->enableAuctionLotListingOnly($this->isAuctionLotListingOnly())
                    ->enableBiddingPaused($this->isBiddingPaused())
                    ->setAuctionStatus($this->getAuctionStatusId())
                    ->setBuyNowAmount($this->getBuyNowAmount())
                    ->setCurrentAbsenteeBid($this->getCurrentBid())
                    ->setCurrentTransactionBid($this->getTransactionCurrentBid())
                    ->setLotItemId($this->getLotItemId())
                    ->setLotStatus($this->getLotStatusId())
                    ->setRestriction($this->getBuyNowRestriction())
                    ->setRunningLotItemId($this->getRtbCurrentLotId())
                    ->setStartDateUtc($this->getStartDateUtc())
                    ->isAvailable();
            }
        }
        return $this->isBuyNowAvailable;
    }

    /**
     * @param bool|null $buyNowAvailable
     * @return static
     */
    public function enableBuyNowAvailable(?bool $buyNowAvailable): static
    {
        $this->isBuyNowAvailable = $buyNowAvailable;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getAskingBid(): ?float
    {
        return $this->askingBid;
    }

    /**
     * @param float|null $askingBid
     * @return static
     */
    public function setAskingBid(?float $askingBid): static
    {
        $this->askingBid = $askingBid;
        return $this;
    }
}
