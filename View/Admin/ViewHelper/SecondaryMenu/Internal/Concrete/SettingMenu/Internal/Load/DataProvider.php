<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SettingMenu\Internal\Load;

use Sam\Account\Access\Management\AccountManagementAccessChecker;
use Sam\Application\Access\ApplicationAccessChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Category\Access\Management\LotCategoryManagementAccessChecker;
use Sam\Tax\StackedTax\Feature\StackedTaxFeatureAvailabilityChecker;
use Sam\View\Admin\Form\BuyerGroupEditForm\Access\Management\BuyerGroupManagementAccessChecker;

/**
 * Class DataProviderCreateTrait
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SettingMenu\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function hasAccessToAccountManagement(?int $editorUserId, int $systemAccountId, bool $isReadOnlyDb = false): bool
    {
        return AccountManagementAccessChecker::new()->hasAccess($editorUserId, $systemAccountId, $isReadOnlyDb);
    }

    public function hasAccessToLotCategoryManagement(?int $editorUserId, int $systemAccountId, bool $isReadOnlyDb = false): bool
    {
        return LotCategoryManagementAccessChecker::new()->hasAccess($editorUserId, $systemAccountId, $isReadOnlyDb);
    }

    public function hasAccessToCustomFieldManagement(?int $editorUserId, int $systemAccountId, bool $isReadOnlyDb = false): bool
    {
        return ApplicationAccessChecker::new()->isCrossDomainAdminOnMainAccountForMultipleTenantOrAdminForSingleTenant($editorUserId, $systemAccountId, $isReadOnlyDb);
    }

    public function hasAccessToBuyerGroupManagement(?int $editorUserId, int $systemAccountId, bool $isReadOnlyDb = false): bool
    {
        return BuyerGroupManagementAccessChecker::new()->hasAccess($editorUserId, $systemAccountId, $isReadOnlyDb);
    }

    public function isStackedTaxFeature(): bool
    {
        return StackedTaxFeatureAvailabilityChecker::new()->isEnabled();
    }
}
