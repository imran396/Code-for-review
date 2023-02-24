<?php
/**
 * SAM-10637: Decouple settings to "setting_password" table
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\SettingPassword;

use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\SettingPassword\Internal\AuditTrailLogger;
use SettingPassword;
use SplObserver;
use SplSubject;

/**
 * Class SettingPasswordObserver
 * @package Sam\Observer\SettingPassword
 */
class SettingPasswordObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    public function update(SplSubject $subject): void
    {
        if (!$subject instanceof SettingPassword) {
            log_warning('Subject not instance of ' . composeLogData(['SettingPassword' => get_class($subject)]));
            return;
        }

        $handlers = [
            AuditTrailLogger::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
