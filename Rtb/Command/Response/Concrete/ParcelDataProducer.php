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

use RtbCurrent;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\LotGroup\Load\LotGroupAuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionLotAwareTrait;

/**
 * Class ResponseDataProducer
 * @package Sam\Rtb\Command\Response
 */
class ParcelDataProducer extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionLotAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use LotGroupAuctionLotLoaderAwareTrait;

    protected ?RtbCurrent $rtbCurrent = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function isParcelAvailable(): bool
    {
        // parcel data shouldn't be available when lots are already grouped
        $is = !$this->getRtbCurrent()->LotGroup
            && $this->getAuction()
            && $this->getAuctionLot()
            && $this->getAuctionLot()->GroupId;
        return $is;
    }

    /**
     * Generate data related to lot grouping via parcel id feature
     * We send lot ids to perform their grouping (on admin console) corresponding to "Parcel Choice" value
     * @return array
     */
    public function produceData(): array
    {
        $auctionLots = $this->getLotGroupAuctionLotLoader()
            ->loadAvailable($this->getAuctionId(), $this->getAuctionLot()->GroupId);
        $lotItemIds = [];
        foreach ($auctionLots as $auctionLotFromGroup) {
            if ($auctionLotFromGroup->LotItemId === $this->getRtbCurrent()->LotItemId) {
                continue;
            }
            $lotItemIds[] = $auctionLotFromGroup->LotItemId;
        }
        $data[Constants\Rtb::RES_GROUP_LOT_ITEM_IDS] = $lotItemIds;
        $data[Constants\Rtb::RES_IS_PARCEL_CHOICE] = $this->getAuction()->ParcelChoice;
        return $data;
    }

    /**
     * @return RtbCurrent
     */
    public function getRtbCurrent(): RtbCurrent
    {
        return $this->rtbCurrent;
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return static
     */
    public function setRtbCurrent(RtbCurrent $rtbCurrent): static
    {
        $this->rtbCurrent = $rtbCurrent;
        $this->setAuctionId($rtbCurrent->AuctionId);
        $auctionLot = $this->getAuctionLotLoader()->load($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $this->setAuctionLot($auctionLot);
        return $this;
    }
}
