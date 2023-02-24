<?php
/**
 * Observer for UserCustData
 * Mantis ticket:
 * SAM-1444: Walmart - Track user profile changes
 *
 * @author        Igors Kotlevskis
 * @package       com.swb.sam2
 * @version       SVN: $Id: DataObserver.php 13865 2013-07-18 12:36:33Z SWB\igors $
 * @since         Feb 20, 2013
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\UserCustData;

use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\UserCustData\Internal\UserCustDataLogger;
use SplObserver;
use SplSubject;
use UserCustData;

/**
 * Class UserCustDataObserver
 * @package Sam\Observer\UserCustData
 */
class UserCustDataObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of UserCustDataObserver
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
        if (!$subject instanceof UserCustData) {
            log_warning(composeLogData(['Subject not instance of UserCustData' => get_class($subject)]));
            return;
        }
        $handlers = [
            UserCustDataLogger::new()
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
