<?php
/**
 * SAM-11182: Extract timed lot bidding logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\OfferBid\Place;

use Sam\Bidding\OfferBid\Place\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Storage\WriteRepository\Entity\TimedOnlineOfferBid\TimedOnlineOfferBidWriteRepositoryAwareTrait;
use TimedOnlineOfferBid;

/**
 * Class OfferBidSaver
 * @package Sam\Bidding\OfferBid\Place
 */
class OfferBidSaver extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use TimedOnlineOfferBidWriteRepositoryAwareTrait;
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function placeOfferBid(
        int $userId,
        int $auctionLotId,
        float $bidAmount,
        int $editorUserId,
        bool $isCounterBid = false,
        int $status = Constants\TimedOnlineOfferBid::STATUS_NONE
    ): TimedOnlineOfferBid {
        $timedOfferBid = $this->createDataProvider()->loadTimedOnlineOfferBid($userId, $auctionLotId);
        if ($timedOfferBid) {
            $timedOfferBid->Bid = $bidAmount;
            $timedOfferBid->Status = Constants\TimedOnlineOfferBid::STATUS_NONE;
            $timedOfferBid->IsCounterBid = $isCounterBid;
            if ($status !== Constants\TimedOnlineOfferBid::STATUS_NONE) {
                $timedOfferBid->Status = $status;
            }
        } else {
            $timedOfferBid = $this->createEntityFactory()->timedOnlineOfferBid();
            $timedOfferBid->UserId = $userId;
            $timedOfferBid->AuctionLotItemId = $auctionLotId;
            $timedOfferBid->Bid = $bidAmount;
            $timedOfferBid->IsCounterBid = $isCounterBid;
            if ($status !== Constants\TimedOnlineOfferBid::STATUS_NONE) {
                $timedOfferBid->Status = $status;
            }
            $timedOfferBid->DateAdded = $this->createDataProvider()->detectCurrentDateUtc();
        }
        $this->getTimedOnlineOfferBidWriteRepository()->saveWithModifier($timedOfferBid, $editorUserId);
        return $timedOfferBid;
    }
}
