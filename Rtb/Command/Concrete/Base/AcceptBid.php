<?php

namespace Sam\Rtb\Command\Concrete\Base;

use AuctionBidder;
use AuctionLotItem;
use LotItem;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidder\BidderInfo\BidderInfoRendererAwareTrait;
use Sam\Bidding\BidTransaction\Place\Live\LiveBidSaverCreateTrait;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Math\Floating;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Bid\BidUpdaterAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\State\History\HistoryServiceFactoryCreateTrait;
use Sam\Rtb\User\UserHashGeneratorCreateTrait;
use Sam\Storage\WriteRepository\Entity\BidTransaction\BidTransactionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Flag\UserFlaggingAwareTrait;
use User;

/**
 * Class AcceptBid
 * @package Sam\Rtb\Command\Concrete\Base
 * @method AuctionLotItem getAuctionLot() - existence checked in checkRunningLot()
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 */
abstract class AcceptBid extends CommandBase implements RtbCommandHelperAwareInterface
{
    use AuctionBidderHelperAwareTrait;
    use BidderInfoRendererAwareTrait;
    use BidUpdaterAwareTrait;
    use BidTransactionWriteRepositoryAwareTrait;
    use HighBidDetectorCreateTrait;
    use HistoryServiceFactoryCreateTrait;
    use LiveBidSaverCreateTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbRendererCreateTrait;
    use UserFlaggingAwareTrait;
    use UserHashGeneratorCreateTrait;

    private const RESPONSE_BID_CONFIRM_FOR_SOLD = 1;
    private const RESPONSE_ACCEPTED_BID_OF_CURRENT_OWNER = 2;
    private const RESPONSE_ONLINE_BID_POSTED = 3;
    private const RESPONSE_DENIED_BID = 4;

    private const DENIED_BID_ACCEPTING_MESSAGE = 'Accepting online bid for user has been denied';

    protected string $langInternetBidder2 = '';
    protected string $langFloorBidder2 = '';
    protected ?float $askingBid = null;
    protected string $referrer = '';
    protected bool $isConfirmBuy = false;
    protected string $bidByUserHash = '';
    protected ?int $responseType = null;
    protected ?int $highBidUserId = null;
    protected string $deniedBidAcceptingClarification = '';
    private ?int $deniedPendingBidUserId = null;

    /**
     * @override
     * Check console request state consistency to current rtbd state
     * @return bool
     */
    protected function checkConsoleSync(): bool
    {
        $isCorrectState = parent::checkConsoleSync()
            && $this->checkAskingBid()
            && $this->checkPendingBidder();
        return $isCorrectState;
    }

    public function execute(): void
    {
        if (
            !$this->checkConsoleSync()
            || !$this->checkRunningLot()
        ) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        $rtbCurrent = $this->getRtbCurrent();
        $auctionLot = $this->getAuctionLot();
        $this->highBidUserId = $this->createHighBidDetector()
            ->detectUserId($rtbCurrent->LotItemId, $this->getAuctionId());
        $this->initTranslations($this->getAuction()->AccountId);

        $historyService = $this->createHistoryServiceFactory()->create($rtbCurrent);
        $historyService->snapshot($rtbCurrent, Constants\Rtb::CMD_ACCEPT_Q, $this->detectModifierUserId());

        log_debug("Trying to accept bid from user" . $this->getLogInfo());

        /* SAM-970
         * floor (FloorQ) or online (AcceptQ) bid shall be ignored if RtbCurrent.LotActive==false
         * */
        if ($rtbCurrent->isIdleLot()) {
            log_warning('AcceptQ: Attempt to accept online bid on in-active lot' . $this->getLogInfo());
            return;
        }

        if (
            $auctionLot->BuyNow
            && !$this->isConfirmBuy
            && $auctionLot->isAmongWonStatuses()
        ) {
            $this->responseType = self::RESPONSE_BID_CONFIRM_FOR_SOLD;
            return;
        }

        if (
            $this->highBidUserId > 0
            && $rtbCurrent->NewBidBy === $this->highBidUserId
        ) {
            log_info("Admin accepted bid from bidder who already is the current owner" . $this->getLogInfo());
            // Already current owner no need to accept online bid
            $this->getBidUpdater()
                ->setRtbCurrent($rtbCurrent)
                ->setLotItem($this->getLotItem())
                ->setAuction($this->getAuction())
                ->update($this->detectModifierUserId(), $this->highBidUserId, null, $rtbCurrent->LotGroup);
            $this->responseType = self::RESPONSE_ACCEPTED_BID_OF_CURRENT_OWNER;
            return;
        }

        $user = $this->getUserLoader()->load($rtbCurrent->NewBidBy);
        if (!$user) {
            $this->deniedBidAcceptingClarification = "Available user not found, when accepting bid";
            log_info(self::DENIED_BID_ACCEPTING_MESSAGE . ' - ' . $this->deniedBidAcceptingClarification . $this->getLogInfo());
            $this->responseType = self::RESPONSE_DENIED_BID;
            $this->dropPendingBidUser();
            return;
        }

        $auctionBidder = $this->getAuctionBidderLoader()->load($rtbCurrent->NewBidBy, $this->getAuctionId(), true);
        $canPlay = $auctionBidder && $this->getAuctionBidderHelper()->isApproved($auctionBidder);
        if (!$canPlay) {
            $this->deniedBidAcceptingClarification = "User has been removed from auction";
            log_info(self::DENIED_BID_ACCEPTING_MESSAGE . ' - ' . $this->deniedBidAcceptingClarification . $this->getLogInfo());
            $this->responseType = self::RESPONSE_DENIED_BID;
            $this->dropPendingBidUser();
            return;
        }

        $userFlag = $this->getUserFlagging()->detectFlagByUser($user, $this->getAuction()->AccountId);
        if (in_array($userFlag, [Constants\User::FLAG_BLOCK, Constants\User::FLAG_NOAUCTIONAPPROVAL], true)) {
            $this->deniedBidAcceptingClarification = sprintf('User is flagged by "%s"', Constants\User::FLAG_NAMES[$userFlag]);
            log_info(self::DENIED_BID_ACCEPTING_MESSAGE . ' - ' . $this->deniedBidAcceptingClarification . $this->getLogInfo());
            $this->responseType = self::RESPONSE_DENIED_BID;
            $this->dropPendingBidUser();
            return;
        }

        $success = $this->acceptBid($user);
        if ($success) {
            $this->log($auctionBidder, $auctionLot);
            $this->responseType = self::RESPONSE_ONLINE_BID_POSTED;
        }
    }

    protected function acceptBid(User $user): bool
    {
        $liveBidSaver = $this->createLiveBidSaver()
            ->setAmount((float)$this->askingBid)
            ->setAuction($this->getAuction())
            ->setEditorUserId($this->detectModifierUserId())
            ->setHttpReferrer($this->referrer)
            ->setLotItem($this->getLotItem())
            ->setUser($user);
        $bidTransaction = $liveBidSaver->place();
        if (!$bidTransaction) {
            $this->deniedBidAcceptingClarification = $liveBidSaver->errorMessage();
            log_info(self::DENIED_BID_ACCEPTING_MESSAGE . ' - ' . $this->deniedBidAcceptingClarification . $this->getLogInfo());
            $this->responseType = self::RESPONSE_DENIED_BID;
            $this->dropPendingBidUser();
            return false;
        }

        $rtbCurrent = $this->getRtbCurrent();
        $bidTransaction->AbsenteeBid = $rtbCurrent->AbsenteeBid;
        $this->getBidTransactionWriteRepository()->saveWithModifier($bidTransaction, $this->detectModifierUserId());

        $outbidUserId = $this->highBidUserId;
        $this->getBidUpdater()
            ->setRtbCurrent($rtbCurrent)
            ->setLotItem($this->getLotItem())
            ->setAuction($this->getAuction())
            ->update($this->detectModifierUserId(), $bidTransaction->UserId, $outbidUserId, $rtbCurrent->LotGroup);
        $data = $this->getBidUpdater()->getData();
        // reload RtbCurrent, since it is manipulated in UpdateBid
        $this->reloadRtbCurrent();

        // Save in static file
        [$publicMessage, $adminMessage] = $this->buildStatuses(
            Cast::toFloat($data[Constants\Rtb::RES_CURRENT_BID], Constants\Type::F_FLOAT_POSITIVE_OR_ZERO),
            trim($data[Constants\Rtb::RES_CURRENT_BIDDER_NO]),
            $bidTransaction->UserId
        );
        $publicFull = $this->createRtbRenderer()->renderAuctioneerMessage($publicMessage, $this->getAuction());
        $adminFull = $this->createRtbRenderer()->renderAuctioneerMessage($adminMessage, $this->getAuction());
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $publicFull);
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $adminFull, true);
        return true;
    }

    /**
     * Drop pending bid user from running rtb state.
     * Store removed user id in state of command handler.
     */
    protected function dropPendingBidUser(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $this->deniedPendingBidUserId = $rtbCurrent->NewBidBy;
        log_info('Drop pending bid user from running rtb state' . $this->getLogInfo());
        $rtbCurrent->NewBidBy = null;
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
    }

    /**
     * Checks, that asking bid value on console (passed in request) corresponds to actual asking bid amount at server side
     * @return bool
     */
    protected function checkAskingBid(): bool
    {
        $rtbCurrent = $this->getRtbCurrent();
        $success = Floating::eq($this->askingBid, $rtbCurrent->AskBid);
        if (!$success) {
            $logData = [
                'request ask' => $this->askingBid,
                'actual ask' => $rtbCurrent->AskBid,
                'li' => $rtbCurrent->LotItemId,
                'a' => $rtbCurrent->AuctionId,
                'u' => $rtbCurrent->NewBidBy,
            ];
            log_warning(
                "Out of sync - actual running asking bid differs to asking bid in request"
                . composeSuffix($logData)
            );
        }
        return $success;
    }

    /**
     * Checks, that pending bid user id on console (passed in request) corresponds to actual pending bid bidder at server side
     * @return bool
     */
    protected function checkPendingBidder(): bool
    {
        $rtbCurrent = $this->getRtbCurrent();
        $newBidByUserHash = $this->createUserHashGenerator()->generate($rtbCurrent->NewBidBy, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $success = $newBidByUserHash === $this->bidByUserHash;
        if (!$success) {
            $logData = [
                'actual u' => $rtbCurrent->NewBidBy,
                'li' => $rtbCurrent->LotItemId,
                'a' => $rtbCurrent->AuctionId,
                'ask' => $rtbCurrent->AskBid,
            ];
            log_warning(
                "Out of sync - actual pending bid owner differs to pending bid bidder in request"
                . composeSuffix($logData)
            );
        }
        return $success;
    }

    protected function createResponses(): void
    {
        $responses = null;
        $rtbCurrent = $this->getRtbCurrent();
        if ($this->responseType === self::RESPONSE_BID_CONFIRM_FOR_SOLD) {
            $responses = $this->getResponseHelper()->getResponseForBidConfirmationForSoldLot(
                $this->getAuctionId(),
                $this->getLotItem(),
                $rtbCurrent->NewBidBy,
                $rtbCurrent->AskBid,
                Constants\Rtb::RES_BID_TO_ACCEPT
            );
        } elseif ($this->responseType === self::RESPONSE_ACCEPTED_BID_OF_CURRENT_OWNER) {
            $data = $this->getBidUpdater()->getData();
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S,
                Constants\Rtb::RES_DATA => $data,
            ];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        } elseif ($this->responseType === self::RESPONSE_ONLINE_BID_POSTED) {
            $data = $this->getBidUpdater()->getData();
            $bidderUserId = $this->getBidUpdater()->getBidderUserId();

            [$publicMessage, $adminMessage] = $this->buildStatuses(
                Cast::toFloat($data[Constants\Rtb::RES_CURRENT_BID], Constants\Type::F_FLOAT_POSITIVE_OR_ZERO),
                trim($data[Constants\Rtb::RES_CURRENT_BIDDER_NO]),
                $bidderUserId
            );

            $data[Constants\Rtb::RES_STATUS] = $adminMessage;

            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S,
                Constants\Rtb::RES_DATA => $data
            ];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_CLERK] = $responseJson;

            // Make auctioneer console response
            $data = array_replace(
                $data,
                $this->getResponseDataProducer()->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_AUCTIONEER)
            );
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S,
                Constants\Rtb::RES_DATA => $data
            ];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

            // Make public consoles response
            $data[Constants\Rtb::RES_STATUS] = $publicMessage;
            $delayAfterBidAccepted = (int)$this->getSettingsManager()
                ->get(Constants\Setting::DELAY_AFTER_BID_ACCEPTED, $this->getAuction()->AccountId);
            if ($delayAfterBidAccepted > 0) {
                $data[Constants\Rtb::RES_DELAY_AFTER_BID_ACCEPTED] = $delayAfterBidAccepted;
            }
            $data = $this->getResponseHelper()->removeSensitiveData($data);
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S,
                Constants\Rtb::RES_DATA => $data
            ];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
            $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
            $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;

            $responses = $this->getResponseHelper()
                ->addForSimultaneousAuction($responses, $this->getSimultaneousAuctionId(), $publicMessage);
        } elseif ($this->responseType === self::RESPONSE_DENIED_BID) {
            $message = $this->createRtbRenderer()->renderMessageForDeniedBidAccepting(
                $this->deniedPendingBidUserId,
                $this->deniedBidAcceptingClarification
            );
            $messageToFile = $message . "<br />\n"; // TODO: this line delimiting should be appended to message inside Messenger
            $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageToFile, true);
            $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageToFile);
            $responses = $this->getResponseHelper()->getResponseForDeniedBidAccepting($this->getRtbCurrent(), $message);
        }
        $this->setResponses($responses);
    }

    /**
     * @return string
     */
    protected function getLogInfo(): string
    {
        $rtbCurrent = $this->getRtbCurrent();
        $logInfo = composeSuffix(
            [
                'li' => $rtbCurrent->LotItemId,
                'a' => $rtbCurrent->AuctionId,
                'u' => $rtbCurrent->NewBidBy,
                'ask' => $rtbCurrent->AskBid,
            ]
        );
        return $logInfo;
    }

    /**
     * @param string $referrer
     * @return static
     */
    public function setReferrer(string $referrer): static
    {
        $this->referrer = $referrer;
        return $this;
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

    /**
     * Set and normalize to int
     * @param bool $isConfirmBuy
     * @return static
     */
    public function enableConfirmBuy(bool $isConfirmBuy): static
    {
        $this->isConfirmBuy = $isConfirmBuy;
        return $this;
    }

    /**
     * @param string $bidByHash
     * @return static
     */
    public function setBidByHash(string $bidByHash): static
    {
        $this->bidByUserHash = $bidByHash;
        return $this;
    }

    /**
     * @param int $accountId
     */
    protected function initTranslations(int $accountId): void
    {
        $this->getTranslator()->setAccountId($accountId);
        $this->langInternetBidder2 = $this->translate('BIDDERCLIENT_INTERNETBIDDER2');
        $this->langFloorBidder2 = $this->translate('BIDDERCLIENT_FLOORBIDDER2');
    }

    /**
     * @param float|null $currentBid
     * @param string $bidderNo
     * @param int|null $bidderUserId
     * @return array
     */
    protected function buildStatuses(?float $currentBid, string $bidderNo, ?int $bidderUserId): array
    {
        $adminMessage = '';
        $publicMessage = '';
        $currentBidFormatted = $this->getNumberFormatter()->formatMoney($currentBid, $this->getAuction()->AccountId);
        if ($bidderNo !== '') {
            $bidderInfoForAdmin = $this->getBidderInfoRenderer()->renderForPublicRtb($bidderUserId, $this->getAuctionId());
            $adminMessage = $this->getCurrency() . $currentBidFormatted . " " . $this->langInternetBidder2 . " " . $bidderInfoForAdmin;
            $publicMessage = $this->getCurrency() . $currentBidFormatted . " " . $this->langInternetBidder2;
            $isHideBidderNumber = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::HIDE_BIDDER_NUMBER, $this->getAuction()->AccountId);
            if (!$isHideBidderNumber) {
                $bidderInfoForPublic = $this->getBidderInfoRenderer()->renderForPublicRtb($bidderUserId, $this->getAuctionId());
                $publicMessage .= " " . $bidderInfoForPublic;
            }
        } elseif (Floating::gt($currentBid, 0)) {
            $adminMessage = $this->getCurrency() . $currentBidFormatted . " " . $this->langFloorBidder2;
            $publicMessage = $this->getCurrency() . $currentBidFormatted . " " . $this->langFloorBidder2;
        }
        return [$publicMessage, $adminMessage];
    }

    /**
     * @param AuctionBidder $bidder
     * @param AuctionLotItem $auctionLot
     */
    protected function log(AuctionBidder $bidder, AuctionLotItem $auctionLot): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $message = $this->getLogger()->getUserRoleName($this->getUserType());
        $acceptedBid = $this->askingBid; // It is not $rtbCurrent->AskBid
        $message .= " posts online bid by bidder# {$bidder->BidderNum}" . composeSuffix(['u' => $bidder->UserId])
            . " at {$acceptedBid} on lot# {$lotNo}" . composeSuffix(['li' => $rtbCurrent->LotItemId]);
        $this->getLogger()->log($message);
    }
}
