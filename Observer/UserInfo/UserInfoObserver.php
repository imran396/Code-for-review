<?php
/**
 * Observer for UserInfo
 * SAM-1444: Walmart - Track user profile changes
 *
 * @author        Igors Kotlevskis
 * @package       com.swb.sam2
 * @version       SVN: $Id: Info.php 12459 2013-03-03 07:23:40Z SWB\igors $
 * @since         Feb 20, 2013
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\UserInfo;

use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\UserInfo\Internal\UserInfoLogger;
use SplObserver;
use SplSubject;
use UserInfo;

/**
 * Class UserInfoObserver
 * @package Sam\Observer\UserInfo
 */
class UserInfoObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of UserInfoObserver
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
        if (!$subject instanceof UserInfo) {
            log_warning('Subject not instance of UserInfo: ' . get_class($subject));
            return;
        }
        $handlers = [
            UserInfoLogger::new()
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
