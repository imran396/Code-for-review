<?php
/**
 * Build responses and save auction history messages to static file
 *
 * SAM-6527: Rtb refactor SellLots command
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\SellLots\Base\Internal\Response;

use Auction;
use RtbCurrent;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Base\ResponseHelper;
use Sam\Rtb\Command\Concrete\SellLots\Base\Internal\WinnerInfo\WinnerInfoDto;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\RtbMessengerCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class ResponseBuilder
 * @package Sam\Rtb
 * @method getAuction(bool $isReadOnlyDb = false) : Auction -  availability is checked at construct method
 */
class ResponseBuilder extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use CurrencyLoaderAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbMessengerCreateTrait;
    use RtbRendererCreateTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;
    use TranslatorAwareTrait;

    protected RtbCurrent $rtbCurrent;
    protected ResponseHelper $responseHelper;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Auction $auction
     * @param RtbCurrent $rtbCurrent
     * @param int $systemAccountId
     * @param ResponseHelper $responseHelper
     * @return $this
     */
    public function construct(
        Auction $auction,
        RtbCurrent $rtbCurrent,
        int $systemAccountId,
        ResponseHelper $responseHelper
    ): static {
        $this->setAuction($auction);
        $this->responseHelper = $responseHelper;
        $this->rtbCurrent = $rtbCurrent;
        $this->setSystemAccountId($systemAccountId);
        return $this;
    }

    /**
     * Responses are the same for command initiated from bidder and clerk consoles
     * @param array $auctionLots
     * @param float $hammerPrice
     * @param WinnerInfoDto $winnerInfoDto
     * @param bool $isLastLot
     * @param int|null $simultaneousAuctionId
     * @return array
     */
    public function makeResponses(
        array $auctionLots,
        float $hammerPrice,
        WinnerInfoDto $winnerInfoDto,
        bool $isLastLot,
        ?int $simultaneousAuctionId
    ): array {
        [$publicMessage, $adminMessage, $soldLotNo, $hammerPrices]
            = $this->determineResponseValues(
            $auctionLots,
            $hammerPrice,
            $winnerInfoDto->infoPublic,
            $winnerInfoDto->infoAdmin
        );

        $ownerBidderNums = $winnerInfoDto->ownerBidderNums;
        $username = $winnerInfoDto->username;

        $rtbCurrent = $this->rtbCurrent;
        $data = $this->responseHelper->getLotData($rtbCurrent);

        if ($isLastLot) {
            $data[Constants\Rtb::RES_STATUS] = '';
        }
        $gameStatus = $data[Constants\Rtb::RES_STATUS];

        $data[Constants\Rtb::RES_SOLD_LOT_NO] = $soldLotNo;
        $data[Constants\Rtb::RES_SOLD_LOT_HAMMER_PRICES] = $hammerPrices; // Sold lot
        $data[Constants\Rtb::RES_SOLD_LOT_WINNER_BIDDER_NO] = $this->responseHelper
            ->hashSoldLotWinnerBidderNoUserId($ownerBidderNums, $rtbCurrent); //user id hash, bidder num;
        $data[Constants\Rtb::RES_SOLD_LOT_WINNER_USERNAME] = $username;

        $clerkData = $auctioneerData = array_merge(
            $data,
            $this->getResponseDataProducer()->produceAdminSideData($rtbCurrent),
            [Constants\Rtb::RES_SOLD_LOT_WINNER_BIDDER_NO => $ownerBidderNums] //user id, bidder num
        );

        // Generate status messages

        [$publicMessage, $adminMessage] = $this->saveChatMessages(
            $gameStatus,
            $publicMessage,
            $adminMessage
        );

        // Make bidder/viewer/projector consoles responses

        $publicData = $data;
        $publicData[Constants\Rtb::RES_STATUS] = $publicMessage;
        $publicData = $this->responseHelper->removeSensitiveData($publicData);
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $publicData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;

        // Make auctioneer console response

        $auctioneerData = array_replace(
            $auctioneerData,
            $this->getResponseDataProducer()->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_AUCTIONEER)
        );
        $auctioneerData[Constants\Rtb::RES_STATUS] = $adminMessage;
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $auctioneerData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        // Make admin clerk console response

        $currentBidAmount = (float)$clerkData[Constants\Rtb::RES_CURRENT_BID];
        $clerkData = array_replace(
            $clerkData,
            $this->getResponseDataProducer()->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_CLERK),
            $this->getResponseDataProducer()->produceIncrementData($rtbCurrent, ['currentBid' => $currentBidAmount])
        );
        $clerkData[Constants\Rtb::RES_INCREMENT_RESTORE] = 0.;
        $clerkData[Constants\Rtb::RES_STATUS] = $adminMessage;
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $clerkData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;

        // Make simultaneous auction response

        $responses = $this->responseHelper->addForSimultaneousAuction(
            $responses,
            $simultaneousAuctionId,
            $publicMessage
        );

        // Make sms response

        if (!$isLastLot) {
            $isTextMsgEnabled = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::TEXT_MSG_ENABLED, $this->getAuction()->AccountId);
            $responses = $this->responseHelper->addSmsTextResponse(
                $responses,
                $this->getAuction(),
                $rtbCurrent->LotItemId,
                $isTextMsgEnabled
            );
        }

        return $responses;
    }

    /**
     * @param array $auctionLots
     * @param float $hammerPrice
     * @param string $winnerInfoPublic
     * @param string $winnerInfoAdmin
     * @return array
     */
    protected function determineResponseValues(
        array $auctionLots,
        float $hammerPrice,
        string $winnerInfoPublic,
        string $winnerInfoAdmin
    ): array {
        $adminMessage = '';
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign($this->getAuctionId());
        $hammerPriceFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($hammerPrice);
        $hammerPrices = [];
        $langLotSold = $this->getTranslator()->translateForRtb('BIDDERCLIENT_MSG_LOTSOLD', $this->getAuction());
        $publicMessage = '';
        $soldLotNo = [];
        foreach ($auctionLots as $auctionLot) {
            $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
            $soldLotNo[$auctionLot->LotItemId] = $lotNo;
            $hammerPrices[$auctionLot->LotItemId] = $hammerPrice;
            $adminMessage .= '|' . sprintf($langLotSold, $lotNo, $winnerInfoAdmin, $hammerPriceFormatted);
            $publicMessage .= '|' . sprintf($langLotSold, $lotNo, $winnerInfoPublic, $hammerPriceFormatted);
        }
        return [$publicMessage, $adminMessage, $soldLotNo, $hammerPrices];
    }

    /**
     * @param string $gameStatus
     * @param string $publicMessage
     * @param string $adminMessage
     * @return string[]
     */
    protected function saveChatMessages(string $gameStatus, string $publicMessage, string $adminMessage): array
    {
        $messenger = $this->createRtbMessenger();
        $rtbCurrent = $this->rtbCurrent;
        $auctionLot = $this->getAuctionLotLoader()->load($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);

        $shouldClearMessageCenterLog = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::CLEAR_MESSAGE_CENTER_LOG, $this->getAuction()->AccountId);
        if ($shouldClearMessageCenterLog) {
            $messenger->clearStaticMessage($this->getAuctionId(), true);
            $messenger->clearStaticMessage($this->getAuctionId());
        }

        $messageHtml = '';
        $adminMessages = explode('|', $adminMessage);
        foreach ($adminMessages as $adminMessagePart) {
            if ($adminMessagePart === '') {
                continue;
            }
            $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($adminMessagePart, $this->getAuction())
                . $messageHtml;
        }
        if ($gameStatus !== '') {
            $messageHtml .= $this->createRtbRenderer()->renderAuctioneerMessage($gameStatus, $this->getAuction())
                . $messageHtml;
        }

        if ($auctionLot) {
            $messageHtml = $this->createRtbRenderer()->renderQuantityHtml(
                    $this->getAuction(),
                    $auctionLot->LotItemId,
                    $auctionLot->Quantity,
                    $auctionLot->QuantityXMoney
                )
                . $messageHtml;
        }

        $messenger->createStaticHistoryMessage($this->getAuctionId(), $messageHtml, true);
        $messenger->createStaticHistoryMessage($this->getAuctionId(), $messageHtml);

        $adminMessage .= '| ' . $gameStatus;
        $publicMessage .= '| ' . $gameStatus;
        return [$publicMessage, $adminMessage];
    }
}
