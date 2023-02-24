<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Group\GroupingHelperAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class GroupLots
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class GroupLots extends CommandBase implements RtbCommandHelperAwareInterface
{
    use GroupingHelperAwareTrait;
    use LotRendererAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbRendererCreateTrait;

    protected ?string $groupType = null;
    protected array $groupLotItemIds = [];

    /**
     * @param string $groupType
     * @return static
     */
    public function setGroupType(string $groupType): static
    {
        $this->groupType = Cast::toString($groupType, Constants\Rtb::$groupTypes);
        return $this;
    }

    /**
     * @param int[] $lotItemIds
     * @return static
     */
    public function setLotItemIds(array $lotItemIds): static
    {
        $this->groupLotItemIds = $lotItemIds;
        return $this;
    }

    public function execute(): void
    {
        if (
            !$this->checkConsoleSync()
            || !$this->checkRunningLot()
            || !$this->checkSpecials()
        ) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        $rtbCurrent = $this->getRtbCurrent();
        $runningAuctionLot = $this->getAuctionLot();
        $lotNo = $this->getLotRenderer()->renderLotNo($runningAuctionLot);
        $rtbCurrent->LotGroup = $this->groupType;
        $newBidBy = $rtbCurrent->NewBidBy;

        $this->getGroupingHelper()->clearGroup($this->getAuctionId());

        $groupedLotItemIds = [];
        $titles = [];
        $groupedLotList = $groupingType = '';

        if ($rtbCurrent->LotGroup === Constants\Rtb::GROUP_ALL_FOR_ONE) {
            $groupingType = $this->translate('BIDDERCLIENT_GROUP_ALL_FOR_ONE');
            if ($rtbCurrent->AbsenteeBid) {
                $rtbCurrent->NewBidBy = null;
                $rtbCurrent->AbsenteeBid = false;
            } else {
                $rtbCurrent->NewBidBy = null;
            }
        } elseif ($rtbCurrent->LotGroup === Constants\Rtb::GROUP_X_THE) {
            $groupingType = $this->translate('BIDDERCLIENT_GROUP_X_THE');
            if ($rtbCurrent->AbsenteeBid) {
                $rtbCurrent->NewBidBy = null;
                $rtbCurrent->AbsenteeBid = false;
            } else {
                $rtbCurrent->NewBidBy = null;
            }
        } elseif ($rtbCurrent->LotGroup === Constants\Rtb::GROUP_CHOICE) {
            $groupingType = $this->translate('BIDDERCLIENT_GROUP_CHOICE');
            $rtbCurrent->NewBidBy = null;
        } elseif ($rtbCurrent->LotGroup === Constants\Rtb::GROUP_QUANTITY) {
            $groupingType = $this->translate('BIDDERCLIENT_GROUP_QUANTITY');
            $rtbCurrent->NewBidBy = null;
        }
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
        $rtbGeneralHelper = $this->getRtbGeneralHelper();
        if ($groupingType !== '') {
            $groupingType .= ': ';
        }
        $groupingType .= $this->translate('BIDDERCLIENT_LOT_GROUP');
        $title = "{$groupingType} {$lotNo} + ";
        $titles[] = $rtbGeneralHelper->clean($title);

        $groupingMessage = $this->translate('BIDDERCLIENT_LOT_GROUP') . ' ' . $lotNo
            . ' ' . $this->translate('BIDDERCLIENT_GROUP_WILL_BE_SOLD') . ' ';

        if (!empty($this->groupLotItemIds)) {
            $this->groupLotItemIds[] = $rtbCurrent->LotItemId;
            $this->groupLotItemIds = $this->getGroupingHelper()->loadOrderedIds($this->groupLotItemIds, $this->getAuctionId());
            foreach ($this->groupLotItemIds as $groupLotItemId) {
                $groupAuctionLot = $this->getAuctionLotLoader()->load($groupLotItemId, $this->getAuctionId());
                if ($groupAuctionLot) {
                    $this->getGroupingHelper()->addToGroup($this->getAuctionId(), $groupLotItemId, $this->detectModifierUserId());
                    if ($groupLotItemId !== $rtbCurrent->LotItemId) {
                        $lotNoX = $this->getLotRenderer()->renderLotNo($groupAuctionLot);
                        $groupedLotItemIds[] = $groupLotItemId;
                        $titles[] = [
                            $groupLotItemId,
                            $rtbGeneralHelper->clean($lotNoX),
                            $groupAuctionLot->SeoUrl,
                        ];
                        $groupingMessage .= $lotNoX . ',';
                        $groupedLotList .= $lotNoX . ',';
                    }
                }
            }
        }

        $groupingMessage = trim($groupingMessage, ',');
        $groupedLotList = trim($groupedLotList, ',');

        $this->getLogger()->log("Admin clerk groups lot {$groupedLotList} to {$lotNo} ");

        if ($this->groupType === Constants\Rtb::GROUP_ALL_FOR_ONE) {
            $groupingMessage .= ' (' . $this->translate('BIDDERCLIENT_GROUP_ALL_FOR_ONE');
        } elseif ($this->groupType === Constants\Rtb::GROUP_X_THE) {
            $groupingMessage .= ' (' . $this->translate('BIDDERCLIENT_GROUP_X_THE');
        } elseif ($this->groupType === Constants\Rtb::GROUP_CHOICE) {
            $groupingMessage .= ' (' . $this->translate('BIDDERCLIENT_GROUP_CHOICE');
        } elseif ($this->groupType === Constants\Rtb::GROUP_QUANTITY) {
            $groupingMessage .= ' (' . $this->translate('BIDDERCLIENT_GROUP_QUANTITY');
        }

        $data = [
            Constants\Rtb::RES_GROUP => $this->groupType,
            Constants\Rtb::RES_GROUP_LOT_ITEM_IDS => $groupedLotItemIds,
            Constants\Rtb::RES_GROUP_MESSAGE => $groupingMessage,
            Constants\Rtb::RES_GROUP_TITLE => $titles,
            Constants\Rtb::RES_IS_ABSENTEE_BID => $rtbCurrent->AbsenteeBid,
            Constants\Rtb::RES_LOT_ACTIVITY => $rtbCurrent->LotActive,
            Constants\Rtb::RES_LOT_ITEM_ID => $rtbCurrent->LotItemId,
        ];

        $message = $this->createRtbRenderer()->renderAuctioneerMessage($groupingMessage . ' !!', $this->getAuction());
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message, true);
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message);

        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_GROUP_LOT_S,
            Constants\Rtb::RES_DATA => $data,
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        $responses[Constants\Rtb::RT_BIDDER] = json_encode($response);
        if ($newBidBy) {
            $deniedBidMessage = $this->buildDeniedBidAcceptingMessage();
            $deniedBidAcceptingResponse = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_GROUP_LOT_S,
                Constants\Rtb::RES_DATA => [
                    Constants\Rtb::RES_MESSAGE => $deniedBidMessage,
                ],
            ];
            $responses[Constants\Rtb::RT_INDIVIDUAL] = [$newBidBy, json_encode($deniedBidAcceptingResponse)];
        }
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $groupingMessage
        );

        $this->setResponses($responses);
    }

    /**
     * Get message when bid was placed before lots grouping
     * @return string
     */
    public function buildDeniedBidAcceptingMessage(): string
    {
        $langDenied = $this->translate('BIDDERCLIENT_BIDDENIED_NOTACCEPTED');
        return '<span class="bid-denied">' . $langDenied . '</span>';
    }

    /**
     * Validate request input data special for GroupLots command
     * @return bool
     */
    protected function checkSpecials(): bool
    {
        if (!$this->groupType || !$this->groupLotItemIds) {
            $logData = [
                'group type' => $this->groupType,
                'group li' => $this->groupLotItemIds,
                'li' => $this->getRtbCurrent()->LotItemId,
                'a' => $this->getRtbCurrent()->AuctionId,
            ];
            log_warning("Incorrect input data" . composeSuffix($logData));
            return false;
        }
        return true;
    }
}
