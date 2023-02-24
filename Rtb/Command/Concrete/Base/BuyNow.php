<?php

namespace Sam\Rtb\Command\Concrete\Base;

use AuctionLotItem;
use LotItem;
use Sam\AuctionLot\Load\PositionalAuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Validate\AuctionLotExistenceCheckerAwareTrait;
use Sam\Bidder\BidderInfo\BidderInfoRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Catalog\Bidder\Manage\BidderCatalogManagerFactoryCreateTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\LotInfo\LotInfoServiceAwareTrait;
use Sam\Rtb\State\Reset\RtbStateResetterAwareTrait;
use Sam\Rtb\State\RtbStateUpdaterCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepositoryCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class BuyNow
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class BuyNow extends CommandBase implements RtbCommandHelperAwareInterface
{
    use AuctionBidderReadRepositoryCreateTrait;
    use AuctionLotExistenceCheckerAwareTrait;
    use BidderInfoRendererAwareTrait;
    use BidderCatalogManagerFactoryCreateTrait;
    use LotInfoServiceAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use PositionalAuctionLotLoaderAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbRendererCreateTrait;
    use RtbStateResetterAwareTrait;
    use RtbStateUpdaterCreateTrait;

    protected ?float $buyAmount = null;
    protected ?int $buyLotItemId = null;

    public function execute(): void
    {
        if (!$this->checkProcessingLot()) {
            log_error(
                "Available lot not found for Buy Now command"
                . composeSuffix(
                    [
                        'li' => $this->buyLotItemId,
                        'a' => $this->getAuctionId(),
                        'u' => $this->getEditorUserId(),
                    ]
                )
            );
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        /**
         * The RTBD shall then send a command to the respective clients
         * (clerk, assistant, Client, bidder) connected to this auction
         * about the lot being sold through buy now.
         * It shall als update the static catalog file
         * (see Clerk/ Assistant Clerk/ RTBD 1)
         */

        /** @var LotItem $buyLotItem - existence checked in checkProcessingLot() */
        $buyLotItem = $this->getLotItemLoader()->load($this->buyLotItemId);
        $buyLotItemId = $buyLotItem->Id;
        /** @var AuctionLotItem $buyAuctionLot - existence checked in checkProcessingLot() */
        $buyAuctionLot = $this->getAuctionLotLoader()->load($buyLotItemId, $this->getAuctionId());

        $rtbCurrent = $this->getRtbCurrent();
        $catalogManager = $this->createCatalogManagerFactory()
            ->createByRtbCurrent($rtbCurrent, $this->getAuction()->AccountId, $this->getViewLanguageId());
        $catalogManager->updateRow($buyAuctionLot);
        $this->getLotInfoService()->drop($this->getAuctionId());

        $username = $this->getUserLoader()->load($this->getEditorUserId())->Username;
        $bidderNum = '';
        $bidderInfo = '';
        $auctionBidder = $this->getAuctionBidderLoader()->load($this->getEditorUserId(), $this->getAuctionId(), true);
        if ($auctionBidder) {
            $bidderNum = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
            $bidderInfo = $this->getBidderInfoRenderer()->renderForAdminRtb($this->getEditorUserId(), $this->getAuctionId());
        }

        $displayBidderInfo = (string)$this->getSettingsManager()
            ->get(Constants\Setting::DISPLAY_BIDDER_INFO, $this->getAuction()->AccountId);
        if ($displayBidderInfo !== Constants\SettingAuction::DBI_NUMBER) {
            $bidderLog = $bidderInfo . ' ' . $bidderNum;
        } else {
            $bidderLog = $bidderInfo;
        }
        $lotNo = $this->getLotRenderer()->renderLotNo($buyAuctionLot);
        $logData = [
            'u' => $this->getEditorUserId(),
            'li' => $buyLotItemId,
            'lot#' => $lotNo,
            'price' => $this->buyAmount,
        ];
        $this->getLogger()->log("Bidder {$bidderLog} bought lot via Buy Now command" . composeSuffix($logData));

        if ($rtbCurrent->LotItemId !== $buyLotItemId) {
            $data = [
                Constants\Rtb::RES_LOT_ITEM_ID => $buyLotItemId,
                Constants\Rtb::RES_HAMMER_PRICE => $this->buyAmount,
            ];
            $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_BUY_NOW_S, Constants\Rtb::RES_DATA => $data];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
            $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
            $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;

            $data[Constants\Rtb::RES_USER_ID] = $this->getEditorUserId();
            $data[Constants\Rtb::RES_MESSAGE_SENDER_USERNAME] = $username;
            $data[Constants\Rtb::RES_MESSAGE_SENDER_BIDDER_NO] = $bidderNum;

            $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_BUY_NOW_S, Constants\Rtb::RES_DATA => $data];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_CLERK] = $responseJson;
            $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;
        } else {
            /*
             * This function was retired
             * SAM-6581: Retire rtb function that allows to Buy Now running lot
             *
             * 2) If the lot sold through buy now is the current lot,
             * there shall be a message in the message center that the lot
             * is sold through buy now and the lot shall close and move to
             * the next lot (if that option is enabled)
            */
            $logData = ['li' => $buyLotItemId, 'a' => $this->getAuctionId(), 'u' => $this->getEditorUserId()];
            log_error('Active lot sold through buy now ' . composeSuffix($logData));
        }

        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_BUY_NOW_S,
            Constants\Rtb::RES_DATA => [
                Constants\Rtb::RES_CONFIRM => 1,
            ],
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_SINGLE] = $responseJson;

        $this->setResponses($responses);
    }

    /**
     * @param float|null $buyAmount
     * @return static
     */
    public function setBuyAmount(?float $buyAmount): static
    {
        $this->buyAmount = $buyAmount;
        return $this;
    }

    /**
     * @param int|null $buyLotItemId
     * @return static
     */
    public function setBuyLotItemId(?int $buyLotItemId): static
    {
        $this->buyLotItemId = $buyLotItemId;
        return $this;
    }

    /**
     * Check purchased lot exists in auction
     * @return bool
     */
    protected function checkProcessingLot(): bool
    {
        $isFound = $this->getAuctionLotExistenceChecker()->exist($this->buyLotItemId, $this->getAuctionId());
        return $isFound;
    }
}
