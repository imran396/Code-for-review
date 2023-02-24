<?php
/**
 * SAM-10635: Decouple settings to "setting_shipping_auction_inc" table
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

namespace Sam\Observer\SettingShippingAuctionInc;

use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\SettingShippingAuctionInc\Internal\AuditTrailLogger;
use SettingShippingAuctionInc;
use SplObserver;
use SplSubject;

/**
 * Class SettingShippingAuctionIncObserver
 * @package Sam\Observer\SettingShippingAuctionInc
 */
class SettingShippingAuctionIncObserver extends CustomizableClass implements SplObserver
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
        if (!$subject instanceof SettingShippingAuctionInc) {
            log_warning('Subject not instance of ' . composeLogData(['SettingShippingAuctionInc' => get_class($subject)]));
            return;
        }

        $handlers = [
            AuditTrailLogger::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
