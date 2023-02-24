<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 13, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\AuctionCache\Internal;

use Sam\Auction\Date\AuctionEndDateDetectorCreateTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use AuctionCache;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;

/**
 * Class AuctionEndDateUpdater
 * @package Sam\Observer\AuctionCache
 * @internal
 */
class AuctionEndDateUpdater extends CustomizableClass implements EntityUpdateObserverHandlerInterface
{
    use AuctionEndDateDetectorCreateTrait;
    use AuctionLoaderAwareTrait;
    use AuctionWriteRepositoryAwareTrait;

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
    public function onUpdate(EntityObserverSubject $subject): void
    {
        /** @var AuctionCache $auctionCache */
        $auctionCache = $subject->getEntity();
        $auction = $this->getAuctionLoader()->load($auctionCache->AuctionId);
        if (!$auction) {
            log_errorBackTrace(
                "Available auction not found in post-save for AuctionCache"
                . composeSuffix(['a' => $auctionCache->AuctionId])
            );
            return;
        }

        if (
            !$auction->isClosed()
            && !$auction->isArchived()
        ) {
            $auction->EndDate = $this->createAuctionEndDateDetector()->detect($auction);
            $this->getAuctionWriteRepository()->saveWithSystemModifier($auction);
        }
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isPropertyModified('TotalLots');
    }
}
