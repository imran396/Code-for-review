<?php
/**
 * Observer for UserBilling
 * SAM-1444: Walmart - Track user profile changes
 *
 * @author        Igors Kotlevskis
 * @package       com.swb.sam2
 * @version       SVN: $Id: Billing.php 13104 2013-04-19 20:00:55Z SWB\bregidor $
 * @since         Feb 20, 2013
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\UserBilling;

use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\UserBilling\Internal\UserAccountFlagHandler;
use Sam\Observer\UserBilling\Internal\UserBillingLogger;
use Sam\Observer\UserBilling\Internal\UserFlagHandler;
use SplObserver;
use SplSubject;
use UserBilling;

/**
 * Class UserBillingObserver
 * @package Sam\Observer\UserBilling
 */
class UserBillingObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of UserBillingObserver
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
        if (!$subject instanceof UserBilling) {
            log_warning(composeLogData(['Subject not instance of UserBilling' => get_class($subject)]));
            return;
        }

        $handlers = [
            UserAccountFlagHandler::new(),
            UserBillingLogger::new(),
            UserFlagHandler::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
