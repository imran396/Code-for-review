<?php
/**
 * SAM-10594: Decouple settings to "setting_billing_pay_trace" table
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\SettingBillingPayTrace;

use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\SettingBillingPayTrace\Internal\AuditTrailLogger;
use SettingBillingPayTrace;
use SplObserver;
use SplSubject;

/**
 * Class SettingBillingPayTraceObserver
 * @package Sam\Observer\SettingBillingPayTrace
 */
class SettingBillingPayTraceObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of SettingBillingPayTraceObserver
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
        if (!$subject instanceof SettingBillingPayTrace) {
            log_warning('Subject not instance of ' . composeLogData(['SettingBillingPayTrace' => get_class($subject)]));
            return;
        }

        $handlers = [
            AuditTrailLogger::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
