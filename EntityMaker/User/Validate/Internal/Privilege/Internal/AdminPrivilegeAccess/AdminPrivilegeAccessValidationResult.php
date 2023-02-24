<?php
/**
 * SAM-9520: Important Security user privilege issue
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeAccess;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Contains admin privilege access validation errors
 *
 * Class AdminPrivilegeAccessValidationResult
 * @package Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeAccess
 */
class AdminPrivilegeAccessValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_MANAGE_AUCTIONS_PRIVILEGE_IS_NOT_EDITABLE = 1;
    public const ERR_MANAGE_INVENTORY_PRIVILEGE_IS_NOT_EDITABLE = 2;
    public const ERR_MANAGE_USERS_PRIVILEGE_IS_NOT_EDITABLE = 3;
    public const ERR_MANAGE_INVOICES_PRIVILEGE_IS_NOT_EDITABLE = 4;
    public const ERR_MANAGE_SETTLEMENTS_PRIVILEGE_IS_NOT_EDITABLE = 5;
    public const ERR_MANAGE_SETTINGS_PRIVILEGE_IS_NOT_EDITABLE = 6;
    public const ERR_MANAGE_CC_INFO_PRIVILEGE_IS_NOT_EDITABLE = 7;
    public const ERR_SALES_STAFF_PRIVILEGE_IS_NOT_EDITABLE = 8;
    public const ERR_MANAGE_REPORTS_PRIVILEGE_IS_NOT_EDITABLE = 9;
    public const ERR_SUPERADMIN_PRIVILEGE_IS_NOT_EDITABLE = 10;
    public const ERR_SUB_AUCTION_MANAGE_ALL_PRIVILEGE_IS_NOT_EDITABLE = 11;
    public const ERR_SUB_AUCTION_DELETE_PRIVILEGE_IS_NOT_EDITABLE = 12;
    public const ERR_SUB_AUCTION_ARCHIVE_PRIVILEGE_IS_NOT_EDITABLE = 13;
    public const ERR_SUB_AUCTION_RESET_PRIVILEGE_IS_NOT_EDITABLE = 14;
    public const ERR_SUB_AUCTION_INFORMATION_PRIVILEGE_IS_NOT_EDITABLE = 15;
    public const ERR_SUB_AUCTION_PUBLISH_PRIVILEGE_IS_NOT_EDITABLE = 16;
    public const ERR_SUB_AUCTION_LOT_LIST_PRIVILEGE_IS_NOT_EDITABLE = 17;
    public const ERR_SUB_AUCTION_AVAILABLE_LOT_PRIVILEGE_IS_NOT_EDITABLE = 18;
    public const ERR_SUB_AUCTION_BIDDER_PRIVILEGE_IS_NOT_EDITABLE = 19;
    public const ERR_SUB_AUCTION_REMAINING_USER_PRIVILEGE_IS_NOT_EDITABLE = 20;
    public const ERR_SUB_AUCTION_RUN_LIVE_PRIVILEGE_IS_NOT_EDITABLE = 21;
    public const ERR_SUB_AUCTION_AUCTIONEER_PRIVILEGE_IS_NOT_EDITABLE = 22;
    public const ERR_SUB_AUCTION_PROJECTOR_PRIVILEGE_IS_NOT_EDITABLE = 23;
    public const ERR_SUB_AUCTION_BID_INCREMENT_PRIVILEGE_IS_NOT_EDITABLE = 24;
    public const ERR_SUB_AUCTION_BUYER_PREMIUM_PRIVILEGE_IS_NOT_EDITABLE = 25;
    public const ERR_SUB_AUCTION_PERMISSION_PRIVILEGE_IS_NOT_EDITABLE = 26;
    public const ERR_SUB_USER_BULK_EXPORT_PRIVILEGE_IS_NOT_EDITABLE = 27;
    public const ERR_SUB_USER_DELETE_PRIVILEGE_IS_NOT_EDITABLE = 31;
    public const ERR_SUB_USER_PASSWORD_PRIVILEGE_IS_NOT_EDITABLE = 28;
    public const ERR_SUB_USER_PRIVILEGE_PRIVILEGE_IS_NOT_EDITABLE = 29;
    public const ERR_SUB_AUCTION_CREATE_BIDDER_PRIVILEGE_IS_NOT_EDITABLE = 30;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_MANAGE_AUCTIONS_PRIVILEGE_IS_NOT_EDITABLE => 'Manage Auctions privilege is not editable',
        self::ERR_MANAGE_INVENTORY_PRIVILEGE_IS_NOT_EDITABLE => 'Manage Inventory privilege is not editable',
        self::ERR_MANAGE_USERS_PRIVILEGE_IS_NOT_EDITABLE => 'Manage Users privilege is not editable',
        self::ERR_MANAGE_INVOICES_PRIVILEGE_IS_NOT_EDITABLE => 'Manage Invoices privilege is not editable',
        self::ERR_MANAGE_SETTLEMENTS_PRIVILEGE_IS_NOT_EDITABLE => 'Manage Settlements privilege is not editable',
        self::ERR_MANAGE_SETTINGS_PRIVILEGE_IS_NOT_EDITABLE => 'Manage Settings privilege is not editable',
        self::ERR_MANAGE_CC_INFO_PRIVILEGE_IS_NOT_EDITABLE => 'Manage CC Info privilege is not editable',
        self::ERR_SALES_STAFF_PRIVILEGE_IS_NOT_EDITABLE => 'Sales Staff privilege is not editable',
        self::ERR_MANAGE_REPORTS_PRIVILEGE_IS_NOT_EDITABLE => 'Manage Reports privilege is not editable',
        self::ERR_SUPERADMIN_PRIVILEGE_IS_NOT_EDITABLE => 'Cross-domain Admin privilege is not editable',
        self::ERR_SUB_AUCTION_MANAGE_ALL_PRIVILEGE_IS_NOT_EDITABLE => 'Auctions Manage All privilege is not editable',
        self::ERR_SUB_AUCTION_DELETE_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Delete privilege is not editable',
        self::ERR_SUB_AUCTION_ARCHIVE_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Archive privilege is not editable',
        self::ERR_SUB_AUCTION_RESET_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Reset privilege is not editable',
        self::ERR_SUB_AUCTION_INFORMATION_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Information privilege is not editable',
        self::ERR_SUB_AUCTION_PUBLISH_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Publish privilege is not editable',
        self::ERR_SUB_AUCTION_LOT_LIST_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Lot List privilege is not editable',
        self::ERR_SUB_AUCTION_AVAILABLE_LOT_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Available Lot privilege is not editable',
        self::ERR_SUB_AUCTION_BIDDER_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Bidder privilege is not editable',
        self::ERR_SUB_AUCTION_REMAINING_USER_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Remaining User privilege is not editable',
        self::ERR_SUB_AUCTION_RUN_LIVE_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Run Live privilege is not editable',
        self::ERR_SUB_AUCTION_AUCTIONEER_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Auctioneer privilege is not editable',
        self::ERR_SUB_AUCTION_PROJECTOR_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Projector privilege is not editable',
        self::ERR_SUB_AUCTION_BID_INCREMENT_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Bid Increment privilege is not editable',
        self::ERR_SUB_AUCTION_BUYER_PREMIUM_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Buyer Premium privilege is not editable',
        self::ERR_SUB_AUCTION_PERMISSION_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Create bidder privilege is not editable',
        self::ERR_SUB_AUCTION_CREATE_BIDDER_PRIVILEGE_IS_NOT_EDITABLE => 'Auction Permission privilege is not editable',
        self::ERR_SUB_USER_BULK_EXPORT_PRIVILEGE_IS_NOT_EDITABLE => 'User Bulk Export privilege is not editable',
        self::ERR_SUB_USER_PASSWORD_PRIVILEGE_IS_NOT_EDITABLE => 'User Password privilege is not editable',
        self::ERR_SUB_USER_PRIVILEGE_PRIVILEGE_IS_NOT_EDITABLE => 'User Privilege privilege is not editable',
        self::ERR_SUB_USER_DELETE_PRIVILEGE_IS_NOT_EDITABLE => 'User Delete privilege is not editable',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    public function addError(int $errorCode): static
    {
        $this->getResultStatusCollector()->addError($errorCode);
        return $this;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }


    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }
}
