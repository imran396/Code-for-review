<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 05, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Discovery\Strategy\Fair\Load;

use DateTime;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Pool\Instance\RtbdDescriptor;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepository;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;

/**
 * Class AuctionDataLoader
 * @package Sam\Rtb\Pool\Discovery\Strategy\Fair\Load
 */
class RtbdLoadingLevelLoader extends CustomizableClass
{
    use AuctionReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param DateTime $dateRangeStart
     * @param DateTime $dateRangeEnd
     * @param array|RtbdDescriptor[] $rtbdList
     * @return array
     */
    public function load(DateTime $dateRangeStart, DateTime $dateRangeEnd, array $rtbdList): array
    {
        $rtbdNames = array_map(
            static function (RtbdDescriptor $rtbd) {
                return $rtbd->getName();
            },
            $rtbdList
        );
        return $this
            ->prepareRepository($dateRangeStart, $dateRangeEnd, $rtbdNames)
            ->loadRows();
    }

    /**
     * @param DateTime $dateRangeStart
     * @param DateTime $dateRangeEnd
     * @param array $rtbdNames
     * @return AuctionReadRepository
     */
    private function prepareRepository(DateTime $dateRangeStart, DateTime $dateRangeEnd, array $rtbdNames): AuctionReadRepository
    {
        $repository = $this->createAuctionReadRepository()->enableReadOnlyDb(true);

        $repository
            ->select(
                [
                    'artbd.rtbd_name as name',
                    'COUNT(1) as auctionsQty'
                ]
            )
            ->filterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->filterAuctionType(Constants\Auction::RTB_AUCTION_TYPES)
            ->filterStartClosingDateGreaterOrEqual($dateRangeStart->format(Constants\Date::ISO))
            ->filterStartClosingDateLessOrEqual($dateRangeEnd->format(Constants\Date::ISO))
            ->joinAuctionRtbdFilterRtbdName($rtbdNames);

        $repository->getQueryBuilder()
            ->group('artbd.rtbd_name')
            ->order('COUNT(1)');

        return $repository;
    }
}
