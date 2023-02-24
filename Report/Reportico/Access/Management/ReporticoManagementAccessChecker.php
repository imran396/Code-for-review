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

namespace Sam\Report\Reportico\Access\Management;

use Sam\Core\Service\CustomizableClass;
use Sam\Report\Reportico\Access\Management\Internal\Load\DataProviderCreateTrait;

/**
 * Class ReporticoManagementAccessChecker
 * @package Sam\Report\Reportico\Access\Management
 */
class ReporticoManagementAccessChecker extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function hasAccess(?int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        $dataProvider = $this->createDataProvider();
        if (!$dataProvider->isFeatureAvailable()) {
            return false;
        }

        if (
            $dataProvider->isFeatureAvailableOnlyForCrossDomainAdmin()
            && !$dataProvider->isCrossDomainAdmin($editorUserId, $isReadOnlyDb)
        ) {
            return false;
        }

        return true;
    }

}
