<?php
/**
 * SAM-11308: Proxied SAM - X-Forwarded request headers handling
 *
 * Project        SAM
 * @author        Georgi Nikolov
 * @version       SVN: $Id: $
 * @since         Sep 29, 2022
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Application\HttpRequest\ProxiedFeature;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

class ProxiedFeatureAvailabilityChecker extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, if proxied feature is enabled
     */
    public function isEnabled(): bool
    {
        return (bool)$this->cfg()->get('core->app->proxied->enabled');
    }

}
