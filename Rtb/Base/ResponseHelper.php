<?php

namespace Sam\Rtb\Base;

use Auction;
use Laminas\Json\Exception\RuntimeException;
use Laminas\Json\Json;
use LotItem;
use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\AuctionEvent\Notify\Sms\Template\AuctionLotSmsTemplateRendererCreateTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Load\PositionalAuctionLotLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Bidding\AbsenteeBid\Detect\HighAbsenteeBidDetectorCreateTrait;
use Sam\Bidding\BidTransaction\Validate\BidTransactionExistenceCheckerAwareTrait;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\NewLineRemover;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Date\CurrentDateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperFactoryCreateTrait;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\Increment\Calculate\RtbIncrementDetectorCreateTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\User\UserHashGeneratorCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class ResponseHelper
 * @package Sam\Rtb\Base
 */
abstract class ResponseHelper extends CustomizableClass
{
    use AuctionBidderHelperAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionLotSmsTemplateRendererCreateTrait;
    use BidTransactionExistenceCheckerAwareTrait;
    use BidderNumPaddingAwareTrait;
    use RtbCommandHelperFactoryCreateTrait;
    use CurrentDateTrait;
    use HighAbsenteeBidDetectorCreateTrait;
    use HighBidDetectorCreateTrait;
    use LotItemLoaderAwareTrait;
    use PositionalAuctionLotLoaderAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbIncrementDetectorCreateTrait;
    use RtbRendererCreateTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UserHashGeneratorCreateTrait;
    use UserLoaderAwareTrait;

    /**
     * @param array $responses
     * @param array $data
     * @return array
     */
    public function addData(array $responses, array $data): array
    {
        if ($responses) {
            foreach ($responses as &$responseJson) {
                if (!is_string($responseJson)) {
                    continue;
                }
                try {
                    $response = Json::decode($responseJson, Json::TYPE_ARRAY);
                    $responseData = $response[Constants\Rtb::RES_DATA] ?? [];
                    $response[Constants\Rtb::RES_DATA] = array_merge($responseData, $data);
                    $responseJson = Json::encode($response);
                } catch (RuntimeException $e) {
                    log_error($e->getMessage() . composeSuffix(['json' => $responseJson]));
                }
            }
        }
        return $responses;
    }

    /**
     * Add to response meta information (its timestamp)
     * @param array $responses array json-encoded responses
     * @return array
     */
    public function addMetaData(array $responses): array
    {
        $metaData = $this->getResponseDataProducer()->produceMetaData();
        $responses = $this->addData($responses, $metaData);
        return $responses;
    }

    /**
     * @param array $responses
     * @param int|null $simultaneousAuctionId null means simultaneous auction isn't assigned
     * @param string $message
     * @param bool $shouldClearMsgCenter
     * @return array
     */
    public function addForSimultaneousAuction(
        array $responses,
        ?int $simultaneousAuctionId,
        string $message,
        bool $shouldClearMsgCenter = false
    ): array {
        if ($simultaneousAuctionId > 0) {
            $data[Constants\Rtb::RES_MESSAGE] = $message;
            if ($shouldClearMsgCenter) {
                $data[Constants\Rtb::RES_MESSAGE_CENTER_CLEAR] = 1;
            }
            $response = [
                Constants\Rtb::REQ_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_TO_SIMULTANEOUS_AUCTION_Q,
                Constants\Rtb::RES_DATA => $data,
            ];
            $messageJson = json_encode($response);
            $simultaneousAuctionResponse = [
                Constants\Rtb::RES_SA_AUCTION_ID => $simultaneousAuctionId,
                Constants\Rtb::RES_SA_MESSAGE => $messageJson,
            ];
            $responses[Constants\Rtb::RT_SIMULT_BIDDER] = $simultaneousAuctionResponse;
            $responses[Constants\Rtb::RT_SIMULT_VIEWER] = $simultaneousAuctionResponse;
        }
        return $responses;
    }

    /**
     * @param array $responses
     * @param int|null $simultaneousAuctionId null means simultaneous auction isn't assigned
     * @param string $message
     * @param int $userId
     * @return array
     */
    public function addIndividualForSimultaneousAuction(
        array $responses,
        ?int $simultaneousAuctionId,
        string $message,
        int $userId
    ): array {
        if ($simultaneousAuctionId > 0) {
            $data[Constants\Rtb::RES_MESSAGE] = $message;
            $response = [
                Constants\Rtb::REQ_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_TO_SIMULTANEOUS_AUCTION_Q,
                Constants\Rtb::RES_DATA => $data,
            ];
            $messageJson = json_encode($response);
            $simultaneousAuctionResponse = [
                Constants\Rtb::RES_SA_USER_ID => $userId,
                Constants\Rtb::RES_SA_AUCTION_ID => $simultaneousAuctionId,
                Constants\Rtb::RES_SA_MESSAGE => $messageJson,
            ];
            $responses[Constants\Rtb::RT_SIMULT_INDIVIDUAL] = $simultaneousAuctionResponse;
        }
        return $responses;
    }

    /**
     * @param array $responses
     * @param Auction $auction
     * @param int|null $lotItemId
     * @param bool $isTextMsgEnabled
     * @return array
     */
    public function addSmsTextResponse(
        array $responses,
        Auction $auction,
        ?int $lotItemId,
        bool $isTextMsgEnabled
    ): array {
        if (
            $isTextMsgEnabled
            && $auction->EventId
            && $auction->NotifyXLots > 0
        ) {
            $dbResult = $this->getPositionalAuctionLotLoader()->loadNextLots(
                $auction->Id,
                $lotItemId,
                ['offset' => $auction->NotifyXLots, 'limit' => 1]
            );
            $auctionLot = $dbResult[0] ?? null;
            if ($auctionLot) {
                $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
                if (!$lotItem) {
                    log_error("Available LotItem not found" . composeSuffix(['li' => $auctionLot->LotItemId]));
                    return $responses;
                }
                $message = $this->createAuctionLotSmsTemplateRenderer()->render($lotItem, $auctionLot, $auction);
                $responseJson = json_encode([Constants\Rtb::REQ_SMS_PAYLOAD => $message]);
                $responses[Constants\Rtb::RT_SMS] = $responseJson;
            }
        }
        return $responses;
    }

    /**
     * Apply additional info to response of command.
     * Dummy method for overloading in child ResponseHelper.
     * @param CommandBase $command
     * @return CommandBase
     */
    public function applyAdditionalInfo(CommandBase $command): CommandBase
    {
        return $command;
    }

    /**
     * TODO: eliminate of 'RES_STATUS' response
     * @param RtbCurrent $rtbCurrent
     * @param array $optionals = [
     *  'currentBid' => float
     * ]
     * @return array = [
     *
     * ]
     */
    public function getLotData(RtbCurrent $rtbCurrent, array $optionals = []): array
    {
        $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId);
        if (!$auction) {
            log_error(
                "Available auction not found, when building rtb lot data"
                . composeSuffix(['a' => $rtbCurrent->AuctionId])
            );
            return [];
        }
        $data = [
            Constants\Rtb::RES_LOT_POSITION => null,
        ];

        $currentBidAmount = Cast::toFloat($optionals['currentBid'] ?? null);
        $newBidByUserId = null;
        $bidderNum = '';
        $user = null;
        $username = '';
        $highBidUserId = null;
        $bidderNo = '';
        $highBidderName = '';
        $hasTransaction = false;
        $highAbsenteeUserId = null;
        $currentIncrement = 0.;
        $bidCountdown = '';
        $responseDataProducer = $this->getResponseDataProducer();

        $lotItem = $this->getLotItemLoader()->load($rtbCurrent->LotItemId);
        if ($lotItem instanceof LotItem) {
            $newBidByUserId = $rtbCurrent->NewBidBy;

            $bidCountdown = NewLineRemover::new()->replaceWithSpace($rtbCurrent->BidCountdown);
            $auctionBidderLoader = $this->getAuctionBidderLoader();

            $highBidUserId = $this->createHighBidDetector()->detectUserId($lotItem->Id, $auction->Id);
            if ($highBidUserId > 0) {
                $auctionBidder = $auctionBidderLoader->load($highBidUserId, $auction->Id, true);
                if ($auctionBidder) {
                    $bidderNo = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
                }
                $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($highBidUserId, true);
                $highBidderName = UserPureRenderer::new()->renderFullName($userInfo);
            }

            $currentBidAmount = $currentBidAmount ?? $this->createHighBidDetector()->detectAmount($rtbCurrent->LotItemId, $auction->Id);
            $currentIncrement = $this->createRtbIncrementDetector()->detect($rtbCurrent, $currentBidAmount);

            if ($rtbCurrent->AbsenteeBid) {
                $highAbsentee = $this->createHighAbsenteeBidDetector()
                    ->detectFirstHighestByCurrentBid($lotItem->Id, $auction->Id, $currentBidAmount, true);
                $highAbsenteeUserId = $highAbsentee->UserId ?? null;
            }

            if ($newBidByUserId) {
                $auctionBidder = $auctionBidderLoader->load($newBidByUserId, $auction->Id, true);
                if (
                    $auctionBidder
                    && $this->getAuctionBidderHelper()->isApproved($auctionBidder)
                ) {
                    $bidderNum = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
                    $user = $this->getUserLoader()->load($auctionBidder->UserId, true);
                    $username = $user->Username;
                } else {
                    $newBidByUserId = null;
                }
            }

            if (
                $rtbCurrent->isStartedOrPausedLot()
                && in_array($auction->AuctionStatusId, Constants\Auction::$openAuctionStatuses, true)
            ) {
                $hasTransaction = $this->createBidTransactionExistenceChecker()->exist(null, $lotItem->Id, $auction->Id);
            }

            $onlinebidButtonInfo = (int)$this->getSettingsManager()
                ->get(Constants\Setting::ONLINEBID_BUTTON_INFO, $auction->AccountId);
            if (
                $onlinebidButtonInfo
                && $user
            ) {
                $rtbCommandHelper = $this->createRtbCommandHelperFactory()->createByAuction($auction);
                $username = $rtbCommandHelper->getButtonInfo($user, $onlinebidButtonInfo);
            }
            //make sure to clean up the strUser before passing to command
            $username = $this->getRtbGeneralHelper()->clean($username);
        }

        $userHashGenerator = $this->createUserHashGenerator();
        $currentBidderUserHash = $userHashGenerator->generate($highBidUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $highAbsenteeUserHash = $userHashGenerator->generate($highAbsenteeUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $pendingBidUserHash = $userHashGenerator->generate($newBidByUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $data = array_merge(
            $data,
            [
                Constants\Rtb::RES_AUCTION_STATUS_ID => $auction->AuctionStatusId, // Auction Status Id
                Constants\Rtb::RES_BID_COUNTDOWN => ee($bidCountdown),       // TODO: html-encode at js side
                Constants\Rtb::RES_CURRENT_BIDDER_NAME => $highBidderName,
                Constants\Rtb::RES_CURRENT_BIDDER_NO => $bidderNo,    // Current Lot Winner/Owner Paddle #
                Constants\Rtb::RES_CURRENT_BIDDER_USER_ID => $highBidUserId,    // Current Lot Winner/Owner User Id
                Constants\Rtb::RES_CURRENT_BIDDER_USER_HASH => $currentBidderUserHash,
                Constants\Rtb::RES_HIGH_ABSENTEE_USER_HASH => $highAbsenteeUserHash, // High absentee bidder hash
                Constants\Rtb::RES_INCREMENT_CURRENT => $currentIncrement,
                Constants\Rtb::RES_IS_ABSENTEE_BID => $rtbCurrent->AbsenteeBid,       // Is an absentee bid
                Constants\Rtb::RES_IS_CURRENT_BID => $hasTransaction,       // Has Previous Transaction
                Constants\Rtb::RES_LOT_ACTIVITY => $rtbCurrent->LotActive,
                Constants\Rtb::RES_OUTBID_BIDDER_NO => '',
                Constants\Rtb::RES_OUTBID_USER_HASH => '',
                Constants\Rtb::RES_PENDING_BID_BIDDER_NO => $bidderNum,      // Placed Bid Paddle #
                Constants\Rtb::RES_PENDING_BID_USER_HASH => $pendingBidUserHash, // Placed Bid User Hash
                Constants\Rtb::RES_PLACE_BID_BUTTON_INFO => $username,        // Placed Bid Username
            ]
        );

        $data = array_merge(
            $data,
            $responseDataProducer->produceBidData($rtbCurrent, ['currentBid' => $currentBidAmount]),
            $responseDataProducer->produceBuyerGroupData($rtbCurrent),
            $responseDataProducer->produceGameStatusData($rtbCurrent),
            $responseDataProducer->produceGroupingData($rtbCurrent),
            $responseDataProducer->produceLotChangesData($rtbCurrent),
            $responseDataProducer->produceLotGeneralData($rtbCurrent),
            $responseDataProducer->produceLotPositionData($rtbCurrent),
            $responseDataProducer->produceUndoButtonData($rtbCurrent)
        );
        return $data;
    }

    /**
     * Return response, which should be sent, when bid accepting is denied
     *
     * @param RtbCurrent $rtbCurrent
     * @param string $message
     * @return array
     */
    public function getResponseForDeniedBidAccepting(RtbCurrent $rtbCurrent, string $message): array
    {
        $data = $this->getLotData($rtbCurrent);
        $clerkData = array_merge(
            $data,
            $this->getResponseDataProducer()->produceAdminSideData($rtbCurrent)
        );

        $clerkData[Constants\Rtb::RES_STATUS] = $message;
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $clerkData
        ];

        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        return $responses;
    }


    /**
     * Return response, which should be sent, when clerk accept a bid
     * and he doesn't know that the item has been sold already
     *
     * @param int $auctionId auction.id
     * @param LotItem $lotItem
     * @param int|null $bidByUserId user.id whose bid is planing to accept, null - floor bidder
     * @param float $askingBid
     * @param string $bidToStatus Constants\Rtb::RES_BID_TO_ACCEPT or Constants\Rtb::RES_BID_TO_FLOOR
     * @return array
     */
    public function getResponseForBidConfirmationForSoldLot(
        int $auctionId,
        LotItem $lotItem,
        ?int $bidByUserId,
        float $askingBid,
        string $bidToStatus
    ): array {
        $bidderNum = '';
        $auctionBidder = $this->getAuctionBidderLoader()->load($lotItem->WinningBidderId, $auctionId, true);
        if ($auctionBidder) {
            $bidderNum = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
        }

        $pendingBidUserHash = $this->createUserHashGenerator()->generate($bidByUserId, $lotItem->Id, $auctionId);
        $data = [
            Constants\Rtb::RES_ASKING_BID => $askingBid,
            Constants\Rtb::RES_BID_TO_STATUS => $bidToStatus,
            Constants\Rtb::RES_LOT_ITEM_ID => $lotItem->Id,
            Constants\Rtb::RES_PENDING_BID_USER_HASH => $pendingBidUserHash,
            Constants\Rtb::RES_STATUS => "This lot has just been sold to bidder {$bidderNum}. ",
        ];
        $questions = [
            Constants\Rtb::RES_BID_TO_ACCEPT => 'Are you sure you want to accept this online bid?',
            Constants\Rtb::RES_BID_TO_FLOOR => 'Are you sure you want to accept this floor bid?',
        ];
        $data[Constants\Rtb::RES_STATUS] .= $questions[$bidToStatus];
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_CONFIRM_BID_ON_SOLD_LOT_S,
            Constants\Rtb::RES_DATA => $data,
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        return $responses;
    }

    /**
     * Avoid exposing secure data to public consoles
     * @param array $data
     * @return array
     */
    public function removeSensitiveData(array $data): array
    {
        unset(
            $data[Constants\Rtb::RES_INCREMENT_CURRENT],
            $data[Constants\Rtb::RES_INCREMENT_RESTORE],
            $data[Constants\Rtb::RES_INCREMENT_NEXT_1],
            $data[Constants\Rtb::RES_INCREMENT_NEXT_2],
            $data[Constants\Rtb::RES_INCREMENT_NEXT_3],
            $data[Constants\Rtb::RES_INCREMENT_NEXT_4],
            $data[Constants\Rtb::RES_PLACE_BID_BUTTON_INFO],
            $data[Constants\Rtb::RES_CURRENT_BIDDER_NAME],
            $data[Constants\Rtb::RES_CURRENT_BIDDER_ADDRESS],
            $data[Constants\Rtb::RES_CURRENT_BIDDER_USER_ID]
        );
        return $data;
    }

    /**
     * @param array $soldLotWinnerBidderNo
     * @param RtbCurrent $rtbCurrent
     * @return array
     */
    public function hashSoldLotWinnerBidderNoUserId(array $soldLotWinnerBidderNo, RtbCurrent $rtbCurrent): array
    {
        $userHashGenerator = $this->createUserHashGenerator();
        $winnerBidderNoWithHashedUserId = [];
        foreach ($soldLotWinnerBidderNo as $userId => $bidderNo) {
            $userIdHash = $userHashGenerator->generate($userId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
            $winnerBidderNoWithHashedUserId[$userIdHash] = $bidderNo;
        }
        return $winnerBidderNoWithHashedUserId;
    }
}
