<?php
/**
 * SAM-10591: Decouple settings to "setting_billing_authorize_net" table
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\SettingBillingAuthorizeNet;

use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\SettingBillingAuthorizeNet\Internal\AuditTrailLogger;
use SettingBillingAuthorizeNet;
use SplObserver;
use SplSubject;

/**
 * Class SettingBillingAuthorizeNetObserver
 * @package Sam\Observer\SettingBillingAuthorizeNet
 */
class SettingBillingAuthorizeNetObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Class instantiation method
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
        if (!$subject instanceof SettingBillingAuthorizeNet) {
            log_warning('Subject not instance of ' . composeLogData(['SettingBillingAuthorizeNet' => get_class($subject)]));
            return;
        }

        $handlers = [
            AuditTrailLogger::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
