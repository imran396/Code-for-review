<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Bidder\HouseBidder\Helper;
use Sam\Core\Constants;
use Sam\Rtb\Catalog\Bidder\Manage\BidderCatalogManagerFactoryCreateTrait;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\LotInfo\LotInfoServiceAwareTrait;
use Sam\Rtb\State\Reset\RtbStateResetterAwareTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;

/**
 * Class StopAuction
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class StopAuction extends CommandBase
{
    use AuctionWriteRepositoryAwareTrait;
    use BidderCatalogManagerFactoryCreateTrait;
    use LotInfoServiceAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbRendererCreateTrait;
    use RtbStateResetterAwareTrait;
    use UrlBuilderAwareTrait;

    public function execute(): void
    {
        $auction = $this->getAuction();
        $auction->toClosed();
        $auction->EndDate = $this->getCurrentDateUtc();
        $this->getAuctionWriteRepository()->saveWithModifier($auction, $this->detectModifierUserId());

        $catalogManager = $this->createCatalogManagerFactory()
            ->createByRtbCurrent($this->getRtbCurrent(), $this->getAuction()->AccountId, $this->getViewLanguageId());
        $catalogManager->deleteMobilePageFiles($auction->Id);
        $catalogManager->drop($auction->Id);

        $this->getLotInfoService()->deleteStaticFile($auction->Id);
        $this->getLotInfoService()->drop($auction->Id);

        $this->getRtbStateResetter()->resetByAuction($auction->Id, $this->detectModifierUserId());

        Helper::new()->unsoldLotsForHouseBidders($auction->Id, $this->detectModifierUserId());

        $this->createStaticMessages();
        $this->log();
    }

    protected function createResponses(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $data = $this->getResponseHelper()->getLotData($rtbCurrent);
        $data[Constants\Rtb::RES_STATUS] = $this->translate('BIDDERCLIENT_MSG_AUCCLOSED');
        $data[Constants\Rtb::RES_AUCTION_STATUS_ID] = Constants\Auction::AS_CLOSED;
        $responses = $this->makePublicConsoleResponses($data)
            + $this->makeAdminConsoleResponses($data);
        $this->setResponses($responses);
    }

    /**
     * Make viewer/projector/bidder console responses
     * @param array $publicData
     * @return array
     */
    protected function makePublicConsoleResponses(array $publicData): array
    {
        // Make viewer/projector consoles responses
        $publicData = $this->getResponseHelper()->removeSensitiveData($publicData);
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_STOP_S,
            Constants\Rtb::RES_DATA => $publicData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;

        // Make bidder console response
        $publicData[Constants\Rtb::RES_MY_WON_ITEMS_URL] = $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_ITEMS_WON)
        );
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_STOP_S,
            Constants\Rtb::RES_DATA => $publicData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;

        return $responses;
    }

    /**
     * Make admin/auctioneer console responses
     * @param array $data
     * @return array
     */
    protected function makeAdminConsoleResponses(array $data): array
    {
        $rtbCurrent = $this->getRtbCurrent();

        // Make mutual for admin consoles data
        $clerkData = array_merge(
            $data,
            $this->getResponseDataProducer()->produceAdminSideData($rtbCurrent)
        );
        $auctionResultUrl = $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_LIVE_AUCTION_RESULT, $this->getAuctionId())
        );
        $clerkData[Constants\Rtb::RES_AUCTION_RESULT_URL] = $auctionResultUrl;
        $bidHistoryUrl = $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_BID_HISTORY_CSV, $this->getAuctionId())
        );
        $clerkData[Constants\Rtb::RES_AUCTION_BID_HISTORY_URL] = $bidHistoryUrl;
        $auctioneerData = $clerkData;

        // Make clerk console response
        $currentBidAmount = (float)$clerkData[Constants\Rtb::RES_CURRENT_BID];
        $clerkData = array_merge(
            $clerkData,
            $this->getResponseDataProducer()->produceIncrementData($rtbCurrent, ['currentBid' => $currentBidAmount])
        );
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_STOP_S,
            Constants\Rtb::RES_DATA => $clerkData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;

        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_STOP_S,
            Constants\Rtb::RES_DATA => $auctioneerData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        return $responses;
    }

    /**
     * Log to auction trail
     */
    protected function log(): void
    {
        $message = $this->getLogger()->getUserRoleName($this->getUserType());
        $message .= ' stops auction' . composeSuffix(['a' => $this->getAuctionId()]);
        $this->getLogger()->log($message);
    }

    protected function createStaticMessages(): void
    {
        $auctionClosed = $this->translate('BIDDERCLIENT_MSG_AUCCLOSED');
        $message = $this->createRtbRenderer()->renderAuctioneerMessage($auctionClosed, $this->getAuction());
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message, true);
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message);
    }
}
