<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/2/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Meta\Configuration;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

class JsScriptsMetaConfiguration extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array[]
     */
    public function get(): array
    {
        $type = Constants\Installation::META_ATTR_TYPE;
        $cInstallationSetting = Constants\AdminRoute::C_INSTALLATION_SETTING;
        $cLogin = Constants\AdminRoute::C_LOGIN;
        $cManageAccount = Constants\AdminRoute::C_MANAGE_ACCOUNT;
        $cManageBidIncrement = Constants\AdminRoute::C_MANAGE_BID_INCREMENT;
        $cManageAuctions = Constants\AdminRoute::C_MANAGE_AUCTIONS;
        $cManageAuctioneer = Constants\AdminRoute::C_MANAGE_AUCTIONEER;
        $cManageBuyerGroup = Constants\AdminRoute::C_MANAGE_BUYER_GROUP;
        $cManageBuyersPremium = Constants\AdminRoute::C_MANAGE_BUYERS_PREMIUM;
        $cManageCategory = Constants\AdminRoute::C_MANAGE_CATEGORY;
        $cManageChangePassword = Constants\AdminRoute::C_MANAGE_CHANGE_PASSWORD;
        $cManageCoupon = Constants\AdminRoute::C_MANAGE_COUPON;
        $cManageCustomField = Constants\AdminRoute::C_MANAGE_CUSTOM_FIELD;
        $cManageEmailTemplate = Constants\AdminRoute::C_MANAGE_EMAIL_TEMPLATE;
        $cManageHome = Constants\AdminRoute::C_MANAGE_HOME;
        $cManageFeed = Constants\AdminRoute::C_MANAGE_FEED;
        $cManageInventory = Constants\AdminRoute::C_MANAGE_INVENTORY;
        $cManageInvoices = Constants\AdminRoute::C_MANAGE_INVOICES;
        $cManageStackedTaxInvoice = Constants\AdminRoute::C_MANAGE_STACKED_TAX_INVOICE;
        $cManageLocation = Constants\AdminRoute::C_MANAGE_LOCATION;
        $cManageLotImages = Constants\AdminRoute::C_MANAGE_LOT_IMAGES;
        $cManageReports = Constants\AdminRoute::C_MANAGE_REPORTS;
        $cManageMailingListReport = Constants\AdminRoute::C_MANAGE_MAILING_LIST_REPORT;
        $cManageSalesStaffReport = Constants\AdminRoute::C_MANAGE_SALES_STAFF_REPORT;
        $cManageSettlements = Constants\AdminRoute::C_MANAGE_SETTLEMENTS;
        $cManageSettlementCheck = Constants\AdminRoute::C_MANAGE_SETTLEMENT_CHECK;
        $cManageSiteContent = Constants\AdminRoute::C_MANAGE_SITE_CONTENT;
        $cManageSync = Constants\AdminRoute::C_MANAGE_SYNC;
        $cManageSystemParameter = Constants\AdminRoute::C_MANAGE_SYSTEM_PARAMETER;
        $cManageTranslation = Constants\AdminRoute::C_MANAGE_TRANSLATION;
        $cManageUsers = Constants\AdminRoute::C_MANAGE_USERS;

        $cResponsiveAccounts = Constants\ResponsiveRoute::C_ACCOUNTS;
        $cResponsiveAuctions = Constants\ResponsiveRoute::C_AUCTIONS;
        $cResponsiveChangePassword = Constants\ResponsiveRoute::C_CHANGE_PASSWORD;
        $cResponsiveForgotPassword = Constants\ResponsiveRoute::C_FORGOT_PASSWORD;
        $cResponsiveForgotUsername = Constants\ResponsiveRoute::C_FORGOT_USERNAME;
        $cResponsiveLogin = Constants\ResponsiveRoute::C_LOGIN;
        $cResponsiveLotDetails = Constants\ResponsiveRoute::C_LOT_DETAILS;
        $cResponsiveMyAlerts = Constants\ResponsiveRoute::C_MY_ALERTS;
        $cResponsiveMyInvoices = Constants\ResponsiveRoute::C_MY_INVOICES;
        $cResponsiveMyItems = Constants\ResponsiveRoute::C_MY_ITEMS;
        $cResponsiveMySettlements = Constants\ResponsiveRoute::C_MY_SETTLEMENTS;
        $cResponsiveProfile = Constants\ResponsiveRoute::C_PROFILE;
        $cResponsiveRegister = Constants\ResponsiveRoute::C_REGISTER;
        $cResponsiveResetPassword = Constants\ResponsiveRoute::C_RESET_PASSWORD;
        $cResponsiveSearch = Constants\ResponsiveRoute::C_SEARCH;
        $cResponsiveSignup = Constants\ResponsiveRoute::C_SIGNUP;

        return [
            //admin
            "admin->{$cInstallationSetting}->" . Constants\AdminRoute::AMIS_EDIT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cInstallationSetting}->" . Constants\AdminRoute::AMIS_INFO => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cInstallationSetting}->" . Constants\AdminRoute::AMIS_LOGIN => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cLogin}->" . Constants\AdminRoute::AL_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAccount}->" . Constants\AdminRoute::AMACC_LIST => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAccount}->" . Constants\AdminRoute::AMACC_EDIT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAccount}->" . Constants\AdminRoute::AMACC_CREATE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_ADD_LOT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_ADD_NEW_BIDDER => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_AUCTION_INVOICE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_AUCTION_REPORTS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_AUCTIONEER => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_BID_INCREMENTS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_BIDDERS_ABSENTEE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_BIDDERS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_BUYERS_PREMIUM => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_CREATE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_CUSTOM_CSV_EXPORT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_EDIT_LOT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_EDIT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_EMAIL => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_ENCODE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_ENTER_BIDS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_LAST_BIDS_REPORT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_LOT_PRESALE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_LOTS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_PERMISSIONS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_PHONE_BIDDERS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_PROJECTOR_POP_SIMPLE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_PROJECTOR_POP => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_PROJECTOR => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_RUN => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_SETTINGS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_SHOW_IMPORT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_SMS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_SORT_BY_CONSIGNOR => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_SPENDING_REPORT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctions}->" . Constants\AdminRoute::AMA_UNSOLD_LOTS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageAuctioneer}->" . Constants\AdminRoute::AMAEER_LIST => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageBidIncrement}->" . Constants\AdminRoute::AMBI_HYBRID_AUCTION => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageBidIncrement}->" . Constants\AdminRoute::AMBI_LIVE_AUCTION => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageBidIncrement}->" . Constants\AdminRoute::AMBI_TIMED_AUCTION => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageBuyerGroup}->" . Constants\AdminRoute::AMBG_LIST => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageBuyerGroup}->" . Constants\AdminRoute::AMBG_CREATE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageBuyerGroup}->" . Constants\AdminRoute::AMBG_EDIT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageBuyerGroup}->" . Constants\AdminRoute::AMBG_ADD_USER => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageBuyersPremium}->" . Constants\AdminRoute::AMBP_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageBuyersPremium}->" . Constants\AdminRoute::AMBP_LIST => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageBuyersPremium}->" . Constants\AdminRoute::AMBP_EDIT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageBuyersPremium}->" . Constants\AdminRoute::AMBP_CREATE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageCategory}->" . Constants\AdminRoute::AMCAT_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageChangePassword}->" . Constants\AdminRoute::AMCP_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageCoupon}->" . Constants\AdminRoute::AMCOUPON_LIST => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageCoupon}->" . Constants\AdminRoute::AMCOUPON_EDIT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageCoupon}->" . Constants\AdminRoute::AMCOUPON_CREATE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageCustomField}->" . Constants\AdminRoute::AMCF_EDIT_AUCTION => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageCustomField}->" . Constants\AdminRoute::AMCF_EDIT_LOT_ITEM => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageCustomField}->" . Constants\AdminRoute::AMCF_EDIT_USER => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageCustomField}->" . Constants\AdminRoute::AMCF_LIST_AUCTION => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageCustomField}->" . Constants\AdminRoute::AMCF_LIST_LOT_ITEM => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageCustomField}->" . Constants\AdminRoute::AMCF_LIST_USER => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageEmailTemplate}->" . Constants\AdminRoute::AMETPL_LIST => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageHome}->" . Constants\AdminRoute::AMH_DASHBOARD => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageFeed}->" . Constants\AdminRoute::AMFEED_LIST => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageFeed}->" . Constants\AdminRoute::AMFEED_EDIT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageFeed}->" . Constants\AdminRoute::AMFEED_CREATE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageInventory}->" . Constants\AdminRoute::AMIN_ADD => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageInventory}->" . Constants\AdminRoute::AMIN_BARCODE_OPERATIONS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageInventory}->" . Constants\AdminRoute::AMIN_EDIT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageInventory}->" . Constants\AdminRoute::AMIN_ITEMS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageInvoices}->" . Constants\AdminRoute::AMI_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageInvoices}->" . Constants\AdminRoute::AMI_VIEW => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageStackedTaxInvoice}->" . Constants\AdminRoute::AMSTI_LIST => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageStackedTaxInvoice}->" . Constants\AdminRoute::AMSTI_EDIT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageLocation}->" . Constants\AdminRoute::AML_LIST => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageLocation}->" . Constants\AdminRoute::AML_CREATE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageLocation}->" . Constants\AdminRoute::AML_EDIT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageLotImages}->" . Constants\AdminRoute::AMLIMG_SHOW_IMPORT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageMailingListReport}->" . Constants\AdminRoute::AMMLR_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageMailingListReport}->" . Constants\AdminRoute::AMMLR_CREATE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageMailingListReport}->" . Constants\AdminRoute::AMMLR_EDIT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageMailingListReport}->" . Constants\AdminRoute::AMMLR_VIEW_REPORT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSalesStaffReport}->" . Constants\AdminRoute::AMSSR_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageReports}->" . Constants\AdminRoute::AMR_AUCTIONS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageReports}->" . Constants\AdminRoute::AMR_AUDIT_TRAIL => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageReports}->" . Constants\AdminRoute::AMR_CONSIGNORS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageReports}->" . Constants\AdminRoute::AMR_CUSTOM_LOTS_TEMPLATE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageReports}->" . Constants\AdminRoute::AMR_CUSTOM_LOTS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageReports}->" . Constants\AdminRoute::AMR_DOCUMENT_VIEWS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageReports}->" . Constants\AdminRoute::AMR_INTERNAL_NOTE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageReports}->" . Constants\AdminRoute::AMR_PAYMENT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageReports}->" . Constants\AdminRoute::AMR_REFERRER_DETAILS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageReports}->" . Constants\AdminRoute::AMR_REFERRERS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageReports}->" . Constants\AdminRoute::AMR_SPECIAL_TERMS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageReports}->" . Constants\AdminRoute::AMR_TAX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageReports}->" . Constants\AdminRoute::AMR_UNDER_BIDDERS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSync}->" . Constants\AdminRoute::AMSYNC_LIST => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSystemParameter}->" . Constants\AdminRoute::AMSP_ADMIN_OPTION => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSystemParameter}->" . Constants\AdminRoute::AMSP_AUCTION => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSystemParameter}->" . Constants\AdminRoute::AMSP_HYBRID_AUCTION => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSystemParameter}->" . Constants\AdminRoute::AMSP_INTEGRATION => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSystemParameter}->" . Constants\AdminRoute::AMSP_INVOICING => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSystemParameter}->" . Constants\AdminRoute::AMSP_PAYMENT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSystemParameter}->" . Constants\AdminRoute::AMSP_LAYOUT_AND_SITE_CUSTOMIZATION => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSystemParameter}->" . Constants\AdminRoute::AMSP_LIVE_HYBRID_AUCTION => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSystemParameter}->" . Constants\AdminRoute::AMSP_SYSTEM => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSystemParameter}->" . Constants\AdminRoute::AMSP_TIMED_ONLINE_AUCTION => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSystemParameter}->" . Constants\AdminRoute::AMSP_USER_OPTION => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSettlements}->" . Constants\AdminRoute::AMS_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSettlements}->" . Constants\AdminRoute::AMS_VIEW => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSettlementCheck}->" . Constants\AdminRoute::AMSCH_CREATE_BATCH => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSettlementCheck}->" . Constants\AdminRoute::AMSCH_LIST => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSettlementCheck}->" . Constants\AdminRoute::AMSCH_EDIT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSettlementCheck}->" . Constants\AdminRoute::AMSCH_DELETE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSettlementCheck}->" . Constants\AdminRoute::AMSCH_CREATE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSettlementCheck}->" . Constants\AdminRoute::AMSCH_PRINT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageSiteContent}->" . Constants\AdminRoute::AMSC_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageTranslation}->" . Constants\AdminRoute::AMT_EDIT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageUsers}->" . Constants\AdminRoute::AMU_CREATE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageUsers}->" . Constants\AdminRoute::AMU_EDIT => [
                $type => Constants\Type::T_ARRAY,
            ],
            "admin->{$cManageUsers}->" . Constants\AdminRoute::AMU_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],

            //m
            "m->{$cResponsiveAccounts}->" . Constants\ResponsiveRoute::AACC_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveAuctions}->" . Constants\ResponsiveRoute::AA_ABSENTEE_BIDS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveAuctions}->" . Constants\ResponsiveRoute::AA_ASK_QUESTION => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveAuctions}->" . Constants\ResponsiveRoute::AA_BIDDING_HISTORY => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveAuctions}->" . Constants\ResponsiveRoute::AA_CATALOG => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveAuctions}->" . Constants\ResponsiveRoute::AA_CONFIRM_BID => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveAuctions}->" . Constants\ResponsiveRoute::AA_CONFIRM_BUY => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveAuctions}->" . Constants\ResponsiveRoute::AA_INFO => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveAuctions}->" . Constants\ResponsiveRoute::AA_LIST => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveAuctions}->" . Constants\ResponsiveRoute::AA_LIVE_SALE => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveAuctions}->" . Constants\ResponsiveRoute::AA_TELL_FRIEND => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveChangePassword}->" . Constants\ResponsiveRoute::ACP_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveForgotPassword}->" . Constants\ResponsiveRoute::AFP_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveForgotUsername}->" . Constants\ResponsiveRoute::AFU_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveLogin}->" . Constants\ResponsiveRoute::AL_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveLotDetails}->" . Constants\ResponsiveRoute::ALI_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveMyAlerts}->" . Constants\ResponsiveRoute::AALR_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveMyInvoices}->" . Constants\ResponsiveRoute::AINV_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveMyInvoices}->" . Constants\ResponsiveRoute::AINV_VIEW => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveMyItems}->" . Constants\ResponsiveRoute::AIT_ALL => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveMyItems}->" . Constants\ResponsiveRoute::AIT_BIDDING => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveMyItems}->" . Constants\ResponsiveRoute::AIT_CONSIGNED => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveMyItems}->" . Constants\ResponsiveRoute::AIT_NOT_WON => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveMyItems}->" . Constants\ResponsiveRoute::AIT_WATCHLIST => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveMyItems}->" . Constants\ResponsiveRoute::AIT_WON => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveMySettlements}->" . Constants\ResponsiveRoute::ASTL_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveMySettlements}->" . Constants\ResponsiveRoute::ASTL_VIEW => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveProfile}->" . Constants\ResponsiveRoute::APR_VIEW => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveRegister}->" . Constants\ResponsiveRoute::AR_AUCTION_LOT_ITEM_CHANGES => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveRegister}->" . Constants\ResponsiveRoute::AR_CONFIRM_BIDDER_OPTIONS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveRegister}->" . Constants\ResponsiveRoute::AR_CONFIRM_SHIPPING => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveRegister}->" . Constants\ResponsiveRoute::AR_REGISTRATION_CONFIRM => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveRegister}->" . Constants\ResponsiveRoute::AR_REVISE_BILLING => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveRegister}->" . Constants\ResponsiveRoute::AR_SPECIAL_TERMS_AND_CONDITIONS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveRegister}->" . Constants\ResponsiveRoute::AR_TERMS_AND_CONDITIONS => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveResetPassword}->" . Constants\ResponsiveRoute::ARP_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveSearch}->" . Constants\ResponsiveRoute::ASRCH_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
            "m->{$cResponsiveSignup}->" . Constants\ResponsiveRoute::ASI_INDEX => [
                $type => Constants\Type::T_ARRAY,
            ],
        ];
    }
}
