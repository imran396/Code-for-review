<?php

namespace Sam\Core\Constants;

/**
 * Class AdminPrivilege
 * @package Sam\Core\Constants
 */
class AdminPrivilege
{
    /**
     * ATTENTION: Adjust value when add/remove privilege
     * All privileges sum
     * echo array_sum(array_keys(Constants\AdminPrivilege::$privilegeNames));
     */
    public const SUM = 1535;   // 10 1111 1111
    public const ALL_EXCEPT_SUPERADMIN = 511; // 00 1111 1111

    public const NONE = 0;
    public const MANAGE_AUCTIONS = 1;
    public const MANAGE_INVENTORY = 2;
    public const MANAGE_USERS = 4;
    public const MANAGE_INVOICES = 8;
    public const MANAGE_SETTLEMENTS = 16;
    public const MANAGE_SETTINGS = 32;
    public const MANAGE_CC_INFO = 64;
    public const SALES_STAFF = 128;
    public const MANAGE_REPORTS = 256;
    public const SUPERADMIN = 1024;

    /** @var int[] */
    public static array $privileges = [
        self::MANAGE_AUCTIONS,
        self::MANAGE_INVENTORY,
        self::MANAGE_USERS,
        self::MANAGE_INVOICES,
        self::MANAGE_SETTLEMENTS,
        self::MANAGE_SETTINGS,
        self::MANAGE_CC_INFO,
        self::SALES_STAFF,
        self::MANAGE_REPORTS,
        self::SUPERADMIN,
    ];

    /**
     * @var string[]
     */
    public static array $privilegeNames = [
        self::MANAGE_AUCTIONS => 'Manage auctions',
        self::MANAGE_INVENTORY => 'Manage inventory',
        self::MANAGE_USERS => 'Manage users',
        self::MANAGE_INVOICES => 'Manage invoices',
        self::MANAGE_SETTLEMENTS => 'Manage consignor settlements',
        self::MANAGE_SETTINGS => 'Manage settings',
        self::MANAGE_CC_INFO => 'Manage CC info',
        self::SALES_STAFF => 'Sales staff',
        self::MANAGE_REPORTS => 'Manage reports',
        self::SUPERADMIN => 'Superadmin',
    ];
    public static array $privilegeNamesTranslations = [
        self::MANAGE_AUCTIONS => 'user_privileges.admin_privileges.manage_auctions',
        self::MANAGE_INVENTORY => 'user_privileges.admin_privileges.manage_inventory',
        self::MANAGE_USERS => 'user_privileges.admin_privileges.manage_users',
        self::MANAGE_INVOICES => 'user_privileges.admin_privileges.manage_invoices',
        self::MANAGE_SETTLEMENTS => 'user_privileges.admin_privileges.manage_settlements',
        self::MANAGE_SETTINGS => 'user_privileges.admin_privileges.manage_settings',
        self::MANAGE_CC_INFO => 'user_privileges.admin_privileges.manage_cc_info',
        self::SALES_STAFF => 'user_privileges.admin_privileges.sales_staff',
        self::MANAGE_REPORTS => 'user_privileges.admin_privileges.manage_reports',
        self::SUPERADMIN => 'user_privileges.admin_privileges.superadmin',
    ];

    /**
     * Sub-privilege values correspond `admin` table column,
     * but in camel-case view like property of Admin->$property
     */
    public const SUB_AUCTION_MANAGE_ALL = 'ManageAllAuctions';
    public const SUB_AUCTION_DELETE = 'DeleteAuction';
    public const SUB_AUCTION_ARCHIVE = 'ArchiveAuction';
    public const SUB_AUCTION_RESET = 'ResetAuction';
    public const SUB_AUCTION_INFORMATION = 'Information';
    public const SUB_AUCTION_PUBLISH = 'Publish';
    public const SUB_AUCTION_LOT_LIST = 'Lots';
    public const SUB_AUCTION_AVAILABLE_LOT = 'AvailableLots';
    public const SUB_AUCTION_BIDDER = 'Bidders';
    public const SUB_AUCTION_REMAINING_USER = 'RemainingUsers';
    public const SUB_AUCTION_RUN_LIVE = 'RunLiveAuction';
    public const SUB_AUCTION_AUCTIONEER = 'AuctioneerScreen';
    public const SUB_AUCTION_PROJECTOR = 'Projector';
    public const SUB_AUCTION_BID_INCREMENT = 'BidIncrements';
    public const SUB_AUCTION_BUYER_PREMIUM = 'BuyersPremium';
    public const SUB_AUCTION_PERMISSION = 'Permissions';
    public const SUB_AUCTION_CREATE_BIDDER = 'CreateBidder';
    public const SUB_USER_PASSWORD = 'UserPasswords';
    public const SUB_USER_BULK_EXPORT = 'BulkUserExport';
    public const SUB_USER_DELETE = 'DeleteUser';
    public const SUB_USER_PRIVILEGE = 'UserPrivileges';
    public const SUB_USER_GRANT_ADMIN_ROLE = 'GrantAdminRole';
    public const SUB_USER_GRANT_BIDDER_ROLE = 'GrantBidderRole';
    public const SUB_USER_GRANT_CONSIGNOR_ROLE = 'GrantConsignorRole';

    /**
     * Names for sub-privileges of "Manage Auctions" privilege
     * Associative array, where key corresponds property of Admin->$property
     * @var string[]
     */
    public static array $manageAuctionSubPrivilegeNames = [
        self::SUB_AUCTION_MANAGE_ALL => 'All auctions',
        self::SUB_AUCTION_DELETE => 'Delete',
        self::SUB_AUCTION_ARCHIVE => 'Archive',
        self::SUB_AUCTION_RESET => 'Reset',
        self::SUB_AUCTION_INFORMATION => 'Information',
        self::SUB_AUCTION_PUBLISH => 'Publish',
        self::SUB_AUCTION_LOT_LIST => 'Lots',
        self::SUB_AUCTION_AVAILABLE_LOT => 'Available lots',
        self::SUB_AUCTION_BIDDER => 'Bidders',
        self::SUB_AUCTION_REMAINING_USER => 'Remaining users',
        self::SUB_AUCTION_RUN_LIVE => 'Run live auction',
        self::SUB_AUCTION_AUCTIONEER => 'Auctioneer screen',
        self::SUB_AUCTION_PROJECTOR => 'Projector',
        self::SUB_AUCTION_BID_INCREMENT => 'Bid increments',
        self::SUB_AUCTION_BUYER_PREMIUM => 'Buyers premium',
        self::SUB_AUCTION_PERMISSION => 'Permissions',
        self::SUB_AUCTION_CREATE_BIDDER => 'Create bidder',
    ];
    public static array $manageAuctionSubPrivilegeNamesTranslations = [
        self::SUB_AUCTION_MANAGE_ALL => 'user_privileges.admin_privileges.manage_auctions.all_auctions',
        self::SUB_AUCTION_DELETE => 'user_privileges.admin_privileges.manage_auctions.delete',
        self::SUB_AUCTION_ARCHIVE => 'user_privileges.admin_privileges.manage_auctions.archive',
        self::SUB_AUCTION_RESET => 'user_privileges.admin_privileges.manage_auctions.reset',
        self::SUB_AUCTION_INFORMATION => 'user_privileges.admin_privileges.manage_auctions.information',
        self::SUB_AUCTION_PUBLISH => 'user_privileges.admin_privileges.manage_auctions.publish',
        self::SUB_AUCTION_LOT_LIST => 'user_privileges.admin_privileges.manage_auctions.lots',
        self::SUB_AUCTION_AVAILABLE_LOT => 'user_privileges.admin_privileges.manage_auctions.available_lots',
        self::SUB_AUCTION_BIDDER => 'user_privileges.admin_privileges.manage_auctions.bidders',
        self::SUB_AUCTION_REMAINING_USER => 'user_privileges.admin_privileges.manage_auctions.remaining_users',
        self::SUB_AUCTION_RUN_LIVE => 'user_privileges.admin_privileges.manage_auctions.run_live_auction',
        self::SUB_AUCTION_AUCTIONEER => 'user_privileges.admin_privileges.manage_auctions.auctioneer_screen',
        self::SUB_AUCTION_PROJECTOR => 'user_privileges.admin_privileges.manage_auctions.projector',
        self::SUB_AUCTION_BID_INCREMENT => 'user_privileges.admin_privileges.manage_auctions.bid_increments',
        self::SUB_AUCTION_BUYER_PREMIUM => 'user_privileges.admin_privileges.manage_auctions.buyers_premium',
        self::SUB_AUCTION_PERMISSION => 'user_privileges.admin_privileges.manage_auctions.permissions',
        self::SUB_AUCTION_CREATE_BIDDER => 'user_privileges.admin_privileges.manage_auctions.create_bidder',
    ];
    /**
     * Sub-privileges of "Manage Auctions" privilege
     * @var string[]
     */
    public static array $manageAuctionSubPrivileges = [
        self::SUB_AUCTION_MANAGE_ALL,
        self::SUB_AUCTION_DELETE,
        self::SUB_AUCTION_ARCHIVE,
        self::SUB_AUCTION_RESET,
        self::SUB_AUCTION_INFORMATION,
        self::SUB_AUCTION_PUBLISH,
        self::SUB_AUCTION_LOT_LIST,
        self::SUB_AUCTION_AVAILABLE_LOT,
        self::SUB_AUCTION_BIDDER,
        self::SUB_AUCTION_REMAINING_USER,
        self::SUB_AUCTION_RUN_LIVE,
        self::SUB_AUCTION_AUCTIONEER,
        self::SUB_AUCTION_PROJECTOR,
        self::SUB_AUCTION_BID_INCREMENT,
        self::SUB_AUCTION_BUYER_PREMIUM,
        self::SUB_AUCTION_PERMISSION,
        self::SUB_AUCTION_CREATE_BIDDER,
    ];
    /**
     * Names for sub-privileges of "Manage Users" privilege
     * Associative array, where key corresponds property of Admin->$property
     * @var string[]
     */
    public static array $manageUserSubPrivilegeNames = [
        self::SUB_USER_PASSWORD => 'Passwords',
        self::SUB_USER_BULK_EXPORT => 'Bulk user export',
        self::SUB_USER_PRIVILEGE => 'User privileges',
        self::SUB_USER_DELETE => 'Delete user',
    ];
    public static array $manageUserSubPrivilegeNamesTranslations = [
        self::SUB_USER_PASSWORD => 'user_privileges.admin_privileges.manage_users.passwords',
        self::SUB_USER_BULK_EXPORT => 'user_privileges.admin_privileges.manage_users.bulk_user_export',
        self::SUB_USER_PRIVILEGE => 'user_privileges.admin_privileges.manage_users.user_privileges',
        self::SUB_USER_DELETE => 'user_privileges.admin_privileges.manage_users.delete_user',
    ];
    /**
     * Sub-privileges of "Manage Users" privilege
     * @var string[]
     */
    public static array $manageUserSubPrivileges = [
        self::SUB_USER_PASSWORD,
        self::SUB_USER_BULK_EXPORT,
        self::SUB_USER_PRIVILEGE,
        self::SUB_USER_DELETE,
    ];

    /**
     * Sub-sub privileges for Manage User / User Privilege. SAM-4927
     */
    /** @var string[] */
    public static array $manageUserGrantRolePrivileges = [
        self::SUB_USER_GRANT_ADMIN_ROLE,
        self::SUB_USER_GRANT_BIDDER_ROLE,
        self::SUB_USER_GRANT_CONSIGNOR_ROLE,
    ];
    /** @var string[] */
    public static array $manageUserGrantRolePrivilegeNames = [
        self::SUB_USER_GRANT_ADMIN_ROLE => 'Grant admin role',
        self::SUB_USER_GRANT_BIDDER_ROLE => 'Grant bidder role',
        self::SUB_USER_GRANT_CONSIGNOR_ROLE => 'Grant consignor role',
    ];
}
