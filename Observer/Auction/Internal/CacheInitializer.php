<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\Auction\Internal;

use Auction;
use Sam\Auction\Cache\AuctionDetailsDbCacheManagerCreateTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Storage\WriteRepository\Entity\AuctionCache\AuctionCacheWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Right after NEW auction is saved, we should create AuctionCache record for it.
 * We don't need to calculate its values yet, we check auction's affected fields lower and drop modification ts.
 *
 * Class CacheInitializer
 * @package Sam\Observer\Auction\Internal
 */
class CacheInitializer extends CustomizableClass implements EntityCreationObserverHandlerInterface
{
    use AuctionCacheWriteRepositoryAwareTrait;
    use AuctionDetailsDbCacheManagerCreateTrait;
    use EntityFactoryCreateTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function onCreate(EntityObserverSubject $subject): void
    {
        /** @var Auction $auction */
        $auction = $subject->getEntity();
        $auctionCache = $this->createEntityFactory()->auctionCache();
        $auctionCache->AuctionId = $auction->Id;
        $this->getAuctionCacheWriteRepository()->saveWithSystemModifier($auctionCache);
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->createAuctionDetailsDbCacheManager()->createInitialRecords($auction->Id, $editorUserId);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return true;
    }
}
