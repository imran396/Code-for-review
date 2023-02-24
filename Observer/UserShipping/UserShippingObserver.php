<?php
/**
 * Observer for UserShipping
 * SAM-1444: Walmart - Track user profile changes
 *
 * @author        Igors Kotlevskis
 * @package       com.swb.sam2
 * @version       SVN: $Id: Shipping.php 12459 2013-03-03 07:23:40Z SWB\igors $
 * @since         Feb 20, 2013
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\UserShipping;

use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\UserShipping\Internal\UserShippingLogger;
use SplObserver;
use SplSubject;
use UserShipping;

/**
 * Class UserShippingObserver
 * @package Sam\Observer\UserShipping
 */
class UserShippingObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of UserShippingObserver
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
        if (!$subject instanceof UserShipping) {
            log_warning(composeLogData(['Subject not instance of UserShipping' => get_class($subject)]));
            return;
        }
        $handlers = [
            UserShippingLogger::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
