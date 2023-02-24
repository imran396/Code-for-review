<?php
/**
 * Observer for User
 * SAM-1595: Admin - User profile dashboard (IndustrialPortal)
 *
 * @author        Boanerge Regidor
 * @package       com.swb.sam2
 * @version       SVN: $Id: Watchlist.php $
 * @since         Jul 27, 2013
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\UserWatchlist;

use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\UserWatchlist\Internal\AuctionExistenceLogger;
use Sam\Observer\UserWatchlist\Internal\UserAccountStatisticUpdaterForUserWatchlist;
use SplObserver;
use SplSubject;
use UserWatchlist;

/**
 * Class UserWatchlistObserver
 * @package Sam\Observer
 */
class UserWatchlistObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of UserWatchlistObserver
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param SplSubject $subject
     */
    public function update(SplSubject $subject): void
    {
        if (!$subject instanceof UserWatchlist) {
            log_warning('Subject not instance of UserWatchlist: ' . get_class($subject));
            return;
        }
        $handlers = [
            AuctionExistenceLogger::new(),
            UserAccountStatisticUpdaterForUserWatchlist::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
