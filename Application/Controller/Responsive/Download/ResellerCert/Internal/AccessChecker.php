<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Download\ResellerCert\Internal;

use Sam\Core\Service\CustomizableClass;
use Sam\Reseller\AuctionBidderCert\AuctionBidderCertHelper;
use Sam\Reseller\UserCert\UserCertHelper;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class AccessChecker
 * @package Sam\Application\Controller\Responsive\Download\ResellerCert\Internal
 */
class AccessChecker extends CustomizableClass
{
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function hasAccessToUserCertFile(int $certUserId, ?int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        $certUser = $this->getUserLoader()->load($certUserId, $isReadOnlyDb);
        if (!$certUser) {
            return false;
        }
        $editorUser = $this->getUserLoader()->load($editorUserId, $isReadOnlyDb);
        $hasAccess = UserCertHelper::new()->isAllowedDownload($certUser, $editorUser);
        return $hasAccess;
    }

    public function hasAccessToAuctionBidderCertFile(int $certUserId, ?int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        $certUser = $this->getUserLoader()->load($certUserId, $isReadOnlyDb);
        if (!$certUser) {
            return false;
        }
        $editorUser = $this->getUserLoader()->load($editorUserId, $isReadOnlyDb);
        $hasAccess = AuctionBidderCertHelper::new()->isAllowedDownload($certUser, $editorUser);
        return $hasAccess;
    }
}
