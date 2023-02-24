<?php
/**
 * AuctionRtbd Loader class
 *
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 13, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Auction\Load;

use AuctionRtbd;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Rtb\Pool\Auction\Save\AuctionRtbdProducerAwareTrait;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionRtbd\AuctionRtbdReadRepositoryCreateTrait;

/**
 * Class AuctionRtbdLoader
 * @package Sam\Auction\Load
 */
class AuctionRtbdLoader extends EntityLoaderBase
{
    use AuctionRtbdProducerAwareTrait;
    use AuctionRtbdReadRepositoryCreateTrait;
    use CurrentDateTrait;
    use EditorUserAwareTrait;
    use EntityMemoryCacheManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $auctionId auction.id
     * @param bool $isReadOnlyDb
     * @return AuctionRtbd|null
     */
    public function load(?int $auctionId, bool $isReadOnlyDb = false): ?AuctionRtbd
    {
        $auctionId = Cast::toInt($auctionId, Constants\Type::F_INT_POSITIVE);
        if (!$auctionId) {
            return null;
        }

        $auctionRtbd = $this->createAuctionRtbdReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->loadEntity();
        return $auctionRtbd;
    }

    /**
     * @param int|null $auctionId auction.id
     * @param bool $isReadOnlyDb
     * @return AuctionRtbd
     */
    public function loadOrCreate(?int $auctionId, bool $isReadOnlyDb = false): AuctionRtbd
    {
        $auctionRtbd = $this->load($auctionId, $isReadOnlyDb);
        if (!$auctionRtbd) {
            $auctionRtbd = $this->getAuctionRtbdProducer()
                ->setAuctionId($auctionId)
                ->setEditorUserId($this->getEditorUserId())
                ->create();
        }
        return $auctionRtbd;
    }
}
