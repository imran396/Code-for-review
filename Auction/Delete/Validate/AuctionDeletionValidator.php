<?php
/**
 * SAM-10981: Replace GET->POST for delete button at Admin Manage auctions page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Delete\Validate;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Delete\Validate\AuctionDeletionValidationResult as Result;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;

class AuctionDeletionValidator extends CustomizableClass
{
    use AdminPrivilegeCheckerAwareTrait;
    use AuctionLoaderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(
        int $auctionId,
        int $editorUserId,
        bool $isReadOnlyDb = false
    ): Result {
        $result = Result::new()->construct();
        $auction = $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb);
        if (!$auction) {
            return $result->addError(Result::ERR_ADMIN_NOT_FOUND);
        }

        $admin = $this->getUserLoader()->loadAdmin($editorUserId, $isReadOnlyDb);
        if (!$admin) {
            return $result->addError(Result::ERR_ADMIN_NOT_FOUND);
        }

        $adminPrivilegeChecker = $this->getAdminPrivilegeChecker()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->initByAdmin($admin);

        if (
            $editorUserId !== $auction->CreatedBy
            && !$adminPrivilegeChecker->hasSubPrivilegeForManageAllAuctions()
        ) {
            return $result->addError(Result::ERR_FORBIDDEN_TO_DELETE_NON_OWN_AUCTION);
        }

        return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
    }
}
