<?php

namespace Sam\Rtb\Command\Concrete\Base;

use AuctionLotItem;
use LotItem;
use Sam\Application\HttpReferrer\HttpReferrerParserAwareTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Bidder\BidderInfo\BidderInfoRendererAwareTrait;
use Sam\Bidder\BidderTerms\BidderTermsAgreementManagerAwareTrait;
use Sam\Bidder\Outstanding\BidderOutstandingHelper;
use Sam\Bidding\AskingBid\AskingBidDetectorCreateTrait;
use Sam\Bidding\BidTransaction\Place\Live\LiveBidSaverCreateTrait;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Transform\Html\HtmlEntityTransformer;
use Sam\Lot\BuyerGroup\Access\LotBuyerGroupAccessHelperAwareTrait;
use Sam\Lot\BuyerGroup\Validate\LotBuyerGroupExistenceCheckerAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Bid\BidUpdaterAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\State\History\HistoryServiceFactoryCreateTrait;
use Sam\Rtb\User\UserHashGeneratorCreateTrait;
use Sam\Settings\TermsAndConditionsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Flag\UserFlaggingAwareTrait;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;

/**
 * Class PlaceBid
 * @package Sam\Rtb\Command\Concrete\Base
 * @method AuctionLotItem getAuctionLot() - existence checked in checkRunningLot()
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 */
abstract class PlaceBid extends CommandBase implements RtbCommandHelperAwareInterface
{
    use AskingBidDetectorCreateTrait;
    use AuctionBidderCheckerAwareTrait;
    use BidderInfoRendererAwareTrait;
    use BidderTermsAgreementManagerAwareTrait;
    use BidUpdaterAwareTrait;
    use HighBidDetectorCreateTrait;
    use HistoryServiceFactoryCreateTrait;
    use HttpReferrerParserAwareTrait;
    use LiveBidSaverCreateTrait;
    use LotBuyerGroupAccessHelperAwareTrait;
    use LotBuyerGroupExistenceCheckerAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RoleCheckerAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbRendererCreateTrait;
    use TermsAndConditionsManagerAwareTrait;
    use UserFlaggingAwareTrait;
    use UserHashGeneratorCreateTrait;

    protected ?float $askingBid = null;
    protected string $httpReferrer = '';
    protected string $lotChanges = '';

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
     * @param string $httpReferrer
     * @return static
     */
    public function setReferrer(string $httpReferrer): static
    {
        $this->httpReferrer = $httpReferrer;
        return $this;
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

    public function execute(): void
    {
        if (
            !$this->checkConsoleSync()
            || !$this->checkRunningLot()
        ) {
            /** @var Sync $syncCommand */
            $syncCommand = $this->getRtbCommandHelper()->createCommand('Sync');
            $syncCommand
                ->enableOutOfSyncMessage(true)
                ->runInContext($this);
            return;
        }

        $bidByUserId = $this->getEditorUserId();
        $lotItem = $this->getLotItem();
        $auctionLot = $this->getAuctionLot();
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $bidderNum = $this->getBidderNum();

        $langDeniedNotAccepted = $this->translate('BIDDERCLIENT_BIDDENIED_NOTACCEPTED');
        $langBiddingPaused = $this->translate('BIDDERCLIENT_BIDDING_PAUSED');
        $langPlaceBid = $this->translate('BIDDERCLIENT_MSG_PLACEBID');

        [$referrer, $referrerHost] = $this->getHttpReferrerParser()->parse($this->httpReferrer);

        /* SAM-970
         * floor (FloorQ) or online (AcceptQ) bid shall be ignored if RtbCurrent.LotActive==false
         * */
        $rtbCurrent = $this->getRtbCurrent();
        $currentAskingBid = (float)$rtbCurrent->AskBid;

        if ($rtbCurrent->isIdleLot()) {
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
                Constants\Rtb::RES_DATA => [
                    Constants\Rtb::RES_MESSAGE => $this->getRtbGeneralHelper()
                        ->clean('<span class="bid-denied">' . $langDeniedNotAccepted . '</span>'),
                    Constants\Rtb::RES_BID_DENIED => Constants\Rtb::BD_LOT_NOT_ACTIVE,
                ],
            ];
            $responses[Constants\Rtb::RT_SINGLE] = json_encode($response);
            $message = "Bidder {$bidderNum} ({$this->getEditorUserId()}) bid request of {$this->askingBid} "
                . "at {$currentAskingBid} on lot {$lotNo} ({$lotItem->Id}) denied: "
                . "Bid was not accepted because item was already for selling";
            $this->getLogger()->log($message);
            $this->setResponses($responses);
            return;
        }

        if ($this->getAuction()->BiddingPaused) {
            $message = '<span class="bid-denied">' . $langDeniedNotAccepted . ' <br />' . $langBiddingPaused . '</span>';
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
                Constants\Rtb::RES_DATA => [
                    Constants\Rtb::RES_MESSAGE => $this->getRtbGeneralHelper()->clean($message),
                    Constants\Rtb::RES_BID_DENIED => Constants\Rtb::BD_BIDDING_PAUSED,
                ],
            ];
            $responses[Constants\Rtb::RT_SINGLE] = json_encode($response);
            $message = "Bidder {$bidderNum} ({$this->getEditorUserId()}) bid request of {$this->askingBid}"
                . " at {$currentAskingBid} on lot {$lotNo} ({$lotItem->Id}) denied: "
                . "Bid was not accepted because auction bidding paused";
            $this->getLogger()->log($message);
            $this->setResponses($responses);
            return;
        }

        if ($this->getAuctionLot()->ListingOnly) {
            $message = '<span class="bid-denied">' . $langDeniedNotAccepted . '</span>';
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
                Constants\Rtb::RES_DATA => [
                    Constants\Rtb::RES_MESSAGE => $this->getRtbGeneralHelper()->clean($message),
                    Constants\Rtb::RES_BID_DENIED => Constants\Rtb::BD_CANNOT_BID,
                ],
            ];
            $responses[Constants\Rtb::RT_SINGLE] = json_encode($response);
            $message = "Bidder {$bidderNum} ({$this->getEditorUserId()}) bids request of {$this->askingBid}"
                . " at {$currentAskingBid} on lot {$lotNo} ({$lotItem->Id}) denied: "
                . "Lot is Listing Only.";
            $this->getLogger()->log($message);
            $this->setResponses($responses);
            return;
        }

        if (!$this->getAuction()->isStarted()) {
            // Only to place bid if the auction has started
            return;
        }

        $lotTerms = $this->getTermsAndConditionsManager()
            ->loadForAuctionLot($lotItem->Id, $this->getAuctionId());
        $hasAgreement = $this->getBidderTermsAgreementManager()
            ->has($bidByUserId, $lotItem->Id, $this->getAuctionId());
        if (
            $lotTerms
            && !$hasAgreement
        ) {
            $data = [
                Constants\Rtb::RES_LOT_ITEM_ID => $lotItem->Id,
                Constants\Rtb::RES_LOT_TERMS => $lotTerms,
                Constants\Rtb::RES_IS_AGREEMENT => false,
            ];
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_PLACE_S,
                Constants\Rtb::RES_DATA => $data
            ];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
            $this->setResponses($responses);
            return;
        }

        if (!empty($this->lotChanges)) {
            $currentLotChanges = $lotItem->Changes;
            $changes = $this->lotChanges;
            if (!empty($currentLotChanges)) {
                $lotChangesHtmlEntity = HtmlEntityTransformer::new()->toHtmlEntity($currentLotChanges, true); // TODO: check this logic
                if ($lotChangesHtmlEntity !== $changes) {
                    $data = [
                        Constants\Rtb::RES_LOT_ITEM_ID => $lotItem->Id,
                        Constants\Rtb::RES_LOT_CHANGES => $currentLotChanges,
                        Constants\Rtb::RES_IS_LOT_CHANGES => $currentLotChanges !== '',
                    ];
                    $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_PLACE_S, Constants\Rtb::RES_DATA => $data];
                    $responseJson = json_encode($response);
                    $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
                    $this->setResponses($responses);
                    return;
                }
            }
        }

        // IK, Sep. 2019, This logic seems old and redundant, because we always update running asking bid in Sync
        // $askBidDebug = 'RTB current ask bid';
        // if (!$currentAskingBid) {
        //     $askBidDebug = 'Calculated asking bid';
        //     $currentAskingBid = $this->createAskingBidDetector()
        //         ->detectQuantizeAskingBid(null, $rtbCurrent->Increment, $lotItem->Id, $this->getAuctionId());
        // }

        $message = "Bidder {$bidderNum} ({$this->getEditorUserId()}) requests to bid {$this->askingBid}"
            . " at {$currentAskingBid} on lot {$lotNo} ({$lotItem->Id}) Referrer: {$referrer}";
        $this->getLogger()->log($message);

        if (!$this->getRoleChecker()->isBidder($bidByUserId, true)) { // Has no bidder privileges
            $langDeniedNoPrivilege = $this->translate('BIDDERCLIENT_BIDDENIED_NOPRIV');
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
                Constants\Rtb::RES_DATA => [
                    Constants\Rtb::RES_MESSAGE => $this->getRtbGeneralHelper()
                        ->clean('<span class="bid-denied">' . $langDeniedNoPrivilege . '</span>'),
                    Constants\Rtb::RES_BID_DENIED => Constants\Rtb::BD_NO_BIDDER_ROLE,
                ],
            ];
            $responses[Constants\Rtb::RT_SINGLE] = json_encode($response);
            $message = "Bidder {$bidderNum} ({$this->getEditorUserId()}) bid request of {$this->askingBid}"
                . " at {$currentAskingBid} on lot {$lotNo} ({$lotItem->Id}) denied: Bidder has no bidder privileges";
            $this->getLogger()->log($message);
            $this->setResponses($responses);
            return;
        }
        // TODO: perform flag check for block separately and force user page reload, which logoff user
        $user = $this->getUserLoader()->load($bidByUserId);
        $userFlag = $this->getUserFlagging()->detectFlagByUser($user, $this->getAuction()->AccountId);
        if (
            !$user
            || $userFlag === Constants\User::FLAG_BLOCK
            || !$this->getAuctionBidderChecker()->isAuctionApproved($user->Id, $this->getAuctionId())
        ) {
            $langDeniedNotReg = $this->translate('BIDDERCLIENT_BIDDENIED_NOTREG');
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
                Constants\Rtb::RES_DATA => [
                    Constants\Rtb::RES_MESSAGE => $this->getRtbGeneralHelper()
                        ->clean('<span class="bid-denied">' . $langDeniedNotReg . '</span>'),
                    Constants\Rtb::RES_BID_DENIED => Constants\Rtb::BD_CANNOT_BID,
                ],
            ];
            $responses[Constants\Rtb::RT_SINGLE] = json_encode($response);
            $message = "Bidder {$bidderNum} ({$this->getEditorUserId()}) bid request of {$this->askingBid}"
                . " at {$currentAskingBid} on lot {$lotNo} ({$lotItem->Id}) denied: Bidder is not registered or not approved";
            $this->getLogger()->log($message);
            $this->setResponses($responses);
            return;
        }

        $hasBuyerGroup = $this->getLotBuyerGroupExistenceChecker()->exist();
        if (
            $hasBuyerGroup
            && $this->getLotBuyerGroupAccessHelper()->isRestrictedBuyerGroup($this->getEditorUserId(), $lotItem->Id)
        ) {
            $langDeniedRestrictedGroup = $this->translate('BIDDERCLIENT_BIDDENIED_RESTRICTEDGROUP');
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
                Constants\Rtb::RES_DATA => [
                    Constants\Rtb::RES_MESSAGE => $this->getRtbGeneralHelper()
                        ->clean('<span class="bid-denied">' . $langDeniedRestrictedGroup . '</span>'),
                    Constants\Rtb::RES_BID_DENIED => Constants\Rtb::BD_RESTRICTED_GROUP,
                ],
            ];
            $responses[Constants\Rtb::RT_SINGLE] = json_encode($response);
            $message = "Bidder {$bidderNum} ({$this->getEditorUserId()}) bid request of {$this->askingBid}"
                . " at {$currentAskingBid} on lot {$lotNo} ({$lotItem->Id}) denied: Restricted group";
            $this->getLogger()->log($message);
            $this->setResponses($responses);
            return;
        }

        $currentNewBidByUserId = (int)$rtbCurrent->NewBidBy;

        if ($bidByUserId === $currentNewBidByUserId) {
            $langJustPlaced = $this->translate('BIDDERCLIENT_JUST_PLACED');
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
                Constants\Rtb::RES_DATA => [
                    Constants\Rtb::RES_MESSAGE => $this->getRtbGeneralHelper()
                        ->clean('<span class="bid-denied">' . $langJustPlaced . '</span>'),
                    Constants\Rtb::RES_BID_DENIED => Constants\Rtb::BD_ALREADY_PLACED,
                ],
            ];
            $responses[Constants\Rtb::RT_SINGLE] = json_encode($response);
            $message = "Bidder {$bidderNum} ({$this->getEditorUserId()}) bid request of {$this->askingBid}"
                . " at {$currentAskingBid} on lot {$lotNo} ({$lotItem->Id}) denied: Bidder already placed bid";
            $this->getLogger()->log($message);
            $this->setResponses($responses);
            return;
        }

        $highBidUserId = $this->createHighBidDetector()->detectUserId($lotItem->Id, $this->getAuctionId());
        if (
            $highBidUserId > 0
            && $bidByUserId === $highBidUserId
        ) {
            $langCurrentHigh = $this->translate('BIDDERCLIENT_CURRENT_HIGH');
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
                Constants\Rtb::RES_DATA => [
                    Constants\Rtb::RES_MESSAGE => $this->getRtbGeneralHelper()
                        ->clean('<span class="bid-denied">' . $langCurrentHigh . '</span>'),
                    Constants\Rtb::RES_BID_DENIED => Constants\Rtb::BD_CURRENT_HIGH,
                ],
            ];
            $responses[Constants\Rtb::RT_SINGLE] = json_encode($response);
            $message = "Bidder {$bidderNum} ({$this->getEditorUserId()}) bid request of {$this->askingBid}"
                . " at {$currentAskingBid} on lot {$lotNo} ({$lotItem->Id}) denied: Bidder is current high bidder";
            $this->getLogger()->log($message);
            $this->setResponses($responses);
            return;
        }

        // SAM-6881: User outstanding limit for live auctions
        $auctionBidder = $this->getAuctionBidderLoader()->load($bidByUserId, $this->getAuctionId(), true);
        $outstandingExceed = $auctionBidder
            && BidderOutstandingHelper::new()->isLimitExceeded($auctionBidder, $currentAskingBid);
        if ($outstandingExceed) {
            $langOutstandingExceed = $this->translate('BIDDERCLIENT_OUTSTANDING_LIMIT_EXCEEDED_BID_REJECTED');
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
                Constants\Rtb::RES_DATA => [
                    Constants\Rtb::RES_MESSAGE => $this->getRtbGeneralHelper()
                        ->clean('<span class="bid-denied">' . $langOutstandingExceed . '</span>'),
                    Constants\Rtb::RES_BID_DENIED => Constants\Rtb::BD_OUTSTANDING_EXCEED,
                ],
            ];
            $responses[Constants\Rtb::RT_SINGLE] = json_encode($response);
            $message = "Bidder {$bidderNum} ({$this->getEditorUserId()}) bid request of {$this->askingBid}"
                . " at {$currentAskingBid} on lot {$lotNo} ({$lotItem->Id}) denied: Max outstanding exceed";
            $this->getLogger()->log($message);
            $this->setResponses($responses);
            return;
        }

        $historyService = $this->createHistoryServiceFactory()->create($rtbCurrent);
        $historyService->snapshot($rtbCurrent, Constants\Rtb::CMD_PLACE_Q, $this->detectModifierUserId());

        if ($currentNewBidByUserId === 0) {
            log_debug(">>>>> Bidder request {$this->askingBid} >= Actual asking bid $currentAskingBid from RTB current ask bid");

            if (Floating::gteq($this->askingBid, $currentAskingBid)) {
                $askingBid = $currentAskingBid;
                $askingBidFormatted = $this->getNumberFormatter()->formatMoney($askingBid);
                $rtbCurrent->NewBidBy = $bidByUserId;
                $rtbCurrent->AbsenteeBid = false;
                $rtbCurrent->Referrer = $referrer;
                $rtbCurrent->ReferrerHost = $referrerHost;
                $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
                $auctionBidder = $this->getAuctionBidderLoader()->load($bidByUserId, $this->getAuctionId(), true);
                if (!$auctionBidder) {
                    log_error(
                        "Available auction bidder not found for PlaceBid command"
                        . composeSuffix(['u' => $bidByUserId, 'a' => $this->getAuctionId()])
                    );
                    return;
                }

                $bidderNumPad = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
                $user = $this->getUserLoader()->load($auctionBidder->UserId, true);
                $buttonInfo = $user->Username;
                $auctionLot = $this->getAuctionLot();

                $isHideBidderNumber = (bool)$this->getSettingsManager()
                    ->get(Constants\Setting::HIDE_BIDDER_NUMBER, $this->getAuction()->AccountId);
                if ($isHideBidderNumber) {
                    $adminMessage = sprintf(
                        $langPlaceBid,
                        $bidderNumPad,
                        $this->getCurrency() . $askingBidFormatted,
                        $this->getLotRenderer()->renderLotNo($auctionLot)
                    );
                } else {
                    $bidderInfoForAdmin = $this->getBidderInfoRenderer()->renderForAdminRtb($bidByUserId, $this->getAuctionId());
                    $adminMessage = sprintf(
                        $langPlaceBid,
                        $bidderInfoForAdmin,
                        $this->getCurrency() . $askingBidFormatted,
                        $lotNo
                    );
                }

                $onlinebidButtonInfo = (int)$this->getSettingsManager()
                    ->get(Constants\Setting::ONLINEBID_BUTTON_INFO, $this->getAuction()->AccountId);
                if (
                    $onlinebidButtonInfo
                    && $user
                ) {
                    $buttonInfo = $this->getRtbCommandHelper()->getButtonInfo($user, $onlinebidButtonInfo);
                }
                $buttonInfo = $this->getRtbGeneralHelper()->clean($buttonInfo);

                $userHashGenerator = $this->createUserHashGenerator();
                $currentBidderUserHash = $userHashGenerator->generate($highBidUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
                $pendingBidUserHash = $userHashGenerator->generate($bidByUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
                $data = [
                    Constants\Rtb::RES_ASKING_BID => $askingBid,
                    Constants\Rtb::RES_CURRENT_BIDDER_USER_HASH => $currentBidderUserHash,
                    Constants\Rtb::RES_LOT_ITEM_ID => $lotItem->Id,
                    Constants\Rtb::RES_PENDING_BID_BIDDER_NO => $bidderNumPad,
                    Constants\Rtb::RES_PENDING_BID_USER_HASH => $pendingBidUserHash,
                    Constants\Rtb::RES_PLACE_BID_BUTTON_INFO => $buttonInfo,
                    Constants\Rtb::RES_REFERRER => $referrer,
                    Constants\Rtb::RES_STATUS => $adminMessage,
                ];

                $data = array_merge(
                    $data,
                    $this->getResponseDataProducer()->produceUndoButtonData($rtbCurrent)
                );

                $message = $this->createRtbRenderer()->renderAuctioneerMessage($adminMessage, $this->getAuction());
                $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message, true);

                $isHideBidderNumber = (bool)$this->getSettingsManager()
                    ->get(Constants\Setting::HIDE_BIDDER_NUMBER, $this->getAuction()->AccountId);
                if ($isHideBidderNumber) {
                    $bidderNumPad = '';
                    $publicMessage = sprintf(
                        $langPlaceBid,
                        $bidderNumPad,
                        $this->getCurrency() . $askingBidFormatted,
                        $this->getLotRenderer()->renderLotNo($auctionLot)
                    );
                } else {
                    $bidderInfoForPublic = $this->getBidderInfoRenderer()->renderForPublicRtb($bidByUserId, $this->getAuctionId());
                    $publicMessage = sprintf(
                        $langPlaceBid,
                        $bidderInfoForPublic,
                        $this->getCurrency() . $askingBidFormatted,
                        $lotNo
                    );
                }

                $message = $this->createRtbRenderer()->renderAuctioneerMessage($publicMessage, $this->getAuction());
                $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message);

                $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_PLACE_S, Constants\Rtb::RES_DATA => $data];
                $responseJson = json_encode($response);
                // check bidder agreement
                $responses[Constants\Rtb::RT_CLERK] = $responseJson;

                $data = [
                    Constants\Rtb::RES_LOT_ITEM_ID => $lotItem->Id,
                    Constants\Rtb::RES_PENDING_BID_USER_HASH => $pendingBidUserHash,
                    Constants\Rtb::RES_PENDING_BID_BIDDER_NO => $bidderNumPad,
                    Constants\Rtb::RES_STATUS => $publicMessage,
                    Constants\Rtb::RES_REFERRER => $referrer,
                ];

                $data = array_merge(
                    $data,
                    $this->getResponseDataProducer()->produceLotChangesData($rtbCurrent)
                );

                $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_PLACE_S, Constants\Rtb::RES_DATA => $data];
                $responseJson = json_encode($response);

                $responses[Constants\Rtb::RT_BIDDER] = $responseJson;

                $data[Constants\Rtb::RES_STATUS] = $publicMessage;
                $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_PLACE_S, Constants\Rtb::RES_DATA => $data];
                $responseJson = json_encode($response);

                $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
                $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;

                $data[Constants\Rtb::RES_STATUS] = $adminMessage;
                $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_PLACE_S, Constants\Rtb::RES_DATA => $data];
                $responseJson = json_encode($response);
                $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

                $responses = $this->getResponseHelper()->addForSimultaneousAuction(
                    $responses,
                    $this->getSimultaneousAuctionId(),
                    $publicMessage
                );
            } else {
                $bidByUserId = null;
                $bidByPaddle = '';
                $bidByUsername = '';
                $auctionBidder = $this->getAuctionBidderLoader()->load($highBidUserId, $this->getAuctionId(), true);
                if ($auctionBidder) {
                    $bidByUserId = $highBidUserId;
                    $bidByPaddle = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
                    $auctionBidderUser = $this->getUserLoader()->load($auctionBidder->UserId);
                    if (!$auctionBidderUser) {
                        log_error(
                            "Available auction bidder user not found"
                            . composeSuffix(['u' => $auctionBidder->UserId, 'ab' => $auctionBidder->Id])
                        );
                        return;
                    }
                    $bidByUsername = $auctionBidderUser->Username;
                }

                // Place failed bid
                $this->createLiveBidSaver()
                    ->setAmount($this->askingBid)
                    ->setAuction($this->getAuction())
                    ->setEditorUserId($this->detectModifierUserId())
                    ->setHttpReferrer($referrer)
                    ->setLotItem($lotItem)
                    ->setUser($user)
                    ->place();

                $this->getBidUpdater()
                    ->setRtbCurrent($rtbCurrent)
                    ->setLotItem($this->getLotItem())
                    ->setAuction($this->getAuction())
                    ->update($this->detectModifierUserId(), $highBidUserId);
                $data = $this->getBidUpdater()->getData();
                $langOutbid = $this->translate('BIDDERCLIENT_OUTBID');
                $data[Constants\Rtb::RES_MESSAGE] = $this->getRtbGeneralHelper()
                    ->clean('<span class="bid-denied">' . $langOutbid . '</span>');
                $data[Constants\Rtb::RES_BID_DENIED] = Constants\Rtb::BD_OUTBID;
                $data = $this->getResponseHelper()->removeSensitiveData($data);
                $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S, Constants\Rtb::RES_DATA => $data];
                $message = "Bidder {$bidderNum} ({$this->getEditorUserId()}) bid request of {$this->askingBid}"
                    . " at {$currentAskingBid} on lot {$lotNo} ({$lotItem->Id}) denied:"
                    . " Bidder outbid by bidder {$bidByPaddle} ({$bidByUserId}) ({$bidByUsername})";
                $this->getLogger()->log($message);
                $responseJson = json_encode($response);
                $responses[Constants\Rtb::RT_SINGLE] = $responseJson;
            }
        } else {
            $bidByUserId = null;
            $bidByPaddle = '';
            $bidByUsername = '';
            $auctionBidder = $this->getAuctionBidderLoader()->load($currentNewBidByUserId, $this->getAuctionId(), true);
            if ($auctionBidder) {
                $bidByUserId = $currentNewBidByUserId;
                $bidByPaddle = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
                $auctionBidderUser = $this->getUserLoader()->load($auctionBidder->UserId);
                $bidByUsername = $auctionBidderUser->Username;
            }
            log_debug(
                ">>>>> Bidder $bidByPaddle ($bidByUsername) already placed bid at {$currentAskingBid}"
                . " on lot# {$lotNo} (li: {$lotItem->Id})"
            );

            $langFaster = $this->translate('BIDDERCLIENT_FASTER');
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
                Constants\Rtb::RES_DATA => [
                    Constants\Rtb::RES_MESSAGE => $this->getRtbGeneralHelper()
                        ->clean('<span class="bid-denied">' . $langFaster . '</span>'),
                    Constants\Rtb::RES_BID_DENIED => Constants\Rtb::BD_OUTBID_BY_FASTER,
                ],
            ];
            $message = "Bidder {$bidderNum} ({$this->getEditorUserId()}) bid request of {$this->askingBid}"
                . " at {$currentAskingBid} on lot {$lotNo} ({$lotItem->Id}) denied: Bidder outbid"
                . " by bidder {$bidByPaddle} ({$bidByUserId}) ({$bidByUsername})";
            $this->getLogger()->log($message);
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_SINGLE] = $responseJson;
        }

        $this->setResponses($responses);
    }
}
