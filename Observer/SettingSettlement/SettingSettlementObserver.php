<?php
/**
 * SAM-10642: Decouple settings to "setting_settlement" table
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\SettingSettlement;

use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\SettingSettlement\Internal\AuditTrailLogger;
use SettingSettlement;
use SplObserver;
use SplSubject;

class SettingSettlementObserver extends CustomizableClass implements SplObserver
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

    public function update(SplSubject $subject): void
    {
        if (!$subject instanceof SettingSettlement) {
            log_warning('Subject not instance of ' . composeLogData(['SettingSettlement' => get_class($subject)]));
            return;
        }

        $handlers = [
            AuditTrailLogger::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
