<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Feed\Internal\Privilege;

use Sam\Application\Controller\Responsive\Feed\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;

/**
 * Class PrivilegeChecker
 * @package Sam\Application\Controller\Responsive\Feed\Internal\Privilege
 */
class PrivilegeChecker extends CustomizableClass
{
    use AdminPrivilegeCheckerAwareTrait;
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function hasPrivilegeForManageReports(string $userName, string $password): bool
    {
        $dataProvider = $this->createDataProvider();
        $userId = $dataProvider->detectLoggedInUserId() ?? $dataProvider->loadUserIdByCredentials($userName, $password);
        $hasPrivilegeForManageReports = $this->getAdminPrivilegeChecker()
            ->initByUserId($userId)
            ->hasPrivilegeForManageReports();
        return $hasPrivilegeForManageReports;
    }
}
