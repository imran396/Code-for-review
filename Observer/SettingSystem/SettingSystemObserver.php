<?php
/**
 * SAM-10666: Decouple settings to "setting_system" table
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\SettingSystem;

use Sam\Core\Service\CustomizableClass;
use Sam\Details\Auction\Base\Observe\AuctionDetailsCacheInvalidatorObserverHandler;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\SettingSystem\Internal\AuditTrailLogger;
use SettingSystem;
use SplObserver;
use SplSubject;

/**
 * Class SettingSystemObserver
 * @package Sam\Observer\SettingSystem
 */
class SettingSystemObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of self
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * SplObserver update method
     * @param SplSubject $subject
     * @see SplObserver::update()
     */
    public function update(SplSubject $subject): void
    {
        if (!$subject instanceof SettingSystem) {
            log_warning('Subject not instance of ' . composeLogData(['SettingSystem' => get_class($subject)]));
            return;
        }

        $handlers = [
            AuctionDetailsCacheInvalidatorObserverHandler::new(),
            AuditTrailLogger::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
