<?php
/**
 * SAM-9553: Apply ConfigRepository dependency
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Security\Ssl\Feature;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class SslAvailabilityChecker
 * @package Sam\Security\Ssl\Feature
 */
class SslAvailabilityChecker extends CustomizableClass
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

    public function isEnabled(): bool
    {
        return $this->cfg()->get('core->security->ssl->enabled');
    }

    public function securitySeal(): string
    {
        return $this->cfg()->get('core->security->ssl->securitySeal');
    }

}
