<?php
/**
 * SAM-9547: Add default reportico reports tab to SAM reports page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Reportico\Access\Management\Internal\Load;

use Sam\Application\Access\ApplicationAccessChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Report\Reportico\Feature\ReporticoFeatureAvailabilityChecker;

/**
 * Class DataProvider
 * @package
 */
class DataProvider extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isFeatureAvailable(): bool
    {
        return ReporticoFeatureAvailabilityChecker::new()->isAvailable();
    }

    public function isFeatureAvailableOnlyForCrossDomainAdmin(): bool
    {
        return ReporticoFeatureAvailabilityChecker::new()->isAvailableOnlyForCrossDomainAdmin();
    }

    public function isCrossDomainAdmin(?int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        $isAdmin = ApplicationAccessChecker::new()
            ->isCrossDomainAdminForMultipleTenantOrAdminForSingleTenant($editorUserId, $isReadOnlyDb);
        return $isAdmin;
    }
}
