<?php
/**
 * SAM-9547: Add default reportico reports tab to SAM reports page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Reportico\Feature;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class ReporticoFeatureAvailabilityChecker
 * @package Sam\Report\Reportico\Feature
 */
class ReporticoFeatureAvailabilityChecker extends CustomizableClass
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

    public function isAvailable(): bool
    {
        return $this->cfg()->get('core->admin->report->reportico->enabled');
    }

    public function isAvailableOnlyForCrossDomainAdmin(): bool
    {
        return $this->cfg()->get('core->admin->report->reportico->onlyCrossDomainAdminAllowed');
    }

}
