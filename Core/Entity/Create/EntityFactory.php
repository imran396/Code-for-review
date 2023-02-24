<?php

/**
 * (!) This file is auto-generated. Don't modify it.
 *
 * Entity creation service.
 * Call its methods to replace newing up data classes by "new <Entity>();" execution.
 * This way you can manage service dependency regular way and stub its methods in unit test.
 *
 * SAM-9486: Entity factory class generator
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 13, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Create;

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
use AuctionFieldConfig;
use AuctionImage;
use AuctionLotItem;
use AuctionLotItemBidderTerms;
use AuctionLotItemCache;
use AuctionLotItemChanges;
use AuctionRtbd;
use AuctionincStats;
use AuditTrail;
use BidIncrement;
use BidTransaction;
use Bidder;
use BuyerGroup;
use BuyerGroupUser;
use BuyersPremium;
use BuyersPremiumRange;
use CachedQueue;
use Consignor;
use ConsignorCommissionFee;
use ConsignorCommissionFeeRange;
use Coupon;
use CouponAuction;
use CouponLotCategory;
use CreditCard;
use CreditCardSurcharge;
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
use InvoiceUser;
use InvoiceUserBilling;
use InvoiceUserShipping;
use Location;
use LocationTaxSchema;
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
use PublicMainMenuItem;
use ReportImageImport;
use ResetPassword;
use RtbCurrent;
use RtbCurrentGroup;
use RtbCurrentIncrement;
use RtbCurrentSnapshot;
use RtbMessage;
use RtbSession;
use SamTaxCountryStates;
use Sam\Core\Service\CustomizableClass;
use SearchIndexFulltext;
use SettingAccessPermission;
use SettingAuction;
use SettingBillingAuthorizeNet;
use SettingBillingEway;
use SettingBillingNmi;
use SettingBillingOpayo;
use SettingBillingPayTrace;
use SettingBillingPaypal;
use SettingBillingSmartPay;
use SettingInvoice;
use SettingPassword;
use SettingRtb;
use SettingSeo;
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
use SettlementCheck;
use SettlementItem;
use SyncNamespace;
use TaxDefinition;
use TaxDefinitionRange;
use TaxSchema;
use TaxSchemaLotCategory;
use TaxSchemaTaxDefinition;
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
use UserConsignorCommissionFee;
use UserCredit;
use UserCustData;
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

class EntityFactory extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function absenteeBid(): AbsenteeBid
    {
        return new AbsenteeBid();
    }

    public function account(): Account
    {
        return new Account();
    }

    public function actionQueue(): ActionQueue
    {
        return new ActionQueue();
    }

    public function additionalSignupConfirmation(): AdditionalSignupConfirmation
    {
        return new AdditionalSignupConfirmation();
    }

    public function admin(): Admin
    {
        return new Admin();
    }

    public function auction(): Auction
    {
        return new Auction();
    }

    public function auctionAuctioneer(): AuctionAuctioneer
    {
        return new AuctionAuctioneer();
    }

    public function auctionBidder(): AuctionBidder
    {
        return new AuctionBidder();
    }

    public function auctionBidderOption(): AuctionBidderOption
    {
        return new AuctionBidderOption();
    }

    public function auctionBidderOptionSelection(): AuctionBidderOptionSelection
    {
        return new AuctionBidderOptionSelection();
    }

    public function auctionCache(): AuctionCache
    {
        return new AuctionCache();
    }

    public function auctionCurrency(): AuctionCurrency
    {
        return new AuctionCurrency();
    }

    public function auctionCustData(): AuctionCustData
    {
        return new AuctionCustData();
    }

    public function auctionCustField(): AuctionCustField
    {
        return new AuctionCustField();
    }

    public function auctionDetailsCache(): AuctionDetailsCache
    {
        return new AuctionDetailsCache();
    }

    public function auctionDynamic(): AuctionDynamic
    {
        return new AuctionDynamic();
    }

    public function auctionEmailTemplate(): AuctionEmailTemplate
    {
        return new AuctionEmailTemplate();
    }

    public function auctionFieldConfig(): AuctionFieldConfig
    {
        return new AuctionFieldConfig();
    }

    public function auctionImage(): AuctionImage
    {
        return new AuctionImage();
    }

    public function auctionLotItem(): AuctionLotItem
    {
        return new AuctionLotItem();
    }

    public function auctionLotItemBidderTerms(): AuctionLotItemBidderTerms
    {
        return new AuctionLotItemBidderTerms();
    }

    public function auctionLotItemCache(): AuctionLotItemCache
    {
        return new AuctionLotItemCache();
    }

    public function auctionLotItemChanges(): AuctionLotItemChanges
    {
        return new AuctionLotItemChanges();
    }

    public function auctionRtbd(): AuctionRtbd
    {
        return new AuctionRtbd();
    }

    public function auctionincStats(): AuctionincStats
    {
        return new AuctionincStats();
    }

    public function auditTrail(): AuditTrail
    {
        return new AuditTrail();
    }

    public function bidIncrement(): BidIncrement
    {
        return new BidIncrement();
    }

    public function bidTransaction(): BidTransaction
    {
        return new BidTransaction();
    }

    public function bidder(): Bidder
    {
        return new Bidder();
    }

    public function buyerGroup(): BuyerGroup
    {
        return new BuyerGroup();
    }

    public function buyerGroupUser(): BuyerGroupUser
    {
        return new BuyerGroupUser();
    }

    public function buyersPremium(): BuyersPremium
    {
        return new BuyersPremium();
    }

    public function buyersPremiumRange(): BuyersPremiumRange
    {
        return new BuyersPremiumRange();
    }

    public function cachedQueue(): CachedQueue
    {
        return new CachedQueue();
    }

    public function consignor(): Consignor
    {
        return new Consignor();
    }

    public function consignorCommissionFee(): ConsignorCommissionFee
    {
        return new ConsignorCommissionFee();
    }

    public function consignorCommissionFeeRange(): ConsignorCommissionFeeRange
    {
        return new ConsignorCommissionFeeRange();
    }

    public function coupon(): Coupon
    {
        return new Coupon();
    }

    public function couponAuction(): CouponAuction
    {
        return new CouponAuction();
    }

    public function couponLotCategory(): CouponLotCategory
    {
        return new CouponLotCategory();
    }

    public function creditCard(): CreditCard
    {
        return new CreditCard();
    }

    public function creditCardSurcharge(): CreditCardSurcharge
    {
        return new CreditCardSurcharge();
    }

    public function currency(): Currency
    {
        return new Currency();
    }

    public function customCsvExportConfig(): CustomCsvExportConfig
    {
        return new CustomCsvExportConfig();
    }

    public function customCsvExportData(): CustomCsvExportData
    {
        return new CustomCsvExportData();
    }

    public function customLotsTemplateConfig(): CustomLotsTemplateConfig
    {
        return new CustomLotsTemplateConfig();
    }

    public function customLotsTemplateField(): CustomLotsTemplateField
    {
        return new CustomLotsTemplateField();
    }

    public function emailTemplate(): EmailTemplate
    {
        return new EmailTemplate();
    }

    public function emailTemplateGroup(): EmailTemplateGroup
    {
        return new EmailTemplateGroup();
    }

    public function entitySync(): EntitySync
    {
        return new EntitySync();
    }

    public function feed(): Feed
    {
        return new Feed();
    }

    public function feedCustomReplacement(): FeedCustomReplacement
    {
        return new FeedCustomReplacement();
    }

    public function imageImportQueue(): ImageImportQueue
    {
        return new ImageImportQueue();
    }

    public function indexQueue(): IndexQueue
    {
        return new IndexQueue();
    }

    public function invoice(): Invoice
    {
        return new Invoice();
    }

    public function invoiceAdditional(): InvoiceAdditional
    {
        return new InvoiceAdditional();
    }

    public function invoiceAuction(): InvoiceAuction
    {
        return new InvoiceAuction();
    }

    public function invoiceItem(): InvoiceItem
    {
        return new InvoiceItem();
    }

    public function invoiceLineItem(): InvoiceLineItem
    {
        return new InvoiceLineItem();
    }

    public function invoiceLineItemLotCat(): InvoiceLineItemLotCat
    {
        return new InvoiceLineItemLotCat();
    }

    public function invoicePaymentMethod(): InvoicePaymentMethod
    {
        return new InvoicePaymentMethod();
    }

    public function invoiceUser(): InvoiceUser
    {
        return new InvoiceUser();
    }

    public function invoiceUserBilling(): InvoiceUserBilling
    {
        return new InvoiceUserBilling();
    }

    public function invoiceUserShipping(): InvoiceUserShipping
    {
        return new InvoiceUserShipping();
    }

    public function location(): Location
    {
        return new Location();
    }

    public function locationTaxSchema(): LocationTaxSchema
    {
        return new LocationTaxSchema();
    }

    public function lotCategory(): LotCategory
    {
        return new LotCategory();
    }

    public function lotCategoryBuyerGroup(): LotCategoryBuyerGroup
    {
        return new LotCategoryBuyerGroup();
    }

    public function lotCategoryCustData(): LotCategoryCustData
    {
        return new LotCategoryCustData();
    }

    public function lotCategoryTemplate(): LotCategoryTemplate
    {
        return new LotCategoryTemplate();
    }

    public function lotFieldConfig(): LotFieldConfig
    {
        return new LotFieldConfig();
    }

    public function lotImage(): LotImage
    {
        return new LotImage();
    }

    public function lotImageInBucket(): LotImageInBucket
    {
        return new LotImageInBucket();
    }

    public function lotItem(): LotItem
    {
        return new LotItem();
    }

    public function lotItemCategory(): LotItemCategory
    {
        return new LotItemCategory();
    }

    public function lotItemCustData(): LotItemCustData
    {
        return new LotItemCustData();
    }

    public function lotItemCustField(): LotItemCustField
    {
        return new LotItemCustField();
    }

    public function lotItemCustFieldLotCategory(): LotItemCustFieldLotCategory
    {
        return new LotItemCustFieldLotCategory();
    }

    public function lotItemGeolocation(): LotItemGeolocation
    {
        return new LotItemGeolocation();
    }

    public function mailingListTemplateCategories(): MailingListTemplateCategories
    {
        return new MailingListTemplateCategories();
    }

    public function mailingListTemplates(): MailingListTemplates
    {
        return new MailingListTemplates();
    }

    public function mySearch(): MySearch
    {
        return new MySearch();
    }

    public function mySearchCategory(): MySearchCategory
    {
        return new MySearchCategory();
    }

    public function mySearchCustom(): MySearchCustom
    {
        return new MySearchCustom();
    }

    public function payment(): Payment
    {
        return new Payment();
    }

    public function phoneBidderDedicatedClerk(): PhoneBidderDedicatedClerk
    {
        return new PhoneBidderDedicatedClerk();
    }

    public function publicMainMenuItem(): PublicMainMenuItem
    {
        return new PublicMainMenuItem();
    }

    public function reportImageImport(): ReportImageImport
    {
        return new ReportImageImport();
    }

    public function resetPassword(): ResetPassword
    {
        return new ResetPassword();
    }

    public function rtbCurrent(): RtbCurrent
    {
        return new RtbCurrent();
    }

    public function rtbCurrentGroup(): RtbCurrentGroup
    {
        return new RtbCurrentGroup();
    }

    public function rtbCurrentIncrement(): RtbCurrentIncrement
    {
        return new RtbCurrentIncrement();
    }

    public function rtbCurrentSnapshot(): RtbCurrentSnapshot
    {
        return new RtbCurrentSnapshot();
    }

    public function rtbMessage(): RtbMessage
    {
        return new RtbMessage();
    }

    public function rtbSession(): RtbSession
    {
        return new RtbSession();
    }

    public function samTaxCountryStates(): SamTaxCountryStates
    {
        return new SamTaxCountryStates();
    }

    public function searchIndexFulltext(): SearchIndexFulltext
    {
        return new SearchIndexFulltext();
    }

    public function settingAccessPermission(): SettingAccessPermission
    {
        return new SettingAccessPermission();
    }

    public function settingAuction(): SettingAuction
    {
        return new SettingAuction();
    }

    public function settingBillingAuthorizeNet(): SettingBillingAuthorizeNet
    {
        return new SettingBillingAuthorizeNet();
    }

    public function settingBillingEway(): SettingBillingEway
    {
        return new SettingBillingEway();
    }

    public function settingBillingNmi(): SettingBillingNmi
    {
        return new SettingBillingNmi();
    }

    public function settingBillingOpayo(): SettingBillingOpayo
    {
        return new SettingBillingOpayo();
    }

    public function settingBillingPayTrace(): SettingBillingPayTrace
    {
        return new SettingBillingPayTrace();
    }

    public function settingBillingPaypal(): SettingBillingPaypal
    {
        return new SettingBillingPaypal();
    }

    public function settingBillingSmartPay(): SettingBillingSmartPay
    {
        return new SettingBillingSmartPay();
    }

    public function settingInvoice(): SettingInvoice
    {
        return new SettingInvoice();
    }

    public function settingPassword(): SettingPassword
    {
        return new SettingPassword();
    }

    public function settingRtb(): SettingRtb
    {
        return new SettingRtb();
    }

    public function settingSeo(): SettingSeo
    {
        return new SettingSeo();
    }

    public function settingSettlement(): SettingSettlement
    {
        return new SettingSettlement();
    }

    public function settingSettlementCheck(): SettingSettlementCheck
    {
        return new SettingSettlementCheck();
    }

    public function settingShippingAuctionInc(): SettingShippingAuctionInc
    {
        return new SettingShippingAuctionInc();
    }

    public function settingSms(): SettingSms
    {
        return new SettingSms();
    }

    public function settingSmtp(): SettingSmtp
    {
        return new SettingSmtp();
    }

    public function settingSystem(): SettingSystem
    {
        return new SettingSystem();
    }

    public function settingUi(): SettingUi
    {
        return new SettingUi();
    }

    public function settingUser(): SettingUser
    {
        return new SettingUser();
    }

    public function settlement(): Settlement
    {
        return new Settlement();
    }

    public function settlementAdditional(): SettlementAdditional
    {
        return new SettlementAdditional();
    }

    public function settlementCheck(): SettlementCheck
    {
        return new SettlementCheck();
    }

    public function settlementItem(): SettlementItem
    {
        return new SettlementItem();
    }

    public function syncNamespace(): SyncNamespace
    {
        return new SyncNamespace();
    }

    public function taxDefinition(): TaxDefinition
    {
        return new TaxDefinition();
    }

    public function taxDefinitionRange(): TaxDefinitionRange
    {
        return new TaxDefinitionRange();
    }

    public function taxSchema(): TaxSchema
    {
        return new TaxSchema();
    }

    public function taxSchemaLotCategory(): TaxSchemaLotCategory
    {
        return new TaxSchemaLotCategory();
    }

    public function taxSchemaTaxDefinition(): TaxSchemaTaxDefinition
    {
        return new TaxSchemaTaxDefinition();
    }

    public function termsAndConditions(): TermsAndConditions
    {
        return new TermsAndConditions();
    }

    public function timedOnlineItem(): TimedOnlineItem
    {
        return new TimedOnlineItem();
    }

    public function timedOnlineOfferBid(): TimedOnlineOfferBid
    {
        return new TimedOnlineOfferBid();
    }

    public function timezone(): Timezone
    {
        return new Timezone();
    }

    public function user(): User
    {
        return new User();
    }

    public function userAccount(): UserAccount
    {
        return new UserAccount();
    }

    public function userAccountStats(): UserAccountStats
    {
        return new UserAccountStats();
    }

    public function userAccountStatsCurrency(): UserAccountStatsCurrency
    {
        return new UserAccountStatsCurrency();
    }

    public function userAuthentication(): UserAuthentication
    {
        return new UserAuthentication();
    }

    public function userBilling(): UserBilling
    {
        return new UserBilling();
    }

    public function userConsignorCommissionFee(): UserConsignorCommissionFee
    {
        return new UserConsignorCommissionFee();
    }

    public function userCredit(): UserCredit
    {
        return new UserCredit();
    }

    public function userCustData(): UserCustData
    {
        return new UserCustData();
    }

    public function userCustField(): UserCustField
    {
        return new UserCustField();
    }

    public function userDocumentViews(): UserDocumentViews
    {
        return new UserDocumentViews();
    }

    public function userInfo(): UserInfo
    {
        return new UserInfo();
    }

    public function userLog(): UserLog
    {
        return new UserLog();
    }

    public function userLogin(): UserLogin
    {
        return new UserLogin();
    }

    public function userSalesCommission(): UserSalesCommission
    {
        return new UserSalesCommission();
    }

    public function userSentLots(): UserSentLots
    {
        return new UserSentLots();
    }

    public function userShipping(): UserShipping
    {
        return new UserShipping();
    }

    public function userWatchlist(): UserWatchlist
    {
        return new UserWatchlist();
    }

    public function userWavebid(): UserWavebid
    {
        return new UserWavebid();
    }

    public function viewLanguage(): ViewLanguage
    {
        return new ViewLanguage();
    }
}
