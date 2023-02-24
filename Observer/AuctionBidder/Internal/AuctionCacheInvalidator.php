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

namespace Sam\Observer\AuctionBidder\Internal;

use AuctionBidder;
use Sam\Auction\Cache\CacheInvalidator\AuctionCacheInvalidatorCreateTrait;
use Sam\Auction\Cache\CacheInvalidator\CacheInvalidatorFilterConditionCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class CacheInvalidator
 * @package Sam\Observer\AuctionBidder
 * @internal
 */
class AuctionCacheInvalidator extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuctionCacheInvalidatorCreateTrait;
    use CacheInvalidatorFilterConditionCreateTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function onCreate(EntityObserverSubject $subject): void
    {
        $this->invalidate($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->invalidate($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isAnyPropertyModified(['AuctionId', 'UserId', 'Approved']);
    }

    protected function invalidate(EntityObserverSubject $subject): void
    {
        /** @var AuctionBidder $auctionBidder */
        $auctionBidder = $subject->getEntity();
        $filterCondition = $this->createCacheInvalidatorFilterCondition()->filterAuctionId([$auctionBidder->AuctionId]);
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->createAuctionCacheInvalidator()->invalidate($filterCondition, $editorUserId);
    }
}
