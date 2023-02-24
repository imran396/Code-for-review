<?php
/**
 *
 * SAM-4751: Refactor mailing list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-16
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\MailingList\Report\Csv;

use Sam\Auction\AuctionHelperAwareTrait;
use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Billing\Gate\Availability\BillingGateAvailabilityCheckerCreateTrait;
use Sam\BuyersPremium\Load\BuyersPremiumRangeLoaderCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Core\Transform\Html\HtmlEntityTransformer;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\CustomField\User\Load\UserCustomDataLoaderAwareTrait;
use Sam\CustomField\User\Render\Csv\UserCustomFieldCsvRendererAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Report\Base\Csv\ReportToolAwareTrait;
use Sam\Report\Base\Csv\RowBuilderBase;
use Sam\Sales\Commission\Load\SalesCommissionLoaderAwareTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Storage\Entity\AwareTrait\UserCustomFieldsAwareTrait;
use Sam\Transform\Number\NextNumberFormatterAwareTrait;
use Sam\User\Calculate\UserDateDetectorAwareTrait;
use Sam\User\Identification\UserIdentificationTransformerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use Sam\User\Privilege\Validate\BidderPrivilegeCheckerAwareTrait;

/**
 * Class Renderer
 * @package Sam\Report\MailingList\Report\Csv
 */
class RowBuilder extends RowBuilderBase
{
    use AdminPrivilegeCheckerAwareTrait;
    use AuctionHelperAwareTrait;
    use BidderPrivilegeCheckerAwareTrait;
    use BillingGateAvailabilityCheckerCreateTrait;
    use BlockCipherProviderCreateTrait;
    use BuyersPremiumRangeLoaderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CreditCardLoaderAwareTrait;
    use DateHelperAwareTrait;
    use NextNumberFormatterAwareTrait;
    use ReportToolAwareTrait;
    use SalesCommissionLoaderAwareTrait;
    use UserCustomDataLoaderAwareTrait;
    use UserCustomFieldCsvRendererAwareTrait;
    use UserCustomFieldsAwareTrait;
    use UserDateDetectorAwareTrait;
    use UserIdentificationTransformerAwareTrait;
    use UserLoaderAwareTrait;

    /** @var bool */
    protected bool $hasPrivilegeForManageCcInfo = false;

    /** @var bool|null */
    protected ?bool $isHybridAuctionAvailable = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForManageCcInfo(): bool
    {
        return $this->hasPrivilegeForManageCcInfo;
    }

    /**
     * @param bool $has
     * @return static
     */
    public function enablePrivilegeForManageCcInfo(bool $has): static
    {
        $this->hasPrivilegeForManageCcInfo = $has;
        return $this;
    }

    /**
     * @return bool
     */
    protected function isHybridAuctionAvailable(): bool
    {
        if ($this->isHybridAuctionAvailable === null) {
            $this->isHybridAuctionAvailable = $this->getAuctionHelper()
                ->isHybridAuctionAvailable($this->getSystemAccountId());
        }
        return $this->isHybridAuctionAvailable;
    }

    /**
     * @param bool $isHybridAuctionAvailable
     * @return static
     */
    public function enableHybridAuctionAvailable(bool $isHybridAuctionAvailable): static
    {
        $this->isHybridAuctionAvailable = $isHybridAuctionAvailable;
        return $this;
    }

    /**
     * Get CSV header titles
     * @return array
     */
    protected function getFields(): array
    {
        $columnHeaders = $this->cfg()->get('csv->admin->user');
        $headerTitles = [
            $columnHeaders->{Constants\Csv\User::USERNAME},
            $columnHeaders->{Constants\Csv\User::COMPANY_NAME},
            $columnHeaders->{Constants\Csv\User::EMAIL},
            $columnHeaders->{Constants\Csv\User::FLAG},
            $columnHeaders->{Constants\Csv\User::FIRST_NAME},
            $columnHeaders->{Constants\Csv\User::LAST_NAME},
            $columnHeaders->{Constants\Csv\User::PHONE},
            $columnHeaders->{Constants\Csv\User::PHONE_TYPE},
            $columnHeaders->{Constants\Csv\User::TEXT_ALERT},
            $columnHeaders->{Constants\Csv\User::CUSTOMER_NO},
            $columnHeaders->{Constants\Csv\User::BUYER_SALES_TAX},
            $columnHeaders->{Constants\Csv\User::APPLY_TAX_TO},
            $columnHeaders->{Constants\Csv\User::NOTES},
            $columnHeaders->{Constants\Csv\User::IDENTIFICATION},
            $columnHeaders->{Constants\Csv\User::IDENTIFICATION_TYPE},
            $columnHeaders->{Constants\Csv\User::LOCATION},
            // billing
            $columnHeaders->{Constants\Csv\User::BILLING_CONTACT_TYPE},
            $columnHeaders->{Constants\Csv\User::BILLING_COMPANY_NAME},
            $columnHeaders->{Constants\Csv\User::BILLING_FIRST_NAME},
            $columnHeaders->{Constants\Csv\User::BILLING_LAST_NAME},
            $columnHeaders->{Constants\Csv\User::BILLING_PHONE},
            $columnHeaders->{Constants\Csv\User::BILLING_FAX},
            $columnHeaders->{Constants\Csv\User::BILLING_COUNTRY},
            $columnHeaders->{Constants\Csv\User::BILLING_ADDRESS},
            $columnHeaders->{Constants\Csv\User::BILLING_ADDRESS_2},
            $columnHeaders->{Constants\Csv\User::BILLING_ADDRESS_3},
            $columnHeaders->{Constants\Csv\User::BILLING_CITY},
            $columnHeaders->{Constants\Csv\User::BILLING_STATE},
            $columnHeaders->{Constants\Csv\User::BILLING_ZIP},
            $columnHeaders->{Constants\Csv\User::CC_TYPE},
            $columnHeaders->{Constants\Csv\User::CC_NUMBER},
            $columnHeaders->{Constants\Csv\User::CC_EXP_DATE},
            $columnHeaders->{Constants\Csv\User::BANK_ROUTING_NO},
            $columnHeaders->{Constants\Csv\User::BANK_ACCOUNT_NO},
            $columnHeaders->{Constants\Csv\User::BANK_ACCOUNT_TYPE},
            $columnHeaders->{Constants\Csv\User::BANK_NAME},
            $columnHeaders->{Constants\Csv\User::BANK_ACCOUNT_NAME},
            // shipping
            $columnHeaders->{Constants\Csv\User::SHIPPING_CONTACT_TYPE},
            $columnHeaders->{Constants\Csv\User::SHIPPING_COMPANY_NAME},
            $columnHeaders->{Constants\Csv\User::SHIPPING_FIRST_NAME},
            $columnHeaders->{Constants\Csv\User::SHIPPING_LAST_NAME},
            $columnHeaders->{Constants\Csv\User::SHIPPING_PHONE},
            $columnHeaders->{Constants\Csv\User::SHIPPING_FAX},
            $columnHeaders->{Constants\Csv\User::SHIPPING_COUNTRY},
            $columnHeaders->{Constants\Csv\User::SHIPPING_ADDRESS},
            $columnHeaders->{Constants\Csv\User::SHIPPING_ADDRESS_2},
            $columnHeaders->{Constants\Csv\User::SHIPPING_ADDRESS_3},
            $columnHeaders->{Constants\Csv\User::SHIPPING_CITY},
            $columnHeaders->{Constants\Csv\User::SHIPPING_STATE},
            $columnHeaders->{Constants\Csv\User::SHIPPING_ZIP},
            $columnHeaders->{Constants\Csv\User::REGISTRATION_DATE},
            $columnHeaders->{Constants\Csv\User::LAST_BID_DATE},
            $columnHeaders->{Constants\Csv\User::LAST_WIN_DATE},
            $columnHeaders->{Constants\Csv\User::LAST_INVOICE_DATE},
            $columnHeaders->{Constants\Csv\User::LAST_PAYMENT_DATE},
            $columnHeaders->{Constants\Csv\User::LAST_LOGIN_DATE},
            $columnHeaders->{Constants\Csv\User::HAS_CREDIT_CARD},
            $columnHeaders->{Constants\Csv\User::IS_BIDDER},
            $columnHeaders->{Constants\Csv\User::HOUSE_BIDDER},
            $columnHeaders->{Constants\Csv\User::IS_PREFERRED_BIDDER},
            $columnHeaders->{Constants\Csv\User::AGENT},
            $columnHeaders->{Constants\Csv\User::NEWSLETTER},
            $columnHeaders->{Constants\Csv\User::MAKE_PERMANENT_BIDDER_NO},
            $columnHeaders->{Constants\Csv\User::REFERRER},
            $columnHeaders->{Constants\Csv\User::REFERRER_HOST},
            $columnHeaders->{Constants\Csv\User::IP_USED},
            // admin privileges
            $columnHeaders->{Constants\Csv\User::IS_ADMIN},
            $columnHeaders->{Constants\Csv\User::MANAGE_AUCTIONS},
            $columnHeaders->{Constants\Csv\User::MANAGE_INVENTORY},
            $columnHeaders->{Constants\Csv\User::MANAGE_USERS},
            $columnHeaders->{Constants\Csv\User::MANAGE_INVOICES},
            $columnHeaders->{Constants\Csv\User::MANAGE_SETTLEMENTS},
            $columnHeaders->{Constants\Csv\User::MANAGE_SETTINGS},
            $columnHeaders->{Constants\Csv\User::MANAGE_CC_INFO},
            $columnHeaders->{Constants\Csv\User::SALES_STAFF},
            $columnHeaders->{Constants\Csv\User::REPORTS},
            $columnHeaders->{Constants\Csv\User::SUPERADMIN},
            $columnHeaders->{Constants\Csv\User::SALES_COMMISSION_STEPDOWN},
            $columnHeaders->{Constants\Csv\User::SALES_CONSIGNMENT_COMMISSION},
            // admin privileges
            $columnHeaders->{Constants\Csv\User::MANAGE_ALL_AUCTIONS},
            $columnHeaders->{Constants\Csv\User::DELETE_AUCTION},
            $columnHeaders->{Constants\Csv\User::ARCHIVE_AUCTION},
            $columnHeaders->{Constants\Csv\User::RESET_AUCTION},
            $columnHeaders->{Constants\Csv\User::INFORMATION},
            $columnHeaders->{Constants\Csv\User::PUBLISH},
            $columnHeaders->{Constants\Csv\User::LOTS},
            $columnHeaders->{Constants\Csv\User::AVAILABLE_LOTS},
            $columnHeaders->{Constants\Csv\User::BIDDERS},
            $columnHeaders->{Constants\Csv\User::REMAINING_USERS},
            $columnHeaders->{Constants\Csv\User::RUN_LIVE_AUCTION},
            $columnHeaders->{Constants\Csv\User::ASSISTANT_CLERK},
            $columnHeaders->{Constants\Csv\User::PROJECTOR},
            $columnHeaders->{Constants\Csv\User::BID_INCREMENTS},
            $columnHeaders->{Constants\Csv\User::BUYERS_PREMIUM},
            $columnHeaders->{Constants\Csv\User::PERMISSIONS},
            $columnHeaders->{Constants\Csv\User::CREATE_BIDDER},
            // manage-users privileges
            $columnHeaders->{Constants\Csv\User::USER_PASSWORDS},
            $columnHeaders->{Constants\Csv\User::USER_PRIVILEGES},
            $columnHeaders->{Constants\Csv\User::DELETE_USER},
            $columnHeaders->{Constants\Csv\User::USER_BULK_EXPORT},
            // consignor privileges
            $columnHeaders->{Constants\Csv\User::CONSIGNOR_PRIVILEGES},
            // consignor settings
            $columnHeaders->{Constants\Csv\User::CONSIGNOR_BUYER_TAX_PERCENTAGE},
            $columnHeaders->{Constants\Csv\User::CONSIGNOR_BUYER_TAX_HP},
            $columnHeaders->{Constants\Csv\User::CONSIGNOR_BUYER_TAX_BP},
            $columnHeaders->{Constants\Csv\User::CONSIGNOR_BUYER_TAX_SERVICES},
            $columnHeaders->{Constants\Csv\User::CONSIGNOR_TAX_PERCENTAGE},
            $columnHeaders->{Constants\Csv\User::CONSIGNOR_TAX_HP},
            $columnHeaders->{Constants\Csv\User::CONSIGNOR_TAX_HP_INCLUSIVE_OR_EXCLUSIVE},
            $columnHeaders->{Constants\Csv\User::CONSIGNOR_TAX_COMMISSION},
            $columnHeaders->{Constants\Csv\User::CONSIGNOR_TAX_SERVICES},
            $columnHeaders->{Constants\Csv\User::PAYMENT_INFO},
            // buyer's premium
            $columnHeaders->{Constants\Csv\User::BP_RANGES_LIVE},
            $columnHeaders->{Constants\Csv\User::ADDITIONAL_BP_PERCENTAGE_FOR_INTERNET_BUYERS_LIVE},
            $columnHeaders->{Constants\Csv\User::BP_CALCULATION_LIVE},
            $columnHeaders->{Constants\Csv\User::BP_RANGES_TIMED},
            $columnHeaders->{Constants\Csv\User::BP_CALCULATION_TIMED},
        ];
        if ($this->isHybridAuctionAvailable()) {
            $headerTitles = array_merge(
                $headerTitles,
                [
                    $columnHeaders->{Constants\Csv\User::BP_RANGES_HYBRID},
                    $columnHeaders->{Constants\Csv\User::ADDITIONAL_BP_PERCENTAGE_FOR_INTERNET_BUYERS_HYBRID},
                    $columnHeaders->{Constants\Csv\User::BP_CALCULATION_HYBRID},
                ]
            );
        }

        $userCustomFields = $this->getUserCustomFields();
        foreach ($userCustomFields as $userCustomField) {
            $headerTitles[] = $userCustomField->Name;
        }

        return $headerTitles;
    }

    /**
     * Build Header Titles
     * @return string
     */
    public function buildHeaderLine(): string
    {
        $headerLine = $this->getReportTool()->rowToLine($this->getFields());
        return $headerLine;
    }

    /**
     * @param array $row
     * @return array
     */
    public function buildBodyRow(array $row): array
    {
        $userName = $row['username'];
        $companyName = $row['company_name'];
        $email = $row['email'];
        $userFlagAbbr = UserPureRenderer::new()->makeFlagCsv((int)$row['flag']);
        $firstName = $row['first_name'];
        $lastName = $row['last_name'];
        $phone = $row['phone'];
        $phoneType = UserPureRenderer::new()->makePhoneType((int)$row['phone_type']);
        $csvIsTextAlert = '';
        if ((int)$row['phone_type'] === Constants\User::PT_MOBILE) {
            $csvIsTextAlert = $this->renderBool($row['send_text_alerts']);
        }
        $customerNo = $row['customer_no'];
        $buyersSalesTax = $this->getNextNumberFormatter()->formatPercent($row['sales_tax']);
        $applyTaxTo = UserPureRenderer::new()->makeTaxApplication((int)$row['tax_application']);
        $note = HtmlEntityTransformer::new()->fromHtmlEntity(trim((string)$row['note']));
        $identificationType = UserPureRenderer::new()->makeIdentificationType((int)$row['identification_type']);
        $identification = $this->getUserIdentificationTransformer()
            ->render($row['identification'], (int)$row['identification_type'], true);
        $locationName = $row['location_name'];
        $billingContactType = Constants\User::CONTACT_TYPE_ENUM[(int)$row['billing_contact_type']];
        $billingCompany = $row['billing_company_name'];
        $billingFirstName = $row['billing_first_name'];
        $billingLastName = $row['billing_last_name'];
        $billingPhone = $row['billing_phone'];
        $billingFax = $row['billing_fax'];
        $billingCountry = AddressRenderer::new()->countryName((string)$row['billing_country']);
        $billingAddress = $row['billing_address'];
        $billingAddress2 = $row['billing_address2'];
        $billingAddress3 = $row['billing_address3'];
        $billingCity = $row['billing_city'];
        $billingStateAbbr = (string)$row['billing_state'];
        $billingState = AddressRenderer::new()->stateName($billingStateAbbr, $billingCountry);
        $billingZip = $row['billing_zip'];

        $bodyRow = [
            $userName,
            $companyName,
            $email,
            $userFlagAbbr,
            $firstName,
            $lastName,
            $phone,
            $phoneType,
            $csvIsTextAlert,
            $customerNo,
            $buyersSalesTax,
            $applyTaxTo,
            $note,
            $identification,
            $identificationType,
            $locationName,
            $billingContactType,
            $billingCompany,
            $billingFirstName,
            $billingLastName,
            $billingPhone,
            $billingFax,
            $billingCountry,
            $billingAddress,
            $billingAddress2,
            $billingAddress3,
            $billingCity,
            $billingState,
            $billingStateAbbr,
            $billingZip,
        ];

        $accountId = (int)$row['account_id'];
        $isAchPayment = $this->createBillingGateAvailabilityChecker()->isAchPaymentEnabled($accountId);
        $ccType = '';
        $ccNumber = '';
        $ccExpDate = '';
        $bankRouting = '';
        $bankAcct = '';
        $bankAcctType = '';
        $bankName = '';
        $bankAcctName = '';
        $isCreditCard = false;

        if ($this->hasPrivilegeForManageCcInfo()) {
            $creditCard = $this->getCreditCardLoader()->load((int)$row['cc_type']);
            if ($row['cc_type'] && $creditCard) {
                $ccType = $creditCard->Name;
            }
            if ($row['cc_number']) {
                $ccNumber = $this->createBlockCipherProvider()->construct()->decrypt($row['cc_number']);
            }

            $ccExpDate = (string)$row['cc_exp_date'];

            if ($isAchPayment) {
                $bankRouting = $row['bank_routing_number'];
                $bankAcct = $this->createBlockCipherProvider()->construct()->decrypt($row['bank_account_number']);
                $bankAcctType = UserPureRenderer::new()->makeBankAccountType($row['bank_account_type']);
                $bankName = $row['bank_name'];
                $bankAcctName = $row['bank_account_name'];
            }
            $isCreditCard = $ccNumber !== '' && $ccExpDate !== '';
        }

        if ($this->hasPrivilegeForManageCcInfo()) {
            $bodyRow = array_merge(
                $bodyRow,
                [
                    $ccType,
                    $ccNumber,
                    $ccExpDate,
                ]
            );
            if ($isAchPayment) {
                $bodyRow = array_merge(
                    $bodyRow,
                    [
                        $bankRouting,
                        $bankAcct,
                        $bankAcctType,
                        $bankName,
                        $bankAcctName,
                    ]
                );
            } else {
                $bodyRow = array_merge($bodyRow, array_fill(0, 5, ''));
            }
        } else {
            $bodyRow = array_merge($bodyRow, array_fill(0, 8, ''));
        }

        $shippingContactType = Constants\User::CONTACT_TYPE_ENUM[(int)$row['shipping_contact_type']];
        $shippingCompany = $row['shipping_company'];
        $shippingFirstName = $row['shipping_first_name'];
        $shippingLastName = $row['shipping_last_name'];
        $shippingPhone = $row['shipping_phone'];
        $shippingFax = $row['shipping_fax'];
        $shippingCountry = AddressRenderer::new()->countryName((string)$row['shipping_country']);
        $shippingAddress = $row['shipping_address'];
        $shippingAddress2 = $row['shipping_address2'];
        $shippingAddress3 = $row['shipping_address3'];
        $shippingCity = $row['shipping_city'];
        $shippingStateAbbr = (string)$row['shipping_state'];
        $shippingState = AddressRenderer::new()->stateName($shippingStateAbbr, $shippingCountry);
        $shippingZip = $row['shipping_zip'];

        $systemAccountId = $this->getSystemAccountId();
        $userId = (int)$row['user_id'];
        $dateRegFormatted = $lastLoginDateFormatted = $lastBidDateFormatted = $lastWinDateFormatted
            = $lastInvoiceDateFormatted = $lastPaymentDateFormatted = '';
        $bidderPrivilegeChecker = $this->getBidderPrivilegeChecker()
            ->enableReadOnlyDb(true)
            ->initByUserId($userId);
        $isBidder = $bidderPrivilegeChecker->isBidder();
        $isHouseBidder = $bidderPrivilegeChecker->hasPrivilegeForHouse();
        $isPreferredBidder = $bidderPrivilegeChecker->hasPrivilegeForPreferred();
        $isAgent = $bidderPrivilegeChecker->hasPrivilegeForAgent();
        $lastBidDateSys = $this->getUserDateDetector()->detectLastBidDateSys($userId);
        //get last winning date
        $lastWinDateSys = $this->getUserDateDetector()->detectLastWinBidDateSys($userId);
        //get last invoice date
        $lastInvoiceDateSys = $this->getUserDateDetector()->detectLastInvoiceDateSys($userId);
        //get last invoice or settlement payment date
        $lastPaymentDateSys = $this->getUserDateDetector()->detectLastPaymentDateSys($userId);

        $dateHelper = $this->getDateHelper();

        $createdOnIso = $row['created_on'];
        $logDateIso = $row['log_date'];
        if ($createdOnIso) {
            $dateRegSys = $dateHelper->convertUtcToSysByDateIso($createdOnIso);
            $dateRegFormatted = $dateHelper->formattedDate($dateRegSys, $systemAccountId);
        }
        if ($lastBidDateSys) {
            $lastBidDateFormatted = $dateHelper->formattedDate($lastBidDateSys, $systemAccountId);
        }
        if ($lastWinDateSys) {
            $lastWinDateFormatted = $dateHelper->formattedDate($lastWinDateSys, $systemAccountId);
        }
        if ($lastInvoiceDateSys) {
            $lastInvoiceDateFormatted = $dateHelper->formattedDate($lastInvoiceDateSys, $systemAccountId);
        }
        if ($lastPaymentDateSys) {
            $lastPaymentDateFormatted = $dateHelper->formattedDate($lastPaymentDateSys, $systemAccountId);
        }
        if ($logDateIso) {
            $logDateSys = $dateHelper->convertUtcToSysByDateIso($logDateIso);
            $lastLoginDateFormatted = $dateHelper->formattedDate($logDateSys, $systemAccountId);
        }

        $isNewsletter = (bool)$row['news_letter'];
        $isUsePermanentBidderno = (bool)$row['use_permanent_bidderno'];
        $referrer = $row['referrer'];
        $referrerHost = $row['referrer_host'];
        $ipUsed = $row['ip_address'] ?: '';
        $bodyRow = array_merge(
            $bodyRow,
            [
                $shippingContactType,
                $shippingCompany,
                $shippingFirstName,
                $shippingLastName,
                $shippingPhone,
                $shippingFax,
                $shippingCountry,
                $shippingAddress,
                $shippingAddress2,
                $shippingAddress3,
                $shippingCity,
                $shippingState,
                $shippingStateAbbr,
                $shippingZip,
                $dateRegFormatted,
                $lastBidDateFormatted,
                $lastWinDateFormatted,
                $lastInvoiceDateFormatted,
                $lastPaymentDateFormatted,
                $lastLoginDateFormatted,
                $this->renderBool($isCreditCard),
                $this->renderBool($isBidder),
                $this->renderBool($isHouseBidder),
                $this->renderBool($isPreferredBidder),
                $this->renderBool($isAgent),
                $this->renderBool($isNewsletter),
                $this->renderBool($isUsePermanentBidderno),
                $referrer,
                $referrerHost,
                $ipUsed,
            ]
        );

        // admin privileges
        $isAdmin = $isManageAuctions = $isManageInventory = $isManageUsers = $isManageInvoices
            = $isManageConsignorSettlements = $isManageSettings = $isManageCcInfo = $isManageSalesStaff
            = $isManageReports = $isSuperadmin = $isSalesCommissionStepdown = $isManageAllAuctions = $isDeleteAuction
            = $isArchiveAuction = $isResetAuction = $isInformation = $isPublish
            = $isLots = $isAvailableLots = $isBidders = $isRemainingUsers
            = $isRunLiveAuction = $isAssistantClerk = $isProjector = $isBidIncrements
            = $isBidPremium = $isPermissions = $isUserPasswords = $isUserPrivileges
            = $isUsersBulkExp = $userSalesCommissionList = false;

        $admin = $this->getUserLoader()->loadAdmin($userId, true);
        if ($admin) {
            $adminPrivilegeChecker = $this->getAdminPrivilegeChecker()
                ->enableReadOnlyDb(true)
                ->initByAdmin($admin);
            $isAdmin = true;
            $isManageAuctions = $adminPrivilegeChecker->hasPrivilegeForManageAuctions();
            $isManageInventory = $adminPrivilegeChecker->hasPrivilegeForManageInventory();
            $isManageUsers = $adminPrivilegeChecker->hasPrivilegeForManageUsers();
            $isManageInvoices = $adminPrivilegeChecker->hasPrivilegeForManageInvoices();
            $isManageConsignorSettlements = $adminPrivilegeChecker->hasPrivilegeForManageSettlements();
            $isManageSettings = $adminPrivilegeChecker->hasPrivilegeForManageSettings();
            $isManageCcInfo = $adminPrivilegeChecker->hasPrivilegeForManageCcInfo();
            $isManageSalesStaff = $adminPrivilegeChecker->hasPrivilegeForSalesStaff();
            $isManageReports = $adminPrivilegeChecker->hasPrivilegeForManageReports();
            $isSuperadmin = $adminPrivilegeChecker->hasPrivilegeForSuperadmin();

            if ($adminPrivilegeChecker->hasPrivilegeForManageReports()) {
                $isSalesCommissionStepdown = $admin->SalesCommissionStepdown;
                $userSalesCommissionList = $this->getSalesCommissionLoader()->loadForUserAsString($admin->UserId, $accountId);
            }

            if ($adminPrivilegeChecker->hasPrivilegeForManageAuctions()) {
                $isManageAllAuctions = $adminPrivilegeChecker->hasSubPrivilegeForManageAllAuctions();
                $isDeleteAuction = $adminPrivilegeChecker->hasSubPrivilegeForDeleteAuction();
                $isArchiveAuction = $adminPrivilegeChecker->hasSubPrivilegeForArchiveAuction();
                $isResetAuction = $adminPrivilegeChecker->hasSubPrivilegeForResetAuction();
                $isInformation = $adminPrivilegeChecker->hasSubPrivilegeForInformation();
                $isPublish = $adminPrivilegeChecker->hasSubPrivilegeForPublish();
                $isLots = $adminPrivilegeChecker->hasSubPrivilegeForLots();
                $isAvailableLots = $adminPrivilegeChecker->hasSubPrivilegeForAvailableLots();
                $isBidders = $adminPrivilegeChecker->hasSubPrivilegeForBidders();
                $isRemainingUsers = $adminPrivilegeChecker->hasSubPrivilegeForRemainingUsers();
                $isRunLiveAuction = $adminPrivilegeChecker->hasSubPrivilegeForRunLiveAuction();
                $isProjector = $adminPrivilegeChecker->hasSubPrivilegeForProjector();
                $isBidIncrements = $adminPrivilegeChecker->hasSubPrivilegeForBidIncrements();
                $isBidPremium = $adminPrivilegeChecker->hasSubPrivilegeForBuyersPremium();
                $isPermissions = $adminPrivilegeChecker->hasSubPrivilegeForPermissions();
            }

            if ($adminPrivilegeChecker->hasPrivilegeForManageUsers()) {
                $isUserPasswords = $adminPrivilegeChecker->hasSubPrivilegeForUserPasswords();
                $isUserPrivileges = $adminPrivilegeChecker->hasSubPrivilegeForUserPrivileges();
                $isUsersBulkExp = $adminPrivilegeChecker->hasSubPrivilegeForBulkUserExport();
            }
        }

        $isConsignorPrivileges = false;
        $consignorBuyerTax = $consignorBuyerTaxHp = $consignorBuyerTaxBp = $consignorBuyerTaxServices
            = $consignorConsignorTax = $consignorTaxHp = $consignorTaxHpIncExc = $consignorTaxComm
            = $consignorTaxServices = $consignorPaymentInfo = '';
        $consignor = $this->getUserLoader()->loadConsignor($userId, true);
        if ($consignor) {
            $isConsignorPrivileges = true;
            $consignorBuyerTax = $this->getNextNumberFormatter()->formatPercent($consignor->SalesTax);
            $consignorBuyerTaxHp = $consignor->BuyerTaxHp;
            $consignorBuyerTaxBp = $consignor->BuyerTaxBp;
            $consignorBuyerTaxServices = $consignor->BuyerTaxServices;
            $consignorConsignorTax = $this->getNextNumberFormatter()->formatPercent($consignor->ConsignorTax);
            $consignorTaxHp = $consignor->ConsignorTaxHp;
            $consignorTaxHpIncExc = Constants\Csv\User::$consignorTaxHpValues[$consignor->ConsignorTaxHpType] ?? '';
            $consignorTaxComm = $consignor->ConsignorTaxComm;
            $consignorTaxServices = $consignor->ConsignorTaxServices;
            $consignorPaymentInfo = $consignor->PaymentInfo;
        }

        $bpRangesLive = $bpAdditionalForInternetLive = $bpRangeCalculationLive = $bpRangesTimed
            = $bpRangeCalculationTimed = $bpRangesHybrid = $bpAdditionalForInternetHybrid
            = $bpRangeCalculationHybrid = '';
        $bidder = $this->getUserLoader()->loadBidder($userId, true);
        if ($bidder) {
            $bpRangesLive = $this->getUserBps($userId, $accountId, Constants\Auction::LIVE);
            $bpAdditionalForInternetLive = $bidder->AdditionalBpInternetLive !== null
                ? $this->getNextNumberFormatter()->formatPercent($bidder->AdditionalBpInternetLive) : '';
            $bpRangeCalculationLive = $bidder->BpRangeCalculationLive;
            $bpRangesTimed = $this->getUserBps($userId, $accountId, Constants\Auction::TIMED);
            $bpRangeCalculationTimed = $bidder->BpRangeCalculationTimed;
            if ($this->isHybridAuctionAvailable()) {
                $bpRangesHybrid = $this->getUserBps($userId, $accountId, Constants\Auction::HYBRID);
                $bpAdditionalForInternetHybrid = $bidder->AdditionalBpInternetHybrid !== null
                    ? $this->getNextNumberFormatter()->formatPercent($bidder->AdditionalBpInternetHybrid) : '';
                $bpRangeCalculationHybrid = $bidder->BpRangeCalculationHybrid;
            }
        }

        $bodyRow = array_merge(
            $bodyRow,
            [
                $this->renderBool($isAdmin),
                $this->renderBool($isManageAuctions),
                $this->renderBool($isManageInventory),
                $this->renderBool($isManageUsers),
                $this->renderBool($isManageInvoices),
                $this->renderBool($isManageConsignorSettlements),
                $this->renderBool($isManageSettings),
                $this->renderBool($isManageCcInfo),
                $this->renderBool($isManageSalesStaff),
                $this->renderBool($isManageReports),
                $this->renderBool($isSuperadmin),
                $this->renderBool($isSalesCommissionStepdown),
                $userSalesCommissionList,
                $this->renderBool($isManageAllAuctions),
                $this->renderBool($isDeleteAuction),
                $this->renderBool($isArchiveAuction),
                $this->renderBool($isResetAuction),
                $this->renderBool($isInformation),
                $this->renderBool($isPublish),
                $this->renderBool($isLots),
                $this->renderBool($isAvailableLots),
                $this->renderBool($isBidders),
                $this->renderBool($isRemainingUsers),
                $this->renderBool($isRunLiveAuction),
                $this->renderBool($isAssistantClerk),
                $this->renderBool($isProjector),
                $this->renderBool($isBidIncrements),
                $this->renderBool($isBidPremium),
                $this->renderBool($isPermissions),
                $this->renderBool($isUserPasswords),
                $this->renderBool($isUserPrivileges),
                $this->renderBool($isUsersBulkExp),
                $this->renderBool($isConsignorPrivileges),
                $consignorBuyerTax,
                $consignorBuyerTaxHp,
                $consignorBuyerTaxBp,
                $consignorBuyerTaxServices,
                $consignorConsignorTax,
                $consignorTaxHp,
                $consignorTaxHpIncExc,
                $consignorTaxComm,
                $consignorTaxServices,
                $consignorPaymentInfo,
                $bpRangesLive,
                $bpAdditionalForInternetLive,
                $bpRangeCalculationLive,
                $bpRangesTimed,
                $bpRangeCalculationTimed,
            ]
        );

        if ($this->isHybridAuctionAvailable()) {
            $bodyRow = array_merge(
                $bodyRow,
                [
                    $bpRangesHybrid,
                    $bpAdditionalForInternetHybrid,
                    $bpRangeCalculationHybrid,
                ]
            );
        }

        // TODO: implement user custom field data fetching via general query
        // write out the custom fields values
        $userCustomFields = $this->getUserCustomFields();
        foreach ($userCustomFields as $userCustomField) {
            $userCustomData = $this->getUserCustomDataLoader()->loadOrCreate(
                $userCustomField,
                $userId,
                true
            );
            $value = $this->getUserCustomFieldCsvRenderer()->render($userCustomField, $userCustomData);
            $bodyRow = array_merge($bodyRow, [$value]);
            unset($userCustomData);
        }

        foreach ($bodyRow as $i => $value) {
            $bodyRow[$i] = $this->getReportTool()->prepareValue($value, $this->getEncoding());
        }

        return $bodyRow;
    }

    /**
     * Get the lot buyers premium for this
     * specific user
     * @param int $userId
     * @param int $accountId
     * @param string $auctionType
     * @return string pipe separated buyers premium values
     */
    protected function getUserBps(int $userId, int $accountId, string $auctionType): string
    {
        $bpRanges = $this->createBuyersPremiumRangeLoader()
            ->loadBpRangeByUserId($userId, $accountId, $auctionType);
        if (count($bpRanges) > 0) {
            $pairCount = 0;
            $pairs = [];
            foreach ($bpRanges as $bpRange) {
                $set = $bpRange->Fixed . '-' . $bpRange->Percent . '-' . $bpRange->Mode;
                if ($pairCount === 0) {
                    $pairs[] = $set;
                } else {
                    $pairs[] = $bpRange->Amount . ':' . $set;
                }
                $pairCount++;
            }
            $output = implode('|', $pairs);
        } else {
            $output = '';
        }
        return $output;
    }

    /**
     * @param bool $is
     * @return string
     */
    protected function renderBool(bool $is): string
    {
        return $this->getReportTool()->renderBool($is);
    }
}
