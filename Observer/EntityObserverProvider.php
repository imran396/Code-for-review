<?php
/**
 * SAM-10663: Remove core->observers from installation config
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer;

use AbsenteeBid;
use Account;
use Auction;
use AuctionAuctioneer;
use AuctionBidder;
use AuctionCache;
use AuctionCustData;
use AuctionCustField;
use AuctionLotItem;
use AuctionLotItemCache;
use BidTransaction;
use Invoice;
use InvoiceItem;
use Location;
use LotCategory;
use LotImage;
use LotItem;
use LotItemCategory;
use LotItemCustData;
use LotItemCustField;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\AbsenteeBid\AbsenteeBidObserver;
use Sam\Observer\Account\AccountObserver;
use Sam\Observer\Auction\AuctionObserver;
use Sam\Observer\AuctionAuctioneer\AuctionAuctioneerObserver;
use Sam\Observer\AuctionBidder\AuctionBidderObserver;
use Sam\Observer\AuctionCache\AuctionCacheObserver;
use Sam\Observer\AuctionCustData\AuctionCustDataObserver;
use Sam\Observer\AuctionCustField\AuctionCustFieldObserver;
use Sam\Observer\AuctionLotItem\AuctionLotItemObserver;
use Sam\Observer\AuctionLotItemCache\AuctionLotItemCacheObserver;
use Sam\Observer\SettingAccessPermission\SettingAccessPermissionObserver;
use Sam\Observer\SettingAuction\SettingAuctionObserver;
use Sam\Observer\BidTransaction\BidTransactionObserver;
use Sam\Observer\Invoice\InvoiceObserver;
use Sam\Observer\InvoiceItem\InvoiceItemObserver;
use Sam\Observer\Location\LocationObserver;
use Sam\Observer\LotCategory\LotCategoryObserver;
use Sam\Observer\LotImage\LotImageObserver;
use Sam\Observer\LotItem\LotItemObserver;
use Sam\Observer\LotItemCategory\LotItemCategoryObserver;
use Sam\Observer\LotItemCustData\LotItemCustDataObserver;
use Sam\Observer\LotItemCustField\LotItemCustFieldObserver;
use Sam\Observer\SamTaxCountryStates\SamTaxCountryStatesObserver;
use Sam\Observer\SettingBillingAuthorizeNet\SettingBillingAuthorizeNetObserver;
use Sam\Observer\SettingBillingEway\SettingBillingEwayObserver;
use Sam\Observer\SettingBillingNmi\SettingBillingNmiObserver;
use Sam\Observer\SettingBillingOpayo\SettingBillingOpayoObserver;
use Sam\Observer\SettingBillingPaypal\SettingBillingPaypalObserver;
use Sam\Observer\SettingBillingPayTrace\SettingBillingPayTraceObserver;
use Sam\Observer\SettingBillingSmartPay\SettingBillingSmartPayObserver;
use Sam\Observer\SettingInvoice\SettingInvoiceObserver;
use Sam\Observer\SettingPassword\SettingPasswordObserver;
use Sam\Observer\SettingRtb\SettingRtbObserver;
use Sam\Observer\SettingSeo\SettingSeoObserver;
use Sam\Observer\SettingSettlement\SettingSettlementObserver;
use Sam\Observer\SettingSettlementCheck\SettingSettlementCheckObserver;
use Sam\Observer\SettingShippingAuctionInc\SettingShippingAuctionIncObserver;
use Sam\Observer\SettingSms\SettingSmsObserver;
use Sam\Observer\SettingSmtp\SettingSmtpObserver;
use Sam\Observer\SettingSystem\SettingSystemObserver;
use Sam\Observer\SettingUi\SettingUiObserver;
use Sam\Observer\SettingUser\SettingUserObserver;
use Sam\Observer\TermsAndConditions\TermsAndConditionsObserver;
use Sam\Observer\User\UserObserver;
use Sam\Observer\UserBilling\UserBillingObserver;
use Sam\Observer\UserCustData\UserCustDataObserver;
use Sam\Observer\UserInfo\UserInfoObserver;
use Sam\Observer\UserShipping\UserShippingObserver;
use Sam\Observer\UserWatchlist\UserWatchlistObserver;
use SamTaxCountryStates;
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
use SettingSeo;
use SettingSettlement;
use SettingSettlementCheck;
use SettingShippingAuctionInc;
use SettingSms;
use SettingSmtp;
use SettingSystem;
use SettingUi;
use SettingUser;
use SplObserver;
use TermsAndConditions;
use User;
use UserBilling;
use UserCustData;
use UserInfo;
use UserShipping;
use UserWatchlist;

/**
 * Class EntityObserverProvider
 * @package Sam\Observer
 */
class EntityObserverProvider extends CustomizableClass
{
    protected const OBSERVERS = [
        AbsenteeBid::class => [AbsenteeBidObserver::class],
        Account::class => [AccountObserver::class],
        Auction::class => [AuctionObserver::class],
        AuctionAuctioneer::class => [AuctionAuctioneerObserver::class],
        AuctionBidder::class => [AuctionBidderObserver::class],
        AuctionCache::class => [AuctionCacheObserver::class],
        AuctionCustData::class => [AuctionCustDataObserver::class],
        AuctionCustField::class => [AuctionCustFieldObserver::class],
        AuctionLotItem::class => [AuctionLotItemObserver::class],
        AuctionLotItemCache::class => [AuctionLotItemCacheObserver::class],
        BidTransaction::class => [BidTransactionObserver::class],
        Invoice::class => [InvoiceObserver::class],
        InvoiceItem::class => [InvoiceItemObserver::class],
        Location::class => [LocationObserver::class],
        LotCategory::class => [LotCategoryObserver::class],
        LotImage::class => [LotImageObserver::class],
        LotItem::class => [LotItemObserver::class],
        LotItemCategory::class => [LotItemCategoryObserver::class],
        LotItemCustData::class => [LotItemCustDataObserver::class],
        LotItemCustField::class => [LotItemCustFieldObserver::class],
        SamTaxCountryStates::class => [SamTaxCountryStatesObserver::class],
        SettingAuction::class => [SettingAuctionObserver::class],
        SettingBillingAuthorizeNet::class => [SettingBillingAuthorizeNetObserver::class],
        SettingBillingEway::class => [SettingBillingEwayObserver::class],
        SettingBillingNmi::class => [SettingBillingNmiObserver::class],
        SettingBillingOpayo::class => [SettingBillingOpayoObserver::class],
        SettingBillingPayTrace::class => [SettingBillingPayTraceObserver::class],
        SettingBillingPaypal::class => [SettingBillingPaypalObserver::class],
        SettingBillingSmartPay::class => [SettingBillingSmartPayObserver::class],
        SettingInvoice::class => [SettingInvoiceObserver::class],
        SettingPassword::class => [SettingPasswordObserver::class],
        SettingRtb::class => [SettingRtbObserver::class],
        SettingSeo::class => [SettingSeoObserver::class],
        SettingSettlement::class => [SettingSettlementObserver::class],
        SettingSettlementCheck::class => [SettingSettlementCheckObserver::class],
        SettingShippingAuctionInc::class => [SettingShippingAuctionIncObserver::class],
        SettingAccessPermission::class => [SettingAccessPermissionObserver::class],
        SettingSms::class => [SettingSmsObserver::class],
        SettingSmtp::class => [SettingSmtpObserver::class],
        SettingSystem::class => [SettingSystemObserver::class],
        SettingUi::class => [SettingUiObserver::class],
        SettingUser::class => [SettingUserObserver::class],
        TermsAndConditions::class => [TermsAndConditionsObserver::class],
        User::class => [UserObserver::class],
        UserBilling::class => [UserBillingObserver::class],
        UserCustData::class => [UserCustDataObserver::class],
        UserInfo::class => [UserInfoObserver::class],
        UserShipping::class => [UserShippingObserver::class],
        UserWatchlist::class => [UserWatchlistObserver::class],
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function hasObservers(string $entityClassName): bool
    {
        return count($this->getObserverClasses($entityClassName)) > 0;
    }

    /**
     * @param string $entityClassName
     * @return SplObserver[]
     */
    public function getObservers(string $entityClassName): array
    {
        $observers = array_map(static fn(string $observerClass) => $observerClass::new(), $this->getObserverClasses($entityClassName));
        return $observers;
    }

    protected function getObserverClasses(string $entityClassName): array
    {
        return self::OBSERVERS[$entityClassName] ?? [];
    }
}
