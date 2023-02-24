<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Discovery\Strategy\Fair\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Pool\Instance\RtbdDescriptor;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepository;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;

/**
 * Class RtbdLoadingDecisionDataLoader
 * @package Sam\Rtb\Pool\Discovery\Strategy\Fair\Load
 */
class RtbdLoadingDatesRangeDecisionDataLoader extends CustomizableClass
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
     * @param array|RtbdDescriptor[] $rtbdList
     * @return array
     */
    public function load(array $rtbdList): array
    {
        $rtbdNames = array_map(
            static function (RtbdDescriptor $rtbd) {
                return $rtbd->getName();
            },
            $rtbdList
        );
        return $this->prepareRepository($rtbdNames)->loadRows();
    }

    /**
     * @param array $rtbdNames
     * @return AuctionReadRepository
     */
    private function prepareRepository(array $rtbdNames): AuctionReadRepository
    {
        $repository = $this->createAuctionReadRepository()->enableReadOnlyDb(true);
        $repository
            ->select(
                [
                    'a.start_closing_date',
                    'a.end_date',
                    'artbd.rtbd_name'
                ]
            )
            ->filterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->filterAuctionType(Constants\Auction::RTB_AUCTION_TYPES)
            ->joinAuctionRtbdFilterRtbdName($rtbdNames);
        return $repository;
    }
}
