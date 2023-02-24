<?php
/**
 * Produce rtb response data
 *
 * SAM-5201: Apply constants for response data and keys
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/23/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Response\Concrete;

use Auction;
use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Group\GroupingHelperAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;

/**
 * Class ResponseDataProducer
 * @package Sam\Rtb\Command\Response
 */
class GroupDataProducer extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use GroupingHelperAwareTrait;
    use LotRendererAwareTrait;
    use TranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Generate data related to lot grouping current state
     * @param RtbCurrent $rtbCurrent
     * @return array
     */
    public function produceData(RtbCurrent $rtbCurrent): array
    {
        $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId);
        if (!$auction) {
            log_error(
                "Available auction not found, when creating group response data"
                . composeSuffix(['a' => $rtbCurrent->AuctionId])
            );
            return [];
        }

        $rtbLotGroup = $rtbCurrent->LotGroup;
        if (!$rtbLotGroup) {
            return [];
        }

        $lotItemId = $rtbCurrent->LotItemId;
        $lotItemIds = [];
        $groupTitles = [];

        $lotRenderer = $this->getLotRenderer();
        $auctionLotLoader = $this->getAuctionLotLoader();
        $auctionLot = $auctionLotLoader->load($lotItemId, $auction->Id);
        $lotNo = $lotRenderer->renderLotNo($auctionLot);

        $tr = $this->getTranslator();
        $rtbGeneralHelper = $this->getRtbGeneralHelper();

        $langGroupingType = $this->findGroupTranslation($rtbLotGroup, $auction);
        if ($langGroupingType !== '') {
            $langGroupingType .= ': ';
        }
        $langGroupingType .= $tr->translateForRtb('BIDDERCLIENT_LOT_GROUP', $auction);
        $title = "{$langGroupingType} {$lotNo} + ";
        $groupTitles[] = $rtbGeneralHelper->clean($title);
        $message = sprintf(
            '%s %s %s ',
            $tr->translateForRtb('BIDDERCLIENT_LOT_GROUP', $auction),
            $lotNo,
            $tr->translateForRtb('BIDDERCLIENT_GROUP_WILL_BE_SOLD', $auction)
        );

        $rtbCurrentGroupRecords = $this->getGroupingHelper()->loadGroups($auction->Id, null, [$lotItemId]);
        foreach ($rtbCurrentGroupRecords as $rtbCurrentGroup) {
            $auctionLotFromGroup = $auctionLotLoader->load($rtbCurrentGroup->LotItemId, $auction->Id, true);
            if ($auctionLotFromGroup) {
                $lotItemIds[] = $rtbCurrentGroup->LotItemId;
                $lotNoFromGroup = $lotRenderer->renderLotNo($auctionLotFromGroup);
                $message .= "{$lotNoFromGroup},";
                $groupTitles[] = [
                    $auctionLotFromGroup->LotItemId,
                    $rtbGeneralHelper->clean($lotNoFromGroup),
                    $auctionLotFromGroup->SeoUrl,
                ];
            }
        }

        $message = trim($message, ', ');
        $langGroupingType = $this->findGroupTranslation($rtbLotGroup, $auction);
        $message .= $rtbLotGroup === Constants\Rtb::GROUP_ALL_FOR_ONE && $langGroupingType !== ''
            ? " ({$langGroupingType})"
            : $langGroupingType;
        $message = rtrim($message, ',');

        $data[Constants\Rtb::RES_GROUP] = $rtbLotGroup;
        $data[Constants\Rtb::RES_GROUP_LOT_ITEM_IDS] = $lotItemIds;
        $data[Constants\Rtb::RES_GROUP_TITLE] = $groupTitles;
        $data[Constants\Rtb::RES_GROUP_MESSAGE] = $message;

        return $data;
    }

    /**
     * @param string $rtbLotGroup
     * @param Auction $auction
     * @return string
     */
    protected function findGroupTranslation(string $rtbLotGroup, Auction $auction): string
    {
        if ($rtbLotGroup === Constants\Rtb::GROUP_ALL_FOR_ONE) {
            return $this->getTranslator()->translateForRtb('BIDDERCLIENT_GROUP_ALL_FOR_ONE', $auction);
        }
        if ($rtbLotGroup === Constants\Rtb::GROUP_X_THE) {
            return $this->getTranslator()->translateForRtb('BIDDERCLIENT_GROUP_X_THE', $auction);
        }
        if ($rtbLotGroup === Constants\Rtb::GROUP_CHOICE) {
            return $this->getTranslator()->translateForRtb('BIDDERCLIENT_GROUP_CHOICE', $auction);
        }
        if ($rtbLotGroup === Constants\Rtb::GROUP_QUANTITY) {
            return $this->getTranslator()->translateForRtb('BIDDERCLIENT_GROUP_QUANTITY', $auction);
        }
        return '';
    }
}
