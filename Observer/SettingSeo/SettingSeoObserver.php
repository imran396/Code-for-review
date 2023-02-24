<?php
/**
 * SAM-10638: Decouple settings to "setting_seo" table
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\SettingSeo;

use Sam\Core\Service\CustomizableClass;
use Sam\Details\Auction\Base\Observe\AuctionDetailsCacheInvalidatorObserverHandler;
use Sam\Details\Lot\SeoUrl\Observe\LotSeoUrlInvalidationObserverHandler;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\SettingSeo\Internal\AuditTrailLogger;
use SettingSeo;
use SplObserver;
use SplSubject;

/**
 * Class SettingSeoObserver
 * @package Sam\Observer\SettingSeo
 */
class SettingSeoObserver extends CustomizableClass implements SplObserver
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
        if (!$subject instanceof SettingSeo) {
            log_warning('Subject not instance of ' . composeLogData(['SettingSeo' => get_class($subject)]));
            return;
        }

        $handlers = [
            AuctionDetailsCacheInvalidatorObserverHandler::new(),
            AuditTrailLogger::new(),
            LotSeoUrlInvalidationObserverHandler::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
