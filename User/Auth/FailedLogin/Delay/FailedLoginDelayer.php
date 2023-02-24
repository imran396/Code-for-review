<?php
/**
 * SAM-6375: Failed authentication attempt response delay
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 07, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth\FailedLogin\Delay;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class FailedLoginDelayer
 * @package Sam\User\Auth\FailedLogin
 */
class FailedLoginDelayer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delay failed login attempt
     * @param int|null $delayMs null for default value from config, value is in milliseconds
     */
    public function delay(int $delayMs = null): void
    {
        $delayMs = $delayMs ?? $this->cfg()->get('core->app->authentication->failedAttemptDelay');
        // Make microseconds to pass since usleep() expects
        $delayMicrosec = $delayMs * 1000;
        log_debug('Delay failed login attempt' . composeSuffix(['ms' => $delayMs]));
        usleep($delayMicrosec);
    }
}
