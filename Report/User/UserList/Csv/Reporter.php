<?php
/**
 * SAM-5274: Refactor user "CSV export"
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-07-23
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\User\UserList\Csv;

use DateTime;
use Sam\Auction\AuctionHelperAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Billing\Gate\Availability\BillingGateAvailabilityCheckerCreateTrait;
use Sam\BuyersPremium\Load\BuyersPremiumRangeLoaderCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Admin\Privilege\AdminPrivilegePureChecker;
use Sam\Core\Entity\Model\Bidder\Privilege\BidderPrivilegePureChecker;
use Sam\Core\Entity\Model\Settlement\Status\SettlementStatusPureChecker;
use Sam\Core\Entity\Model\UserInfo\Contact\UserInfoContactPureChecker;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Transform\Csv\CsvTransformer;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\CustomField\User\Help\UserCustomFieldHelperAwareTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Report\User\UserList\Base\DataLoader;
use Sam\Report\User\UserList\Csv\Internal\ConsignorCommissionFeeReportDataMakerCreateTrait;
use Sam\Report\User\UserList\Csv\Internal\Load\ChunkDataProvider;
use Sam\Report\User\UserList\Csv\Internal\Load\ChunkDataProviderCreateTrait;
use Sam\Sales\Commission\Load\SalesCommissionLoaderAwareTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Identification\UserIdentificationTransformerAwareTrait;
use UserCustField;

/**
 * Class Reporter
 * SortInfoAwareTrait - not used at the moment, only default ordering supported
 */
class Reporter extends ReporterBase
{
    use AuctionHelperAwareTrait;
    use BidderNumPaddingAwareTrait;
    use BillingGateAvailabilityCheckerCreateTrait;
    use BlockCipherProviderCreateTrait;
    use BuyersPremiumRangeLoaderCreateTrait;
    use ChunkDataProviderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use ConsignorCommissionFeeReportDataMakerCreateTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterDatePeriodAwareTrait;
    use LimitInfoAwareTrait;
    use NumberFormatterAwareTrait;
    use SalesCommissionLoaderAwareTrait;
    use SortInfoAwareTrait;
    use UserCustomFieldHelperAwareTrait;
    use UserCustomFieldLoaderAwareTrait;
    use UserIdentificationTransformerAwareTrait;

    /** @var int */
    private const CHUNK_SIZE = 200;

    protected ?DataLoader $dataLoader = null;
    private ?string $defaultExportEncoding = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }


    /**
     * @param int $systemAccountId
     * @return $this
     */
    public function construct(int $systemAccountId): Reporter
    {
        $this->setSystemAccountId($systemAccountId);
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultExportEncoding(): string
    {
        if ($this->defaultExportEncoding === null) {
            $this->defaultExportEncoding = $this->getSettingsManager()->getForSystem(Constants\Setting::DEFAULT_EXPORT_ENCODING);
        }
        return $this->defaultExportEncoding;
    }

    /**
     * @param string $defaultExportEncoding
     * @return static
     */
    public function setDefaultExportEncoding(string $defaultExportEncoding): static
    {
        $this->defaultExportEncoding = trim($defaultExportEncoding);
        return $this;
    }

    /**
     * @param string $stateCode
     * @param string $countryCode
     * @return array
     */
    protected function getUserStates(string $stateCode, string $countryCode): array
    {
        $addressRenderer = AddressRenderer::new();
        $stateName = $addressRenderer->stateName($stateCode, $countryCode);
        if ($stateName) {
            return [$stateName, $stateCode];
        }
        return [$stateCode, ''];
    }

    /**
     * Get Output file name
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $currentDateUtc = $this->getCurrentDateUtc();
            $this->outputFileName = 'users-' . $currentDateUtc->format('m-d-Y-His') . ".csv";
        }
        return $this->outputFileName;
    }

    /**
     * Get DataLoader
     * @return DataLoader
     */
    protected function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new()
                ->enableAscendingOrder($this->isAscendingOrder())
                ->enableFilterDatePeriod($this->isFilterDatePeriod())
                ->filterEndDateSys($this->getFilterEndDateSys())
                ->filterStartDateSys($this->getFilterStartDateSys())
                ->setChunkSize(self::CHUNK_SIZE)
                ->setSortColumn($this->getSortColumn())
                ->construct();
        }
        return $this->dataLoader;
    }

    /**
     * @param array $row
     * @param UserCustField[] $userCustomFields
     * @param ChunkDataProvider $dataProvider
     * @return string
     */
    public function buildBodyLine(array $row, array $userCustomFields, ChunkDataProvider $dataProvider): string
    {
        $accountId = $this->getSystemAccountId();
        $encoding = $this->getDefaultExportEncoding();
        $isHybridAuctionAvailable = $this->getAuctionHelper()->isHybridAuctionAvailable($accountId);
        $countryNames = Constants\Country::$names;
        $bankAccountTypeNames = Constants\BillingBank::ACCOUNT_TYPE_NAMES;
        $hasPrivilegeForManageCcInfo = $row['admin_id']
            && AdminPrivilegePureChecker::new()->hasPrivilegeForManageCcInfo($row['admin_privileges']);

        $username = $row['username'];
        $email = $row['email'];
        $userId = (int)$row['user_id'];

        $customerNo = $row['customer_no'];
        $csvIsUsePermanentBidderno = $row['use_permanent_bidderno'] ? 'Y' : 'N';

        $ipAddress = $dataProvider->detectUserLoginIpAddress($userId);

        $csvTransformer = CsvTransformer::new();
        $ipUsed = $ipAddress ? $csvTransformer->convertEncoding($ipAddress, $encoding) : '';

        $userFlagAbbr = UserPureRenderer::new()->makeFlagCsv((int)$row['flag']);

        $dateHelper = $this->getDateHelper();
        //get last bid date
        $lastBidDateUtc = $dataProvider->detectLastBidDateUtc($userId);
        $lastBidDateSys = $dateHelper->convertUtcToSysByDateIso($lastBidDateUtc);
        //get last winning date
        $lastWinDateUtc = $dataProvider->detectLastWinBidDateUtc($userId);
        $lastWinDateSys = $dateHelper->convertUtcToSysByDateIso($lastWinDateUtc);
        //get last invoice date
        $lastInvoiceDateUtc = $dataProvider->detectLastInvoiceDateUtc($userId);
        $lastInvoiceDateSys = $dateHelper->convertUtcToSysByDateIso($lastInvoiceDateUtc);
        //get last invoice or settlement payment date
        $lastPaymentDateUtc = $dataProvider->detectLastPaymentDateUtc($userId);
        $lastPaymentDateSys = $dateHelper->convertUtcToSysByDateIso($lastPaymentDateUtc);
        $dateRegFormatted = '';
        $lastLoginDateFormatted = '';
        $lastBidDateFormatted = '';
        $lastWinDateFormatted = '';
        $lastInvoiceDateFormatted = '';
        $lastPaymentDateFormatted = '';

        $csvIsBidder = $row['bidder_id'] ? 'Y' : 'N';
        $csvIsPreferredBidder = '';
        $csvIsHouseBidder = '';
        $csIsAgent = '';
        if ($row['bidder_id']) {
            $bidderPrivilegeChecker = BidderPrivilegePureChecker::new();
            $csvIsPreferredBidder = $bidderPrivilegeChecker->isPreferred((bool)$row['preferred']) ? 'Y' : 'N';
            $csvIsHouseBidder = $bidderPrivilegeChecker->isHouse((bool)$row['house']) ? 'Y' : 'N';
            $csIsAgent = $bidderPrivilegeChecker->isAgent((bool)$row['agent']) ? 'Y' : 'N';
        }
        $dateHelper = $this->getDateHelper();
        if ($row['created_on']) {
            $dateRegSys = $dateHelper->convertUtcToSysByDateIso($row['created_on']);
            $dateRegFormatted = $dateHelper->formattedDate($dateRegSys, $accountId);
        }
        if ($lastBidDateSys) {
            $lastBidDateFormatted = $dateHelper->formattedDate($lastBidDateSys, $accountId);
        }
        if ($lastWinDateSys) {
            $lastWinDateFormatted = $dateHelper->formattedDate($lastWinDateSys, $accountId);
        }
        if ($lastInvoiceDateSys) {
            $lastInvoiceDateFormatted = $dateHelper->formattedDate($lastInvoiceDateSys, $accountId);
        }
        if ($lastPaymentDateSys) {
            $lastPaymentDateFormatted = $dateHelper->formattedDate($lastPaymentDateSys, $accountId);
        }
        if ($row['log_date']) {
            $logDateSys = $dateHelper->convertUtcToSysByDateIso($row['log_date']);
            $lastLoginDateFormatted = $dateHelper->formattedDate($logDateSys, $accountId);
        }

        //get the UserInfo
        $csvIsTextAlert = '';
        /** Convert UTF-8 encoding to the set encoding for export in settings * */
        $companyName = $csvTransformer->convertEncoding($row['company_name'], $encoding);
        $firstName = $csvTransformer->convertEncoding($row['first_name'], $encoding);
        $lastName = $csvTransformer->convertEncoding($row['last_name'], $encoding);
        $buyersSalesTax = $this->getNumberFormatter()->formatPercent($row['sales_tax']);
        $applyTaxTo = UserPureRenderer::new()->makeTaxApplication($row['tax_application']);
        $note = $csvTransformer->convertEncoding($row['note'], $encoding);
        $phone = $csvTransformer->convertEncoding($row['phone'], $encoding);
        $phoneType = UserPureRenderer::new()->makePhoneType((int)$row['phone_type']);
        if (UserInfoContactPureChecker::new()->isMobilePhoneType(Cast::toInt($row['phone_type']))) {
            $csvIsTextAlert = $row['send_text_alerts'] ? 'Y' : 'N';
        }
        $csvIsNewsletter = $row['news_letter'] ? 'Y' : 'N';
        $referrer = $csvTransformer->convertEncoding($row['referrer'], $encoding);
        $referrerHost = $csvTransformer->convertEncoding($row['referrer_host'], $encoding);
        $identificationType = UserPureRenderer::new()->makeIdentificationType((int)$row['identification_type']);
        $identificationType = $csvTransformer->convertEncoding($identificationType, $encoding);
        $identification = $this->getUserIdentificationTransformer()
            ->render($row['identification'], (int)$row['identification_type'], true);
        $locationName = $csvTransformer->convertEncoding($row['location_name'], $encoding);

        //get user billing
        /** Convert UTF-8 encoding to the set encoding for export in settings * */
        $billContactType = $csvTransformer->convertEncoding(Constants\User::CONTACT_TYPE_ENUM[(int)$row['ub_contact_type']], $encoding);
        $billCompany = $csvTransformer->convertEncoding($row['ub_company_name'], $encoding);
        $billFName = $csvTransformer->convertEncoding($row['ub_first_name'], $encoding);
        $billLName = $csvTransformer->convertEncoding($row['ub_last_name'], $encoding);
        $billPhone = $csvTransformer->convertEncoding($row['ub_phone'], $encoding);
        $billFax = $csvTransformer->convertEncoding($row['ub_fax'], $encoding);

        $billCountry = '';
        if ($row['ub_country'] && isset($countryNames[$row['ub_country']])) {
            $billCountry = $countryNames[$row['ub_country']];
        }
        $billCountry = $csvTransformer->convertEncoding($billCountry, $encoding);

        $billAddress = $csvTransformer->convertEncoding($row['ub_address'], $encoding);
        $billAddress2 = $csvTransformer->convertEncoding($row['ub_address2'], $encoding);
        $billAddress3 = $csvTransformer->convertEncoding($row['ub_address3'], $encoding);
        $billCity = $csvTransformer->convertEncoding($row['ub_city'], $encoding);

        $billState = '';
        $billStateAbbr = '';
        if ($row['ub_state']) {
            [$billState, $billStateAbbr] = $this->getUserStates($row['ub_state'], $row['ub_country']);
            $billState = $csvTransformer->convertEncoding($billState, $encoding);
            $billStateAbbr = $csvTransformer->convertEncoding($billStateAbbr, $encoding);
        }

        $ccType = '';
        $ccNumber = '';
        $ccExpDate = '';

        $bankRouting = '';
        $bankAcct = '';
        $bankAcctType = '';
        $bankName = '';
        $bankAcctName = '';

        $billZip = $csvTransformer->convertEncoding($row['ub_zip'], $encoding);

        $accountId = (int)$row['account_id'];
        $isAchPayment = $this->createBillingGateAvailabilityChecker()->isAchPaymentEnabled($accountId);
        if ($hasPrivilegeForManageCcInfo) {
            if ($row['ub_cc_type'] && $row['ub_cc_type_name']) {
                $ccType = $row['ub_cc_type_name'];
            }
            $ccType = $csvTransformer->convertEncoding($ccType, $encoding);
            if ($row['ub_cc_number']) {
                $ccNumber = $this->createBlockCipherProvider()->construct()->decrypt($row['ub_cc_number']);
            }

            $ccExpDate = $row['ub_cc_exp_date'];

            //add these columns if ACH is enabled
            if ($isAchPayment) {
                $bankRouting = $row['ub_cc_exp_date'];
                $bankAcct = $this->createBlockCipherProvider()->construct()->decrypt($row['ub_bank_account_number']);
                if ($row['ub_bank_account_type'] && isset($bankAccountTypeNames[$row['ub_bank_account_type']])) {
                    $bankAcctType = $bankAccountTypeNames[$row['ub_bank_account_type']];
                }
                $bankName = $csvTransformer->convertEncoding($row['ub_bank_name'], $encoding);
                $bankAcctName = $csvTransformer->convertEncoding($row['ub_bank_account_name'], $encoding);
            }
        }

        $csvIsCreditCard = ($row['ub_cc_number'] && $row['ub_cc_exp_date']) ? 'Y' : 'N';

        //shipping info
        /** Convert UTF-8 encoding to the set encoding for export in settings * */
        $shippingContactType = $csvTransformer->convertEncoding(Constants\User::CONTACT_TYPE_ENUM[(int)$row['us_contact_type']], $encoding);
        $shippingCompany = $csvTransformer->convertEncoding($row['us_company_name'], $encoding);
        $shippingFirstName = $csvTransformer->convertEncoding($row['us_first_name'], $encoding);
        $shippingLastName = $csvTransformer->convertEncoding($row['us_last_name'], $encoding);
        $shippingPhone = $csvTransformer->convertEncoding($row['us_phone'], $encoding);
        $shippingFax = $csvTransformer->convertEncoding($row['us_fax'], $encoding);

        $shippingCountry = '';
        if ($row['us_country'] && isset($countryNames[$row['us_country']])) {
            $shippingCountry = $countryNames[$row['us_country']];
        }
        $shippingCountry = $csvTransformer->convertEncoding($shippingCountry, $encoding);

        $shippingAddress = $csvTransformer->convertEncoding($row['us_address'], $encoding);
        $shippingAddress2 = $csvTransformer->convertEncoding($row['us_address2'], $encoding);
        $shippingAddress3 = $csvTransformer->convertEncoding($row['us_address3'], $encoding);
        $shippingCity = $csvTransformer->convertEncoding($row['us_city'], $encoding);

        $shippingState = '';
        $shippingStateAbbr = '';
        if ($row['us_state']) {
            [$shippingState, $shippingStateAbbr] = $this->getUserStates(
                (string)$row['us_state'],
                (string)$row['us_country']
            );
            $shippingState = $csvTransformer->convertEncoding($shippingState, $encoding);
            $shippingStateAbbr = $csvTransformer->convertEncoding($shippingStateAbbr, $encoding);
        }

        $shippingZip = $csvTransformer->convertEncoding($row['us_zip'], $encoding);

        /* -------------------------------------
         * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
         * This might include adding, changing, or moving columns,
         * modifying header names,
         * modifying data or data format(s)
         * ------------------------------------- */

        //write out the data row
        $bodyRow = [
            $username,
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
            $billContactType,
            $billCompany,
            $billFName,
            $billLName,
            $billPhone,
            $billFax,
            $billCountry,
            $billAddress,
            $billAddress2,
            $billAddress3,
            $billCity,
            $billState,
            $billStateAbbr,
            $billZip,
        ];

        if ($hasPrivilegeForManageCcInfo) {
            $bodyRow = array_merge($bodyRow, [$ccType, $ccNumber, $ccExpDate]);

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
                // newly added fields
                $dateRegFormatted,
                $lastBidDateFormatted,
                $lastWinDateFormatted,
                $lastInvoiceDateFormatted,
                $lastPaymentDateFormatted,
                $lastLoginDateFormatted,
                $csvIsCreditCard,
                $csvIsBidder,
                $csvIsHouseBidder,
                $csvIsPreferredBidder,
                $csIsAgent,
                $csvIsNewsletter,
                $csvIsUsePermanentBidderno,
                $referrer,
                $referrerHost,
                $ipUsed,
            ]
        );

        // admin privileges

        $csvIsAdmin = 'N';
        $csvIsManageAuctions = '';
        $csvIsManageInventory = '';
        $csvIsManageUsers = '';
        $csvIsManageInvoices = '';
        $csvIsManageConsignorSettlements = '';
        $csvIsManageSettings = '';
        $csvIsManageCcInfo = '';
        $csvIsManageSalesStaff = '';
        $csvIsManageReports = '';
        $csvIsSuperadmin = '';
        $csvIsSalesCommissionStepdown = '';
        $csvIsManageAllAuctions = '';
        $csvIsDeleteAuction = '';
        $csvIsArchiveAuction = '';
        $csvIsResetAuction = '';
        $csvIsInformation = '';
        $csvIsPublish = '';
        $csvIsLots = '';
        $csvIsAvailableLots = '';
        $csvIsBidders = '';
        $csvIsRemainingUsers = '';
        $csvIsRunLiveAuction = '';
        $csvIsAssistantClerk = '';
        $csvIsProjector = '';
        $csvIsBidIncrements = '';
        $csvIsBidPremium = '';
        $csvIsPermissions = '';
        $csvIsUserPasswords = '';
        $csvIsUserPrivileges = '';
        $csvIsUsersBulkExp = '';
        $csvIsCreateBidder = '';
        $userSalesCommissionList = '';

        $isMultipleTenant = $this->cfg()->get('core->portal->enabled');
        if ($row['admin_id']) {
            $adminPrivilegeChecker = AdminPrivilegePureChecker::new();
            $csvIsAdmin = 'Y';
            $adminPrivileges = Cast::toInt($row['admin_privileges']);
            $csvIsManageAuctions = $adminPrivilegeChecker->hasPrivilegeForManageAuctions($adminPrivileges) ? 'Y' : 'N';
            $csvIsManageInventory = $adminPrivilegeChecker->hasPrivilegeForManageInventory($adminPrivileges) ? 'Y' : 'N';
            $csvIsManageUsers = $adminPrivilegeChecker->hasPrivilegeForManageUsers($adminPrivileges) ? 'Y' : 'N';
            $csvIsManageInvoices = $adminPrivilegeChecker->hasPrivilegeForManageInvoices($adminPrivileges) ? 'Y' : 'N';
            $csvIsManageConsignorSettlements = $adminPrivilegeChecker->hasPrivilegeForManageSettlements($adminPrivileges) ? 'Y'
                : 'N';
            $csvIsManageSettings = $adminPrivilegeChecker->hasPrivilegeForManageSettings($adminPrivileges) ? 'Y' : 'N';
            $csvIsManageCcInfo = $adminPrivilegeChecker->hasPrivilegeForManageCcInfo($adminPrivileges) ? 'Y' : 'N';
            $csvIsManageSalesStaff = $adminPrivilegeChecker->hasPrivilegeForSalesStaff($adminPrivileges) ? 'Y' : 'N';
            $csvIsManageReports = $adminPrivilegeChecker->hasPrivilegeForManageReports($adminPrivileges) ? 'Y' : 'N';

            if ($isMultipleTenant) {
                $mainAccountId = $this->cfg()->get('core->portal->mainAccountId');
                $csvIsSuperadmin = $adminPrivilegeChecker->hasPrivilegeForCrossDomain($adminPrivileges, $accountId, $mainAccountId, $isMultipleTenant) ? 'Y' : 'N';
            }

            if ($adminPrivilegeChecker->hasPrivilegeForManageReports($adminPrivileges)) {
                $csvIsSalesCommissionStepdown = $row['sales_commission_stepdown'] ? 'Y' : 'N';
                $userSalesCommissionList = $this->getSalesCommissionLoader()->loadForUserAsString($userId, $accountId);
            }

            if ($adminPrivilegeChecker->hasPrivilegeForManageAuctions($adminPrivileges)) {
                $csvIsManageAllAuctions = $adminPrivilegeChecker->hasSubPrivilegeForManageAllAuctions((bool)$row['manage_all_auctions'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsDeleteAuction = $adminPrivilegeChecker->hasSubPrivilegeForDeleteAuction((bool)$row['delete_auction'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsArchiveAuction = $adminPrivilegeChecker->hasSubPrivilegeForArchiveAuction((bool)$row['archive_auction'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsResetAuction = $adminPrivilegeChecker->hasSubPrivilegeForResetAuction((bool)$row['reset_auction'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsInformation = $adminPrivilegeChecker->hasSubPrivilegeForInformation((bool)$row['information'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsPublish = $adminPrivilegeChecker->hasSubPrivilegeForPublish((bool)$row['publish'], (bool)$row['information'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsLots = $adminPrivilegeChecker->hasSubPrivilegeForLots((bool)$row['lots'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsAvailableLots = $adminPrivilegeChecker->hasSubPrivilegeForAvailableLots((bool)$row['available_lots'], (bool)$row['lots'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsBidders = $adminPrivilegeChecker->hasSubPrivilegeForBidders((bool)$row['bidders'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsRemainingUsers = $adminPrivilegeChecker->hasSubPrivilegeForRemainingUsers((bool)$row['remaining_users'], (bool)$row['bidders'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsRunLiveAuction = $adminPrivilegeChecker->hasSubPrivilegeForRunLiveAuction((bool)$row['run_live_auction'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsProjector = $adminPrivilegeChecker->hasSubPrivilegeForProjector((bool)$row['projector'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsBidIncrements = $adminPrivilegeChecker->hasSubPrivilegeForBidIncrements((bool)$row['bid_increments'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsBidPremium = $adminPrivilegeChecker->hasSubPrivilegeForBuyersPremium((bool)$row['buyers_premium'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsPermissions = $adminPrivilegeChecker->hasSubPrivilegeForPermissions((bool)$row['permissions'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsCreateBidder = $adminPrivilegeChecker->hasSubPrivilegeForCreateBidder((bool)$row['create_bidder'], $adminPrivileges) ? 'Y' : 'N';
            }

            if ($adminPrivilegeChecker->hasPrivilegeForManageUsers($adminPrivileges)) {
                $csvIsUserPasswords = $adminPrivilegeChecker->hasSubPrivilegeForUserPasswords((bool)$row['user_passwords'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsUserPrivileges = $adminPrivilegeChecker->hasSubPrivilegeForUserPrivileges((bool)$row['user_privileges'], $adminPrivileges) ? 'Y' : 'N';
                $csvIsUsersBulkExp = $adminPrivilegeChecker->hasSubPrivilegeForBulkUserExport((bool)$row['bulk_user_export'], $adminPrivileges) ? 'Y' : 'N';
            }
        }

        $csvIsConsignorPrivileges = 'N';
        $consignorBuyerTax = '';
        $consignorBuyerTaxHp = '';
        $consignorBuyerTaxBp = '';
        $consignorBuyerTaxServices = '';
        $consignorConsignorTax = '';
        $consignorTaxHp = '';
        $consignorTaxHpIncExc = '';
        $consignorTaxComm = '';
        $consignorTaxServices = '';

        $consignorPaymentInfo = '';
        $isConsignor = (bool)$row['consignor_id'];
        if ($isConsignor) {
            $csvIsConsignorPrivileges = 'Y';
            $consignorBuyerTax = $this->getNumberFormatter()->formatPercent(Cast::toFloat($row['consignor_sales_tax']));
            $consignorBuyerTaxHp = (bool)$row['buyer_tax_hp'];
            $consignorBuyerTaxBp = (bool)$row['buyer_tax_bp'];
            $consignorBuyerTaxServices = (bool)$row['buyer_tax_services'];
            $consignorConsignorTax = $this->getNumberFormatter()->formatPercent(Cast::toFloat($row['consignor_tax']));
            $consignorTaxHp = (bool)$row['consignor_tax_hp'];
            $settlementStatusPureChecker = SettlementStatusPureChecker::new();
            if ($settlementStatusPureChecker->isConsignorTaxHpExclusive((int)$row['consignor_tax_hp_type'])) {
                $consignorTaxHpIncExc = 'E';
            } elseif ($settlementStatusPureChecker->isConsignorTaxHpInclusive((int)$row['consignor_tax_hp_type'])) {
                $consignorTaxHpIncExc = 'I';
            }
            $consignorTaxComm = (bool)$row['consignor_tax_comm'];
            $consignorTaxServices = (bool)$row['consignor_tax_services'];
            $consignorPaymentInfo = $row['payment_info'];
        }

        $bpRangesLive = '';
        $bpAdditionalForInternetLive = '';
        $bpRangeCalculationLive = '';
        $bpRangesTimed = '';
        $bpRangeCalculationTimed = '';
        $bpRangesHybrid = '';
        $bpAdditionalForInternetHybrid = '';
        $bpRangeCalculationHybrid = '';
        if ($row['bidder_id']) {
            $bpRangesLive = $this->buildUserBpRanges($userId, $accountId, Constants\Auction::LIVE);

            $bpAdditionalForInternetLive = $row['additional_bp_internet_live'] !== null
                ? $this->getNumberFormatter()->formatPercent(Cast::toFloat($row['additional_bp_internet_live'])) : '';
            $bpRangeCalculationLive = $row['bp_range_calculation_live'];

            $bpRangesTimed = $this->buildUserBpRanges($userId, $accountId, Constants\Auction::TIMED);
            $bpRangeCalculationTimed = $row['bp_range_calculation_timed'];

            if ($isHybridAuctionAvailable) {
                $bpRangesHybrid = $this->buildUserBpRanges($userId, $accountId, Constants\Auction::HYBRID);
                $bpAdditionalForInternetHybrid = $row['additional_bp_internet_hybrid'] !== null
                    ? $this->getNumberFormatter()->formatPercent(Cast::toFloat($row['additional_bp_internet_hybrid'])) : '';
                $bpRangeCalculationHybrid = $row['bp_range_calculation_hybrid'];
            }
        }

        $bodyRow = array_merge(
            $bodyRow,
            [
                $csvIsAdmin,
                $csvIsManageAuctions,
                $csvIsManageInventory,
                $csvIsManageUsers,
                $csvIsManageInvoices,
                $csvIsManageConsignorSettlements,
                $csvIsManageSettings,
                $csvIsManageCcInfo,
                $csvIsManageSalesStaff,
                $csvIsManageReports,
            ]
        );
        if ($isMultipleTenant) {
            $bodyRow[] = $csvIsSuperadmin;
        }
        $bodyRow = array_merge(
            $bodyRow,
            [
                $csvIsSalesCommissionStepdown,
                $userSalesCommissionList,
                $csvIsManageAllAuctions,
                $csvIsDeleteAuction,
                $csvIsArchiveAuction,
                $csvIsResetAuction,
                $csvIsInformation,
                $csvIsPublish,
                $csvIsLots,
                $csvIsAvailableLots,
                $csvIsBidders,
                $csvIsRemainingUsers,
                $csvIsRunLiveAuction,
                $csvIsAssistantClerk,
                $csvIsProjector,
                $csvIsBidIncrements,
                $csvIsBidPremium,
                $csvIsPermissions,
                $csvIsCreateBidder,
                $csvIsUserPasswords,
                $csvIsUserPrivileges,
                $csvIsUsersBulkExp,
                $csvIsConsignorPrivileges,
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
        if ($isHybridAuctionAvailable) {
            $bodyRow = array_merge(
                $bodyRow,
                [
                    $bpRangesHybrid,
                    $bpAdditionalForInternetHybrid,
                    $bpRangeCalculationHybrid,
                ]
            );
        }
        $bodyRow = array_merge($bodyRow, $this->createConsignorCommissionFeeReportDataMaker()->makeData($row, $isConsignor));
        //write out the custom fields values
        foreach ($userCustomFields as $userCustomField) {
            $value = '';
            $userCustomData = $dataProvider->loadUserCustomDataOrCreate($userCustomField, $userId);
            $renderMethod = $this->getUserCustomFieldHelper()->makeCustomMethodName($userCustomField->Name, "Render"); // SAM-1570
            if (method_exists($this, $renderMethod)) {
                $value = $this->$renderMethod($userCustomField, $userCustomData, $encoding);
            } else {
                switch ($userCustomField->Type) {
                    case Constants\CustomField::TYPE_INTEGER:
                    case Constants\CustomField::TYPE_CHECKBOX:
                        if ($userCustomData->Numeric !== null) {
                            $value = $this->getNumberFormatter()->formatInteger($userCustomData->Numeric);
                        }
                        break;
                    case Constants\CustomField::TYPE_DECIMAL:
                        if ($userCustomData->Numeric !== null) {
                            $precision = (int)$userCustomField->Parameters;
                            $realValue = $userCustomData->calcDecimalValue($precision);
                            $value = $this->getNumberFormatter()->format($realValue, $precision);
                        }
                        break;
                    case Constants\CustomField::TYPE_TEXT:
                    case Constants\CustomField::TYPE_SELECT:
                    case Constants\CustomField::TYPE_FULLTEXT:
                    case Constants\CustomField::TYPE_PASSWORD:
                    case Constants\CustomField::TYPE_LABEL:
                        $value = CsvTransformer::new()->convertEncoding($userCustomData->Text, $encoding);
                        break;
                    case Constants\CustomField::TYPE_DATE:
                        if ($userCustomData->Numeric !== null) {
                            $value = (new DateTime())
                                ->setTimestamp($userCustomData->Numeric)
                                ->format(Constants\Date::ISO);
                        }
                        break;
                }
            }
            $bodyRow[] = $value;
            unset($userCustomData);
        }

        return $this->makeLine($bodyRow);
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $output = '';
        $dataLoader = $this->getDataLoader();
        log_debug('Start exporting user list csv' . composeSuffix(['total' => $dataLoader->count()]));
        $userCustomFields = $this->getUserCustomFieldLoader()->loadAllEditable([], true);
        $processed = 0;
        while ($rows = $dataLoader->loadNextChunk()) {
            $userIds = array_column($rows, 'user_id');
            $chunkDataProvider = $this->createChunkDataProvider()->construct($userIds, true);
            foreach ($rows as $row) {
                $bodyLine = $this->buildBodyLine($row, $userCustomFields, $chunkDataProvider);
                $output .= $this->processOutput($bodyLine);
            }
            $processed += count($rows);
            log_debug("Exported {$processed} rows for user list csv");
        }
        return $output;
    }

    /**
     * Output CSV header Titles
     * @return string
     */
    protected function outputTitles(): string
    {
        $accountId = $this->getSystemAccountId();
        $isHybridAuctionAvailable = $this->getAuctionHelper()->isHybridAuctionAvailable($accountId);
        $columnHeaders = $this->cfg()->get('csv->admin->user');
        $headerRow = [
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
        ];
        if ($this->cfg()->get('core->portal->enabled')) {
            $headerRow[] = $columnHeaders->{Constants\Csv\User::SUPERADMIN};
        }

        $headerRow = array_merge(
            $headerRow,
            [
                $columnHeaders->{Constants\Csv\User::SALES_COMMISSION_STEPDOWN},
                $columnHeaders->{Constants\Csv\User::SALES_CONSIGNMENT_COMMISSION},

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

                $columnHeaders->{Constants\Csv\User::USER_PASSWORDS},
                $columnHeaders->{Constants\Csv\User::USER_PRIVILEGES},
                $columnHeaders->{Constants\Csv\User::DELETE_USER},
                $columnHeaders->{Constants\Csv\User::USER_BULK_EXPORT},
                // consignor privileges
                $columnHeaders->{Constants\Csv\User::CONSIGNOR_PRIVILEGES},
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
                $columnHeaders->{Constants\Csv\User::BP_CALCULATION_TIMED} . '"',
            ]
        );
        if ($isHybridAuctionAvailable) {
            $headerRow = array_merge(
                $headerRow,
                [
                    $columnHeaders->{Constants\Csv\User::BP_RANGES_HYBRID},
                    $columnHeaders->{Constants\Csv\User::ADDITIONAL_BP_PERCENTAGE_FOR_INTERNET_BUYERS_HYBRID},
                    $columnHeaders->{Constants\Csv\User::BP_CALCULATION_HYBRID},
                ]
            );
        }
        $headerRow = array_merge($headerRow, $this->createConsignorCommissionFeeReportDataMaker()->makeHeaders($columnHeaders));
        //signup custom fields
        $userCustomFields = $this->getUserCustomFieldLoader()->loadAllEditable([], true);
        // $countFields = count($userCustomFields);
        foreach ($userCustomFields as $userCustomField) {
            $headerRow[] = $userCustomField->Name;
        }

        $headerLine = $this->makeLine($headerRow);

        return $this->processOutput($headerLine);
    }

    /**
     * Get the lot buyers premium for this
     * specific user
     * @param int $userId
     * @param int $accountId
     * @param string $auctionType
     * @return string pipe separated buyers premium values
     */
    protected function buildUserBpRanges(int $userId, int $accountId, string $auctionType): string
    {
        $bpRanges = $this->createBuyersPremiumRangeLoader()->loadBpRangeByUserId($userId, $accountId, $auctionType);
        if (count($bpRanges) > 0) {
            $pairCount = 0;
            $pairs = [];
            foreach ($bpRanges as $bpr) {
                $set = $bpr->Fixed . '-' . $bpr->Percent . '-' . ($bpr->Mode ?: '>');
                if ($pairCount === 0) {
                    $pairs[] = $set;
                } else {
                    $pairs[] = $bpr->Amount . ':' . $set;
                }
                $pairCount++;
            }
            return implode('|', $pairs);
        }

        return '';
    }
}
