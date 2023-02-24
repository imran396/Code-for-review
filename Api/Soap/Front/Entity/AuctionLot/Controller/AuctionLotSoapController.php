<?php

namespace Sam\Api\Soap\Front\Entity\AuctionLot\Controller;

use Auction;
use AuctionLotItem;
use BidTransaction;
use Exception;
use InvalidArgumentException;
use RuntimeException;
use Sam\Api\Soap\Front\Entity\Base\Controller\SoapControllerBase;
use Sam\Auction\Date\StartEndPeriod\TimedAuctionDateAssignorAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Delete\AuctionLotDeleterCreateTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\LotNo\Parse\LotNoParserCreateTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Bidding\AbsenteeBid\Detect\HighAbsenteeBidDetectorCreateTrait;
use Sam\Bidding\AbsenteeBid\Place\AbsenteeBidManagerCreateTrait;
use Sam\Bidding\AbsenteeBid\Validate\AbsenteeBidAmountValidatorCreateTrait;
use Sam\Bidding\AskingBid\AskingBidDetectorCreateTrait;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Bidding\CurrentAbsenteeBid\CurrentAbsenteeBidCalculatorCreateTrait;
use Sam\Bidding\TimedBid\Place\TimedBidSaverCreateTrait;
use Sam\Billing\CreditCard\Validate\CreditCardValidatorAwareTrait;
use Sam\Core\Bidding\RegularBid\RegularBidPureChecker;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerConfigDto;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerDtoFactory;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputDto;
use Sam\EntityMaker\AuctionLot\Lock\AuctionLotMakerLocker;
use Sam\EntityMaker\AuctionLot\Save\AuctionLotMakerProducer;
use Sam\EntityMaker\AuctionLot\Validate\AuctionLotMakerValidator;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\Base\Data\PlaceBidOutput;
use Sam\EntitySync\Load\EntitySyncLoaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Lot\Validate\State\LotStateDetectorCreateTrait;
use Sam\PhoneNumber\PhoneNumberHelper;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Flag\UserFlaggingAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\BidderPrivilegeCheckerAwareTrait;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;
use Sam\User\Validate\UserExistenceCheckerAwareTrait;
use Sam\User\Watchlist\WatchlistManagerAwareTrait;
use User;

/**
 * Class AuctionLot
 * @package Sam\Soap
 */
class AuctionLotSoapController extends SoapControllerBase
{
    use AbsenteeBidAmountValidatorCreateTrait;
    use AbsenteeBidManagerCreateTrait;
    use AskingBidDetectorCreateTrait;
    use AuctionBidderCheckerAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotDeleterCreateTrait;
    use AuctionLotLoaderAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use BidderPrivilegeCheckerAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CreditCardValidatorAwareTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentAbsenteeBidCalculatorCreateTrait;
    use CurrentDateTrait;
    use EntitySyncLoaderAwareTrait;
    use HighAbsenteeBidDetectorCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotNoParserCreateTrait;
    use LotRendererAwareTrait;
    use LotStateDetectorCreateTrait;
    use NumberFormatterAwareTrait;
    use RoleCheckerAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use SettingsManagerAwareTrait;
    use TimedAuctionDateAssignorAwareTrait;
    use TimedBidSaverCreateTrait;
    use TranslatorAwareTrait;
    use UserExistenceCheckerAwareTrait;
    use UserFlaggingAwareTrait;
    use UserLoaderAwareTrait;
    use WatchlistManagerAwareTrait;

    protected const BID_STATUS_ACCEPTED = 'accepted';
    protected const BID_STATUS_DECLINED = 'declined';
    protected const BID_STATUS_OUTBID = 'outbid';
    protected const BID_STATUS_TOO_HIGH = 'too high';
    protected const BID_STATUS_TOO_LOW = 'too low';

    private const NAMESPACE_AUCTION_PHONE_NUMBER = 'SAM AuctionId-LotNumber-PhoneNumber';
    private const NAMESPACE_AUCTION_USERNAME = 'SAM AuctionId-LotNumber-Username';
    private const NAMESPACE_EVENT_PHONE_NUMBER = 'SAM EventId-LotNumber-PhoneNumber';
    private const NAMESPACE_EVENT_USERNAME = 'SAM EventId-LotNumber-Username';
    private const NAMESPACE_ID = "SAM auction_lot_item.id";

    /** @var string[] */
    protected array $defaultNamespaces = [
        self::NAMESPACE_AUCTION_PHONE_NUMBER,
        self::NAMESPACE_AUCTION_USERNAME,
        self::NAMESPACE_EVENT_PHONE_NUMBER,
        self::NAMESPACE_EVENT_USERNAME,
        self::NAMESPACE_ID,
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete Auction Lot
     *
     * @param string $key Key is the synchronization key, auction_lot_item.id or entity_sync.key
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function delete(string $key): void
    {
        $auctionLotNamespaceAdapter = new AuctionLotNamespaceAdapter(
            (object)['Key' => $key],
            $this->namespace,
            $this->namespaceId,
            $this->editorUserAccountId
        );
        $auctionLot = $auctionLotNamespaceAdapter->getEntity();
        $this->updateLastSyncIn($key, Constants\EntitySync::TYPE_AUCTION_LOT_ITEM);

        $this->createAuctionLotDeleter()
            ->construct()
            ->delete($auctionLot, $this->editorUserId);

        $auctionDateAssignor = $this->getTimedAuctionDateAssignor()->setAuctionId($auctionLot->AuctionId);
        if ($auctionDateAssignor->shouldUpdate()) {
            $auctionDateAssignor->updateDateFromLots($this->editorUserId);
        }
    }

    /**
     * Place a bid
     * User needs to be registered and approved for the auction
     *
     * @param string $auctionKey Auction id, event id or auction sync key
     * @param string $lotKey Lot num prefix + lot num + lot num extension or entitySync key
     * @param string $userKey User name, phone number or user sync key
     * @param string $amount
     * @param bool $shouldNotifyUsers
     * @return PlaceBidOutput
     * @throws Exception
     */
    public function placeBid(
        string $auctionKey,
        string $lotKey,
        string $userKey,
        string $amount,
        bool $shouldNotifyUsers
    ): PlaceBidOutput {
        $auction = $this->loadAuctionByKey($auctionKey);
        $auctionLot = $this->loadAuctionLotByKey($lotKey, $auction);
        $user = $this->loadUserByKey($userKey, $auction);
        $maxBid = $this->parseAmount($amount);
        $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
        if (!$lotItem) {
            $message = "Available lot item not found, when placing bid"
                . composeSuffix(['li' => $auctionLot->LotItemId, 'ali' => $auctionLot->Id]);
            log_error($message);
            throw new InvalidArgumentException($message);
        }

        $this->validateCreditCard($user, $auction);

        $placeBidOutput = new PlaceBidOutput();
        $placeBidOutput->Accepted = 'N';
        $placeBidOutput->Currency = $this->getCurrencyLoader()->load($auction->Currency)->Name ?? '';
        $placeBidOutput->Max = $maxBid;

        $askingBid = null;
        $isWinningBid = false;
        $shouldReturnCurrentBid = false;
        $currentBidAmount = null;
        // TimedOnlineItem
        if ($auction->isTimed()) {
            // Validate bid
            $askingBid = $this->createAskingBidDetector()->detectAskingBid($lotItem->Id, $auction->Id);
            if (!RegularBidPureChecker::new()->checkBidToAskingBid($maxBid, $askingBid, $auction)) {
                $placeBidOutput->Status = self::BID_STATUS_TOO_LOW;
                $placeBidOutput->StatusDetail = "Too small amount: $maxBid Asking bid: $askingBid";
                return $placeBidOutput;
            }

            $currentBid = $this->createBidTransactionLoader()->loadById($auctionLot->CurrentBidId);
            if ($currentBid instanceof BidTransaction) {
                if (!RegularBidPureChecker::new()->checkBidToCurrentMaxBid($maxBid, (float)$currentBid->MaxBid, $auction)) {
                    if ($currentBid->UserId === $user->Id) { // Checking if user is high bidder
                        $placeBidOutput->Status = self::BID_STATUS_DECLINED;
                        $placeBidOutput->StatusDetail = "User already bid that amount: $maxBid";
                        return $placeBidOutput;
                    }
                    $shouldReturnCurrentBid = true;
                }
            }

            if ($askingBid * $this->cfg()->get('core->bidding->highBidWarningMultiplier') < $maxBid) {
                $placeBidOutput->Status = self::BID_STATUS_TOO_HIGH;
                $placeBidOutput->StatusDetail = "Too high amount: $maxBid Asking bid: $askingBid";
                return $placeBidOutput;
            }

            // Save bid
            try {
                $bidTransaction = $this->createTimedBidSaver()
                    ->setBidDateUtc($this->getCurrentDateUtc())
                    ->placeTimedBid(
                        $user,
                        $auction,
                        $lotItem->Id,
                        $maxBid,
                        $this->editorUserId,
                        Constants\BidTransaction::TYPE_REGULAR
                    );
            } catch (Exception $e) {
                throw new InvalidArgumentException($e->getMessage());
            }

            // If false, then User bid is lower than someone else's max bid
            $isWinningBid = ($bidTransaction->UserId === $user->Id);

            if ($shouldReturnCurrentBid) {
                $currentBidAmount = (float)$bidTransaction->Bid;
            }

            if ($isWinningBid) {
                $this->getWatchlistManager()->autoAdd($user->Id, $lotItem->Id, $auction->Id, $this->editorUserId);
            }
        }
        if ($auction->isLiveOrHybrid()) {
            // Check if lot is running in clerk console, then disable absentee bids
            if ($this->getRtbGeneralHelper()->isRunningLot($auction->Id, $lotItem->Id)) {
                $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
                $message = sprintf($this->getTranslator()->translate('CATALOG_LOT_STARTED_LIVE', 'catalog'), $lotNo);
                throw new RuntimeException($message);
            }

            // Validate bid
            $absenteeBidAmountValidator = $this->createAbsenteeBidAmountValidator()
                ->setUser($user)
                ->setAuctionLot($auctionLot)
                ->setMaxBid($maxBid);
            if (!$absenteeBidAmountValidator->validate()) {
                $placeBidOutput->Status = $absenteeBidAmountValidator->getErrorType();
                $placeBidOutput->StatusDetail = $absenteeBidAmountValidator->getErrorMessageForSoap();
                return $placeBidOutput;
            }

            // Check if a bid is not the highest before saving it
            $previousHighAbsentee = $this->createHighAbsenteeBidDetector()->detectFirstHigh($lotItem->Id, $auction->Id);
            if ($previousHighAbsentee && Floating::lteq($maxBid, $previousHighAbsentee->MaxBid)) {
                $shouldReturnCurrentBid = true;
            }

            // Save bid
            $absenteeBidManager = $this->createAbsenteeBidManager()
                ->enableAddToWatchlist(true)
                ->enableNotifyUsers($shouldNotifyUsers)
                ->setAuctionId($auction->Id)
                ->setBidDateUtc($this->getCurrentDateUtc())
                ->setEditorUserId($this->editorUserId)
                ->setLotItemId($lotItem->Id)
                ->setMaxBid($maxBid)
                ->setUserId($user->Id);
            $absenteeBidManager->place();

            if ($shouldReturnCurrentBid) {
                $currentBidAmount = $this->createCurrentAbsenteeBidCalculator()
                    ->setLotItem($lotItem)
                    ->setAuction($auction)
                    ->calculate();
            }

            $isWinningBid = true;
        }

        $placeBidOutput->Accepted = $isWinningBid ? 'Y' : 'N';
        $placeBidOutput->Asking = $askingBid;
        $placeBidOutput->Current = $currentBidAmount;
        $placeBidOutput->Status = $isWinningBid ? self::BID_STATUS_ACCEPTED : self::BID_STATUS_OUTBID;
        $placeBidOutput->StatusDetail = $shouldReturnCurrentBid
            ? "Current bid: $currentBidAmount"
            : ($isWinningBid ? 'success' : 'outbid');
        return $placeBidOutput;
    }

    /**
     * Create or update an Auction Lot
     * Missing fields keep their content, empty fields will remove the field content
     *
     * @param object $data
     * @return int
     * @throws InvalidArgumentException
     */
    public function save($data): int
    {
        $auctionLotNamespaceAdapter = new AuctionLotNamespaceAdapter(
            $data,
            $this->namespace,
            $this->namespaceId,
            $this->editorUserAccountId
        );
        $data = $auctionLotNamespaceAdapter->toObject();
        $this->parseRanges($data, 'ConsignorCommissionRanges');
        $this->parseRanges($data, 'ConsignorUnsoldFeeRanges');
        $this->parseRanges($data, 'ConsignorSoldFeeRanges');
        /**
         * @var AuctionLotMakerInputDto $auctionLotInputDto
         * @var AuctionLotMakerConfigDto $auctionLotConfigDto
         */
        [$auctionLotInputDto, $auctionLotConfigDto] = AuctionLotMakerDtoFactory::new()->createDtos(
            Mode::SOAP,
            $this->editorUserId,
            $this->editorUserAccountId,
            $this->editorUserAccountId
        );
        $auctionLotInputDto->setArray((array)$data);
        $auction = $this->getAuctionLoader()->load((int)$auctionLotInputDto->auctionId);
        if ($auction) {
            $auctionLotConfigDto->auctionType = $auction->AuctionType;
            $auctionLotConfigDto->extendAll = $auction->ExtendAll;
            $auctionLotConfigDto->reverse = $auction->Reverse;
        }

        $lockingResult = AuctionLotMakerLocker::new()->lock($auctionLotInputDto, $auctionLotConfigDto); // #ali-lock-3
        if (!$lockingResult->isSuccess()) {
            throw new RuntimeException($lockingResult->message());
        }

        $validator = AuctionLotMakerValidator::new()->construct($auctionLotInputDto, $auctionLotConfigDto);
        if ($validator->validate()) {
            try {
                $producer = AuctionLotMakerProducer::new()->construct($auctionLotInputDto, $auctionLotConfigDto);
                $producer->produce();
            } catch (RuntimeException $e) {
                throw $e;
            } finally {
                AuctionLotMakerLocker::new()->unlock($auctionLotConfigDto); // #ali-lock-3, unlock after success or failed save
            }
            return $producer->getAuctionLot()->Id;
        }

        AuctionLotMakerLocker::new()->unlock($auctionLotConfigDto); // #ali-lock-3, unlock after failed validation
        $logData = ['ali' => $data->Id ?? 0, 'editor u' => $this->editorUserId];
        log_debug(implode("\n", $validator->getErrorMessages()) . composeSuffix($logData));
        throw new InvalidArgumentException(implode("\n", $validator->getErrorMessages()));
    }

    /**
     * Get auction by auction id, auction event id or auction sync key
     * @param string $auctionKey
     * @return Auction
     */
    private function loadAuctionByKey(string $auctionKey): Auction
    {
        switch ($this->namespace) {
            case self::NAMESPACE_EVENT_PHONE_NUMBER:
            case self::NAMESPACE_EVENT_USERNAME:
                $authenticatedUser = $this->getUserLoader()->load($this->editorUserId, true);
                if (!$authenticatedUser) {
                    $message = "Available authenticated user not found" . composeSuffix(['u' => $this->editorUserId]);
                    log_error($message);
                    throw new InvalidArgumentException($message);
                }
                $auction = $this->getAuctionLoader()->loadByEventId($auctionKey, $authenticatedUser->AccountId);
                break;
            case self::NAMESPACE_AUCTION_PHONE_NUMBER:
            case self::NAMESPACE_AUCTION_USERNAME:
                $auction = $this->getAuctionLoader()->load((int)$auctionKey);
                break;
            default:
                $auction = $this->getAuctionLoader()->loadBySyncKey($auctionKey, (int)$this->namespaceId, $this->editorUserAccountId);
        }

        if (!$auction) {
            throw new InvalidArgumentException("Auction $auctionKey not found within sync namespace $this->namespace");
        }

        if ($auction->BiddingPaused) {
            throw new RuntimeException("Bidding paused for auction $auctionKey");
        }
        return $auction;
    }

    /**
     * Get auctionLot by lot number or entitySync key
     * @param string $lotKey
     * @param Auction $auction
     * @return AuctionLotItem
     */
    private function loadAuctionLotByKey(string $lotKey, Auction $auction): AuctionLotItem
    {
        $auctionLot = null;
        switch ($this->namespace) {
            case self::NAMESPACE_AUCTION_PHONE_NUMBER:
            case self::NAMESPACE_AUCTION_USERNAME:
            case self::NAMESPACE_EVENT_PHONE_NUMBER:
            case self::NAMESPACE_EVENT_USERNAME:
                $lotNoParser = $this->createLotNoParser()->construct();
                if ($lotNoParser->validate($lotKey)) {
                    $lotNoParsed = $lotNoParser->parse($lotKey);
                    $auctionLot = $this->getAuctionLotLoader()->loadByLotNoParsed($lotNoParsed, $auction->Id);
                } else {
                    log_error('Unable to parse lot#' . composeSuffix(['lot#' => $lotKey, 'error' => $lotNoParser->getErrorMessage()]));
                }
                break;
            default:
                $auctionLot = $this->getAuctionLotLoader()->loadBySyncKey($lotKey, (int)$this->namespaceId);
        }

        if (!$auctionLot) {
            throw new InvalidArgumentException("Auction Lot Item $lotKey not found within sync namespace $this->namespace");
        }

        $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
        if (
            !$lotItem
            || $lotItem->isDeleted()
        ) {
            throw new InvalidArgumentException("Lot item deleted");
        }

        if (!$this->createLotStateDetector()->isOpenForBidding($auctionLot, $this->getCurrentDateUtc())) {
            throw new InvalidArgumentException("Lot is closed for bidding");
        }

        return $auctionLot;
    }

    /**
     * parseAmount
     * @param $amount
     * @return float
     */
    private function parseAmount($amount): float
    {
        // SAM-11418: Avoid number formatting in API
        if (!is_numeric($amount)) {
            throw new InvalidArgumentException("Invalid amount");
        }
        return (float)$amount;
    }

    /**
     * Get user by name, phone number or user sync key
     * @param string $userKey
     * @param Auction $auction
     * @return User
     */
    private function loadUserByKey(string $userKey, Auction $auction): User
    {
        switch ($this->namespace) {
            case self::NAMESPACE_EVENT_PHONE_NUMBER:
            case self::NAMESPACE_AUCTION_PHONE_NUMBER:
                $phone = $userKey;
                if ($phone) {
                    $phoneNumberHelper = PhoneNumberHelper::new();
                    if (!$phoneNumberHelper->isValid($phone)) {
                        throw new InvalidArgumentException($phoneNumberHelper->getErrorMessage());
                    }
                }

                $count = $this->getUserExistenceChecker()->countByPhone($phone);
                if ($count === 0) {
                    // user cannot be found by phone
                    $user = null;
                } elseif ($count === 1) {
                    $user = $this->getUserLoader()->loadByPhone($phone);
                } else {
                    throw new InvalidArgumentException("Phone is not unique");
                }
                break;
            case self::NAMESPACE_EVENT_USERNAME:
            case self::NAMESPACE_AUCTION_USERNAME:
                $user = $this->getUserLoader()->loadByUsername($userKey, true);
                break;
            default:
                $user = $this->getUserLoader()->loadBySyncKey($userKey, $this->namespaceId, $this->editorUserAccountId, true);
                break;
        }

        if (!$user) {
            throw new InvalidArgumentException("User $userKey not found within sync namespace $this->namespace");
        }

        if ($this->getUserFlagging()->detectFlagByUser($user, $auction->AccountId) === Constants\User::FLAG_BLOCK) {
            throw new InvalidArgumentException("User is flagged block");
        }

        if (!$this->getRoleChecker()->isBidder($user->Id, true)) {
            throw new InvalidArgumentException("User doesn't have bidder privileges");
        }

        // Not registered or not approved or is NAA flagged
        $isAuctionApproved = $this->getAuctionBidderChecker()->isAuctionApproved($user->Id, $auction->Id);
        if (!$isAuctionApproved) {
            throw new InvalidArgumentException(
                "User cannot place bids in auction"
                . composeSuffix(['u' => $user->Id, 'a' => $auction->Id])
            );
        }
        return $user;
    }

    /**
     * Validate credit card
     * @param User $user
     * @param Auction $auction
     */
    private function validateCreditCard(User $user, Auction $auction): void
    {
        $isPreferredBidder = $this->getBidderPrivilegeChecker()
            ->enableReadOnlyDb(true)
            ->initByUserId($user->Id)
            ->hasPrivilegeForPreferred();
        $isPlaceBidRequireCc = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::PLACE_BID_REQUIRE_CC, $auction->AccountId);
        $isAllUserRequireCcAuth = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::ALL_USER_REQUIRE_CC_AUTH, $auction->AccountId);
        $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($user->Id, true);
        $hasCc = $userBilling->CcNumber !== ''
            && $this->getCreditCardValidator()->validateExpiredDateFormatted($userBilling->CcExpDate);
        if (
            (
                $isAllUserRequireCcAuth
                && $isPlaceBidRequireCc
                && !$hasCc
            ) || (
                !$isAllUserRequireCcAuth
                && $isPlaceBidRequireCc
                && !$hasCc
                && !$isPreferredBidder
            )
        ) {
            throw new InvalidArgumentException("Credit card information missing");
        }
    }
}
