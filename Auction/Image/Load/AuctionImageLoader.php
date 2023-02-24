<?php
/**
 * Help methods for auction image loading
 *
 * SAM-4746: AuctionImage loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 26, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Image\Load;

use AuctionImage;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\AuctionImage\AuctionImageReadRepositoryCreateTrait;

/**
 * Class AuctionImageLoader
 * @package Sam\Auction\Image\Load
 */
class AuctionImageLoader extends EntityLoaderBase
{
    use AuctionImageReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AuctionImage[]
     */
    public function loadAll(bool $isReadOnlyDb = false): array
    {
        $allAuctionImages = $this->createAuctionImageReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->loadEntities();
        return $allAuctionImages;
    }

    /**
     * Get first image of an auction
     *
     * @param int $auctionId auction.id
     * @param bool $isReadOnlyDb
     * @return AuctionImage|null
     */
    public function loadDefault(int $auctionId, bool $isReadOnlyDb = false): ?AuctionImage
    {
        $auctionImage = $this->createAuctionImageReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->orderById()
            ->loadEntity();
        return $auctionImage;
    }
}
