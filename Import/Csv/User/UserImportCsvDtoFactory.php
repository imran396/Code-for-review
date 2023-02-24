<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\User;

use Sam\BuyersPremium\Csv\Parse\BuyersPremiumCsvParserCreateTrait;
use Sam\Commission\Csv\CommissionCsvParserCreateTrait;
use Sam\Consignor\Commission\Csv\ConsignorCommissionRangeCsvTransformerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Csv\CustomFieldCsvHelperCreateTrait;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerDtoFactory;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\Import\Csv\Base\ImportCsvColumnsHelperCreateTrait;
use Sam\Import\Csv\Read\CsvRow;
use Sam\User\Load\UserLoaderAwareTrait;
use UserCustField;

/**
 * Class UserImportCsvDtoFactory
 * @package Sam\User\Import
 */
class UserImportCsvDtoFactory extends CustomizableClass
{
    use BuyersPremiumCsvParserCreateTrait;
    use CommissionCsvParserCreateTrait;
    use ConsignorCommissionRangeCsvTransformerCreateTrait;
    use CustomFieldCsvHelperCreateTrait;
    use ImportCsvColumnsHelperCreateTrait;
    use UserLoaderAwareTrait;

    protected int $editorUserid;
    protected int $accountId;
    /**
     * @var UserCustField[]
     */
    protected array $userCustomFields;
    protected bool $clearEmptyFields;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $editorUserId
     * @param int $accountId
     * @param UserCustField[] $customFields
     * @param bool $clearEmptyFields
     * @return static
     */
    public function construct(int $editorUserId, int $accountId, array $customFields, bool $clearEmptyFields): static
    {
        $this->editorUserid = $editorUserId;
        $this->accountId = $accountId;
        $this->userCustomFields = $customFields;
        $this->clearEmptyFields = $clearEmptyFields;
        return $this;
    }

    /**
     * Construct the UserMakerInput and UserMakerConfig DTO and fill with data from CSV row
     *
     * @param CsvRow $row
     * @return array [UserMakerInputDto, UserMakerConfigDto]
     */
    public function create(CsvRow $row): array
    {
        $username = $row->getCell(Constants\Csv\User::USERNAME);
        $user = $this->getUserLoader()->loadByUsername($username);

        /**
         * @var UserMakerInputDto $userInputDto
         * @var UserMakerConfigDto $userConfigDto
         */
        [$userInputDto, $userConfigDto] = UserMakerDtoFactory::new()
            ->createDtos(Mode::CSV, $this->editorUserid, null, $this->accountId);

        // User

        $userInputDto->id = $user->Id ?? null;
        $userInputDto = $this->fillUser($userInputDto, $row);
        $userInputDto = $this->fillUserBilling($userInputDto, $row);
        $userInputDto = $this->fillUserShipping($userInputDto, $row);
        $userInputDto = $this->fillUserInfo($userInputDto, $row);
        $userInputDto = $this->fillPrivileges($userInputDto, $row);
        $userInputDto = $this->fillBidder($userInputDto, $row);
        $userInputDto = $this->fillBuyersPremium($userInputDto, $row);
        $userInputDto = $this->fillConsignor($userInputDto, $row);
        $userInputDto = $this->fillConsignorCommissionAndFee($userInputDto, $row);
        $userInputDto = $this->fillSalesCommission($userInputDto, $row);
        $userInputDto = $this->fillLocation($userInputDto, $row);
        $userInputDto = $userInputDto->setArray($this->createCustomFieldCsvHelper()->parseCustomFields($row, $this->userCustomFields));

        $userConfigDto->presentedCsvColumns = $this->createImportCsvColumnsHelper()->detectPresentedCsvColumns($row, $this->userCustomFields, $this->clearEmptyFields);
        $userConfigDto->clearValues = $this->clearEmptyFields;

        return [$userInputDto, $userConfigDto];
    }

    /**
     * @param UserMakerInputDto $userInputDto
     * @param CsvRow $row
     * @return UserMakerInputDto
     */
    protected function fillUser(UserMakerInputDto $userInputDto, CsvRow $row): UserMakerInputDto
    {
        $userInputDto->customerNo = $row->getCell(Constants\Csv\User::CUSTOMER_NO);
        $userInputDto->email = $row->getCell(Constants\Csv\User::EMAIL);
        $userInputDto->flag = $row->getCell(Constants\Csv\User::FLAG);
        $userInputDto->usePermanentBidderno = $row->getCell(Constants\Csv\User::MAKE_PERMANENT_BIDDER_NO);
        $userInputDto->userStatusId = Constants\User::US_ACTIVE;
        // Admin
        $userInputDto->adminSalesCommissionStepdown = $row->getCell(Constants\Csv\User::SALES_COMMISSION_STEPDOWN);
        return $userInputDto;
    }

    /**
     * @param UserMakerInputDto $userInputDto
     * @param CsvRow $row
     * @return UserMakerInputDto
     */
    protected function fillUserBilling(UserMakerInputDto $userInputDto, CsvRow $row): UserMakerInputDto
    {
        $userInputDto->billingAddress = $row->getCell(Constants\Csv\User::BILLING_ADDRESS);
        $userInputDto->billingAddress2 = $row->getCell(Constants\Csv\User::BILLING_ADDRESS_2);
        $userInputDto->billingAddress3 = $row->getCell(Constants\Csv\User::BILLING_ADDRESS_3);
        $userInputDto->billingBankAccountName = $row->getCell(Constants\Csv\User::BANK_ACCOUNT_NAME);
        $userInputDto->billingBankAccountNumber = $row->getCell(Constants\Csv\User::BANK_ACCOUNT_NO);
        $userInputDto->billingBankAccountType = $row->getCell(Constants\Csv\User::BANK_ACCOUNT_TYPE);
        $userInputDto->billingBankName = $row->getCell(Constants\Csv\User::BANK_NAME);
        $userInputDto->billingBankRoutingNumber = $row->getCell(Constants\Csv\User::BANK_ROUTING_NO);
        $userInputDto->billingCcExpDate = $row->getCell(Constants\Csv\User::CC_EXP_DATE);
        $userInputDto->billingCcNumber = $row->getCell(Constants\Csv\User::CC_NUMBER);
        $userInputDto->billingCcType = $row->getCell(Constants\Csv\User::CC_TYPE);
        $userInputDto->billingCity = $row->getCell(Constants\Csv\User::BILLING_CITY);
        $userInputDto->billingCompanyName = $row->getCell(Constants\Csv\User::BILLING_COMPANY_NAME);
        $userInputDto->billingContactType = $row->getCell(Constants\Csv\User::BILLING_CONTACT_TYPE);
        $userInputDto->billingCountry = $row->getCell(Constants\Csv\User::BILLING_COUNTRY);
        $userInputDto->billingFax = $row->getCell(Constants\Csv\User::BILLING_FAX);
        $userInputDto->billingFirstName = $row->getCell(Constants\Csv\User::BILLING_FIRST_NAME);
        $userInputDto->billingLastName = $row->getCell(Constants\Csv\User::BILLING_LAST_NAME);
        $userInputDto->billingPhone = $row->getCell(Constants\Csv\User::BILLING_PHONE);
        $userInputDto->billingState = $row->getCell(Constants\Csv\User::BILLING_STATE);
        $userInputDto->billingUseCard = $row->getCell(Constants\Csv\User::HAS_CREDIT_CARD);
        $userInputDto->billingZip = $row->getCell(Constants\Csv\User::BILLING_ZIP);
        return $userInputDto;
    }

    /**
     * @param UserMakerInputDto $userInputDto
     * @param CsvRow $row
     * @return UserMakerInputDto
     */
    protected function fillUserShipping(UserMakerInputDto $userInputDto, CsvRow $row): UserMakerInputDto
    {
        $userInputDto->shippingAddress = $row->getCell(Constants\Csv\User::SHIPPING_ADDRESS);
        $userInputDto->shippingAddress2 = $row->getCell(Constants\Csv\User::SHIPPING_ADDRESS_2);
        $userInputDto->shippingAddress3 = $row->getCell(Constants\Csv\User::SHIPPING_ADDRESS_3);
        $userInputDto->shippingCity = $row->getCell(Constants\Csv\User::SHIPPING_CITY);
        $userInputDto->shippingCompanyName = $row->getCell(Constants\Csv\User::SHIPPING_COMPANY_NAME);
        $userInputDto->shippingContactType = $row->getCell(Constants\Csv\User::SHIPPING_CONTACT_TYPE);
        $userInputDto->shippingCountry = $row->getCell(Constants\Csv\User::SHIPPING_COUNTRY);
        $userInputDto->shippingFax = $row->getCell(Constants\Csv\User::SHIPPING_FAX);
        $userInputDto->shippingFirstName = $row->getCell(Constants\Csv\User::SHIPPING_FIRST_NAME);
        $userInputDto->shippingLastName = $row->getCell(Constants\Csv\User::SHIPPING_LAST_NAME);
        $userInputDto->shippingPhone = $row->getCell(Constants\Csv\User::SHIPPING_PHONE);
        $userInputDto->shippingState = $row->getCell(Constants\Csv\User::SHIPPING_STATE);
        $userInputDto->shippingZip = $row->getCell(Constants\Csv\User::SHIPPING_ZIP);
        return $userInputDto;
    }

    /**
     * @param UserMakerInputDto $userInputDto
     * @param CsvRow $row
     * @return UserMakerInputDto
     */
    protected function fillUserInfo(UserMakerInputDto $userInputDto, CsvRow $row): UserMakerInputDto
    {
        $userInputDto->companyName = $row->getCell(Constants\Csv\User::COMPANY_NAME);
        $userInputDto->firstName = $row->getCell(Constants\Csv\User::FIRST_NAME);
        $userInputDto->identification = $row->getCell(Constants\Csv\User::IDENTIFICATION);
        $userInputDto->identificationType = $row->getCell(Constants\Csv\User::IDENTIFICATION_TYPE);
        $userInputDto->lastName = $row->getCell(Constants\Csv\User::LAST_NAME);
        $userInputDto->location = $row->getCell(Constants\Csv\User::LOCATION);
        $userInputDto->newsLetter = $row->getCell(Constants\Csv\User::NEWSLETTER);
        $userInputDto->note = $row->getCell(Constants\Csv\User::NOTES);
        $userInputDto->phone = $row->getCell(Constants\Csv\User::PHONE);
        $userInputDto->phoneType = $row->getCell(Constants\Csv\User::PHONE_TYPE);
        $userInputDto->pword = $row->getCell(Constants\Csv\User::PASSWORD);
        $userInputDto->referrer = $row->getCell(Constants\Csv\User::REFERRER);
        $userInputDto->referrerHost = $row->getCell(Constants\Csv\User::REFERRER_HOST);
        $userInputDto->salesTax = $row->getCell(Constants\Csv\User::BUYER_SALES_TAX);
        $userInputDto->sendTextAlerts = $row->getCell(Constants\Csv\User::TEXT_ALERT);
        $userInputDto->taxApplicationName = $row->getCell(Constants\Csv\User::APPLY_TAX_TO);
        $userInputDto->username = $row->getCell(Constants\Csv\User::USERNAME);
        return $userInputDto;
    }

    /**
     * @param UserMakerInputDto $userInputDto
     * @param CsvRow $row
     * @return UserMakerInputDto
     */
    protected function fillPrivileges(UserMakerInputDto $userInputDto, CsvRow $row): UserMakerInputDto
    {
        // Privileges: admin
        $userInputDto->admin = $row->getCell(Constants\Csv\User::IS_ADMIN);
        $userInputDto->archiveAuction = $row->getCell(Constants\Csv\User::ARCHIVE_AUCTION);
        $userInputDto->auctioneerScreen = $row->getCell(Constants\Csv\User::ASSISTANT_CLERK);
        $userInputDto->availableLots = $row->getCell(Constants\Csv\User::AVAILABLE_LOTS);
        $userInputDto->bidders = $row->getCell(Constants\Csv\User::BIDDERS);
        $userInputDto->bidIncrements = $row->getCell(Constants\Csv\User::BID_INCREMENTS);
        $userInputDto->buyersPremium = $row->getCell(Constants\Csv\User::BUYERS_PREMIUM);
        $userInputDto->deleteAuction = $row->getCell(Constants\Csv\User::DELETE_AUCTION);
        $userInputDto->information = $row->getCell(Constants\Csv\User::INFORMATION);
        $userInputDto->lots = $row->getCell(Constants\Csv\User::LOTS);
        $userInputDto->manageAllAuctions = $row->getCell(Constants\Csv\User::MANAGE_ALL_AUCTIONS);
        $userInputDto->manageAuctions = $row->getCell(Constants\Csv\User::MANAGE_AUCTIONS);
        $userInputDto->manageCcInfo = $row->getCell(Constants\Csv\User::MANAGE_CC_INFO);
        $userInputDto->manageConsignorSettlements = $row->getCell(Constants\Csv\User::MANAGE_SETTLEMENTS);
        $userInputDto->manageInventory = $row->getCell(Constants\Csv\User::MANAGE_INVENTORY);
        $userInputDto->manageInvoices = $row->getCell(Constants\Csv\User::MANAGE_INVOICES);
        $userInputDto->manageSettings = $row->getCell(Constants\Csv\User::MANAGE_SETTINGS);
        $userInputDto->manageUsers = $row->getCell(Constants\Csv\User::MANAGE_USERS);
        $userInputDto->permissions = $row->getCell(Constants\Csv\User::PERMISSIONS);
        $userInputDto->createBidder = $row->getCell(Constants\Csv\User::CREATE_BIDDER);
        $userInputDto->projector = $row->getCell(Constants\Csv\User::PROJECTOR);
        $userInputDto->publish = $row->getCell(Constants\Csv\User::PUBLISH);
        $userInputDto->remainingUsers = $row->getCell(Constants\Csv\User::REMAINING_USERS);
        $userInputDto->reports = $row->getCell(Constants\Csv\User::REPORTS);
        $userInputDto->resetAuction = $row->getCell(Constants\Csv\User::RESET_AUCTION);
        $userInputDto->runLiveAuction = $row->getCell(Constants\Csv\User::RUN_LIVE_AUCTION);
        $userInputDto->salesStaff = $row->getCell(Constants\Csv\User::SALES_STAFF);
        $userInputDto->superadmin = $row->getCell(Constants\Csv\User::SUPERADMIN);
        $userInputDto->bulkUserExport = $row->getCell(Constants\Csv\User::USER_BULK_EXPORT);
        $userInputDto->userPasswords = $row->getCell(Constants\Csv\User::USER_PASSWORDS);
        $userInputDto->userPrivileges = $row->getCell(Constants\Csv\User::USER_PRIVILEGES);
        $userInputDto->deleteUser = $row->getCell(Constants\Csv\User::DELETE_USER);
        // Privileges: bidder
        $userInputDto->bidder = $row->getCell(Constants\Csv\User::IS_BIDDER);
        $userInputDto->bidderAgent = $row->getCell(Constants\Csv\User::AGENT);
        $userInputDto->bidderHouse = $row->getCell(Constants\Csv\User::HOUSE_BIDDER);
        $userInputDto->bidderPreferred = $row->getCell(Constants\Csv\User::IS_PREFERRED_BIDDER);
        // Privileges: consignor
        $userInputDto->consignor = $row->getCell(Constants\Csv\User::CONSIGNOR_PRIVILEGES);
        $userInputDto->consignorSalesTax = $row->getCell(Constants\Csv\User::CONSIGNOR_BUYER_TAX_PERCENTAGE);
        $userInputDto->consignorSalesTaxHP = $row->getCell(Constants\Csv\User::CONSIGNOR_BUYER_TAX_HP);
        $userInputDto->consignorSalesTaxBP = $row->getCell(Constants\Csv\User::CONSIGNOR_BUYER_TAX_BP);
        $userInputDto->consignorSalesTaxServices = $row->getCell(Constants\Csv\User::CONSIGNOR_BUYER_TAX_SERVICES);
        $userInputDto->consignorTax = $row->getCell(Constants\Csv\User::CONSIGNOR_TAX_PERCENTAGE);
        $userInputDto->consignorTaxHP = $row->getCell(Constants\Csv\User::CONSIGNOR_TAX_HP);
        $userInputDto->exclusive = (string)($row->getCell(Constants\Csv\User::CONSIGNOR_TAX_HP_INCLUSIVE_OR_EXCLUSIVE) === Constants\Csv\User::TAX_HP_EXCLUSIVE);
        $userInputDto->consignorTaxCommission = $row->getCell(Constants\Csv\User::CONSIGNOR_TAX_COMMISSION);
        $userInputDto->consignorTaxServices = $row->getCell(Constants\Csv\User::CONSIGNOR_TAX_SERVICES);
        return $userInputDto;
    }

    /**
     * @param UserMakerInputDto $userInputDto
     * @param CsvRow $row
     * @return UserMakerInputDto
     */
    protected function fillBidder(UserMakerInputDto $userInputDto, CsvRow $row): UserMakerInputDto
    {
        $userInputDto->additionalBpInternetHybrid = $row->getCell(Constants\Csv\User::ADDITIONAL_BP_PERCENTAGE_FOR_INTERNET_BUYERS_HYBRID);
        $userInputDto->additionalBpInternetLive = $row->getCell(Constants\Csv\User::ADDITIONAL_BP_PERCENTAGE_FOR_INTERNET_BUYERS_LIVE);
        $userInputDto->bpRangeCalculationHybrid = $row->getCell(Constants\Csv\User::BP_CALCULATION_HYBRID);
        $userInputDto->bpRangeCalculationLive = $row->getCell(Constants\Csv\User::BP_CALCULATION_LIVE);
        $userInputDto->bpRangeCalculationTimed = $row->getCell(Constants\Csv\User::BP_CALCULATION_TIMED);
        return $userInputDto;
    }

    /**
     * @param UserMakerInputDto $userInputDto
     * @param CsvRow $row
     * @return UserMakerInputDto
     */
    protected function fillConsignor(UserMakerInputDto $userInputDto, CsvRow $row): UserMakerInputDto
    {
        $userInputDto->consignorPaymentInfo = $row->getCell(Constants\Csv\User::PAYMENT_INFO);
        return $userInputDto;
    }

    /**
     * @param UserMakerInputDto $userInputDto
     * @param CsvRow $row
     * @return UserMakerInputDto
     */
    protected function fillBuyersPremium(UserMakerInputDto $userInputDto, CsvRow $row): UserMakerInputDto
    {
        $userInputDto->buyersPremiumHybridString = $row->getCell(Constants\Csv\User::BP_RANGES_HYBRID);
        $userInputDto->buyersPremiumLiveString = $row->getCell(Constants\Csv\User::BP_RANGES_LIVE);
        $userInputDto->buyersPremiumTimedString = $row->getCell(Constants\Csv\User::BP_RANGES_TIMED);
        return $userInputDto;
    }

    /**
     * @param UserMakerInputDto $userInputDto
     * @param CsvRow $row
     * @return UserMakerInputDto
     */
    protected function fillConsignorCommissionAndFee(UserMakerInputDto $userInputDto, CsvRow $row): UserMakerInputDto
    {
        $rangeCsvTransformer = $this->createConsignorCommissionRangeCsvTransformer();
        $userInputDto->consignorCommissionCalculationMethod = $row->getCell(Constants\Csv\User::CONSIGNOR_COMMISSION_CALCULATION_METHOD);
        $userInputDto->consignorCommissionId = $row->getCell(Constants\Csv\User::CONSIGNOR_COMMISSION_ID);
        $userInputDto->consignorCommissionRanges = $rangeCsvTransformer->transformCsvStringToDtos(
            $row->getCell(Constants\Csv\User::CONSIGNOR_COMMISSION_RANGES)
        );
        $userInputDto->consignorSoldFeeCalculationMethod = $row->getCell(Constants\Csv\User::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD);
        $userInputDto->consignorSoldFeeId = $row->getCell(Constants\Csv\User::CONSIGNOR_SOLD_FEE_ID);
        $userInputDto->consignorSoldFeeRanges = $rangeCsvTransformer->transformCsvStringToDtos(
            $row->getCell(Constants\Csv\User::CONSIGNOR_SOLD_FEE_RANGES)
        );
        $userInputDto->consignorSoldFeeReference = $row->getCell(Constants\Csv\User::CONSIGNOR_SOLD_FEE_REFERENCE);
        $userInputDto->consignorUnsoldFeeCalculationMethod = $row->getCell(Constants\Csv\User::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD);
        $userInputDto->consignorUnsoldFeeId = $row->getCell(Constants\Csv\User::CONSIGNOR_UNSOLD_FEE_ID);
        $userInputDto->consignorUnsoldFeeRanges = $rangeCsvTransformer->transformCsvStringToDtos(
            $row->getCell(Constants\Csv\User::CONSIGNOR_UNSOLD_FEE_RANGES)
        );
        $userInputDto->consignorUnsoldFeeReference = $row->getCell(Constants\Csv\User::CONSIGNOR_UNSOLD_FEE_REFERENCE);
        return $userInputDto;
    }

    /**
     * @param UserMakerInputDto $userInputDto
     * @param CsvRow $row
     * @return UserMakerInputDto
     */
    protected function fillSalesCommission(UserMakerInputDto $userInputDto, CsvRow $row): UserMakerInputDto
    {
        $commissionCsvParser = $this->createCommissionCsvParser()->construct();
        $userInputDto->salesCommissions = $commissionCsvParser->parse($row->getCell(Constants\Csv\User::SALES_CONSIGNMENT_COMMISSION));
        return $userInputDto;
    }

    protected function fillLocation(UserMakerInputDto $userInputDto, CsvRow $row): UserMakerInputDto
    {
        $specificLocation = [
            'Address' => $row->getCell(Constants\Csv\User::LOCATION_ADDRESS),
            'City' => $row->getCell(Constants\Csv\User::LOCATION_CITY),
            'Country' => $row->getCell(Constants\Csv\User::LOCATION_COUNTRY),
            'County' => $row->getCell(Constants\Csv\User::LOCATION_COUNTY),
            'Logo' => $row->getCell(Constants\Csv\User::LOCATION_LOGO),
            'State' => $row->getCell(Constants\Csv\User::LOCATION_STATE),
            'Zip' => $row->getCell(Constants\Csv\User::LOCATION_ZIP),
        ];
        if (array_filter($specificLocation)) {
            $userInputDto->specificLocation = (object)$specificLocation;
        }
        return $userInputDto;
    }
}
