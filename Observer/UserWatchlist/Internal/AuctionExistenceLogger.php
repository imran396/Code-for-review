<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\UserWatchlist\Internal;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use UserWatchlist;

/**
 * Class AuctionExistenceLogger
 * @package Sam\Observer\UserWatchlist
 * @internal
 */
class AuctionExistenceLogger extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuctionLoaderAwareTrait;

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
        $this->log($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->log($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        /** @var UserWatchlist $userWatchlist */
        $userWatchlist = $subject->getEntity();
        $auction = $this->getAuctionLoader()
            ->clear()
            ->load($userWatchlist->AuctionId);
        return !$auction;
    }

    protected function log(EntityObserverSubject $subject): void
    {
        /** @var UserWatchlist $userWatchlist */
        $userWatchlist = $subject->getEntity();
        $logInfo = composeSuffix(['a' => $userWatchlist->AuctionId, 'u' => $userWatchlist->UserId]);
        log_error("Available auction not found for UserWatchlist record" . $logInfo);
    }
}
