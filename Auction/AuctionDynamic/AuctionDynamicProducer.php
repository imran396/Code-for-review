<?php
/**
 * SAM-6019: Auction end date overhaul
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\AuctionDynamic;

use Auction;
use AuctionDynamic;
use LogicException;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AuctionDynamic\AuctionDynamicWriteRepositoryAwareTrait;

/**
 * Class AuctionDynamicProducer
 * @package Sam\Auction\AuctionDynamic
 */
class AuctionDynamicProducer extends CustomizableClass
{
    use AuctionDynamicWriteRepositoryAwareTrait;
    use AuctionLoaderAwareTrait;
    use EntityFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Create and fill auction dynamic entity object for the auction
     *
     * @param int $auctionId
     * @return AuctionDynamic
     */
    public function create(int $auctionId): AuctionDynamic
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            throw new LogicException('Cannot create AuctionDynamic record, because auction not found' . composeSuffix(['a' => $auctionId]));
        }
        $auctionDynamic = $this->buildAuctionDynamic($auction);
        return $auctionDynamic;
    }

    /**
     * Create and save auction dynamic entity object for the auction
     *
     * @param int $auctionId
     * @param int $editorUserId
     * @return AuctionDynamic
     */
    public function createPersisted(int $auctionId, int $editorUserId): AuctionDynamic
    {
        $auctionDynamic = $this->create($auctionId);
        $this->getAuctionDynamicWriteRepository()->saveWithModifier($auctionDynamic, $editorUserId);
        return $auctionDynamic;
    }

    /**
     * @param Auction $auction
     * @return AuctionDynamic
     */
    private function buildAuctionDynamic(Auction $auction): AuctionDynamic
    {
        $auctionDynamic = $this->createEntityFactory()->auctionDynamic();
        $auctionDynamic->AuctionId = $auction->Id;
        $auctionDynamic->ExtendAllStartClosingDate = $auction->StartClosingDate;
        return $auctionDynamic;
    }
}
