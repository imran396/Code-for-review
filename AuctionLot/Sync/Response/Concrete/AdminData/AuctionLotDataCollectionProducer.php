<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\AdminData;

use Sam\AuctionLot\Sync\Response\Concrete\AdminData\Dto\SyncAdminAuctionLotDto;
use Sam\AuctionLot\Sync\Response\Concrete\AdminData\Internal\AuctionLotDataMessageFactoryCreateTrait;
use Sam\AuctionLot\Sync\Response\Concrete\AdminData\Internal\Load\AdminDataLoaderCreateTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;

/**
 * Provides up-to-date auction lots data, which is used to synchronize admin lots list page
 *
 * Class AuctionLotDataCollectionProducer
 * @package Sam\AuctionLot\Sync\Response\Concrete\AdminData
 */
class AuctionLotDataCollectionProducer extends CustomizableClass
{
    use AdminDataLoaderCreateTrait;
    use AuctionLotDataMessageFactoryCreateTrait;

    /**
     * @var array
     */
    protected array $rtbCurrentLotOrderNumCache = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Fetch up-to-date auction lots data and put them to the protobuf message objects
     *
     * @param array $auctionLotIds
     * @param int $auctionId
     * @param bool $isProfilingEnabled
     * @return array
     */
    public function produce(array $auctionLotIds, int $auctionId, bool $isProfilingEnabled = false): array
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $auctionLotDataMessageFactory = $this->createAuctionLotDataMessageFactory();
        $auctionLotDtos = $this->createAdminDataLoader()->loadAuctionLotDtos($auctionLotIds, $auctionId, $isProfilingEnabled);

        $collection = [];
        foreach ($auctionLotDtos as $auctionLotDto) {
            $auctionLotId = $auctionLotDto->auctionLotId;
            if ($auctionStatusPureChecker->isTimed($auctionLotDto->auctionType)) {
                $collection[$auctionLotId] = $auctionLotDataMessageFactory->createForTimedAuction($auctionLotDto);
            } elseif ($auctionStatusPureChecker->isLive($auctionLotDto->auctionType)) {
                $collection[$auctionLotId] = $auctionLotDataMessageFactory->createForLiveAuction($auctionLotDto);
            } else {
                $rtbCurrentLotOrderNum = $this->fetchRtbCurrentLotOrderNum($auctionLotDto);
                $collection[$auctionLotId] = $auctionLotDataMessageFactory->createForHybridAuction($auctionLotDto, $rtbCurrentLotOrderNum);
            }
        }
        return $collection;
    }

    /**
     * @param SyncAdminAuctionLotDto $auctionLotDto
     * @return int|null
     */
    protected function fetchRtbCurrentLotOrderNum(SyncAdminAuctionLotDto $auctionLotDto): ?int
    {
        if (!$auctionLotDto->rtbCurrentLotItemId) {
            return null;
        }
        $key = $auctionLotDto->auctionId . '.' . $auctionLotDto->rtbCurrentLotItemId;
        if (!array_key_exists($key, $this->rtbCurrentLotOrderNumCache)) {
            $this->rtbCurrentLotOrderNumCache[$key] = $this->createAdminDataLoader()
                ->loadAuctionLotOrderNum($auctionLotDto->auctionId, $auctionLotDto->rtbCurrentLotItemId);
        }
        return $this->rtbCurrentLotOrderNumCache[$key];
    }
}
