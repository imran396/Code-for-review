<?php
/**
 * Describe fields and their properties for soap documentation and wsdl for entity-maker of User.
 *
 * SAM-8841: User entity-maker module structural adjustments for v3-5
 * SAM-4989: User Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           May 22, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * @package Sam\EntityMaker\User
 */
class UserMakerInputMeta extends CustomizableClass
{
    /**
     * @var string only available if core->portal->enabled is true and admin has super admin privilege
     * @soap-type-hint int
     */
    public $accountId;
    /**
     * @var string
     * @soap-type-hint float
     */
    public $additionalBpInternetHybrid;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $admin;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $adminSalesCommissionStepdown;
    /**
     * @var string
     * @soap-type-hint float
     */
    public $additionalBpInternetLive;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $agent;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $archiveAuction;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $auctioneerScreen;
    /**
     * @var string
     * @group User Billing
     */
    public $authNetCai;
    /**
     * @var string
     * @group User Billing
     */
    public $authNetCpi;
    /**
     * @var string
     * @group User Billing
     */
    public $authNetCppi;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $availableLots;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $bidder;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $bidderAgent;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $bidderHouse;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $bidderPreferred;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $bidders;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $bidIncrements;
    /**
     * @var string
     * @group User Billing
     */
    public $billingAddress;
    /**
     * @var string
     * @group User Billing
     */
    public $billingAddress2;
    /**
     * @var string
     * @group User Billing
     */
    public $billingAddress3;
    /**
     * @var string
     * @group User Billing
     */
    public $billingBankAccountName;
    /**
     * @var string
     * @group User Billing
     */
    public $billingBankAccountNumber;
    /**
     * @var string C|B|S or CHECKING|BUSINESSCHECKING|SAVINGS or Checking|Business Checking|Savings
     * @group User Billing
     */
    public $billingBankAccountType;
    /**
     * @var string
     * @group User Billing
     */
    public $billingBankName;
    /**
     * @var string
     * @group User Billing
     */
    public $billingBankRoutingNumber;
    /**
     * @var string
     * @group User Billing
     */
    public $billingCcCode;
    /**
     * @var string m-Y
     * @group User Billing
     */
    public $billingCcExpDate;
    /**
     * @var string
     * @group User Billing
     */
    public $billingCcNumber;
    /**
     * @var string
     * @group User Billing
     */
    public $billingCcNumberHash;
    /**
     * @var string
     * @group User Billing
     */
    public $billingCcType;
    /**
     * @var string
     * @group User Billing
     */
    public $billingCity;
    /**
     * @var string
     * @group User Billing
     */
    public $billingCompanyName;
    /**
     * @var string WORK|HOME|NONE
     * @group User Billing
     * @soap-default-value NONE
     */
    public $billingContactType;
    /**
     * @var string <a href="/api/soap12?op=CountriesAndStates">2 character country Code</a>
     * @group User Billing
     */
    public $billingCountry;
    /**
     * @var string
     * @group User Billing
     */
    public $billingEmail;
    /**
     * @var string
     * @group User Billing
     */
    public $billingFax;
    /**
     * @var string
     * @group User Billing
     */
    public $billingFirstName;
    /**
     * @var string
     * @group User Billing
     */
    public $billingLastName;
    /**
     * @var string
     * @group User Billing
     */
    public $billingPhone;
    /**
     * @var string <a href="/api/soap12?op=CountriesAndStates">2 character code</a> for US states, Canadian provinces or Mexico states
     * @group User Billing
     */
    public $billingState;
    /**
     * @var string
     * @group User Billing
     * @soap-type-hint bool
     */
    public $billingUseCard;
    /**
     * @var string
     * @group User Billing
     */
    public $billingZip;
    /**
     * @var string sliding|tiered
     * @soap-default-value sliding
     */
    public $bpRangeCalculationHybrid;
    /**
     * @var string sliding|tiered
     * @soap-default-value sliding
     */
    public $bpRangeCalculationLive;
    /**
     * @var string sliding|tiered
     * @soap-default-value sliding
     */
    public $bpRangeCalculationTimed;
    /**
     * @var string BP Rule short name
     */
    public $bpRule;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $bulkUserExport;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $buyersPremium;
    /**
     * For optional per user buyer's premium. One Premium range needs to start at 0!
     * @var \Sam\EntityMaker\Base\Data\Premium[]
     */
    public $buyersPremiumsHybrid;
    /**
     * For optional per user buyer's premium. One Premium range needs to start at 0!
     * @var \Sam\EntityMaker\Base\Data\Premium[]
     */
    public $buyersPremiumsLive;
    /**
     * For optional per user buyer's premium. One Premium range needs to start at 0!
     * @var \Sam\EntityMaker\Base\Data\Premium[]
     */
    public $buyersPremiumsTimed;
    /**
     * @var int
     */
    public $consignorCommissionId;
    /**
     * @var \Sam\EntityMaker\Base\Data\Range[]
     */
    public $consignorCommissionRanges;
    /**
     * @var string sliding|tiered
     */
    public $consignorCommissionCalculationMethod;
    /**
     * @var int
     */
    public $consignorSoldFeeId;
    /**
     * @var \Sam\EntityMaker\Base\Data\Range[]
     */
    public $consignorSoldFeeRanges;
    /**
     * @var string sliding|tiered
     */
    public $consignorSoldFeeCalculationMethod;
    /**
     * @var string zero|hammer_price|starting_bid|reserve_price|max_bid|current_bid|low_estimate|high_estimate|cost|replacement_price|custom_field:Int
     */
    public $consignorSoldFeeReference;
    /**
     * @var int
     */
    public $consignorUnsoldFeeId;
    /**
     * @var \Sam\EntityMaker\Base\Data\Range[]
     */
    public $consignorUnsoldFeeRanges;
    /**
     * @var string sliding|tiered
     */
    public $consignorUnsoldFeeCalculationMethod;
    /**
     * @var string zero|hammer_price|starting_bid|reserve_price|max_bid|current_bid|low_estimate|high_estimate|cost|replacement_price|custom_field:Int
     */
    public $consignorUnsoldFeeReference;
    /**
     * @var string
     * @group User Info
     */
    public $companyName;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $consignor;
    /**
     * @var string
     * @group User privileges
     */
    public $consignorPaymentInfo;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint float
     */
    public $consignorSalesTax;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $consignorSalesTaxBP;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $consignorSalesTaxHP;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $consignorSalesTaxServices;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint float
     */
    public $consignorTax;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $consignorTaxHP;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $consignorTaxCommission;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $consignorTaxServices;
    /**
     * @var string Required if usePermanentBidderno is true
     * @soap-required-conditionally
     * @soap-type-hint int
     */
    public $customerNo;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $deleteAuction;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $deleteUser;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string For email verification
     * @soap-type-hint bool
     */
    public $emailVerified;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $exclusive;
    /**
     * @var string
     * @group User Info
     */
    public $firstName;
    /**
     * @var string BLOCKED|NAA|UNFLAGGED
     * @soap-default-value UNFLAGGED
     */
    public $flag;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $id;
    /**
     * @var string
     * @group User Info
     */
    public $identification;
    /**
     * @var string DRIVERSLICENSE|PASSPORT|SSN|VAT|OTHER
     * @group User Info
     */
    public $identificationType;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint int
     */
    public $information;
    /**
     * @var string
     * @group User Info
     */
    public $lastName;
    /**
     * @var string
     * @group User Info
     */
    public $locale;
    /**
     * @var string Location.Name
     * @group User Info
     */
    public $location;
    /**
     * @var string Location.Id
     * @group User Info
     * @soap-type-hint int
     */
    public $locationId;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $lots;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $manageAllAuctions;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $manageAuctions;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $manageCcInfo;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $manageConsignorSettlements;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $manageInventory;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $manageInvoices;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $manageSettings;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $manageUsers;
    /**
     * @var string
     * @group User Info
     * @soap-type-hint float
     */
    public $maxOutstanding;
    /**
     * @var string
     * @group User Info
     * @soap-type-hint bool
     */
    public $newsLetter;
    /**
     * @var string
     * @group User Billing
     */
    public $nmiVaultId;
    /**
     * @var string
     * @group User Info
     * @soap-type-hint bool
     */
    public $noTax;
    /**
     * @var string
     * @group User Info
     * @soap-type-hint bool
     */
    public $noTaxBp;
    /**
     * @var string
     * @group User Info
     */
    public $note;
    /**
     * @var string
     * @group User Billing
     */
    public $payTraceCustId;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $permissions;
    /**
     * @var string For email verification
     * @group User Info
     */
    public $phone;
    /**
     * @var string WORK|HOME|MOBILE
     * @group User Info
     */
    public $phoneType;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $projector;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $publish;
    /**
     * @var string
     */
    public $pword;
    /**
     * @var string yyyy-mm-dd hh:mm:ss
     * @group User Info
     */
    public $regAuthDate;
    /**
     * @var string
     * @group User Info
     */
    public $referrer;
    /**
     * @var string
     * @group User Info
     */
    public $referrerHost;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $runLiveAuction;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $remainingUsers;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $reports;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $resetAuction;
    /**
     * @var string For email verification
     * @group User Info
     */
    public $resume;
    /**
     * @var string
     * @group User Billing
     */
    public $opayoTokenId;
    /**
     * @var string
     * @group User Billing
     */
    public $ccNumberEway;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $createBidder;
    /**
     * Sales commissions. One range needs to start at 0!
     * @var \Sam\EntityMaker\Base\Data\SalesCommission[]
     */
    public $salesCommissions;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $salesStaff;
    /**
     * @var string
     * @group User Info
     * @soap-type-hint float
     */
    public $salesTax;
    /**
     * @var string
     * @group User Info
     * @soap-type-hint bool
     */
    public $sendTextAlerts;
    /**
     * @var string
     * @group User Shipping
     */
    public $shippingAddress;
    /**
     * @var string
     * @group User Shipping
     */
    public $shippingAddress2;
    /**
     * @var string
     * @group User Shipping
     */
    public $shippingAddress3;
    /**
     * @var string
     * @group User Shipping
     */
    public $shippingCarrierMethod;
    /**
     * @var string
     * @group User Shipping
     */
    public $shippingCity;
    /**
     * @var string
     * @group User Shipping
     */
    public $shippingCompanyName;
    /**
     * @var string WORK|HOME|NONE
     * @group User Shipping
     * @soap-default-value NONE
     */
    public $shippingContactType;
    /**
     * @var string <a href="/api/soap12?op=CountriesAndStates">2 character country Code</a>
     * @group User Shipping
     */
    public $shippingCountry;
    /**
     * @var string
     * @group User Shipping
     */
    public $shippingFax;
    /**
     * @var string
     * @group User Shipping
     */
    public $shippingFirstName;
    /**
     * @var string
     * @group User Shipping
     */
    public $shippingLastName;
    /**
     * @var string
     * @group User Shipping
     */
    public $shippingPhone;
    /**
     * @var string <a href="/api/soap12?op=CountriesAndStates">2 character code</a> for US states, Canadian provinces or Mexico states
     * @group User Shipping
     */
    public $shippingState;
    /**
     * @var string
     * @group User Shipping
     */
    public $shippingZip;
    /**
     * @var \Sam\EntityMaker\Base\Data\Location
     */
    public $specificLocation;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $superadmin;
    /**
     * @var string UserSyncKey
     */
    public $syncKey;
    /**
     * @var string
     */
    public $syncNamespaceId;
    /**
     * @var string
     * @group User Info
     * @soap-default-value 1
     * @soap-type-hint int
     */
    public $taxApplication;
    /**
     * @var string
     * @group User Info
     */
    public $taxApplicationName;
    /**
     * @var string
     * @group User Info
     */
    public $timezone;
    /**
     * @var \Sam\EntityMaker\Base\Data\Field[]
     */
    public $userCustomFields;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $usePermanentBidderno;
    /**
     * @var string
     * @group User Log
     */
    public $userLog;
    /**
     * @var string
     * @soap-required
     */
    public $username;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $userPasswords;
    /**
     * @var string
     * @group User privileges
     * @soap-type-hint bool
     */
    public $userPrivileges;
    /**
     * @var string Active|Deleted
     */
    public $userStatus;
    /**
     * @var string 1|3
     * @soap-type-hint int
     */
    public $userStatusId;
    /**
     * @var string For email verification
     */
    public $verificationCode;
    /**
     * @var string ViewLanguage.Name
     * @group User Info
     */
    public $viewLanguage;
    /**
     * @var string ViewLanguage.Id
     * @group User Info
     * @soap-type-hint int
     */
    public $viewLanguageId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
