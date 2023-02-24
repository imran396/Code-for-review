<?php
/**
 * SAM-6459: Rtbd response - lot data producers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Response\Concrete;

use Sam\Core\Service\CustomizableClass;
use RtbCurrent;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Lot\Load\LotItemLoaderAwareTrait;

/**
 * Class BiddingDataProducer
 * @package Sam\Rtb\Command\Response\Concrete
 */
class BidDataProducer extends CustomizableClass
{
    use HighBidDetectorCreateTrait;
    use LotItemLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @param array $optionals = [
     *  'askingBid' => float,
     *  'currentBid' => float,
     *  'startingBid' => float,
     * ]
     * @return array = [
     *  Constants\Rtb::RES_ASKING_BID => float,
     *  Constants\Rtb::RES_CURRENT_BID => float,
     *  Constants\Rtb::RES_STARTING_BID => float
     * ]
     */
    public function produceData(RtbCurrent $rtbCurrent, array $optionals = []): array
    {
        $askingBid = $optionals['askingBid'] ?? $rtbCurrent->AskBid;
        $currentBid = $optionals['currentBid']
            ?? $this->createHighBidDetector()->detectAmount($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $startingBid = $optionals['startingBid'] ?? null;
        if (!$startingBid) {
            $lotItem = $this->getLotItemLoader()->load($rtbCurrent->LotItemId);
            $startingBid = $lotItem->StartingBid ?? null;
        }
        $data = [
            Constants\Rtb::RES_ASKING_BID => $askingBid,
            Constants\Rtb::RES_CURRENT_BID => $currentBid,
            Constants\Rtb::RES_STARTING_BID => $startingBid
        ];
        return $data;
    }
}
