<?php
/**
 * Observer for User
 * SAM-1444: Walmart - Track user profile changes
 *
 * @author        Igors Kotlevskis
 * @package       com.swb.sam2
 * @since         Feb 20, 2013
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\User;

use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\User\Internal\BuyerGroupUpdater;
use Sam\Observer\User\Internal\SearchIndexUpdater;
use Sam\Observer\User\Internal\UserLogger;
use SplObserver;
use SplSubject;
use User;

/**
 * Class UserObserver
 * @package Sam\Observer\User
 */
class UserObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of UserObserver
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
        if (!$subject instanceof User) {
            log_warning('Subject not instance of User: ' . get_class($subject));
            return;
        }
        $handlers = [
            BuyerGroupUpdater::new(),
            SearchIndexUpdater::new(),
            UserLogger::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
