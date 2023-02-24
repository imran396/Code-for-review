<?php
/**
 * SAM-8543: Dummy classes for service stubbing in unit tests
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Db\Schema;

use AbsenteeBid;
use Account;
use ActionQueue;
use AdditionalSignupConfirmation;
use Admin;
use Auction;
use AuctionAuctioneer;
use AuctionBidder;
use AuctionBidderOption;
use AuctionBidderOptionSelection;
use AuctionCache;
use AuctionCurrency;
use AuctionCustData;
use AuctionCustField;
use AuctionDetailsCache;
use AuctionDynamic;
use AuctionEmailTemplate;
use AuctionImage;
use AuctionincStats;
use AuctionLotItem;
use AuctionLotItemBidderTerms;
use AuctionLotItemCache;
use AuctionLotItemChanges;
use AuctionRtbd;
use AuditTrail;
use Bidder;
use BidIncrement;
use BidTransaction;
use BuyerGroup;
use BuyerGroupUser;
use BuyersPremium;
use BuyersPremiumRange;
use CachedQueue;
use Consignor;
use Coupon;
use CouponAuction;
use CouponLotCategory;
use CreditCard;
use Currency;
use CustomCsvExportConfig;
use CustomCsvExportData;
use CustomLotsTemplateConfig;
use CustomLotsTemplateField;
use EmailTemplate;
use EmailTemplateGroup;
use EntitySync;
use Feed;
use FeedCustomReplacement;
use ImageImportQueue;
use IndexQueue;
use Invoice;
use InvoiceAdditional;
use InvoiceAuction;
use InvoiceItem;
use InvoiceLineItem;
use InvoiceLineItemLotCat;
use InvoicePaymentMethod;
use InvoiceUserBilling;
use InvoiceUserShipping;
use Location;
use LotCategory;
use LotCategoryBuyerGroup;
use LotCategoryCustData;
use LotCategoryTemplate;
use LotFieldConfig;
use LotImage;
use LotImageInBucket;
use LotItem;
use LotItemCategory;
use LotItemCustData;
use LotItemCustField;
use LotItemCustFieldLotCategory;
use LotItemGeolocation;
use MailingListTemplateCategories;
use MailingListTemplates;
use MySearch;
use MySearchCategory;
use MySearchCustom;
use Payment;
use PhoneBidderDedicatedClerk;
use ReportImageImport;
use ResetPassword;
use RtbCurrent;
use RtbCurrentGroup;
use RtbCurrentIncrement;
use RtbCurrentSnapshot;
use RtbMessage;
use RtbSession;
use SamTaxCountryStates;
use SearchIndexFulltext;
use SettingAccessPermission;
use SettingAuction;
use SettingBillingAuthorizeNet;
use SettingBillingEway;
use SettingBillingNmi;
use SettingBillingOpayo;
use SettingBillingPaypal;
use SettingBillingPayTrace;
use SettingBillingSmartPay;
use SettingInvoice;
use SettingPassword;
use SettingRtb;
use SettingSettlement;
use SettingSettlementCheck;
use SettingShippingAuctionInc;
use SettingSms;
use SettingSmtp;
use SettingSystem;
use SettingUi;
use SettingUser;
use Settlement;
use SettlementAdditional;
use SettlementItem;
use SyncNamespace;
use TermsAndConditions;
use TimedOnlineItem;
use TimedOnlineOfferBid;
use Timezone;
use User;
use UserAccount;
use UserAccountStats;
use UserAccountStatsCurrency;
use UserAuthentication;
use UserBilling;
use UserCredit;
use UserCustData;
use SettingSeo;
use UserCustField;
use UserDocumentViews;
use UserInfo;
use UserLog;
use UserLogin;
use UserSalesCommission;
use UserSentLots;
use UserShipping;
use UserWatchlist;
use UserWavebid;
use ViewLanguage;

/**
 * Class DbSchema
 * @package Sam\Core\Constants
 */
class DbSchemaConstants
{
    public const PK_TABLE_PROPERTY_MAP = [
        AbsenteeBid::class => [
            'pk_properties' => ['Id'],
        ],
        Account::class => [
            'pk_properties' => ['Id'],
        ],
        ActionQueue::class => [
            'pk_properties' => ['Id'],
        ],
        AdditionalSignupConfirmation::class => [
            'pk_properties' => ['Id'],
        ],
        Admin::class => [
            'pk_properties' => ['Id'],
        ],
        Auction::class => [
            'pk_properties' => ['Id'],
        ],
        AuctionAuctioneer::class => [
            'pk_properties' => ['Id'],
        ],
        AuctionBidder::class => [
            'pk_properties' => ['Id'],
        ],
        AuctionBidderOption::class => [
            'pk_properties' => ['Id'],
        ],
        AuctionBidderOptionSelection::class => [
            'pk_properties' => ['AuctionId', 'UserId', 'AuctionBidderOptionId'],
        ],
        AuctionCache::class => [
            'pk_properties' => ['AuctionId'],
        ],
        AuctionCurrency::class => [
            'pk_properties' => ['Id'],
        ],
        AuctionCustData::class => [
            'pk_properties' => ['Id'],
        ],
        AuctionCustField::class => [
            'pk_properties' => ['Id'],
        ],
        AuctionDetailsCache::class => [
            'pk_properties' => ['AuctionId', 'Key'],
        ],
        AuctionDynamic::class => [
            'pk_properties' => ['AuctionId'],
        ],
        AuctionEmailTemplate::class => [
            'pk_properties' => ['Id'],
        ],
        AuctionImage::class => [
            'pk_properties' => ['Id'],
        ],
        AuctionLotItem::class => [
            'pk_properties' => ['Id'],
        ],
        AuctionLotItemBidderTerms::class => [
            'pk_properties' => ['AuctionId', 'LotItemId', 'UserId'],
        ],
        AuctionLotItemCache::class => [
            'pk_properties' => ['AuctionLotItemId'],
        ],
        AuctionLotItemChanges::class => [
            'pk_properties' => ['AuctionId', 'LotItemId', 'UserId'],
        ],
        AuctionRtbd::class => [
            'pk_properties' => ['AuctionId'],
        ],
        AuctionincStats::class => [
            'pk_properties' => ['Id'],
        ],
        AuditTrail::class => [
            'pk_properties' => ['Id'],
        ],
        BidIncrement::class => [
            'pk_properties' => ['Id'],
        ],
        BidTransaction::class => [
            'pk_properties' => ['Id'],
        ],
        Bidder::class => [
            'pk_properties' => ['Id'],
        ],
        BuyerGroup::class => [
            'pk_properties' => ['Id'],
        ],
        BuyerGroupUser::class => [
            'pk_properties' => ['Id'],
        ],
        BuyersPremium::class => [
            'pk_properties' => ['Id'],
        ],
        BuyersPremiumRange::class => [
            'pk_properties' => ['Id'],
        ],
        CachedQueue::class => [
            'pk_properties' => ['Id'],
        ],
        Consignor::class => [
            'pk_properties' => ['Id'],
        ],
        Coupon::class => [
            'pk_properties' => ['Id'],
        ],
        CouponAuction::class => [
            'pk_properties' => ['Id'],
        ],
        CouponLotCategory::class => [
            'pk_properties' => ['Id'],
        ],
        CreditCard::class => [
            'pk_properties' => ['Id'],
        ],
        Currency::class => [
            'pk_properties' => ['Id'],
        ],
        CustomCsvExportConfig::class => [
            'pk_properties' => ['Id'],
        ],
        CustomCsvExportData::class => [
            'pk_properties' => ['Id'],
        ],
        CustomLotsTemplateConfig::class => [
            'pk_properties' => ['Id'],
        ],
        CustomLotsTemplateField::class => [
            'pk_properties' => ['Id'],
        ],
        EmailTemplate::class => [
            'pk_properties' => ['Id'],
        ],
        EmailTemplateGroup::class => [
            'pk_properties' => ['Id'],
        ],
        EntitySync::class => [
            'pk_properties' => ['Id'],
        ],
        Feed::class => [
            'pk_properties' => ['Id'],
        ],
        FeedCustomReplacement::class => [
            'pk_properties' => ['Id'],
        ],
        ImageImportQueue::class => [
            'pk_properties' => ['Id'],
        ],
        IndexQueue::class => [
            'pk_properties' => ['EntityType', 'EntityId'],
        ],
        Invoice::class => [
            'pk_properties' => ['Id'],
        ],
        InvoiceAdditional::class => [
            'pk_properties' => ['Id'],
        ],
        InvoiceAuction::class => [
            'pk_properties' => ['Id'],
        ],
        InvoiceItem::class => [
            'pk_properties' => ['Id'],
        ],
        InvoiceLineItem::class => [
            'pk_properties' => ['Id'],
        ],
        InvoiceLineItemLotCat::class => [
            'pk_properties' => ['Id'],
        ],
        InvoicePaymentMethod::class => [
            'pk_properties' => ['Id'],
        ],
        InvoiceUserBilling::class => [
            'pk_properties' => ['InvoiceId'],
        ],
        InvoiceUserShipping::class => [
            'pk_properties' => ['InvoiceId'],
        ],
        Location::class => [
            'pk_properties' => ['Id'],
        ],
        LotCategory::class => [
            'pk_properties' => ['Id'],
        ],
        LotCategoryBuyerGroup::class => [
            'pk_properties' => ['Id'],
        ],
        LotCategoryCustData::class => [
            'pk_properties' => ['Id'],
        ],
        LotCategoryTemplate::class => [
            'pk_properties' => ['Id'],
        ],
        LotFieldConfig::class => [
            'pk_properties' => ['Id'],
        ],
        LotImage::class => [
            'pk_properties' => ['Id'],
        ],
        LotImageInBucket::class => [
            'pk_properties' => ['Id'],
        ],
        LotItem::class => [
            'pk_properties' => ['Id'],
        ],
        LotItemCategory::class => [
            'pk_properties' => ['Id'],
        ],
        LotItemCustData::class => [
            'pk_properties' => ['Id'],
        ],
        LotItemCustField::class => [
            'pk_properties' => ['Id'],
        ],
        LotItemCustFieldLotCategory::class => [
            'pk_properties' => ['Id'],
        ],
        LotItemGeolocation::class => [
            'pk_properties' => ['LotItemId', 'LotItemCustDataId'],
        ],
        MailingListTemplateCategories::class => [
            'pk_properties' => ['MailingListId', 'CategoryId'],
        ],
        MailingListTemplates::class => [
            'pk_properties' => ['Id'],
        ],
        MySearch::class => [
            'pk_properties' => ['Id'],
        ],
        MySearchCategory::class => [
            'pk_properties' => ['Id'],
        ],
        MySearchCustom::class => [
            'pk_properties' => ['Id'],
        ],
        Payment::class => [
            'pk_properties' => ['Id'],
        ],
        PhoneBidderDedicatedClerk::class => [
            'pk_properties' => ['Id'],
        ],
        ReportImageImport::class => [
            'pk_properties' => ['Id'],
        ],
        ResetPassword::class => [
            'pk_properties' => ['Id'],
        ],
        RtbCurrent::class => [
            'pk_properties' => ['Id'],
        ],
        RtbCurrentGroup::class => [
            'pk_properties' => ['Id'],
        ],
        RtbCurrentIncrement::class => [
            'pk_properties' => ['Id'],
        ],
        RtbCurrentSnapshot::class => [
            'pk_properties' => ['Id'],
        ],
        RtbMessage::class => [
            'pk_properties' => ['Id'],
        ],
        RtbSession::class => [
            'pk_properties' => ['Id'],
        ],
        SamTaxCountryStates::class => [
            'pk_properties' => ['Id'],
        ],
        SearchIndexFulltext::class => [
            'pk_properties' => ['AccountId', 'EntityType', 'EntityId'],
        ],
        SettingAuction::class => [
            'pk_properties' => ['Id'],
        ],
        SettingAccessPermission::class => [
            'pk_properties' => ['Id'],
        ],
        SettingBillingAuthorizeNet::class => [
            'pk_properties' => ['Id'],
        ],
        SettingBillingEway::class => [
            'pk_properties' => ['Id'],
        ],
        SettingBillingNmi::class => [
            'pk_properties' => ['Id'],
        ],
        SettingBillingOpayo::class => [
            'pk_properties' => ['Id'],
        ],
        SettingBillingPayTrace::class => [
            'pk_properties' => ['Id'],
        ],
        SettingBillingPaypal::class => [
            'pk_properties' => ['Id'],
        ],
        SettingBillingSmartPay::class => [
            'pk_properties' => ['Id'],
        ],
        SettingInvoice::class => [
            'pk_properties' => ['Id'],
        ],
        SettingPassword::class => [
            'pk_properties' => ['Id'],
        ],
        SettingRtb::class => [
            'pk_properties' => ['Id'],
        ],
        SettingSeo::class => [
            'pk_properties' => ['Id'],
        ],
        SettingSettlement::class => [
            'pk_properties' => ['Id'],
        ],
        SettingSettlementCheck::class => [
            'pk_properties' => ['Id'],
        ],
        SettingShippingAuctionInc::class => [
            'pk_properties' => ['Id'],
        ],
        SettingSms::class => [
            'pk_properties' => ['Id'],
        ],
        SettingSmtp::class => [
            'pk_properties' => ['Id'],
        ],
        SettingSystem::class => [
            'pk_properties' => ['Id'],
        ],
        SettingUi::class => [
            'pk_properties' => ['Id'],
        ],
        SettingUser::class => [
            'pk_properties' => ['Id'],
        ],
        Settlement::class => [
            'pk_properties' => ['Id'],
        ],
        SettlementAdditional::class => [
            'pk_properties' => ['Id'],
        ],
        SettlementItem::class => [
            'pk_properties' => ['Id'],
        ],
        SyncNamespace::class => [
            'pk_properties' => ['Id'],
        ],
        TermsAndConditions::class => [
            'pk_properties' => ['Id'],
        ],
        TimedOnlineItem::class => [
            'pk_properties' => ['Id'],
        ],
        TimedOnlineOfferBid::class => [
            'pk_properties' => ['Id'],
        ],
        Timezone::class => [
            'pk_properties' => ['Id'],
        ],
        User::class => [
            'pk_properties' => ['Id'],
        ],
        UserAccount::class => [
            'pk_properties' => ['UserId', 'AccountId'],
        ],
        UserAccountStats::class => [
            'pk_properties' => ['AccountId', 'UserId'],
        ],
        UserAccountStatsCurrency::class => [
            'pk_properties' => ['AccountId', 'UserId', 'CurrencySign'],
        ],
        UserAuthentication::class => [
            'pk_properties' => ['UserId'],
        ],
        UserBilling::class => [
            'pk_properties' => ['Id'],
        ],
        UserCredit::class => [
            'pk_properties' => ['Id'],
        ],
        UserCustData::class => [
            'pk_properties' => ['Id'],
        ],
        UserCustField::class => [
            'pk_properties' => ['Id'],
        ],
        UserDocumentViews::class => [
            'pk_properties' => ['Id'],
        ],
        UserInfo::class => [
            'pk_properties' => ['Id'],
        ],
        UserLog::class => [
            'pk_properties' => ['Id'],
        ],
        UserLogin::class => [
            'pk_properties' => ['Id'],
        ],
        UserSalesCommission::class => [
            'pk_properties' => ['Id'],
        ],
        UserSentLots::class => [
            'pk_properties' => ['Id'],
        ],
        UserShipping::class => [
            'pk_properties' => ['Id'],
        ],
        UserWatchlist::class => [
            'pk_properties' => ['Id'],
        ],
        UserWavebid::class => [
            'pk_properties' => ['UserId', 'AccountId'],
        ],
        ViewLanguage::class => [
            'pk_properties' => ['Id'],
        ],
    ];
}
