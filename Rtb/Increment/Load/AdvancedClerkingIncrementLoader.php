<?php
/**
 * SAM-5346: Rtb asking bid calculator
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: AdvancedClerkingIncrementLoader: $
 * @since           9/10/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Increment\Load;

use BidIncrement;
use RtbCurrentIncrement;
use Sam\Bidding\BidIncrement\Load\BidIncrementLoaderAwareTrait;
use Sam\Bidding\BidIncrement\Validate\BidIncrementExistenceCheckerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\RtbCurrentIncrement\RtbCurrentIncrementReadRepositoryCreateTrait;

/**
 * Class AdvancedClerkingIncrementLoader
 * @package
 */
class AdvancedClerkingIncrementLoader extends CustomizableClass
{
    use BidIncrementExistenceCheckerAwareTrait;
    use BidIncrementLoaderAwareTrait;
    use RtbCurrentIncrementReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int|null $lotItemId
     * @param bool $isReadOnlyDb
     * @return BidIncrement[]|RtbCurrentIncrement[]
     */
    public function loadEntities(int $auctionId, ?int $lotItemId = null, bool $isReadOnlyDb = false): array
    {
        // check first if increments are defined at lot level
        if ($this->getBidIncrementExistenceChecker()->existForLot($lotItemId, $isReadOnlyDb)) {
            $increments = $this->getBidIncrementLoader()->loadForLot($lotItemId, $isReadOnlyDb);
        } else {
            $increments = $this->createRtbCurrentIncrementReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterAuctionId($auctionId)
                ->loadEntities();
        }
        return $increments;
    }

    /**
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return RtbCurrentIncrement|null
     */
    public function loadFirstForAuction(int $auctionId, bool $isReadOnlyDb = false): ?RtbCurrentIncrement
    {
        return $this->createRtbCurrentIncrementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->orderByIncrement()
            ->loadEntity();
    }
}
