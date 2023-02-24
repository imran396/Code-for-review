<?php
/**
 * Pure checking service that helps to detect if other account entities are available for view.
 * THey are available for multiple-tenant installation in case of true one of the next conditions:
 * - you are visiting domain of main account;
 * - "Show all account entities" account option enabled;
 * - account transparency enabled by installation config;
 *
 * SAM-6068: Issue related to "Show content from all account" on a portal account
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Account\Transparency;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class CrossDomainTransparencyPureChecker
 * @package Sam\Core\Account\CrossDomainTransparency
 */
class CrossAccountTransparencyPureChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Is available cross-account transparency to view entities
     *
     * @param int $targetAccountId
     * @param bool $isShowAll
     * @param bool $isMultipleTenant
     * @param int $mainAccountId
     * @param string $domainAuctionVisibility
     * @return bool
     * #[Pure]
     */
    public function isAvailable(
        int $targetAccountId,
        bool $isShowAll,
        bool $isMultipleTenant,
        int $mainAccountId,
        string $domainAuctionVisibility
    ): bool {
        if (!$isMultipleTenant) {
            return false;
        }

        if ($targetAccountId === $mainAccountId) {
            return true;
        }

        if ($domainAuctionVisibility === Constants\AccountVisibility::TRANSPARENT) {
            return true;
        }

        if ($isShowAll) {
            return true;
        }

        return false;
    }

}
