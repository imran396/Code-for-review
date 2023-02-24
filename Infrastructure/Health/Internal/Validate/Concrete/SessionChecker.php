<?php
/**
 * Check session access, is used in HealthChecker
 *
 * SAM-7956: Create a basic health check endpoint /health
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Health\Internal\Validate\Concrete;

use Sam\Application\Index\Base\Concrete\NativeSession\PhpSessionInitializer;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SessionChecker
 * @package Sam\Infrastructure\Health
 */
class SessionChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        $phpSessionInitializer = PhpSessionInitializer::new();
        return $phpSessionInitializer->startPhpSession()
            && session_status() === PHP_SESSION_ACTIVE;
    }
}
