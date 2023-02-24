<?php
/**
 * SAM-10096: Refactor auto-completer data loading end-points for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Account;

use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class FilterAccountDetector
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Account
 */
class FilterAccountDetector extends CustomizableClass
{
    use ApplicationAccessCheckerCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detect account filtering restriction, because of editor user access rights.
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @return int|null
     */
    public function detectFilterAccountId(?int $editorUserId, int $systemAccountId): ?int
    {
        // Explicit account filtering for all users except superadmin on main account of portal installation.
        // We filter by main account in case of single tenant installation here.
        $hasAccess = $this->createApplicationAccessChecker()
            ->isCrossDomainAdminOnMainAccountForMultipleTenantOrAdminForSingleTenant(
                $editorUserId,
                $systemAccountId,
                true
            );
        if (!$hasAccess) {
            return $systemAccountId;
        }
        return null;
    }

}
