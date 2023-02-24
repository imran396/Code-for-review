<?php
/**
 * Independent of user session, we pass running user id to constructor
 *
 * SAM-9538: Decouple ACL checking logic from front controller
 * SAM-5171: Application layer
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/2/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Acl\Role;

use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Resource\GenericResource as Resource;
use Laminas\Permissions\Acl\Role\GenericRole as Role;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Constants;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;

/**
 * Access control rules fore front end application
 */
class AclChecker extends Acl
{
    use AclRoleDetectorCreateTrait;
    use RoleCheckerAwareTrait;
    use UserLoaderAwareTrait;

    public const RESOURCE_MANAGE_AUCTIONS_ALL = Constants\AdminRoute::C_MANAGE_AUCTIONS . ':all';
    public const RESOURCE_MANAGE_USERS_ALL = Constants\AdminRoute::C_MANAGE_USERS . ':all';

    /**
     * AclRole constructor.
     * @param int|null $editorUserId
     * @param Ui $ui
     */
    public function __construct(?int $editorUserId, Ui $ui)
    {
        $anonymousRole = Constants\Role::ACL_ANONYMOUS;
        $customerRole = Constants\Role::ACL_CUSTOMER;
        $staffRole = Constants\Role::ACL_STAFF;
        $adminRole = Constants\Role::ACL_ADMIN;

        // Add Resources
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_INDEX));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_IMAGE));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_LANGUAGE));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_LOCATION));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_API));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_BILLING_OPAYO));

        $this->addResource(new Resource(Constants\ResponsiveRoute::C_LOGIN));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_FORGOT_PASSWORD));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_CHANGE_PASSWORD));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_RESET_PASSWORD));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_FORGOT_USERNAME));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_SIGNUP));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_REGISTER));

        $this->addResource(new Resource(Constants\ResponsiveRoute::C_AUCTIONS));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_ACCOUNTS));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_MY_ITEMS));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_MY_ALERTS));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_PROFILE));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_MY_INVOICES));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_MY_SETTLEMENTS));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_REPORT_PROBLEMS));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_LOT_ITEM));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_LOGOUT));

        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_LOGOUT));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_PLACE_BID));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_ACCOUNT));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_AUCTIONS));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_AUCTIONEER));
        $this->addResource(new Resource(self::RESOURCE_MANAGE_AUCTIONS_ALL));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_AUCTION_LOCATION));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_BID_INCREMENT));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_BUYER_GROUP));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_BUYERS_PREMIUM));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_CATEGORY));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_CHANGE_PASSWORD));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_COUPON));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_CSV_IMPORT));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_CUSTOM_FIELD));  // part of settings
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_EMAIL_TEMPLATE));  // part of settings
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_HOME));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_FEED));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_INVENTORY));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_INVOICES));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_STACKED_TAX_INVOICE));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_LOCATION));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_LOT_IMAGES));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_LOT_ITEM));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_REPORTS));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_SALES_STAFF_REPORT));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_MAILING_LIST_REPORT));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_SEARCH));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_SITE_CONTENT));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_CONSIGNOR_COMMISSION_FEE));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_SETTLEMENTS));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_SETTLEMENT_CHECK));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_SYNC));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_TRANSLATION));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_TAX_DEFINITION));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_TAX_SCHEMA));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_USERS));
        $this->addResource(new Resource(self::RESOURCE_MANAGE_USERS_ALL));
        $this->addResource(new Resource(Constants\AdminRoute::C_RTB_INCREMENT));
        $this->addResource(new Resource(Constants\AdminRoute::C_RTB_MESSAGE));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_SYSTEM_PARAMETER));
        $this->addResource(new Resource(Constants\AdminRoute::C_MANAGE_CUSTOM_TEMPLATE));

        $this->addResource(new Resource(Constants\AdminRoute::C_ACCESS_ERROR));

        // installation: edit local config
        $this->addResource(new Resource(Constants\AdminRoute::C_INSTALLATION_SETTING));

        //additional resources for Mobile
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_SEARCH));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_LOT_DETAILS));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_NOTIFICATIONS));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_HEALTH));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_CROSS_DOMAIN_AJAX_REQUEST_PROXY));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_WATCHLIST));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_OTHER_LOTS));
        $this->addResource(new Resource(Constants\AdminRoute::AMA_PROJECTOR));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_FEED));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_AUDIO));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_ERROR_REPORT));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_DOWNLOAD));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_SYNC));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_SITEMAP));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_SSO));
        $this->addResource(new Resource(Constants\ResponsiveRoute::C_STACKED_TAX_INVOICE));

        // Add Roles
        $this->addRole(new Role($anonymousRole));
        $this->addRole(new Role($customerRole), $anonymousRole);

        $this->addRole(new Role($adminRole), $customerRole);
        $this->addRole(new Role($staffRole), $adminRole);

        // Add Access
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_ACCESS_ERROR);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_ACCOUNTS);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_API);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_BILLING_OPAYO);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_AUCTIONS);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_CHANGE_PASSWORD);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_FORGOT_PASSWORD);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_FORGOT_USERNAME);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_IMAGE);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_INDEX);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_LANGUAGE);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_LOCATION);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_LOGIN);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_LOGOUT);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_LOT_DETAILS);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_LOT_ITEM);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_NOTIFICATIONS);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_OTHER_LOTS);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_PROJECTOR);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_REGISTER);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_RESET_PASSWORD);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_SEARCH);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_SIGNUP);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_HEALTH);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_CROSS_DOMAIN_AJAX_REQUEST_PROXY);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_WATCHLIST);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_ERROR_REPORT);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_DOWNLOAD);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_FEED);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_AUDIO);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_SYNC);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_SITEMAP);
        $this->allow($anonymousRole, Constants\ResponsiveRoute::C_SSO);

        $this->allow($customerRole, Constants\ResponsiveRoute::C_ACCOUNTS);
        $this->allow($customerRole, Constants\ResponsiveRoute::C_MY_ALERTS);
        $this->allow($customerRole, Constants\ResponsiveRoute::C_MY_INVOICES);
        $this->allow($customerRole, Constants\ResponsiveRoute::C_MY_ITEMS);
        $this->allow($customerRole, Constants\ResponsiveRoute::C_MY_SETTLEMENTS);
        $this->allow($customerRole, Constants\ResponsiveRoute::C_PROFILE);
        $this->allow($customerRole, Constants\ResponsiveRoute::C_REPORT_PROBLEMS);
        $this->allow($customerRole, Constants\ResponsiveRoute::C_SEARCH);
        $this->allow($customerRole, Constants\ResponsiveRoute::C_STACKED_TAX_INVOICE);

        $this->allow($adminRole, Constants\AdminRoute::C_INSTALLATION_SETTING);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_PLACE_BID);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_ACCOUNT);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONEER);
        $this->allow($adminRole, self::RESOURCE_MANAGE_AUCTIONS_ALL);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_AUCTION_LOCATION);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_BID_INCREMENT);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_BUYER_GROUP);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_BUYERS_PREMIUM);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_CATEGORY);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_CHANGE_PASSWORD);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_COUPON);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_CSV_IMPORT);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_CUSTOM_FIELD);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_EMAIL_TEMPLATE);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_HOME);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_FEED);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_INVENTORY);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_INVOICES);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_STACKED_TAX_INVOICE);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_LOCATION);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_LOGOUT);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_LOT_IMAGES);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_LOT_ITEM);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_REPORTS);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_SALES_STAFF_REPORT);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_MAILING_LIST_REPORT);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_SEARCH);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_SITE_CONTENT);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_SYNC);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_CONSIGNOR_COMMISSION_FEE);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_SETTLEMENTS);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_SETTLEMENT_CHECK);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_TRANSLATION);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_TAX_DEFINITION);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_TAX_SCHEMA);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_USERS);
        $this->allow($adminRole, self::RESOURCE_MANAGE_USERS_ALL);
        $this->allow($adminRole, Constants\AdminRoute::C_RTB_INCREMENT);
        $this->allow($adminRole, Constants\AdminRoute::C_RTB_MESSAGE);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_SYSTEM_PARAMETER);
        $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_CUSTOM_TEMPLATE);

        if ($editorUserId) {
            $adminPrivilegeChecker = AdminPrivilegeChecker::new()->initByUserId($editorUserId);
            $isAdmin = $adminPrivilegeChecker->isAdmin();
            if ($isAdmin) {
                $this->applyAdminRestrictions($adminPrivilegeChecker);
            }
            $role = $this->createAclRoleDetector()->detect($editorUserId, $ui);
            if ($role === $staffRole) {
                $this->applyStaffRestrictions($adminPrivilegeChecker);
            } elseif ($role === $customerRole) {
                $this->applyCustomerRestrictions($editorUserId);
            }
        }
    }

    /**
     * @param AdminPrivilegeChecker $adminPrivilegeChecker
     */
    protected function applyAdminRestrictions(AdminPrivilegeChecker $adminPrivilegeChecker): void
    {
        $adminRole = Constants\Role::ACL_ADMIN;
        if (!$adminPrivilegeChecker->hasSubPrivilegeForManageAllAuctions()) {
            // “All auctions” would be auctions created by you and others. Without “All auctions” your manage auction privilege would only be for auctions created by you
            $this->deny($adminRole, self::RESOURCE_MANAGE_AUCTIONS_ALL);
        }
        if (!$adminPrivilegeChecker->hasSubPrivilegeForInformation()) {
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_AUCTION_INVOICE);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_CREATE);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_EDIT);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_EMAIL);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_ENCODE);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_ENTER_BIDS);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_SETTINGS);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_SHOW_IMPORT);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_SMS);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_UPLOAD_AUCTION_IMAGE);
            if (!$adminPrivilegeChecker->hasPrivilegeForManageInventory()) {
                $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_LOT_IMAGES);
            }
            // Auction > Reports
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_AUCTION_LOT);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_AUCTION_LOT_CSV);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_AUCTION_REPORTS);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_BIDDER_OVERVIEW);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_BID_BOOK);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_BID_MASTER_REPORT);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_CUSTOM_CSV_EXPORT);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_LAST_BIDS_REPORT);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_LIVE_TRAIL);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_PHONE_BIDDER_CSV);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_SPENDING_REPORT);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_UNSOLD_LOTS);
        }
        if (!$adminPrivilegeChecker->hasSubPrivilegeForBidders()) {
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_ADD_NEW_BIDDER);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_APPROVE_RESELLER);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_BIDDERS);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_BIDDERS_ABSENTEE);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_BIDDERS_ABSENTEE_LOT);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_BIDDER_EXPORT_BIDMASTER);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_BIDDER_EXPORT_CSV);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_BIDDER_EXPORT_PACTS);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_PHONE_BIDDERS);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_PHONE_BIDDER_AUCTION_BIDDER_AUTOCOMPLETE);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_PHONE_BIDDER_CSV);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_WITHDRAW_RESELLER);
        }
        if (!$adminPrivilegeChecker->hasSubPrivilegeForRunLiveAuction()) {
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_BIDDER_INTEREST);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_HISTORY);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_RESULT);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_RTB_USERS);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_RUN);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_RTB_BIDDER_AUTOCOMPLETE);
            $this->deny($adminRole, Constants\AdminRoute::C_RTB_MESSAGE, Constants\AdminRoute::ARTBMES_ADD);
            $this->deny($adminRole, Constants\AdminRoute::C_RTB_MESSAGE, Constants\AdminRoute::ARTBMES_DEL);
        }
        if (!$adminPrivilegeChecker->hasSubPrivilegeForDeleteAuction()) {
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_DELETE);
        }
        if (!$adminPrivilegeChecker->hasSubPrivilegeForAuctioneerScreen()) {
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_AUCTIONEER);
        }
        if (
            !$adminPrivilegeChecker->hasSubPrivilegeForRunLiveAuction()
            && !$adminPrivilegeChecker->hasSubPrivilegeForAuctioneerScreen()
        ) {
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_REOPEN);
        }
        if (!$adminPrivilegeChecker->hasSubPrivilegeForProjector()) {
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_PROJECTOR);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_PROJECTOR_POP);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_PROJECTOR_POP_SIMPLE);
        }
        if (!$adminPrivilegeChecker->hasSubPrivilegeForBidIncrements()) {
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_BID_INCREMENTS);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_BID_INCREMENT);
        }
        if (!$adminPrivilegeChecker->hasSubPrivilegeForBuyersPremium()) {
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_BUYERS_PREMIUM);
        }
        if (!$adminPrivilegeChecker->hasSubPrivilegeForPermissions()) {
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_PERMISSIONS);
        }
        if (!$adminPrivilegeChecker->hasSubPrivilegeForLots()) {
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_ADD_LOT);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_ADJOINING_LOT);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_CONSIGNOR_SCHEDULE);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_EDIT_LOT);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_EDIT_LOT_AUCTION_BIDDER_AUTOCOMPLETE);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_EDIT_LOT_CONSIGNOR_AUTOCOMPLETE);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_EDIT_LOT_POPULATE_TAX_SCHEMA);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_IMPORT_SAMPLE);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_LOTS);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_LOT_BID_HISTORY);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_LOT_LIST_QUICK_EDIT_AUCTION_BIDDER_AUTOCOMPLETE);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_LOT_LIST_QUICK_EDIT_CONSIGNOR_AUTOCOMPLETE);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_LOT_SYNC);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_REMOVE_LOT);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_RESET_ALL_VIEWS);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_SORT_BY_CONSIGNOR);
        }
        if (
            !$adminPrivilegeChecker->hasSubPrivilegeForLots()
            && !$adminPrivilegeChecker->hasSubPrivilegeForRunLiveAuction()
        ) {
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_LOT_PRESALE);
        }
        if (!$adminPrivilegeChecker->hasAnyPrivilegeForManageAuctions()) {
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_INDEX);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS);
            if (!$adminPrivilegeChecker->hasPrivilegeForManageInventory()) {
                $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_LOT_IMAGES);
            }
        }
        if (!$adminPrivilegeChecker->hasPrivilegeForManageInvoices()) {
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_AUCTION_INVOICE);
            $this->deny($adminRole, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_AUCTION_INVOICE_BIDDER_AUTOCOMPLETE);
        }
    }

    /**
     * @param AdminPrivilegeChecker $adminPrivilegeChecker
     */
    protected function applyStaffRestrictions(AdminPrivilegeChecker $adminPrivilegeChecker): void
    {
        $staffRole = Constants\Role::ACL_STAFF;
        $adminRole = Constants\Role::ACL_ADMIN;
        if (!$adminPrivilegeChecker->hasPrivilegeForManageAuctions()) {
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_AUCTIONS);
            if (!$adminPrivilegeChecker->hasPrivilegeForManageInventory()) {
                $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_LOT_IMAGES);
            }
        }
        if (!$adminPrivilegeChecker->hasPrivilegeForManageInventory()) {
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_INVENTORY);
        }
        if (!$adminPrivilegeChecker->hasPrivilegeForManageUsers()) {
            if (!$adminPrivilegeChecker->hasSubPrivilegeForBidders()) {
                $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_USERS);
            } else {
                $this->allow($adminRole, Constants\AdminRoute::C_MANAGE_USERS);
                $this->deny($adminRole, self::RESOURCE_MANAGE_USERS_ALL);
            }
        }
        if (!$adminPrivilegeChecker->hasPrivilegeForManageInvoices()) {
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_INVOICES);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_STACKED_TAX_INVOICE);
        }
        if (!$adminPrivilegeChecker->hasPrivilegeForManageSettlements()) {
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_SETTLEMENTS);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_SETTLEMENT_CHECK);
        }
        if (!$adminPrivilegeChecker->hasPrivilegeForManageSettings()) {
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_SITE_CONTENT);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_LOCATION);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_CUSTOM_FIELD);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_CONSIGNOR_COMMISSION_FEE);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_COUPON);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_FEED);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_BUYER_GROUP);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_BUYERS_PREMIUM);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_ACCOUNT);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_BID_INCREMENT);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_AUCTIONEER);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_EMAIL_TEMPLATE);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_TRANSLATION);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_SYNC);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_CATEGORY);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_SYSTEM_PARAMETER);
        }
        if (!$adminPrivilegeChecker->hasPrivilegeForManageReports()) {
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_REPORTS);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_SALES_STAFF_REPORT);
            $this->deny($staffRole, Constants\AdminRoute::C_MANAGE_MAILING_LIST_REPORT);
        }
    }

    /**
     * @param int $editorUserId
     */
    protected function applyCustomerRestrictions(int $editorUserId): void
    {
        $isConsignor = $this->getRoleChecker()->isConsignor($editorUserId);
        if (!$isConsignor) {
            $this->deny(Constants\Role::ACL_CUSTOMER, Constants\ResponsiveRoute::C_MY_SETTLEMENTS);
        }
    }
}
