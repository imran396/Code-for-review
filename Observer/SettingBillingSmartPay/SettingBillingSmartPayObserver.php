<?php
/**
 * SAM-10598: Decouple settings to "setting_billing_smart_pay" table
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 11, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\SettingBillingSmartPay;

use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\SettingBillingSmartPay\Internal\AuditTrailLogger;
use SettingBillingSmartPay;
use SplObserver;
use SplSubject;

/**
 * Class SettingBillingSmartPayObserver
 * @package Sam\Observer\SettingBillingSmartPay
 */
class SettingBillingSmartPayObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of self
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * SplObserver update method
     * @param SplSubject $subject
     * @see SplObserver::update()
     */
    public function update(SplSubject $subject): void
    {
        if (!$subject instanceof SettingBillingSmartPay) {
            log_warning('Subject not instance of ' . composeLogData(['SettingBillingSmartPay' => get_class($subject)]));
            return;
        }

        $handlers = [
            AuditTrailLogger::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
