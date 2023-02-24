<?php
/**
 * SAM-9579: Check access for buyer group management
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

namespace Sam\View\Admin\Form\BuyerGroupEditForm\Access\Management;

use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\BuyerGroupEditForm\Access\Management\Internal\Load\DataProviderCreateTrait;

/**
 * Class BuyerGroupManagementAccessChecker
 * @package Sam\View\Admin\Form\BuyerGroupEditForm\Access\Management
 */
class BuyerGroupManagementAccessChecker extends CustomizableClass
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

    public function hasAccess(?int $editorUserId, int $systemAccountId, bool $isReadOnlyDb = false): bool
    {
        $dataProvider = $this->createDataProvider();
        if (!$dataProvider->isMainAccountForMultipleTenantOrSingleTenant($systemAccountId)) {
            return false;
        }

        if (!$dataProvider->hasPrivilegeForManageSettings($editorUserId, $isReadOnlyDb)) {
            return false;
        }

        return true;
    }

}
