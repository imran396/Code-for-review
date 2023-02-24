<?php
/**
 * Class for validating of User input data
 *
 * SAM-8841: User entity-maker module structural adjustments for v3-5
 * SAM-4989: User Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 22, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate;

use Payment_Eway;
use Sam\Account\Validate\AccountExistenceCheckerAwareTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Billing\CreditCard\Validate\CreditCardExistenceCheckerAwareTrait;
use Sam\BuyersPremium\Csv\Parse\BuyersPremiumCsvParserCreateTrait;
use Sam\BuyersPremium\Validate\BuyersPremiumExistenceCheckerCreateTrait;
use Sam\Consignor\Commission\Convert\ConsignorCommissionFeeRangeDtoConverterCreateTrait;
use Sam\Consignor\Commission\Edit\Validate\ConsignorCommissionFeeRangesValidatorCreateTrait;
use Sam\Consignor\Commission\Edit\Validate\ConsignorCommissionFeeValidatorCreateTrait;
use Sam\Consignor\Commission\Edit\Validate\RangeValidationResultStatus;
use Sam\Consignor\Commission\Validate\ConsignorCommissionFeeExistenceCheckerCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Platform\Constant\Base\ConstantNameResolver;
use Sam\Core\RangeTable\BuyersPremium\Validate\BuyersPremiumRangesValidationResult;
use Sam\Core\RangeTable\SalesCommission\Validate\SalesCommissionRangesValidationResult;
use Sam\Core\RangeTable\SalesCommission\Validate\SalesCommissionRangesValidatorCreateTrait;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\EntityMaker\Base\Common\ValueResolver;
use Sam\EntityMaker\Base\Validate\BaseMakerValidator;
use Sam\EntityMaker\Base\Validate\Internal\BuyersPremium\BuyersPremiumValidationInput;
use Sam\EntityMaker\Base\Validate\Internal\BuyersPremium\BuyersPremiumValidationIntegratorCreateTrait;
use Sam\EntityMaker\User\Common\Access\UserMakerAccessCheckerAwareTrait;
use Sam\EntityMaker\User\Common\DirectAccount\UserDirectAccountDetectionInput;
use Sam\EntityMaker\User\Common\DirectAccount\UserDirectAccountDetector;
use Sam\EntityMaker\User\Common\DirectAccount\UserDirectAccountDetectorAwareTrait;
use Sam\EntityMaker\User\Common\UserMakerCustomFieldManager;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerDtoHelperAwareTrait;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\EntityMaker\User\Validate\Constants\ResultCode;
use Sam\EntityMaker\User\Validate\Internal\DirectAccount\DirectAccountChangeValidationInput;
use Sam\EntityMaker\User\Validate\Internal\DirectAccount\DirectAccountChangeValidatorCreateTrait;
use Sam\EntityMaker\User\Validate\Internal\Privilege\PrivilegeValidationIntegratorCreateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lang\ViewLanguage\Validate\ViewLanguageExistenceCheckerAwareTrait;
use Sam\PaymentGateway\PaymentGatewayFactory;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settings\User\UserSettingCheckerCreateTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Password\Strength\PasswordStrengthValidator;

/**
 * The following methods are handled by \Sam\EntityMaker\Base\Validator::__call() method:
 * GetErrorMessage Methods
 * @method getAdditionalBpInternetHybridErrorMessage()
 * @method getAdditionalBpInternetLiveErrorMessage()
 * @method getAdminErrorMessage()
 * @method getBillingAddressErrorMessage()
 * @method getBillingBankAccountNameErrorMessage()
 * @method getBillingBankAccountNumberErrorMessage()
 * @method getBillingBankAccountTypeErrorMessage()
 * @method getBillingBankNameErrorMessage()
 * @method getBillingBankRoutingNumberErrorMessage()
 * @method getBillingCcCodeErrorMessage()
 * @method getBillingCcExpDateErrorMessage()
 * @method getBillingCcNumberErrorMessage()
 * @method getBillingCcTypeErrorMessage()
 * @method getBillingCityErrorMessage()
 * @method getBillingContactTypeErrorMessage()
 * @method getBillingCountryErrorMessage()
 * @method getBillingEmailErrorMessage()
 * @method getBillingFaxErrorMessage()
 * @method getBillingFirstNameErrorMessage()
 * @method getBillingLastNameErrorMessage()
 * @method getBillingPhoneErrorMessage()
 * @method getBillingStateErrorMessage()
 * @method getBillingZipErrorMessage()
 * @method getBuyersPremiumsHybridErrorMessage()
 * @method getBuyersPremiumsLiveErrorMessage()
 * @method getBuyersPremiumsTimedErrorMessage()
 * @method getCompanyNameErrorMessage()
 * @method getConsignorSalesTaxErrorMessage()
 * @method getConsignorTaxErrorMessage()
 * @method getCustomerNoErrorMessage()
 * @method getEmailErrorMessage()
 * @method getEmailConfirmationErrorMessage()
 * @method getFirstNameErrorMessage()
 * @method getIdentificationErrorMessage()
 * @method getIdentificationTypeErrorMessage()
 * @method getLastNameErrorMessage()
 * @method getMaxOutstandingErrorMessage()
 * @method getPasswordErrorMessage()
 * @method getPasswordConfirmErrorMessage()
 * @method getPayTraceCustIdErrorMessage()
 * @method getPhoneErrorMessage()
 * @method getPhoneTypeErrorMessage()
 * @method getReferrerErrorMessage()
 * @method getSalesCommissionsErrorMessage()
 * @method getSalesTaxErrorMessage()
 * @method getShippingAddressErrorMessage()
 * @method getShippingCityErrorMessage()
 * @method getShippingContactTypeErrorMessage()
 * @method getShippingCountryErrorMessage()
 * @method getShippingFaxErrorMessage()
 * @method getShippingFirstNameErrorMessage()
 * @method getShippingLastNameErrorMessage()
 * @method getShippingPhoneErrorMessage()
 * @method getShippingStateErrorMessage()
 * @method getShippingZipErrorMessage()
 * @method getUsernameErrorMessage()
 * @method getTimezoneErrorMessage()
 * @method getConsignorCommissionIdMessage()
 * @method getConsignorCommissionCalculationMethodMessage()
 * @method getConsignorCommissionRangeMessage()
 * @method getConsignorSoldFeeIdMessage()
 * @method getConsignorSoldFeeCalculationMethodMessage()
 * @method getConsignorSoldFeeRangeMessage()
 * @method getConsignorUnsoldFeeIdMessage()
 * @method getConsignorUnsoldFeeCalculationMethodMessage()
 * @method getConsignorUnsoldFeeRangeMessage()
 * @method getLocationErrorMessage()
 * HasError Methods
 * @method hasAdditionalBpInternetHybridError()
 * @method hasAdditionalBpInternetLiveError()
 * @method hasAdminError()
 * @method hasBillingAddressError()
 * @method hasBillingBankAccountNameError()
 * @method hasBillingBankAccountNumberError()
 * @method hasBillingBankAccountTypeError()
 * @method hasBillingBankNameError()
 * @method hasBillingBankRoutingNumberError()
 * @method hasBillingCcCodeError()
 * @method hasBillingCcExpDateError()
 * @method hasBillingCcNumberError()
 * @method hasBillingCcTypeError()
 * @method hasBillingCityError()
 * @method hasBillingContactTypeError()
 * @method hasBillingCountryError()
 * @method hasBillingEmailError()
 * @method hasBillingFaxError()
 * @method hasBillingFirstNameError()
 * @method hasBillingLastNameError()
 * @method hasBillingPhoneError()
 * @method hasBillingStateError()
 * @method hasBillingZipError()
 * @method hasBuyersPremiumsHybridError()
 * @method hasBuyersPremiumsLiveError()
 * @method hasBuyersPremiumsTimedError()
 * @method hasCompanyNameError()
 * @method hasConsignorSalesTaxError()
 * @method hasConsignorTaxError()
 * @method hasCustomerNoError()
 * @method hasEmailError()
 * @method hasEmailConfirmationError()
 * @method hasFirstNameError()
 * @method hasIdentificationError()
 * @method hasIdentificationTypeError()
 * @method hasLastNameError()
 * @method hasMaxOutstandingError()
 * @method hasPasswordError()
 * @method hasPasswordConfirmError()
 * @method hasPayTraceCustIdError()
 * @method hasPhoneError()
 * @method hasPhoneTypeError()
 * @method hasReferrerError()
 * @method hasSalesCommissionsError()
 * @method hasSalesTaxError()
 * @method hasShippingAddressError()
 * @method hasShippingCityError()
 * @method hasShippingContactTypeError()
 * @method hasShippingCountryError()
 * @method hasShippingFaxError()
 * @method hasShippingFirstNameError()
 * @method hasShippingLastNameError()
 * @method hasShippingPhoneError()
 * @method hasShippingStateError()
 * @method hasShippingZipError()
 * @method hasUsernameError()
 * @method hasTimezoneError()
 * @method hasConsignorCommissionIdError()
 * @method hasConsignorCommissionCalculationMethodError()
 * @method hasConsignorCommissionRangeError()
 * @method hasConsignorSoldFeeIdError()
 * @method hasConsignorSoldFeeCalculationMethodError()
 * @method hasConsignorSoldFeeRangeError()
 * @method hasConsignorSoldFeeReferenceError()
 * @method hasConsignorUnsoldFeeIdError()
 * @method hasConsignorUnsoldFeeCalculationMethodError()
 * @method hasConsignorUnsoldFeeRangeError()
 * @method hasConsignorUnsoldFeeReferenceError()
 * @method hasLocationError()
 *
 * @method UserMakerInputDto getInputDto()
 * @method UserMakerConfigDto getConfigDto()
 * @property UserMakerCustomFieldManager $customFieldManager
 */
class UserMakerValidator extends BaseMakerValidator
{
    use AccountExistenceCheckerAwareTrait;
    use AuctionBidderCheckerAwareTrait;
    use BuyersPremiumCsvParserCreateTrait;
    use BuyersPremiumExistenceCheckerCreateTrait;
    use BuyersPremiumValidationIntegratorCreateTrait;
    use ConsignorCommissionFeeExistenceCheckerCreateTrait;
    use ConsignorCommissionFeeRangeDtoConverterCreateTrait;
    use ConsignorCommissionFeeRangesValidatorCreateTrait;
    use ConsignorCommissionFeeValidatorCreateTrait;
    use CreditCardExistenceCheckerAwareTrait;
    use DirectAccountChangeValidatorCreateTrait;
    use OptionalsTrait;
    use PasswordStrengthValidator;
    use PrivilegeValidationIntegratorCreateTrait;
    use SalesCommissionRangesValidatorCreateTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UserAwareTrait;
    use UserDirectAccountDetectorAwareTrait;
    use UserLoaderAwareTrait;
    use UserMakerAccessCheckerAwareTrait;
    use UserMakerDtoHelperAwareTrait;
    use UserSettingCheckerCreateTrait;
    use ViewLanguageExistenceCheckerAwareTrait;

    public const OP_TARGET_USER_DIRECT_ACCOUNT_ID = 'targetUserDirectAccountId'; // int

    protected bool $needValidateCustomFields = true;

    /** @var string[] */
    protected array $errorMessages = [
        ResultCode::ACCOUNT_ID_NOT_FOUND => 'Not found',
        ResultCode::ACCOUNT_NO_RIGHTS => 'Super admin privileges required',
        ResultCode::ADDITIONAL_BP_INTERNET_HYBRID_INVALID => 'Invalid',
        ResultCode::ADDITIONAL_BP_INTERNET_LIVE_INVALID => 'Invalid',
        ResultCode::ADMIN_NO_SUB_PRIVILEGES_SELECTED => 'No sub privileges selected',
        ResultCode::AGENT_NOT_FOUND => 'Not found',
        ResultCode::BILLING_ADDRESS_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_ADDRESS_REQUIRED => 'Required',
        ResultCode::BILLING_ADDRESS2_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_ADDRESS3_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_BANK_ACCOUNT_NAME_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_BANK_ACCOUNT_NAME_REQUIRED => 'Required',
        ResultCode::BILLING_BANK_ACCOUNT_NUMBER_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_BANK_ACCOUNT_NUMBER_REQUIRED => 'Required',
        ResultCode::BILLING_BANK_ACCOUNT_TYPE_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_BANK_ACCOUNT_TYPE_REQUIRED => 'Required',
        ResultCode::BILLING_BANK_ACCOUNT_TYPE_UNKNOWN => 'Unknown',
        ResultCode::BILLING_BANK_NAME_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_BANK_NAME_REQUIRED => 'Required',
        ResultCode::BILLING_BANK_ROUTING_NUMBER_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_BANK_ROUTING_NUMBER_REQUIRED => 'Required',
        ResultCode::BILLING_CC_CODE_REQUIRED => 'Required',
        ResultCode::BILLING_CC_EXP_DATE_INVALID => 'Invalid',
        ResultCode::BILLING_CC_EXP_DATE_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_CC_EXP_DATE_REQUIRED => 'Required',
        ResultCode::BILLING_CC_NUMBER_HASH_EXIST => 'Warning: This credit card number is found for users: ',
        ResultCode::BILLING_CC_NUMBER_HASH_INVALID => 'Invalid',
        ResultCode::BILLING_CC_NUMBER_INVALID => 'Invalid',
        ResultCode::BILLING_CC_NUMBER_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_CC_NUMBER_REQUIRED => 'Required',
        ResultCode::BILLING_CC_TYPE_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_CC_TYPE_NOT_FOUND => 'Not found',
        ResultCode::BILLING_CC_TYPE_REQUIRED => 'Required',
        ResultCode::BILLING_COMPANY_NAME_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_CONTACT_TYPE_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_CONTACT_TYPE_REQUIRED => 'Required',
        ResultCode::BILLING_CONTACT_TYPE_UNKNOWN => 'Unknown',
        ResultCode::BILLING_COUNTRY_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_COUNTRY_REQUIRED => 'Required',
        ResultCode::BILLING_COUNTRY_UNKNOWN => 'Unknown',
        ResultCode::BILLING_EMAIL_INVALID => 'Invalid',
        ResultCode::BILLING_FAX_INVALID => 'Invalid',
        ResultCode::BILLING_FIRST_NAME_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_FIRST_NAME_REQUIRED => 'Required',
        ResultCode::BILLING_LAST_NAME_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_LAST_NAME_REQUIRED => 'Required',
        ResultCode::BILLING_PHONE_INVALID => 'Invalid',
        ResultCode::BILLING_PHONE_REQUIRED => 'Required',
        ResultCode::BILLING_CITY_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_CITY_REQUIRED => 'Required',
        ResultCode::BILLING_STATE_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_STATE_REQUIRED => 'Required',
        ResultCode::BILLING_STATE_NAME_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::BILLING_STATE_UNKNOWN => 'Unknown',
        ResultCode::BILLING_ZIP_REQUIRED => 'Required',
        ResultCode::BP_RANGE_CALCULATION_HYBRID_UNKNOWN => 'Unknown',
        ResultCode::BP_RANGE_CALCULATION_LIVE_UNKNOWN => 'Unknown',
        ResultCode::BP_RANGE_CALCULATION_TIMED_UNKNOWN => 'Unknown',
        ResultCode::BP_RULE_NOT_FOUND => 'Not found',
        ResultCode::BUYERS_PREMIUMS_HYBRID_VALIDATION_FAILED => 'Validation failed',
        ResultCode::BUYERS_PREMIUMS_LIVE_VALIDATION_FAILED => 'Validation failed',
        ResultCode::BUYERS_PREMIUMS_TIMED_VALIDATION_FAILED => 'Validation failed',
        ResultCode::BP_RULE_WITH_INDIVIDUAL_BP_CAN_NOT_BE_ASSIGNED_TOGETHER => 'Named rule can\'t be assigned together with individual ranges or additional BP internet',
        ResultCode::COLLATERAL_ACCOUNT_ID_NOT_FOUND => 'Not found',
        ResultCode::COMPANY_NAME_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::COMPANY_NAME_REQUIRED => 'Required',
        ResultCode::CONSIGNOR_PAYMENT_INFO_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::CONSIGNOR_SALES_TAX_INVALID => 'Invalid',
        ResultCode::CONSIGNOR_TAX_INVALID => 'Invalid',
        ResultCode::CUSTOMER_NO_AS_BIDDER_NO_EXIST => 'as bidder no already exists',
        ResultCode::CUSTOMER_NO_INVALID => 'Should be integer',
        ResultCode::CUSTOMER_NO_EXIST => 'Already exists',
        ResultCode::CUSTOMER_NO_HIGHER_MAX_AVAILABLE_VALUE => 'Higher than the max available value',
        ResultCode::CUSTOMER_NO_REQUIRED => 'Required',
        ResultCode::EMAIL_CONFIRM_NOT_MATCH => 'Not match',
        ResultCode::EMAIL_CONFIRM_REQUIRED => 'Required',
        ResultCode::EMAIL_EXIST => 'Already exists',
        ResultCode::EMAIL_INVALID => 'Invalid',
        ResultCode::EMAIL_REQUIRED => 'Required',
        ResultCode::FIRST_NAME_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::FIRST_NAME_REQUIRED => 'Required',
        ResultCode::FLAG_UNKNOWN => 'Unknown',
        ResultCode::IDENTIFICATION_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::IDENTIFICATION_REQUIRED => 'Required',
        ResultCode::IDENTIFICATION_TYPE_REQUIRED => 'Required',
        ResultCode::IDENTIFICATION_TYPE_UNKNOWN => 'Unknown',
        ResultCode::LAST_NAME_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::LAST_NAME_REQUIRED => 'Required',
        ResultCode::LOCATION_ID_NOT_FOUND => 'Not found',
        ResultCode::LOCATION_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::LOCATION_NOT_FOUND => 'Not found',
        ResultCode::MAX_OUTSTANDING_INVALID => 'Invalid',
        ResultCode::NMI_VAULT_ID_EXIST => 'Already exists',
        ResultCode::NOTE_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::PASSWORD_CONFIRM_REQUIRED => 'Required',
        ResultCode::PASSWORD_INVALID => 'Invalid',
        ResultCode::PASSWORD_NOT_MATCH => 'Not match',
        ResultCode::PASSWORD_REQUIRED => 'Required',
        ResultCode::PAY_TRACE_CUST_ID_EXIST => 'Already exists',
        ResultCode::PHONE_INVALID => 'Invalid',
        ResultCode::PHONE_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::PHONE_REQUIRED => 'Required',
        ResultCode::PHONE_TYPE_REQUIRED => 'Required',
        ResultCode::PHONE_TYPE_UNKNOWN => 'Unknown',
        ResultCode::REFERRER_INVALID => 'Invalid',
        ResultCode::REG_AUTH_DATE_INVALID => 'Invalid',
        ResultCode::SALES_COMMISSIONS_VALIDATION_FAILED => 'Validation failed',
        ResultCode::SALES_TAX_INVALID => 'Invalid',
        ResultCode::SHIPPING_ADDRESS_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::SHIPPING_ADDRESS_REQUIRED => 'Required',
        ResultCode::SHIPPING_ADDRESS2_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::SHIPPING_ADDRESS3_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::SHIPPING_CITY_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::SHIPPING_CITY_REQUIRED => 'Required',
        ResultCode::SHIPPING_COMPANY_NAME_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::SHIPPING_CONTACT_TYPE_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::SHIPPING_CONTACT_TYPE_REQUIRED => 'Required',
        ResultCode::SHIPPING_CONTACT_TYPE_UNKNOWN => 'Unknown',
        ResultCode::SHIPPING_COUNTRY_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::SHIPPING_COUNTRY_REQUIRED => 'Required',
        ResultCode::SHIPPING_COUNTRY_UNKNOWN => 'Unknown',
        ResultCode::SHIPPING_FAX_INVALID => 'Invalid',
        ResultCode::SHIPPING_FIRST_NAME_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::SHIPPING_FIRST_NAME_REQUIRED => 'Required',
        ResultCode::SHIPPING_LAST_NAME_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::SHIPPING_LAST_NAME_REQUIRED => 'Required',
        ResultCode::SHIPPING_PHONE_INVALID => 'Invalid',
        ResultCode::SHIPPING_PHONE_REQUIRED => 'Required',
        ResultCode::SHIPPING_STATE_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::SHIPPING_STATE_REQUIRED => 'Required',
        ResultCode::SHIPPING_STATE_NAME_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::SHIPPING_STATE_UNKNOWN => 'Unknown',
        ResultCode::SHIPPING_ZIP_REQUIRED => 'Required',
        ResultCode::SPECIFIC_LOCATION_INVALID => 'Invalid',
        ResultCode::SPECIFIC_LOCATION_REDUNDANT => 'Both specific and common event locations are provided',
        ResultCode::SYNC_KEY_EXIST => 'Already exists',
        ResultCode::TAX_APPLICATION_INVALID => 'Invalid',
        ResultCode::TAX_APPLICATION_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::USERNAME_EXIST => 'Already exists',
        ResultCode::USERNAME_INVALID => 'Invalid',
        ResultCode::USERNAME_INVALID_ENCODING => 'Invalid encoding',
        ResultCode::USERNAME_REQUIRED => 'Required',
        ResultCode::USER_STATUS_ID_UNKNOWN => 'Unknown',
        ResultCode::USER_STATUS_UNKNOWN => 'Unknown',
        ResultCode::VIEW_LANGUAGE_NOT_FOUND => 'Not found',
        ResultCode::TIMEZONE_INVALID => 'Invalid',
        ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID => 'Invalid ranges',
        ResultCode::CONSIGNOR_COMMISSION_ID_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID => 'Invalid ranges',
        ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID => 'Invalid ranges',
        ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID => 'Unknown',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID => 'Unknown',
        ResultCode::ADMIN_AND_BIDDER_CONSIGNOR_PRIVILEGE_TOGETHER_NOT_ALLOWED => 'Admin privileges can\'t be set together with bidder or consignor privileges',
        ResultCode::ADMIN_PRIVILEGES_IS_NOT_EDITABLE => 'Admin privileges is not editable',
        ResultCode::BIDDER_AND_CONSIGNOR_PRIVILEGES_IS_NOT_EDITABLE => 'Bidder and consignor privileges is not editable',
        ResultCode::MANAGE_AUCTIONS_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::MANAGE_INVENTORY_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::MANAGE_USERS_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::MANAGE_INVOICES_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::MANAGE_SETTLEMENTS_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::MANAGE_SETTINGS_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::MANAGE_CC_INFO_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SALES_STAFF_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::MANAGE_REPORTS_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUPERADMIN_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_MANAGE_ALL_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_DELETE_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_ARCHIVE_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_RESET_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_INFORMATION_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_PUBLISH_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_LOT_LIST_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_AVAILABLE_LOT_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_BIDDER_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_REMAINING_USER_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_RUN_LIVE_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_AUCTIONEER_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_PROJECTOR_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_BID_INCREMENT_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_BUYER_PREMIUM_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_PERMISSION_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_AUCTION_CREATE_BIDDER_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_USER_BULK_EXPORT_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_USER_DELETE_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_USER_PASSWORD_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::SUB_USER_PRIVILEGE_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
        ResultCode::CROSS_DOMAIN_ADMIN_AT_PORTAL_ACCOUNT_NOT_APPLICABLE => 'Cross domain access rights are not applicable for the portal admin',
    ];

    /** @var string[] */
    protected array $tagNames = [
        ResultCode::ACCOUNT_ID_NOT_FOUND => 'AccountId',
        ResultCode::ACCOUNT_NO_RIGHTS => 'AccountId',
        ResultCode::ADDITIONAL_BP_INTERNET_HYBRID_INVALID => 'AdditionalBpInternetHybrid',
        ResultCode::ADDITIONAL_BP_INTERNET_LIVE_INVALID => 'AdditionalBpInternetLive',
        ResultCode::ADMIN_NO_SUB_PRIVILEGES_SELECTED => 'Admin',
        ResultCode::AGENT_NOT_FOUND => 'Agent',
        ResultCode::BILLING_ADDRESS_INVALID_ENCODING => 'BillingAddress',
        ResultCode::BILLING_ADDRESS_REQUIRED => 'BillingAddress',
        ResultCode::BILLING_ADDRESS2_INVALID_ENCODING => 'BillingAddress2',
        ResultCode::BILLING_ADDRESS3_INVALID_ENCODING => 'BillingAddress3',
        ResultCode::BILLING_BANK_ACCOUNT_NAME_INVALID_ENCODING => 'BillingBankAccountName',
        ResultCode::BILLING_BANK_ACCOUNT_NAME_REQUIRED => 'BillingBankAccountName',
        ResultCode::BILLING_BANK_ACCOUNT_NUMBER_INVALID_ENCODING => 'BillingBankAccountNumber',
        ResultCode::BILLING_BANK_ACCOUNT_NUMBER_REQUIRED => 'BillingBankAccountNumber',
        ResultCode::BILLING_BANK_ACCOUNT_TYPE_INVALID_ENCODING => 'BillingBankAccountType',
        ResultCode::BILLING_BANK_ACCOUNT_TYPE_REQUIRED => 'BillingBankAccountType',
        ResultCode::BILLING_BANK_ACCOUNT_TYPE_UNKNOWN => 'BillingBankAccountType',
        ResultCode::BILLING_BANK_NAME_INVALID_ENCODING => 'BillingBankName',
        ResultCode::BILLING_BANK_NAME_REQUIRED => 'BillingBankName',
        ResultCode::BILLING_BANK_ROUTING_NUMBER_INVALID_ENCODING => 'BillingBankRoutingNumber',
        ResultCode::BILLING_BANK_ROUTING_NUMBER_REQUIRED => 'BillingBankRoutingNumber',
        ResultCode::BILLING_CC_CODE_REQUIRED => 'BillingCcCode',
        ResultCode::BILLING_CC_EXP_DATE_INVALID => 'BillingCcExpDate',
        ResultCode::BILLING_CC_EXP_DATE_INVALID_ENCODING => 'BillingCcExpDate',
        ResultCode::BILLING_CC_EXP_DATE_REQUIRED => 'BillingCcExpDate',
        ResultCode::BILLING_CC_NUMBER_HASH_EXIST => 'BillingCcNumberHash',
        ResultCode::BILLING_CC_NUMBER_HASH_INVALID => 'BillingCcNumberHash',
        ResultCode::BILLING_CC_NUMBER_INVALID => 'BillingCcNumber',
        ResultCode::BILLING_CC_NUMBER_INVALID_ENCODING => 'BillingCcNumber',
        ResultCode::BILLING_CC_NUMBER_REQUIRED => 'BillingCcNumber',
        ResultCode::BILLING_CC_TYPE_INVALID_ENCODING => 'BillingCcType',
        ResultCode::BILLING_CC_TYPE_NOT_FOUND => 'BillingCcType',
        ResultCode::BILLING_CC_TYPE_REQUIRED => 'BillingCcType',
        ResultCode::BILLING_COMPANY_NAME_INVALID_ENCODING => 'BillingCompanyName',
        ResultCode::BILLING_CONTACT_TYPE_INVALID_ENCODING => 'BillingContactType',
        ResultCode::BILLING_CONTACT_TYPE_REQUIRED => 'BillingContactType',
        ResultCode::BILLING_CONTACT_TYPE_UNKNOWN => 'BillingContactType',
        ResultCode::BILLING_COUNTRY_INVALID_ENCODING => 'BillingCountry',
        ResultCode::BILLING_COUNTRY_REQUIRED => 'BillingCountry',
        ResultCode::BILLING_COUNTRY_UNKNOWN => 'BillingCountry',
        ResultCode::BILLING_EMAIL_INVALID => 'BillingEmail',
        ResultCode::BILLING_FAX_INVALID => 'BillingFax',
        ResultCode::BILLING_FIRST_NAME_INVALID_ENCODING => 'BillingFirstName',
        ResultCode::BILLING_FIRST_NAME_REQUIRED => 'BillingFirstName',
        ResultCode::BILLING_LAST_NAME_INVALID_ENCODING => 'BillingLastName',
        ResultCode::BILLING_LAST_NAME_REQUIRED => 'BillingLastName',
        ResultCode::BILLING_PHONE_INVALID => 'BillingPhone',
        ResultCode::BILLING_PHONE_REQUIRED => 'BillingPhone',
        ResultCode::BILLING_CITY_INVALID_ENCODING => 'BillingCity',
        ResultCode::BILLING_CITY_REQUIRED => 'BillingCity',
        ResultCode::BILLING_STATE_INVALID_ENCODING => 'BillingState',
        ResultCode::BILLING_STATE_REQUIRED => 'BillingState',
        ResultCode::BILLING_STATE_NAME_INVALID_ENCODING => 'BillingState',
        ResultCode::BILLING_STATE_UNKNOWN => 'BillingState',
        ResultCode::BILLING_ZIP_REQUIRED => 'BillingZip',
        ResultCode::BP_RANGE_CALCULATION_HYBRID_UNKNOWN => 'bpRangeCalculationHybrid',
        ResultCode::BP_RANGE_CALCULATION_LIVE_UNKNOWN => 'bpRangeCalculationLive',
        ResultCode::BP_RANGE_CALCULATION_TIMED_UNKNOWN => 'bpRangeCalculationTimed',
        ResultCode::BP_RULE_NOT_FOUND => 'BpRule',
        ResultCode::BUYERS_PREMIUMS_HYBRID_VALIDATION_FAILED => 'BuyersPremiumsHybrid',
        ResultCode::BUYERS_PREMIUMS_LIVE_VALIDATION_FAILED => 'BuyersPremiumsLive',
        ResultCode::BUYERS_PREMIUMS_TIMED_VALIDATION_FAILED => 'BuyersPremiumsTimed',
        ResultCode::BP_RULE_WITH_INDIVIDUAL_BP_CAN_NOT_BE_ASSIGNED_TOGETHER => 'BpRule',
        ResultCode::COLLATERAL_ACCOUNT_ID_NOT_FOUND => 'CollateralAccountId', // not implemented is SOAP yet
        ResultCode::COMPANY_NAME_INVALID_ENCODING => 'CompanyName',
        ResultCode::COMPANY_NAME_REQUIRED => 'CompanyName',
        ResultCode::CONSIGNOR_COMMISSION_ID_INVALID => 'ConsignorCommissionId',
        ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID => 'ConsignorCommissionCalculationMethod',
        ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID => 'ConsignorCommissionRanges',
        ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID => 'ConsignorSoldFeeId',
        ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID => 'ConsignorSoldFeeCalculationMethod',
        ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_INVALID => 'ConsignorSoldFeeCReference',
        ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID => 'ConsignorSoldFeeRanges',
        ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID => 'ConsignorUnsoldFeeId',
        ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID => 'ConsignorUnsoldFeeCalculationMethod',
        ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID => 'ConsignorUnsoldFeeCReference',
        ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID => 'ConsignorUnsoldFeeRanges',
        ResultCode::CONSIGNOR_PAYMENT_INFO_INVALID_ENCODING => 'ConsignorPaymentInfo',
        ResultCode::CONSIGNOR_SALES_TAX_INVALID => 'ConsignorSalesTax',
        ResultCode::CONSIGNOR_TAX_INVALID => 'ConsignorTax',
        ResultCode::CUSTOMER_NO_AS_BIDDER_NO_EXIST => 'CustomerNo',
        ResultCode::CUSTOMER_NO_INVALID => 'CustomerNo',
        ResultCode::CUSTOMER_NO_EXIST => 'CustomerNo',
        ResultCode::CUSTOMER_NO_HIGHER_MAX_AVAILABLE_VALUE => 'CustomerNo',
        ResultCode::CUSTOMER_NO_REQUIRED => 'CustomerNo',
        ResultCode::EMAIL_CONFIRM_NOT_MATCH => 'EmailConfirmation',
        ResultCode::EMAIL_CONFIRM_REQUIRED => 'EmailConfirmation',
        ResultCode::EMAIL_EXIST => 'Email',
        ResultCode::EMAIL_INVALID => 'Email',
        ResultCode::EMAIL_REQUIRED => 'Email',
        ResultCode::FIRST_NAME_INVALID_ENCODING => 'FirstName',
        ResultCode::FIRST_NAME_REQUIRED => 'FirstName',
        ResultCode::FLAG_UNKNOWN => 'Flag',
        ResultCode::IDENTIFICATION_INVALID_ENCODING => 'Identification',
        ResultCode::IDENTIFICATION_REQUIRED => 'Identification',
        ResultCode::IDENTIFICATION_TYPE_REQUIRED => 'IdentificationType',
        ResultCode::IDENTIFICATION_TYPE_UNKNOWN => 'IdentificationType',
        ResultCode::LAST_NAME_INVALID_ENCODING => 'LastName',
        ResultCode::LAST_NAME_REQUIRED => 'LastName',
        ResultCode::LOCATION_ID_NOT_FOUND => 'LocationId',
        ResultCode::LOCATION_INVALID_ENCODING => 'Location',
        ResultCode::LOCATION_NOT_FOUND => 'Location',
        ResultCode::MAX_OUTSTANDING_INVALID => 'MaxOutstanding',
        ResultCode::NMI_VAULT_ID_EXIST => 'NmiVaultId',
        ResultCode::NOTE_INVALID_ENCODING => 'Note',
        ResultCode::PASSWORD_CONFIRM_REQUIRED => 'PasswordConfirm',
        ResultCode::PASSWORD_INVALID => 'Password',
        ResultCode::PASSWORD_NOT_MATCH => 'Password',
        ResultCode::PASSWORD_REQUIRED => 'Password',
        ResultCode::PAY_TRACE_CUST_ID_EXIST => 'PayTraceCustId',
        ResultCode::PHONE_INVALID => 'Phone',
        ResultCode::PHONE_INVALID_ENCODING => 'Phone',
        ResultCode::PHONE_REQUIRED => 'Phone',
        ResultCode::PHONE_TYPE_REQUIRED => 'PhoneType',
        ResultCode::PHONE_TYPE_UNKNOWN => 'PhoneType',
        ResultCode::REFERRER_INVALID => 'Referrer',
        ResultCode::REG_AUTH_DATE_INVALID => 'RegAuthDate',
        ResultCode::SALES_COMMISSIONS_VALIDATION_FAILED => 'SalesCommissions',
        ResultCode::SALES_TAX_INVALID => 'SalesTax',
        ResultCode::SHIPPING_ADDRESS_INVALID_ENCODING => 'ShippingAddress',
        ResultCode::SHIPPING_ADDRESS_REQUIRED => 'ShippingAddress',
        ResultCode::SHIPPING_ADDRESS2_INVALID_ENCODING => 'ShippingAddress2',
        ResultCode::SHIPPING_ADDRESS3_INVALID_ENCODING => 'ShippingAddress3',
        ResultCode::SHIPPING_CITY_INVALID_ENCODING => 'ShippingCity',
        ResultCode::SHIPPING_CITY_REQUIRED => 'ShippingCity',
        ResultCode::SHIPPING_COMPANY_NAME_INVALID_ENCODING => 'ShippingCompanyName',
        ResultCode::SHIPPING_CONTACT_TYPE_INVALID_ENCODING => 'ShippingContactType',
        ResultCode::SHIPPING_CONTACT_TYPE_REQUIRED => 'ShippingContactType',
        ResultCode::SHIPPING_CONTACT_TYPE_UNKNOWN => 'ShippingContactType',
        ResultCode::SHIPPING_COUNTRY_INVALID_ENCODING => 'ShippingCountry',
        ResultCode::SHIPPING_COUNTRY_REQUIRED => 'ShippingCountry',
        ResultCode::SHIPPING_COUNTRY_UNKNOWN => 'ShippingCountry',
        ResultCode::SHIPPING_FAX_INVALID => 'ShippingFax',
        ResultCode::SHIPPING_FIRST_NAME_INVALID_ENCODING => 'ShippingFirstName',
        ResultCode::SHIPPING_FIRST_NAME_REQUIRED => 'ShippingFirstName',
        ResultCode::SHIPPING_LAST_NAME_INVALID_ENCODING => 'ShippingLastName',
        ResultCode::SHIPPING_LAST_NAME_REQUIRED => 'ShippingLastName',
        ResultCode::SHIPPING_PHONE_INVALID => 'ShippingPhone',
        ResultCode::SHIPPING_PHONE_REQUIRED => 'ShippingPhone',
        ResultCode::SHIPPING_STATE_INVALID_ENCODING => 'ShippingState',
        ResultCode::SHIPPING_STATE_REQUIRED => 'ShippingState',
        ResultCode::SHIPPING_STATE_NAME_INVALID_ENCODING => 'ShippingState',
        ResultCode::SHIPPING_STATE_UNKNOWN => 'ShippingState',
        ResultCode::SHIPPING_ZIP_REQUIRED => 'ShippingZip',
        ResultCode::SPECIFIC_LOCATION_INVALID => 'SpecificLocation',
        ResultCode::SPECIFIC_LOCATION_REDUNDANT => 'SpecificLocation',
        ResultCode::SYNC_KEY_EXIST => 'SyncKey',
        ResultCode::TAX_APPLICATION_INVALID => 'TaxApplication',
        ResultCode::TAX_APPLICATION_INVALID_ENCODING => 'TaxApplication',
        ResultCode::USERNAME_EXIST => 'Username',
        ResultCode::USERNAME_INVALID => 'Username',
        ResultCode::USERNAME_INVALID_ENCODING => 'Username',
        ResultCode::USERNAME_REQUIRED => 'Username',
        ResultCode::USER_STATUS_ID_UNKNOWN => 'UserStatusId',
        ResultCode::USER_STATUS_UNKNOWN => 'UserStatus',
        ResultCode::VIEW_LANGUAGE_NOT_FOUND => 'ViewLanguage',
        ResultCode::TIMEZONE_INVALID => 'Timezone',
        ResultCode::ADMIN_AND_BIDDER_CONSIGNOR_PRIVILEGE_TOGETHER_NOT_ALLOWED => 'Privileges',
        ResultCode::ADMIN_PRIVILEGES_IS_NOT_EDITABLE => 'Privileges',
        ResultCode::BIDDER_AND_CONSIGNOR_PRIVILEGES_IS_NOT_EDITABLE => 'Privileges',
        ResultCode::MANAGE_AUCTIONS_PRIVILEGE_IS_NOT_EDITABLE => 'ManageAuctionsPrivilege',
        ResultCode::MANAGE_INVENTORY_PRIVILEGE_IS_NOT_EDITABLE => 'ManageInventoryPrivilege',
        ResultCode::MANAGE_USERS_PRIVILEGE_IS_NOT_EDITABLE => 'ManageUsersPrivilege',
        ResultCode::MANAGE_INVOICES_PRIVILEGE_IS_NOT_EDITABLE => 'ManageInvoicesPrivilege',
        ResultCode::MANAGE_SETTLEMENTS_PRIVILEGE_IS_NOT_EDITABLE => 'ManageConsignorSettlementsPrivilege',
        ResultCode::MANAGE_SETTINGS_PRIVILEGE_IS_NOT_EDITABLE => 'ManageSettingsPrivilege',
        ResultCode::MANAGE_CC_INFO_PRIVILEGE_IS_NOT_EDITABLE => 'ManageCcInfoPrivilege',
        ResultCode::SALES_STAFF_PRIVILEGE_IS_NOT_EDITABLE => 'SalesStaffPrivilege',
        ResultCode::MANAGE_REPORTS_PRIVILEGE_IS_NOT_EDITABLE => 'ReportsPrivilege',
        ResultCode::SUPERADMIN_PRIVILEGE_IS_NOT_EDITABLE => 'SuperadminPrivilege',
        ResultCode::SUB_AUCTION_MANAGE_ALL_PRIVILEGE_IS_NOT_EDITABLE => 'ManageAllAuctionsPrivilege',
        ResultCode::SUB_AUCTION_DELETE_PRIVILEGE_IS_NOT_EDITABLE => 'DeleteAuctionPrivilege',
        ResultCode::SUB_AUCTION_ARCHIVE_PRIVILEGE_IS_NOT_EDITABLE => 'ArchiveAuctionPrivilege',
        ResultCode::SUB_AUCTION_RESET_PRIVILEGE_IS_NOT_EDITABLE => 'ResetAuctionPrivilege',
        ResultCode::SUB_AUCTION_INFORMATION_PRIVILEGE_IS_NOT_EDITABLE => 'InformationPrivilege',
        ResultCode::SUB_AUCTION_PUBLISH_PRIVILEGE_IS_NOT_EDITABLE => 'PublishPrivilege',
        ResultCode::SUB_AUCTION_LOT_LIST_PRIVILEGE_IS_NOT_EDITABLE => 'LotsPrivilege',
        ResultCode::SUB_AUCTION_AVAILABLE_LOT_PRIVILEGE_IS_NOT_EDITABLE => 'AvailableLotsPrivilege',
        ResultCode::SUB_AUCTION_BIDDER_PRIVILEGE_IS_NOT_EDITABLE => 'BiddersPrivilege',
        ResultCode::SUB_AUCTION_REMAINING_USER_PRIVILEGE_IS_NOT_EDITABLE => 'RemainingUsersPrivilege',
        ResultCode::SUB_AUCTION_RUN_LIVE_PRIVILEGE_IS_NOT_EDITABLE => 'RunLiveAuctionPrivilege',
        ResultCode::SUB_AUCTION_AUCTIONEER_PRIVILEGE_IS_NOT_EDITABLE => 'AuctioneerScreenPrivilege',
        ResultCode::SUB_AUCTION_PROJECTOR_PRIVILEGE_IS_NOT_EDITABLE => 'ProjectorPrivilege',
        ResultCode::SUB_AUCTION_BID_INCREMENT_PRIVILEGE_IS_NOT_EDITABLE => 'BidIncrementsPrivilege',
        ResultCode::SUB_AUCTION_BUYER_PREMIUM_PRIVILEGE_IS_NOT_EDITABLE => 'BuyersPremiumPrivilege',
        ResultCode::SUB_AUCTION_PERMISSION_PRIVILEGE_IS_NOT_EDITABLE => 'PermissionsPrivilege',
        ResultCode::SUB_AUCTION_CREATE_BIDDER_PRIVILEGE_IS_NOT_EDITABLE => 'CreateBidderPrivilege',
        ResultCode::SUB_USER_BULK_EXPORT_PRIVILEGE_IS_NOT_EDITABLE => 'BulkUserExportPrivilege',
        ResultCode::SUB_USER_DELETE_PRIVILEGE_IS_NOT_EDITABLE => 'DeleteUserPrivilege',
        ResultCode::SUB_USER_PASSWORD_PRIVILEGE_IS_NOT_EDITABLE => 'UserPasswordsPrivilege',
        ResultCode::SUB_USER_PRIVILEGE_PRIVILEGE_IS_NOT_EDITABLE => 'UserPrivilegesPrivilege',
        ResultCode::CROSS_DOMAIN_ADMIN_AT_PORTAL_ACCOUNT_NOT_APPLICABLE => 'SuperadminPrivilege'
    ];

    protected function initColumnNames(): void
    {
        $columnHeaders = $this->cfg()->get('csv->admin->user');
        $this->columnNames = [
            ResultCode::ACCOUNT_ID_NOT_FOUND => '',
            ResultCode::ACCOUNT_NO_RIGHTS => '',
            ResultCode::ADDITIONAL_BP_INTERNET_HYBRID_INVALID => $columnHeaders->{Constants\Csv\User::ADDITIONAL_BP_PERCENTAGE_FOR_INTERNET_BUYERS_HYBRID},
            ResultCode::ADDITIONAL_BP_INTERNET_LIVE_INVALID => $columnHeaders->{Constants\Csv\User::ADDITIONAL_BP_PERCENTAGE_FOR_INTERNET_BUYERS_LIVE},
            ResultCode::ADMIN_NO_SUB_PRIVILEGES_SELECTED => '',
            ResultCode::AGENT_NOT_FOUND => $columnHeaders->{Constants\Csv\User::AGENT},
            ResultCode::BILLING_ADDRESS_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BILLING_ADDRESS},
            ResultCode::BILLING_ADDRESS_REQUIRED => $columnHeaders->{Constants\Csv\User::BILLING_ADDRESS},
            ResultCode::BILLING_ADDRESS2_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BILLING_ADDRESS_2},
            ResultCode::BILLING_ADDRESS3_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BILLING_ADDRESS_3},
            ResultCode::BILLING_BANK_ACCOUNT_NAME_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BANK_ACCOUNT_NAME},
            ResultCode::BILLING_BANK_ACCOUNT_NAME_REQUIRED => $columnHeaders->{Constants\Csv\User::BANK_ACCOUNT_NAME},
            ResultCode::BILLING_BANK_ACCOUNT_NUMBER_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BANK_ACCOUNT_NO},
            ResultCode::BILLING_BANK_ACCOUNT_NUMBER_REQUIRED => $columnHeaders->{Constants\Csv\User::BANK_ACCOUNT_NO},
            ResultCode::BILLING_BANK_ACCOUNT_TYPE_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BANK_ACCOUNT_TYPE},
            ResultCode::BILLING_BANK_ACCOUNT_TYPE_REQUIRED => $columnHeaders->{Constants\Csv\User::BANK_ACCOUNT_TYPE},
            ResultCode::BILLING_BANK_ACCOUNT_TYPE_UNKNOWN => $columnHeaders->{Constants\Csv\User::BANK_ACCOUNT_TYPE},
            ResultCode::BILLING_BANK_NAME_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BANK_NAME},
            ResultCode::BILLING_BANK_NAME_REQUIRED => $columnHeaders->{Constants\Csv\User::BANK_NAME},
            ResultCode::BILLING_BANK_ROUTING_NUMBER_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BANK_ROUTING_NO},
            ResultCode::BILLING_BANK_ROUTING_NUMBER_REQUIRED => $columnHeaders->{Constants\Csv\User::BANK_ROUTING_NO},
            ResultCode::BILLING_CC_CODE_REQUIRED => '',
            ResultCode::BILLING_CC_EXP_DATE_INVALID => $columnHeaders->{Constants\Csv\User::CC_EXP_DATE},
            ResultCode::BILLING_CC_EXP_DATE_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::CC_EXP_DATE},
            ResultCode::BILLING_CC_EXP_DATE_REQUIRED => $columnHeaders->{Constants\Csv\User::CC_EXP_DATE},
            ResultCode::BILLING_CC_NUMBER_HASH_EXIST => '',
            ResultCode::BILLING_CC_NUMBER_HASH_INVALID => '',
            ResultCode::BILLING_CC_NUMBER_INVALID => $columnHeaders->{Constants\Csv\User::CC_NUMBER},
            ResultCode::BILLING_CC_NUMBER_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::CC_NUMBER},
            ResultCode::BILLING_CC_NUMBER_REQUIRED => $columnHeaders->{Constants\Csv\User::CC_NUMBER},
            ResultCode::BILLING_CC_TYPE_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::CC_TYPE},
            ResultCode::BILLING_CC_TYPE_NOT_FOUND => $columnHeaders->{Constants\Csv\User::CC_TYPE},
            ResultCode::BILLING_CC_TYPE_REQUIRED => $columnHeaders->{Constants\Csv\User::CC_TYPE},
            ResultCode::BILLING_COMPANY_NAME_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BILLING_COMPANY_NAME},
            ResultCode::BILLING_CONTACT_TYPE_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BILLING_CONTACT_TYPE},
            ResultCode::BILLING_CONTACT_TYPE_REQUIRED => $columnHeaders->{Constants\Csv\User::BILLING_CONTACT_TYPE},
            ResultCode::BILLING_CONTACT_TYPE_UNKNOWN => $columnHeaders->{Constants\Csv\User::BILLING_CONTACT_TYPE},
            ResultCode::BILLING_COUNTRY_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BILLING_COUNTRY},
            ResultCode::BILLING_COUNTRY_REQUIRED => $columnHeaders->{Constants\Csv\User::BILLING_COUNTRY},
            ResultCode::BILLING_COUNTRY_UNKNOWN => $columnHeaders->{Constants\Csv\User::BILLING_COUNTRY},
            ResultCode::BILLING_EMAIL_INVALID => '',
            ResultCode::BILLING_FAX_INVALID => $columnHeaders->{Constants\Csv\User::BILLING_FAX},
            ResultCode::BILLING_FIRST_NAME_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BILLING_FIRST_NAME},
            ResultCode::BILLING_FIRST_NAME_REQUIRED => $columnHeaders->{Constants\Csv\User::BILLING_FIRST_NAME},
            ResultCode::BILLING_LAST_NAME_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BILLING_LAST_NAME},
            ResultCode::BILLING_LAST_NAME_REQUIRED => $columnHeaders->{Constants\Csv\User::BILLING_LAST_NAME},
            ResultCode::BILLING_PHONE_INVALID => $columnHeaders->{Constants\Csv\User::BILLING_PHONE},
            ResultCode::BILLING_PHONE_REQUIRED => $columnHeaders->{Constants\Csv\User::BILLING_PHONE},
            ResultCode::BILLING_CITY_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BILLING_CITY},
            ResultCode::BILLING_CITY_REQUIRED => $columnHeaders->{Constants\Csv\User::BILLING_CITY},
            ResultCode::BILLING_STATE_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BILLING_STATE},
            ResultCode::BILLING_STATE_REQUIRED => $columnHeaders->{Constants\Csv\User::BILLING_STATE},
            ResultCode::BILLING_STATE_NAME_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::BILLING_STATE},
            ResultCode::BILLING_STATE_UNKNOWN => $columnHeaders->{Constants\Csv\User::BILLING_STATE},
            ResultCode::BILLING_ZIP_REQUIRED => $columnHeaders->{Constants\Csv\User::BILLING_ZIP},
            ResultCode::BP_RANGE_CALCULATION_HYBRID_UNKNOWN => $columnHeaders->{Constants\Csv\User::BP_CALCULATION_HYBRID},
            ResultCode::BP_RANGE_CALCULATION_LIVE_UNKNOWN => $columnHeaders->{Constants\Csv\User::BP_CALCULATION_LIVE},
            ResultCode::BP_RANGE_CALCULATION_TIMED_UNKNOWN => $columnHeaders->{Constants\Csv\User::BP_CALCULATION_TIMED},
            ResultCode::BP_RULE_NOT_FOUND => '',
            ResultCode::BUYERS_PREMIUMS_HYBRID_VALIDATION_FAILED => $columnHeaders->{Constants\Csv\User::BP_RANGES_HYBRID},
            ResultCode::BUYERS_PREMIUMS_LIVE_VALIDATION_FAILED => $columnHeaders->{Constants\Csv\User::BP_RANGES_LIVE},
            ResultCode::BUYERS_PREMIUMS_TIMED_VALIDATION_FAILED => $columnHeaders->{Constants\Csv\User::BP_RANGES_TIMED},
            ResultCode::BP_RULE_WITH_INDIVIDUAL_BP_CAN_NOT_BE_ASSIGNED_TOGETHER => $columnHeaders->{Constants\Csv\User::BUYERS_PREMIUM},
            ResultCode::CONSIGNOR_COMMISSION_ID_INVALID => $columnHeaders->{Constants\Csv\User::CONSIGNOR_COMMISSION_ID},
            ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID => $columnHeaders->{Constants\Csv\User::CONSIGNOR_COMMISSION_RANGES},
            ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID => $columnHeaders->{Constants\Csv\User::CONSIGNOR_COMMISSION_CALCULATION_METHOD},
            ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID => $columnHeaders->{Constants\Csv\User::CONSIGNOR_SOLD_FEE_ID},
            ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID => $columnHeaders->{Constants\Csv\User::CONSIGNOR_SOLD_FEE_RANGES},
            ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID => $columnHeaders->{Constants\Csv\User::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD},
            ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_INVALID => $columnHeaders->{Constants\Csv\User::CONSIGNOR_SOLD_FEE_REFERENCE},
            ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID => $columnHeaders->{Constants\Csv\User::CONSIGNOR_UNSOLD_FEE_ID},
            ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID => $columnHeaders->{Constants\Csv\User::CONSIGNOR_UNSOLD_FEE_RANGES},
            ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID => $columnHeaders->{Constants\Csv\User::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD},
            ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID => $columnHeaders->{Constants\Csv\User::CONSIGNOR_UNSOLD_FEE_REFERENCE},
            ResultCode::COLLATERAL_ACCOUNT_ID_NOT_FOUND => '', // Not implemented in CSV yet
            ResultCode::COMPANY_NAME_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::COMPANY_NAME},
            ResultCode::COMPANY_NAME_REQUIRED => $columnHeaders->{Constants\Csv\User::COMPANY_NAME},
            ResultCode::CONSIGNOR_PAYMENT_INFO_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::PAYMENT_INFO},
            ResultCode::CONSIGNOR_SALES_TAX_INVALID => $columnHeaders->{Constants\Csv\User::CONSIGNOR_BUYER_TAX_PERCENTAGE},
            ResultCode::CONSIGNOR_TAX_INVALID => $columnHeaders->{Constants\Csv\User::CONSIGNOR_TAX_PERCENTAGE},
            ResultCode::CUSTOMER_NO_AS_BIDDER_NO_EXIST => $columnHeaders->{Constants\Csv\User::CUSTOMER_NO},
            ResultCode::CUSTOMER_NO_INVALID => $columnHeaders->{Constants\Csv\User::CUSTOMER_NO},
            ResultCode::CUSTOMER_NO_EXIST => $columnHeaders->{Constants\Csv\User::CUSTOMER_NO},
            ResultCode::CUSTOMER_NO_HIGHER_MAX_AVAILABLE_VALUE => $columnHeaders->{Constants\Csv\User::CUSTOMER_NO},
            ResultCode::CUSTOMER_NO_REQUIRED => $columnHeaders->{Constants\Csv\User::CUSTOMER_NO},
            ResultCode::EMAIL_CONFIRM_NOT_MATCH => '',
            ResultCode::EMAIL_CONFIRM_REQUIRED => '',
            ResultCode::EMAIL_EXIST => $columnHeaders->{Constants\Csv\User::EMAIL},
            ResultCode::EMAIL_INVALID => $columnHeaders->{Constants\Csv\User::EMAIL},
            ResultCode::EMAIL_REQUIRED => $columnHeaders->{Constants\Csv\User::EMAIL},
            ResultCode::FIRST_NAME_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::FIRST_NAME},
            ResultCode::FIRST_NAME_REQUIRED => $columnHeaders->{Constants\Csv\User::FIRST_NAME},
            ResultCode::FLAG_UNKNOWN => $columnHeaders->{Constants\Csv\User::FLAG},
            ResultCode::IDENTIFICATION_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::IDENTIFICATION},
            ResultCode::IDENTIFICATION_REQUIRED => $columnHeaders->{Constants\Csv\User::IDENTIFICATION},
            ResultCode::IDENTIFICATION_TYPE_REQUIRED => $columnHeaders->{Constants\Csv\User::IDENTIFICATION_TYPE},
            ResultCode::IDENTIFICATION_TYPE_UNKNOWN => $columnHeaders->{Constants\Csv\User::IDENTIFICATION_TYPE},
            ResultCode::LAST_NAME_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::LAST_NAME},
            ResultCode::LAST_NAME_REQUIRED => $columnHeaders->{Constants\Csv\User::LAST_NAME},
            ResultCode::LOCATION_ID_NOT_FOUND => $columnHeaders->{Constants\Csv\User::LOCATION},
            ResultCode::LOCATION_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::LOCATION},
            ResultCode::LOCATION_NOT_FOUND => $columnHeaders->{Constants\Csv\User::LOCATION},
            ResultCode::MAX_OUTSTANDING_INVALID => '',
            ResultCode::NMI_VAULT_ID_EXIST => '',
            ResultCode::NOTE_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::NOTES},
            ResultCode::PASSWORD_CONFIRM_REQUIRED => '',
            ResultCode::PASSWORD_INVALID => $columnHeaders->{Constants\Csv\User::PASSWORD},
            ResultCode::PASSWORD_NOT_MATCH => $columnHeaders->{Constants\Csv\User::PASSWORD},
            ResultCode::PASSWORD_REQUIRED => $columnHeaders->{Constants\Csv\User::PASSWORD},
            ResultCode::PAY_TRACE_CUST_ID_EXIST => '',
            ResultCode::PHONE_INVALID => $columnHeaders->{Constants\Csv\User::PHONE},
            ResultCode::PHONE_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::PHONE},
            ResultCode::PHONE_REQUIRED => $columnHeaders->{Constants\Csv\User::PHONE},
            ResultCode::PHONE_TYPE_REQUIRED => $columnHeaders->{Constants\Csv\User::PHONE_TYPE},
            ResultCode::PHONE_TYPE_UNKNOWN => $columnHeaders->{Constants\Csv\User::PHONE_TYPE},
            ResultCode::REFERRER_INVALID => $columnHeaders->{Constants\Csv\User::REFERRER},
            ResultCode::REG_AUTH_DATE_INVALID => 'RegAuthDate',
            ResultCode::SALES_COMMISSIONS_VALIDATION_FAILED => $columnHeaders->{Constants\Csv\User::SALES_CONSIGNMENT_COMMISSION},
            ResultCode::SALES_TAX_INVALID => $columnHeaders->{Constants\Csv\User::BUYER_SALES_TAX},
            ResultCode::SHIPPING_ADDRESS_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::SHIPPING_ADDRESS},
            ResultCode::SHIPPING_ADDRESS_REQUIRED => $columnHeaders->{Constants\Csv\User::SHIPPING_ADDRESS},
            ResultCode::SHIPPING_ADDRESS2_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::SHIPPING_ADDRESS_2},
            ResultCode::SHIPPING_ADDRESS3_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::SHIPPING_ADDRESS_3},
            ResultCode::SHIPPING_CITY_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::SHIPPING_CITY},
            ResultCode::SHIPPING_CITY_REQUIRED => $columnHeaders->{Constants\Csv\User::SHIPPING_CITY},
            ResultCode::SHIPPING_COMPANY_NAME_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::SHIPPING_COMPANY_NAME},
            ResultCode::SHIPPING_CONTACT_TYPE_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::SHIPPING_CONTACT_TYPE},
            ResultCode::SHIPPING_CONTACT_TYPE_REQUIRED => $columnHeaders->{Constants\Csv\User::SHIPPING_CONTACT_TYPE},
            ResultCode::SHIPPING_CONTACT_TYPE_UNKNOWN => $columnHeaders->{Constants\Csv\User::SHIPPING_CONTACT_TYPE},
            ResultCode::SHIPPING_COUNTRY_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::SHIPPING_COUNTRY},
            ResultCode::SHIPPING_COUNTRY_REQUIRED => $columnHeaders->{Constants\Csv\User::SHIPPING_COUNTRY},
            ResultCode::SHIPPING_COUNTRY_UNKNOWN => $columnHeaders->{Constants\Csv\User::SHIPPING_COUNTRY},
            ResultCode::SHIPPING_FAX_INVALID => $columnHeaders->{Constants\Csv\User::SHIPPING_FAX},
            ResultCode::SHIPPING_FIRST_NAME_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::SHIPPING_FIRST_NAME},
            ResultCode::SHIPPING_FIRST_NAME_REQUIRED => $columnHeaders->{Constants\Csv\User::SHIPPING_FIRST_NAME},
            ResultCode::SHIPPING_LAST_NAME_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::SHIPPING_LAST_NAME},
            ResultCode::SHIPPING_LAST_NAME_REQUIRED => $columnHeaders->{Constants\Csv\User::SHIPPING_LAST_NAME},
            ResultCode::SHIPPING_PHONE_INVALID => $columnHeaders->{Constants\Csv\User::SHIPPING_PHONE},
            ResultCode::SHIPPING_PHONE_REQUIRED => $columnHeaders->{Constants\Csv\User::SHIPPING_PHONE},
            ResultCode::SHIPPING_STATE_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::SHIPPING_STATE},
            ResultCode::SHIPPING_STATE_REQUIRED => $columnHeaders->{Constants\Csv\User::SHIPPING_STATE},
            ResultCode::SHIPPING_STATE_NAME_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::SHIPPING_STATE},
            ResultCode::SHIPPING_STATE_UNKNOWN => $columnHeaders->{Constants\Csv\User::SHIPPING_STATE},
            ResultCode::SHIPPING_ZIP_REQUIRED => $columnHeaders->{Constants\Csv\User::SHIPPING_ZIP},
            ResultCode::SPECIFIC_LOCATION_INVALID => 'Specific location',
            ResultCode::SPECIFIC_LOCATION_REDUNDANT => 'Specific location',
            ResultCode::SYNC_KEY_EXIST => '',
            ResultCode::TAX_APPLICATION_INVALID => $columnHeaders->{Constants\Csv\User::APPLY_TAX_TO},
            ResultCode::TAX_APPLICATION_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::APPLY_TAX_TO},
            ResultCode::USERNAME_EXIST => $columnHeaders->{Constants\Csv\User::USERNAME},
            ResultCode::USERNAME_INVALID => $columnHeaders->{Constants\Csv\User::USERNAME},
            ResultCode::USERNAME_INVALID_ENCODING => $columnHeaders->{Constants\Csv\User::USERNAME},
            ResultCode::USERNAME_REQUIRED => $columnHeaders->{Constants\Csv\User::USERNAME},
            ResultCode::USER_STATUS_ID_UNKNOWN => '',
            ResultCode::USER_STATUS_UNKNOWN => '',
            ResultCode::VIEW_LANGUAGE_NOT_FOUND => '',
            ResultCode::TIMEZONE_INVALID => '',
            ResultCode::ADMIN_AND_BIDDER_CONSIGNOR_PRIVILEGE_TOGETHER_NOT_ALLOWED => '',
            ResultCode::ADMIN_PRIVILEGES_IS_NOT_EDITABLE => '',
            ResultCode::BIDDER_AND_CONSIGNOR_PRIVILEGES_IS_NOT_EDITABLE => '',
            ResultCode::MANAGE_AUCTIONS_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::MANAGE_AUCTIONS},
            ResultCode::MANAGE_INVENTORY_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::MANAGE_INVENTORY},
            ResultCode::MANAGE_USERS_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::MANAGE_USERS},
            ResultCode::MANAGE_INVOICES_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::MANAGE_INVOICES},
            ResultCode::MANAGE_SETTLEMENTS_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::MANAGE_SETTLEMENTS},
            ResultCode::MANAGE_SETTINGS_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::MANAGE_SETTINGS},
            ResultCode::MANAGE_CC_INFO_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::MANAGE_CC_INFO},
            ResultCode::SALES_STAFF_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::SALES_STAFF},
            ResultCode::MANAGE_REPORTS_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::REPORTS},
            ResultCode::SUPERADMIN_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::SUPERADMIN},
            ResultCode::SUB_AUCTION_MANAGE_ALL_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::MANAGE_ALL_AUCTIONS},
            ResultCode::SUB_AUCTION_DELETE_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::DELETE_AUCTION},
            ResultCode::SUB_AUCTION_ARCHIVE_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::ARCHIVE_AUCTION},
            ResultCode::SUB_AUCTION_RESET_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::RESET_AUCTION},
            ResultCode::SUB_AUCTION_INFORMATION_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::INFORMATION},
            ResultCode::SUB_AUCTION_PUBLISH_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::PUBLISH},
            ResultCode::SUB_AUCTION_LOT_LIST_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::LOTS},
            ResultCode::SUB_AUCTION_AVAILABLE_LOT_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::AVAILABLE_LOTS},
            ResultCode::SUB_AUCTION_BIDDER_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::BIDDERS},
            ResultCode::SUB_AUCTION_REMAINING_USER_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::REMAINING_USERS},
            ResultCode::SUB_AUCTION_RUN_LIVE_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::RUN_LIVE_AUCTION},
            ResultCode::SUB_AUCTION_AUCTIONEER_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::ASSISTANT_CLERK},
            ResultCode::SUB_AUCTION_PROJECTOR_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::PROJECTOR},
            ResultCode::SUB_AUCTION_BID_INCREMENT_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::BID_INCREMENTS},
            ResultCode::SUB_AUCTION_BUYER_PREMIUM_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::BUYERS_PREMIUM},
            ResultCode::SUB_AUCTION_PERMISSION_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::PERMISSIONS},
            ResultCode::SUB_AUCTION_CREATE_BIDDER_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::CREATE_BIDDER},
            ResultCode::CROSS_DOMAIN_ADMIN_AT_PORTAL_ACCOUNT_NOT_APPLICABLE => $columnHeaders->{Constants\Csv\User::SUPERADMIN},
            ResultCode::SUB_USER_BULK_EXPORT_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::USER_BULK_EXPORT},
            ResultCode::SUB_USER_DELETE_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::DELETE_USER},
            ResultCode::SUB_USER_PASSWORD_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::USER_PASSWORDS},
            ResultCode::SUB_USER_PRIVILEGE_PRIVILEGE_IS_NOT_EDITABLE => $columnHeaders->{Constants\Csv\User::USER_PRIVILEGES},
        ];
    }

    protected function translateErrorMessages(): void
    {
        $requiredText = $this->translate('SIGNUP_ERR_REQUIRED');

        $this->errorMessages = [
            ResultCode::ACCOUNT_ID_NOT_FOUND => 'Not found',
            ResultCode::ACCOUNT_NO_RIGHTS => 'Super admin privileges required',
            ResultCode::ADDITIONAL_BP_INTERNET_HYBRID_INVALID => 'Invalid',
            ResultCode::ADDITIONAL_BP_INTERNET_LIVE_INVALID => 'Invalid',
            ResultCode::ADMIN_NO_SUB_PRIVILEGES_SELECTED => 'No sub privileges selected',
            ResultCode::AGENT_NOT_FOUND => 'Not found',
            ResultCode::BILLING_ADDRESS_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_ADDRESS_REQUIRED => $requiredText,
            ResultCode::BILLING_ADDRESS2_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_ADDRESS3_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_BANK_ACCOUNT_NAME_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_BANK_ACCOUNT_NAME_REQUIRED => $requiredText,
            ResultCode::BILLING_BANK_ACCOUNT_NUMBER_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_BANK_ACCOUNT_NUMBER_REQUIRED => $requiredText,
            ResultCode::BILLING_BANK_ACCOUNT_TYPE_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_BANK_ACCOUNT_TYPE_REQUIRED => $requiredText,
            ResultCode::BILLING_BANK_ACCOUNT_TYPE_UNKNOWN => 'Unknown',
            ResultCode::BILLING_BANK_NAME_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_BANK_NAME_REQUIRED => $requiredText,
            ResultCode::BILLING_BANK_ROUTING_NUMBER_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_BANK_ROUTING_NUMBER_REQUIRED => $requiredText,
            ResultCode::BILLING_CC_CODE_REQUIRED => $requiredText,
            ResultCode::BILLING_CC_EXP_DATE_INVALID => 'Invalid',
            ResultCode::BILLING_CC_EXP_DATE_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_CC_EXP_DATE_REQUIRED => $requiredText,
            ResultCode::BILLING_CC_NUMBER_HASH_EXIST => 'Warning: This credit card number is found for users: ',
            ResultCode::BILLING_CC_NUMBER_HASH_INVALID => 'Invalid',
            ResultCode::BILLING_CC_NUMBER_INVALID => $this->getTranslator()->translate('GENERAL_VALIDATING_CREDIT_CARD_ERROR', 'general'),
            ResultCode::BILLING_CC_NUMBER_INVALID_ENCODING => 'Invalid encoding', // YV: vpautoff@r41236
            ResultCode::BILLING_CC_NUMBER_REQUIRED => $requiredText,
            ResultCode::BILLING_CC_TYPE_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_CC_TYPE_NOT_FOUND => 'Not found',
            ResultCode::BILLING_CC_TYPE_REQUIRED => $requiredText,
            ResultCode::BILLING_COMPANY_NAME_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_CONTACT_TYPE_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_CONTACT_TYPE_REQUIRED => $requiredText,
            ResultCode::BILLING_CONTACT_TYPE_UNKNOWN => 'Unknown',
            ResultCode::BILLING_COUNTRY_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_COUNTRY_REQUIRED => $requiredText,
            ResultCode::BILLING_COUNTRY_UNKNOWN => 'Unknown',
            ResultCode::BILLING_EMAIL_INVALID => 'Invalid',
            ResultCode::BILLING_FAX_INVALID => $this->translate('USER_ERR_FAX_NUMBER_INVALID'),
            ResultCode::BILLING_FIRST_NAME_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_FIRST_NAME_REQUIRED => $requiredText,
            ResultCode::BILLING_LAST_NAME_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_LAST_NAME_REQUIRED => $requiredText,
            ResultCode::BILLING_PHONE_INVALID => $this->translate('USER_ERR_PHONE_NUMBER_INVALID'),
            ResultCode::BILLING_PHONE_REQUIRED => $this->translate('USER_ERR_PHONE_NUMBER_REQUIRED'),
            ResultCode::BILLING_CITY_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_CITY_REQUIRED => $requiredText,
            ResultCode::BILLING_STATE_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_STATE_REQUIRED => $requiredText,
            ResultCode::BILLING_STATE_NAME_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::BILLING_STATE_UNKNOWN => 'Unknown',
            ResultCode::BILLING_ZIP_REQUIRED => $requiredText,
            ResultCode::BP_RANGE_CALCULATION_HYBRID_UNKNOWN => 'Unknown',
            ResultCode::BP_RANGE_CALCULATION_LIVE_UNKNOWN => 'Unknown',
            ResultCode::BP_RANGE_CALCULATION_TIMED_UNKNOWN => 'Unknown',
            ResultCode::BP_RULE_NOT_FOUND => 'Not found',
            ResultCode::BUYERS_PREMIUMS_HYBRID_VALIDATION_FAILED => 'Validation failed',
            ResultCode::BUYERS_PREMIUMS_LIVE_VALIDATION_FAILED => 'Validation failed',
            ResultCode::BUYERS_PREMIUMS_TIMED_VALIDATION_FAILED => 'Validation failed',
            ResultCode::BP_RULE_WITH_INDIVIDUAL_BP_CAN_NOT_BE_ASSIGNED_TOGETHER => 'Named rule can\'t be assigned together with individual ranges or additional BP internet',
            ResultCode::COLLATERAL_ACCOUNT_ID_NOT_FOUND => 'Not found',
            ResultCode::COMPANY_NAME_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::COMPANY_NAME_REQUIRED => $requiredText,
            ResultCode::CONSIGNOR_PAYMENT_INFO_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::CONSIGNOR_SALES_TAX_INVALID => 'Invalid',
            ResultCode::CONSIGNOR_TAX_INVALID => 'Invalid',
            ResultCode::CUSTOMER_NO_AS_BIDDER_NO_EXIST => 'as bidder no already exists',
            ResultCode::CUSTOMER_NO_INVALID => 'Should be integer',
            ResultCode::CUSTOMER_NO_EXIST => 'Already exists',
            ResultCode::CUSTOMER_NO_HIGHER_MAX_AVAILABLE_VALUE => 'Higher than the max available value',
            ResultCode::CUSTOMER_NO_REQUIRED => $requiredText,
            ResultCode::EMAIL_CONFIRM_NOT_MATCH => 'Not match',
            ResultCode::EMAIL_CONFIRM_REQUIRED => $requiredText,
            ResultCode::EMAIL_EXIST => $this->translate('SIGNUP_ERR_INFO_EMAILEXISTS'),
            ResultCode::EMAIL_INVALID => $this->translate('SIGNUP_ERR_INFO_EMAILINVALID'),
            ResultCode::EMAIL_REQUIRED => $requiredText,
            ResultCode::FIRST_NAME_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::FIRST_NAME_REQUIRED => $requiredText,
            ResultCode::FLAG_UNKNOWN => 'Unknown',
            ResultCode::IDENTIFICATION_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::IDENTIFICATION_REQUIRED => $requiredText,
            ResultCode::IDENTIFICATION_TYPE_REQUIRED => $requiredText,
            ResultCode::IDENTIFICATION_TYPE_UNKNOWN => 'Unknown',
            ResultCode::LAST_NAME_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::LAST_NAME_REQUIRED => $requiredText,
            ResultCode::LOCATION_ID_NOT_FOUND => 'Not found',
            ResultCode::LOCATION_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::LOCATION_NOT_FOUND => 'Not found',
            ResultCode::MAX_OUTSTANDING_INVALID => 'Invalid',
            ResultCode::NMI_VAULT_ID_EXIST => 'Already exists',
            ResultCode::NOTE_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::PASSWORD_CONFIRM_REQUIRED => $requiredText,
            ResultCode::PASSWORD_INVALID => 'Invalid',
            ResultCode::PASSWORD_NOT_MATCH => 'Not match',
            ResultCode::PASSWORD_REQUIRED => $requiredText,
            ResultCode::PAY_TRACE_CUST_ID_EXIST => 'Already exists',
            ResultCode::PHONE_INVALID => $this->translate('USER_ERR_PHONE_NUMBER_INVALID'),
            ResultCode::PHONE_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::PHONE_REQUIRED => $requiredText,
            ResultCode::PHONE_TYPE_REQUIRED => $requiredText,
            ResultCode::PHONE_TYPE_UNKNOWN => 'Unknown',
            ResultCode::REFERRER_INVALID => 'Invalid',
            ResultCode::REG_AUTH_DATE_INVALID => 'Invalid',
            ResultCode::SALES_COMMISSIONS_VALIDATION_FAILED => 'Validation failed',
            ResultCode::SALES_TAX_INVALID => 'Invalid',
            ResultCode::SHIPPING_ADDRESS_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::SHIPPING_ADDRESS_REQUIRED => $requiredText,
            ResultCode::SHIPPING_ADDRESS2_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::SHIPPING_ADDRESS3_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::SHIPPING_CITY_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::SHIPPING_CITY_REQUIRED => $requiredText,
            ResultCode::SHIPPING_COMPANY_NAME_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::SHIPPING_CONTACT_TYPE_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::SHIPPING_CONTACT_TYPE_REQUIRED => $requiredText,
            ResultCode::SHIPPING_CONTACT_TYPE_UNKNOWN => 'Unknown',
            ResultCode::SHIPPING_COUNTRY_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::SHIPPING_COUNTRY_REQUIRED => $requiredText,
            ResultCode::SHIPPING_COUNTRY_UNKNOWN => 'Unknown',
            ResultCode::SHIPPING_FAX_INVALID => $this->translate('USER_ERR_FAX_NUMBER_INVALID'),
            ResultCode::SHIPPING_FIRST_NAME_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::SHIPPING_FIRST_NAME_REQUIRED => $requiredText,
            ResultCode::SHIPPING_LAST_NAME_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::SHIPPING_LAST_NAME_REQUIRED => $requiredText,
            ResultCode::SHIPPING_PHONE_INVALID => $this->translate('USER_ERR_PHONE_NUMBER_INVALID'),
            ResultCode::SHIPPING_PHONE_REQUIRED => $this->translate('USER_ERR_PHONE_NUMBER_REQUIRED'),
            ResultCode::SHIPPING_STATE_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::SHIPPING_STATE_REQUIRED => $requiredText,
            ResultCode::SHIPPING_STATE_NAME_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::SHIPPING_STATE_UNKNOWN => 'Unknown',
            ResultCode::SHIPPING_ZIP_REQUIRED => $requiredText,
            ResultCode::SPECIFIC_LOCATION_INVALID => 'Invalid',
            ResultCode::SPECIFIC_LOCATION_REDUNDANT => 'Both specific and common event locations are provided',
            ResultCode::SYNC_KEY_EXIST => 'Already exists',
            ResultCode::TAX_APPLICATION_INVALID => 'Invalid',
            ResultCode::TAX_APPLICATION_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::USERNAME_EXIST => $this->translate('SIGNUP_ERR_INFO_USERNAMEEXISTS'),
            ResultCode::USERNAME_INVALID => $this->translate('SIGNUP_ERR_INVALIDUSER'),
            ResultCode::USERNAME_INVALID_ENCODING => 'Invalid encoding',
            ResultCode::USERNAME_REQUIRED => $requiredText,
            ResultCode::USER_STATUS_ID_UNKNOWN => 'Unknown',
            ResultCode::USER_STATUS_UNKNOWN => 'Unknown',
            ResultCode::VIEW_LANGUAGE_NOT_FOUND => 'Not found',
            ResultCode::TIMEZONE_INVALID => 'Invalid',
            ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID => 'Invalid ranges',
            ResultCode::CONSIGNOR_COMMISSION_ID_INVALID => 'Unknown',
            ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID => 'Unknown',
            ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID => 'Invalid ranges',
            ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID => 'Unknown',
            ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID => 'Unknown',
            ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_INVALID => 'Unknown',
            ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID => 'Invalid ranges',
            ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID => 'Unknown',
            ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID => 'Unknown',
            ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID => 'Unknown',
            ResultCode::ADMIN_AND_BIDDER_CONSIGNOR_PRIVILEGE_TOGETHER_NOT_ALLOWED => 'Admin privileges can\'t be set together with bidder or consignor privileges',
            ResultCode::ADMIN_PRIVILEGES_IS_NOT_EDITABLE => 'Admin privileges is not editable',
            ResultCode::BIDDER_AND_CONSIGNOR_PRIVILEGES_IS_NOT_EDITABLE => 'Bidder and consignor privileges is not editable',
            ResultCode::MANAGE_AUCTIONS_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::MANAGE_INVENTORY_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::MANAGE_USERS_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::MANAGE_INVOICES_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::MANAGE_SETTLEMENTS_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::MANAGE_SETTINGS_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::MANAGE_CC_INFO_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SALES_STAFF_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::MANAGE_REPORTS_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUPERADMIN_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_MANAGE_ALL_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_DELETE_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_ARCHIVE_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_RESET_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_INFORMATION_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_PUBLISH_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_LOT_LIST_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_AVAILABLE_LOT_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_BIDDER_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_REMAINING_USER_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_RUN_LIVE_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_AUCTIONEER_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_PROJECTOR_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_BID_INCREMENT_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_BUYER_PREMIUM_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_PERMISSION_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_AUCTION_CREATE_BIDDER_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_USER_BULK_EXPORT_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_USER_DELETE_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_USER_PASSWORD_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::SUB_USER_PRIVILEGE_PRIVILEGE_IS_NOT_EDITABLE => 'Privilege is not editable',
            ResultCode::CROSS_DOMAIN_ADMIN_AT_PORTAL_ACCOUNT_NOT_APPLICABLE => 'Cross domain access rights are not applicable for the portal admin',
        ];
    }

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param UserMakerInputDto $inputDto
     * @param UserMakerConfigDto $configDto
     * @param array $optionals
     * @return $this
     */
    public function construct(
        UserMakerInputDto $inputDto,
        UserMakerConfigDto $configDto,
        array $optionals = []
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        $this->initOptionals($optionals);
        $this->getUserMakerAccessChecker()->construct($inputDto, $configDto);
        $this->customFieldManager = UserMakerCustomFieldManager::new()->construct($inputDto, $configDto);
        $this->getUserMakerDtoHelper()->constructUserMakerDtoHelper($configDto->mode, $this->customFieldManager);
        return $this;
    }

    public function enableCustomFieldValidation(bool $enabled): static
    {
        $this->needValidateCustomFields = $enabled;
        return $this;
    }

    /**
     * Validate data
     * @return bool
     */
    public function validate(): bool
    {
        $inputDto = $this->getUserMakerDtoHelper()->prepareValues($this->getInputDto(), $this->getConfigDto());
        $this->setInputDto($inputDto);
        $this->setUserId(Cast::toInt($inputDto->id));
        $configDto = $this->getConfigDto();

        if (
            !$configDto->mode->isWebAdmin()
            && !$configDto->mode->isWebResponsive()
            && !$configDto->mode->isSsoResponsive()
        ) {
            $this->addTagNamesToErrorMessages();
        }
        if (
            $configDto->mode->isWebResponsive()
            || $configDto->mode->isSsoResponsive()
        ) {
            $this->translateErrorMessages();
        }
        $this->validateForRequiredData();
        $this->processMainValidation();
        $this->log();

        $isValid = $this->needValidateCustomFields ? empty($this->errors) && empty($this->customFieldsErrors) : empty($this->errors);
        $configDto->enableValidStatus($isValid);
        return $isValid;
    }

    protected function validateForRequiredData(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if ($configDto->mode->isWebResponsive()) {
            $sm = $this->getSettingsManager();
            $userSettingChecker = $this->createUserSettingChecker();
            $isEnableUserCompany = (bool)$sm->getForMain(Constants\Setting::ENABLE_USER_COMPANY);
            $isMandatoryAchInfo = $userSettingChecker->isMandatoryAchInfo();
            $isMandatoryBasicInfo = $userSettingChecker->isMandatoryBasicInfo();
            $isMandatoryBillingInfo = $userSettingChecker->isMandatoryBillingInfo();
            $isMandatoryCcInfo = $userSettingChecker->isMandatoryCcInfo();
            $isProfileBillingOptional = $userSettingChecker->isProfileBillingOptional();
            $isProfileShippingOptional = $userSettingChecker->isProfileShippingOptional();
            $isRegistrationRequireCc = (bool)$sm->getForMain(Constants\Setting::REGISTRATION_REQUIRE_CC);
            $isRequireIdentification = (bool)$sm->getForMain(Constants\Setting::REQUIRE_IDENTIFICATION);
            $isSimplifiedSignup = $userSettingChecker->isSimplifiedSignup();
            $isVerifyEmail = (bool)$sm->getForMain(Constants\Setting::VERIFY_EMAIL);

            if (
                !$isSimplifiedSignup
                && !$isProfileShippingOptional
                && !isset($inputDto->isSignupAccountAdmin)
            ) {
                $this->checkRequired('shippingAddress', ResultCode::SHIPPING_ADDRESS_REQUIRED);
                $this->checkRequired('shippingCity', ResultCode::SHIPPING_CITY_REQUIRED);
                $this->checkRequired('shippingContactType', ResultCode::SHIPPING_CONTACT_TYPE_REQUIRED);
                $this->checkRequired('shippingCountry', ResultCode::SHIPPING_COUNTRY_REQUIRED);
                $this->checkRequired('shippingFirstName', ResultCode::SHIPPING_FIRST_NAME_REQUIRED);
                $this->checkRequired('shippingLastName', ResultCode::SHIPPING_LAST_NAME_REQUIRED);
                $this->checkRequired('shippingPhone', ResultCode::SHIPPING_PHONE_REQUIRED);
                $this->checkRequired('shippingState', ResultCode::SHIPPING_STATE_REQUIRED);
                $this->checkRequired('shippingZip', ResultCode::SHIPPING_ZIP_REQUIRED);
            }

            // TODO: add validateForRequiredBasicData, validateForRequiredBillingData, validateForRequiredShippingData in SAM-9317
            if ($configDto->isConfirmPage) {
                return;
            }

            $this->checkRequired('email', ResultCode::EMAIL_REQUIRED);
            $this->checkRequired('firstName', ResultCode::FIRST_NAME_REQUIRED);
            $this->checkRequired('lastName', ResultCode::LAST_NAME_REQUIRED);
            $this->checkRequired('phone', ResultCode::PHONE_REQUIRED);
            $this->checkRequired('phoneType', ResultCode::PHONE_TYPE_REQUIRED);
            if (!$inputDto->id) {
                $this->checkRequired('pword', ResultCode::PASSWORD_REQUIRED);
                $this->checkRequired('pwordConfirmation', ResultCode::PASSWORD_CONFIRM_REQUIRED);
                if ($isVerifyEmail) {
                    $this->checkRequired('emailConfirmation', ResultCode::EMAIL_CONFIRM_REQUIRED);
                    $this->checkSame('emailConfirmation', ResultCode::EMAIL_CONFIRM_NOT_MATCH, 'email');
                }
            }

            if ((
                    $isSimplifiedSignup
                    && $isMandatoryBillingInfo
                ) || (
                    !$isSimplifiedSignup
                    && !$isProfileBillingOptional
                )
            ) {
                $this->checkRequired('billingAddress', ResultCode::BILLING_ADDRESS_REQUIRED);
                $this->checkRequired('billingCity', ResultCode::BILLING_CITY_REQUIRED);
                $this->checkRequired('billingCountry', ResultCode::BILLING_COUNTRY_REQUIRED);
                $this->checkRequired('billingState', ResultCode::BILLING_STATE_REQUIRED);
                $this->checkRequired('billingZip', ResultCode::BILLING_ZIP_REQUIRED);
            }

            if ((
                    $isSimplifiedSignup
                    && $isMandatoryBasicInfo
                ) || (
                    !$isSimplifiedSignup
                    && !$isProfileBillingOptional
                )
            ) {
                $this->checkRequired('billingContactType', ResultCode::BILLING_CONTACT_TYPE_REQUIRED);
                $this->checkRequired('billingFirstName', ResultCode::BILLING_FIRST_NAME_REQUIRED);
                $this->checkRequired('billingLastName', ResultCode::BILLING_LAST_NAME_REQUIRED);
                $this->checkRequired('billingPhone', ResultCode::BILLING_PHONE_REQUIRED);
            }

            if ($isEnableUserCompany) {
                $this->checkRequired('companyName', ResultCode::COMPANY_NAME_REQUIRED);
            }
            if ($isMandatoryAchInfo) {
                $this->checkRequired('billingBankAccountName', ResultCode::BILLING_BANK_ACCOUNT_NAME_REQUIRED);
                $this->checkRequired('billingBankAccountNumber', ResultCode::BILLING_BANK_ACCOUNT_NUMBER_REQUIRED);
                $this->checkRequired('billingBankAccountType', ResultCode::BILLING_BANK_ACCOUNT_TYPE_REQUIRED);
                $this->checkRequired('billingBankName', ResultCode::BILLING_BANK_NAME_REQUIRED);
                $this->checkRequired('billingBankRoutingNumber', ResultCode::BILLING_BANK_ROUTING_NUMBER_REQUIRED);
            }

            if ((
                    $isSimplifiedSignup
                    && (
                        $isMandatoryCcInfo
                        || $isRegistrationRequireCc
                    )
                ) || (
                    !$isSimplifiedSignup
                    && !$isProfileBillingOptional
                    && $isRegistrationRequireCc
                ) || (
                    $inputDto->billingCcCode
                    || $inputDto->billingCcExpDate
                    || $inputDto->billingCcNumber
                    || $inputDto->billingCcType
                )
            ) {
                if (!$this->checkCreditCardInfoExistAndNotAssigned()) {
                    $this->checkRequired('billingCcCode', ResultCode::BILLING_CC_CODE_REQUIRED);
                    $this->checkRequired('billingCcExpDate', ResultCode::BILLING_CC_EXP_DATE_REQUIRED);
                    $this->checkRequired('billingCcNumber', ResultCode::BILLING_CC_NUMBER_REQUIRED);
                    $this->checkRequired('billingCcType', ResultCode::BILLING_CC_TYPE_REQUIRED);
                }
            }
            if ($isRequireIdentification) {
                $this->checkRequired('identification', ResultCode::IDENTIFICATION_REQUIRED);
                $this->checkRequired('identificationType', ResultCode::IDENTIFICATION_TYPE_REQUIRED);
            }
        }
        $this->checkRequired('username', ResultCode::USERNAME_REQUIRED);
        if (ValueResolver::new()->isTrue($inputDto->usePermanentBidderno)) {
            $this->checkRequired('customerNo', ResultCode::CUSTOMER_NO_REQUIRED);
        }
    }

    protected function processMainValidation(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();

        $billingTypes = array_merge(
            Constants\BillingBank::ACCOUNT_TYPES,
            array_values(Constants\BillingBank::ACCOUNT_TYPE_SOAP_VALUES),
            array_values(Constants\BillingBank::ACCOUNT_TYPE_NAMES),
        );
        $billingContactTypes = Constants\User::CONTACT_TYPE_NAMES;
        $billingCountryCode = AddressRenderer::new()->normalizeCountry($inputDto->billingCountry);
        $identificationTypes = Constants\User::IDENTIFICATION_TYPES;
        $phoneTypes = Constants\User::PHONE_TYPES;
        $range = Constants\BuyersPremium::$rangeCalculationNames;
        $shippingContactTypes = Constants\User::CONTACT_TYPE_NAMES;
        $shippingCountryCode = AddressRenderer::new()->normalizeCountry($inputDto->shippingCountry);
        $timezones = $this->getApplicationTimezoneProvider()->getAvailableTimezoneLocations();

        if (!$this->checkDirectAccount()) {
            // Don't continue other validations, because some of them are based on input account
            return;
        }

        if (
            !$configDto->mode->isWebAdmin()
            && !$configDto->mode->isSsoResponsive()
        ) {
            $this->checkCreditCardExpiryDate();
            $this->checkPhoneNumber('billingFax', ResultCode::BILLING_FAX_INVALID);
            $this->checkPhoneNumber('billingPhone', ResultCode::BILLING_PHONE_INVALID);
            $this->checkPhoneNumber('phone', ResultCode::PHONE_INVALID);
            $this->checkPhoneNumber('shippingFax', ResultCode::SHIPPING_FAX_INVALID);
            $this->checkPhoneNumber('shippingPhone', ResultCode::SHIPPING_PHONE_INVALID);
        }

        // Bidder
        $this->checkReal('additionalBpInternetHybrid', ResultCode::ADDITIONAL_BP_INTERNET_HYBRID_INVALID, true);
        $this->checkReal('additionalBpInternetLive', ResultCode::ADDITIONAL_BP_INTERNET_LIVE_INVALID, true);
        $this->checkInArrayKeys('bpRangeCalculationHybrid', ResultCode::BP_RANGE_CALCULATION_HYBRID_UNKNOWN, $range);
        $this->checkInArrayKeys('bpRangeCalculationLive', ResultCode::BP_RANGE_CALCULATION_LIVE_UNKNOWN, $range);
        $this->checkInArrayKeys('bpRangeCalculationTimed', ResultCode::BP_RANGE_CALCULATION_TIMED_UNKNOWN, $range);
        $this->checkBuyersPremiumShortName();

        // Consignor
        $this->checkReal('consignorSalesTax', ResultCode::CONSIGNOR_SALES_TAX_INVALID, true);
        $this->checkReal('consignorTax', ResultCode::CONSIGNOR_TAX_INVALID, true);

        // User
        $this->checkInArray('flag', ResultCode::FLAG_UNKNOWN, Constants\User::FLAG_SOAP_VALUES);
        $this->checkInArray('userStatus', ResultCode::USER_STATUS_UNKNOWN, Constants\User::USER_STATUS_NAMES);
        $this->checkInArrayKeys('userStatusId', ResultCode::USER_STATUS_ID_UNKNOWN, Constants\User::USER_STATUS_NAMES);
        $this->checkEmail('email', ResultCode::EMAIL_INVALID);
        $this->checkExistUserId('agent', ResultCode::AGENT_NOT_FOUND);
        $this->checkLessThan('customerNo', ResultCode::CUSTOMER_NO_HIGHER_MAX_AVAILABLE_VALUE, $this->cfg()->get('core->db->mysqlMaxInt'));
        $this->checkInteger('customerNo', ResultCode::CUSTOMER_NO_INVALID);
        $this->checkNotExistUserCustomerNo('customerNo', ResultCode::CUSTOMER_NO_EXIST);
        $this->checkNotExistUserEmail('email', ResultCode::EMAIL_EXIST);
        $this->checkNotExistUserName('username', ResultCode::USERNAME_EXIST);
        $this->checkPassword('pword', ResultCode::PASSWORD_INVALID);
        $this->checkPasswordConfirmed('pword', ResultCode::PASSWORD_NOT_MATCH);
        $this->checkUsername('username', ResultCode::USERNAME_INVALID);
        if (ValueResolver::new()->isTrue($inputDto->usePermanentBidderno)) {
            $this->checkNotExistUserCustomerNoAsBidderNo('customerNo', ResultCode::CUSTOMER_NO_AS_BIDDER_NO_EXIST);
        }

        // User Billing
        if (AddressChecker::new()->isCountryWithStates($billingCountryCode)) {
            $this->checkState('billingCountry', 'billingState', ResultCode::BILLING_STATE_UNKNOWN);
        }
        $this->checkInArray('billingBankAccountType', ResultCode::BILLING_BANK_ACCOUNT_TYPE_UNKNOWN, $billingTypes);
        $this->checkInArrayKeys('billingContactType', ResultCode::BILLING_CONTACT_TYPE_UNKNOWN, $billingContactTypes);
        $this->checkCountry('billingCountry', ResultCode::BILLING_COUNTRY_UNKNOWN);
        $this->checkCreditCardCcNumber();
        $this->checkCreditCardCcNumberHash('billingCcNumberHash', ResultCode::BILLING_CC_NUMBER_HASH_INVALID);
        $this->checkEmail('billingEmail', ResultCode::BILLING_EMAIL_INVALID);
        $this->checkExistCreditCardName('billingCcType', ResultCode::BILLING_CC_TYPE_NOT_FOUND);
        $this->checkNotExistUserCcHash('billingCcNumberHash', ResultCode::BILLING_CC_NUMBER_HASH_EXIST);
        $this->checkNotExistUserNmiVaultId('nmiVaultId', ResultCode::NMI_VAULT_ID_EXIST);
        $this->checkNotExistUserPayTraceCustId('payTraceCustId', ResultCode::PAY_TRACE_CUST_ID_EXIST);
        $this->checkSyncKeyUnique('syncKey', ResultCode::SYNC_KEY_EXIST, Constants\EntitySync::TYPE_USER, $this->userDirectAccountId());

        // User Info
        $this->checkInArray('identificationType', ResultCode::IDENTIFICATION_TYPE_UNKNOWN, $identificationTypes);
        $this->checkInArray('phoneType', ResultCode::PHONE_TYPE_UNKNOWN, $phoneTypes);
        $this->checkInArray('taxApplicationName', ResultCode::TAX_APPLICATION_INVALID, Constants\User::TAX_APPLICATION_NAMES);
        $this->checkBetween('taxApplication', ResultCode::TAX_APPLICATION_INVALID, 1, 4);
        $this->checkDate('regAuthDate', ResultCode::REG_AUTH_DATE_INVALID);
        $this->checkLocation();
        $this->createLocationValidationIntegrator()->validate(
            $this,
            $inputDto->specificLocation,
            ResultCode::SPECIFIC_LOCATION_INVALID,
            Constants\Location::TYPE_USER,
            $this->userDirectAccountId()
        );
        $this->checkProhibits('specificLocation', ['location', 'locationId'], ResultCode::SPECIFIC_LOCATION_REDUNDANT);
        $this->checkExistViewLanguageId();
        $this->checkExistViewLanguageName();
        $this->checkReal('maxOutstanding', ResultCode::MAX_OUTSTANDING_INVALID, true);
        $this->checkReal('salesTax', ResultCode::SALES_TAX_INVALID, true);
        $this->checkUrl('referrer', ResultCode::REFERRER_INVALID);
        $this->checkInArray('timezone', ResultCode::TIMEZONE_INVALID, $timezones);
        // User Shipping
        if (AddressChecker::new()->isCountryWithStates($shippingCountryCode)) {
            $this->checkState('shippingCountry', 'shippingState', ResultCode::SHIPPING_STATE_UNKNOWN);
        }
        $this->checkInArrayKeys('shippingContactType', ResultCode::SHIPPING_CONTACT_TYPE_UNKNOWN, $shippingContactTypes);
        $this->checkCountry('shippingCountry', ResultCode::SHIPPING_COUNTRY_UNKNOWN);
        // User Account
        $this->checkCollateralAccount();

        // Check encoding
        $this->checkEncoding('billingAddress', ResultCode::BILLING_ADDRESS_INVALID_ENCODING);
        $this->checkEncoding('billingAddress2', ResultCode::BILLING_ADDRESS2_INVALID_ENCODING);
        $this->checkEncoding('billingAddress3', ResultCode::BILLING_ADDRESS3_INVALID_ENCODING);
        $this->checkEncoding('billingBankAccountName', ResultCode::BILLING_BANK_ACCOUNT_NAME_INVALID_ENCODING);
        $this->checkEncoding('billingBankAccountNumber', ResultCode::BILLING_BANK_ACCOUNT_NUMBER_INVALID_ENCODING);
        $this->checkEncoding('billingBankAccountType', ResultCode::BILLING_BANK_ACCOUNT_TYPE_INVALID_ENCODING);
        $this->checkEncoding('billingBankName', ResultCode::BILLING_BANK_NAME_INVALID_ENCODING);
        $this->checkEncoding('billingBankRoutingNumber', ResultCode::BILLING_BANK_ROUTING_NUMBER_INVALID_ENCODING);
        $this->checkEncoding('billingCcExpDate', ResultCode::BILLING_CC_EXP_DATE_INVALID_ENCODING);
        $this->checkEncoding('billingCcNumber', ResultCode::BILLING_CC_NUMBER_INVALID_ENCODING);
        $this->checkEncoding('billingCcType', ResultCode::BILLING_CC_TYPE_INVALID_ENCODING);
        $this->checkEncoding('billingCity', ResultCode::BILLING_CITY_INVALID_ENCODING);
        $this->checkEncoding('billingCompanyName', ResultCode::BILLING_COMPANY_NAME_INVALID_ENCODING);
        $this->checkEncoding('billingContactType', ResultCode::BILLING_CONTACT_TYPE_INVALID_ENCODING);
        $this->checkEncoding('billingCountry', ResultCode::BILLING_COUNTRY_INVALID_ENCODING);
        $this->checkEncoding('billingFirstName', ResultCode::BILLING_FIRST_NAME_INVALID_ENCODING);
        $this->checkEncoding('billingLastName', ResultCode::BILLING_LAST_NAME_INVALID_ENCODING);
        $this->checkEncoding('billingState', ResultCode::BILLING_STATE_INVALID_ENCODING);
        $this->checkEncoding('companyName', ResultCode::COMPANY_NAME_INVALID_ENCODING);
        $this->checkEncoding('consignorPaymentInfo', ResultCode::CONSIGNOR_PAYMENT_INFO_INVALID_ENCODING);
        $this->checkEncoding('firstName', ResultCode::FIRST_NAME_INVALID_ENCODING);
        $this->checkEncoding('lastName', ResultCode::LAST_NAME_INVALID_ENCODING);
        $this->checkEncoding('location', ResultCode::LOCATION_INVALID_ENCODING);
        $this->checkEncoding('note', ResultCode::NOTE_INVALID_ENCODING);
        $this->checkEncoding('phone', ResultCode::PHONE_INVALID_ENCODING);
        $this->checkEncoding('shippingAddress', ResultCode::SHIPPING_ADDRESS_INVALID_ENCODING);
        $this->checkEncoding('shippingAddress2', ResultCode::SHIPPING_ADDRESS2_INVALID_ENCODING);
        $this->checkEncoding('shippingAddress3', ResultCode::SHIPPING_ADDRESS3_INVALID_ENCODING);
        $this->checkEncoding('shippingCity', ResultCode::SHIPPING_CITY_INVALID_ENCODING);
        $this->checkEncoding('shippingCompanyName', ResultCode::SHIPPING_COMPANY_NAME_INVALID_ENCODING);
        $this->checkEncoding('shippingContactType', ResultCode::SHIPPING_CONTACT_TYPE_INVALID_ENCODING);
        $this->checkEncoding('shippingCountry', ResultCode::SHIPPING_COUNTRY_INVALID_ENCODING);
        $this->checkEncoding('shippingFirstName', ResultCode::SHIPPING_FIRST_NAME_INVALID_ENCODING);
        $this->checkEncoding('shippingLastName', ResultCode::SHIPPING_LAST_NAME_INVALID_ENCODING);
        $this->checkEncoding('shippingState', ResultCode::SHIPPING_STATE_INVALID_ENCODING);
        $this->checkEncoding('taxApplicationName', ResultCode::TAX_APPLICATION_INVALID_ENCODING);
        $this->checkEncoding('username', ResultCode::USERNAME_INVALID_ENCODING);

        $this->validateBuyersPremium();
        $this->validateConsignorCommission();
        $this->validateConsignorSoldFee();
        $this->validateConsignorUnsoldFee();
        $this->validateSalesCommissions();
        $this->validatePrivileges();
        if ($this->needValidateCustomFields) {
            $this->validateCustomFields();
        }

        if (
            !count($this->getErrors())
            && (
                $configDto->mode->isWebResponsive()
                || $configDto->mode->isCsv()
                || $configDto->mode->isSoap()
            )
        ) {
            $this->validateCcInfoViaPaymentGateway();
        }
    }

    protected function validateBuyersPremium(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $errorCodes = [
            Constants\Auction::TIMED => ResultCode::BUYERS_PREMIUMS_TIMED_VALIDATION_FAILED,
            Constants\Auction::LIVE => ResultCode::BUYERS_PREMIUMS_LIVE_VALIDATION_FAILED,
            Constants\Auction::HYBRID => ResultCode::BUYERS_PREMIUMS_HYBRID_VALIDATION_FAILED,
        ];
        $userDirectAccountId = $this->userDirectAccountId();
        foreach ($errorCodes as $auctionType => $errorCode) {
            $input = BuyersPremiumValidationInput::new()->fromUserMakerDto($inputDto, $configDto, $auctionType, $userDirectAccountId);
            $this->createBuyersPremiumValidationIntegrator()->validate($this, $input, $errorCode);
        }

        if (
            $inputDto->bpRule
            && (!$this->isIndividualBuyersPremiumEmpty(
                    $inputDto->buyersPremiumTimedString,
                    $inputDto->buyersPremiumTimedDataRows
                )
                || !$this->isIndividualBuyersPremiumEmpty(
                    $inputDto->buyersPremiumLiveString,
                    $inputDto->buyersPremiumLiveDataRows,
                    $inputDto->additionalBpInternetLive
                )
                || !$this->isIndividualBuyersPremiumEmpty(
                    $inputDto->buyersPremiumHybridString,
                    $inputDto->buyersPremiumHybridDataRows,
                    $inputDto->additionalBpInternetHybrid
                )
            )
        ) {
            $this->addError(ResultCode::BP_RULE_WITH_INDIVIDUAL_BP_CAN_NOT_BE_ASSIGNED_TOGETHER);
        }
    }

    protected function isIndividualBuyersPremiumEmpty(
        mixed $buyersPremiumString,
        mixed $buyersPremiumDataRows,
        mixed $additionalBpInternet = null
    ): bool {
        $configDto = $this->getConfigDto();
        if ($additionalBpInternet) {
            return false;
        }

        if ($configDto->mode->isCsv()) {
            $csvParser = $this->createBuyersPremiumCsvParser();
            $isSyntaxCorrect = $csvParser->validateSyntax((string)$buyersPremiumString);
            $buyersPremiumDataRows = $isSyntaxCorrect
                ? $csvParser->parse((string)$buyersPremiumString, $configDto->serviceAccountId)
                : [];
        } else {
            $buyersPremiumDataRows = (array)$buyersPremiumDataRows;
        }

        return count($buyersPremiumDataRows) === 0;
    }

    /**
     * Validate a credit card using active payment gateway
     */
    protected function validateCcInfoViaPaymentGateway(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();

        if (
            !$inputDto->billingCcNumber
            || $configDto->isSignupPage
        ) {
            // Skip CC verification, when it is absent and for signup page.
            return;
        }

        if (!$this->getUserMakerAccessChecker()->willTargetUserHaveBidderRole()) {
            // Skip CC verification, when user doesn't have bidder role or it will be unset.
            return;
        }

        $noAutoAuthorization = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::NO_AUTO_AUTHORIZATION);
        if ($noAutoAuthorization) {
            // Skip CC verification, when "No auto authorization" setting is enabled for account of service context.
            return;
        }

        /**
         * Payment gateway service is initialized by main account,
         * because we perform CC info verification procedure, not a payment transaction.
         */
        $paymentGateway = PaymentGatewayFactory::new()->getActivePaymentGateway($this->mainAccountId());
        if (!$paymentGateway) {
            // Skip CC verification, when Payment Gateway is not configured.
            return;
        }

        $isEwayEnabled = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::CC_PAYMENT_EWAY);
        $ewayEncryptionKey = $this->getSettingsManager()->getForMain(Constants\Setting::EWAY_ENCRYPTION_KEY);
        $isEwayPaymentGateway = $paymentGateway instanceof Payment_Eway;
        if (
            $isEwayEnabled
            && $ewayEncryptionKey
            && $isEwayPaymentGateway
        ) {
            $ccNumber = $inputDto->ccNumberEway;
        } else {
            $ccNumber = $inputDto->billingCcNumber;
        }

        $paymentGateway->validate(
            [
                'address' => $inputDto->billingAddress,
                'ccNumber' => $ccNumber,
                'ccNumberHash' => $inputDto->billingCcNumberHash,
                'ccType' => $inputDto->billingCcType,
                'city' => $inputDto->billingCity,
                'country' => AddressRenderer::new()->normalizeCountry($inputDto->billingCountry),
                'email' => $inputDto->billingEmail,
                'expiration' => $inputDto->billingCcExpDate,
                'firstName' => $inputDto->firstName,
                'id' => $inputDto->id,
                'lastName' => $inputDto->lastName,
                'phone' => $inputDto->billingPhone,
                'securityCode' => $inputDto->billingCcCode,
                'state' => AddressRenderer::new()->normalizeState($inputDto->billingCountry, $inputDto->billingState),
                'zip' => $inputDto->billingZip,
            ]
        );

        if (
            $paymentGateway->isError()
            || $paymentGateway->isDeclined()
        ) {
            $this->addError(ResultCode::BILLING_CC_NUMBER_INVALID, $paymentGateway->getErrorSummary(true));
        }

        $paymentGateway->voidLastTransaction();
    }

    /**
     * Validate privileges assignment for all user roles (admin, bidder, consignor)
     */
    protected function validatePrivileges(): void
    {
        $this->createPrivilegeValidationIntegrator()->validate($this, $this->userDirectAccountId());
    }

    /**
     * Do the customFields fields have an error
     * @return bool
     */
    public function hasCustomFieldsErrors(): bool
    {
        $has = !empty($this->getCustomFieldsErrors());
        return $has;
    }

    /** GetErrors Methods */

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getAdditionalBpInternetHybridErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::ADDITIONAL_BP_INTERNET_HYBRID_INVALID]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getAdditionalBpInternetLiveErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::ADDITIONAL_BP_INTERNET_LIVE_INVALID]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getAdminErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::ADMIN_NO_SUB_PRIVILEGES_SELECTED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getBillingAddressErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BILLING_ADDRESS_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getBillingBankAccountNameErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BILLING_BANK_ACCOUNT_NAME_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getBillingBankAccountNumberErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BILLING_BANK_ACCOUNT_NUMBER_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getBillingBankAccountTypeErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::BILLING_BANK_ACCOUNT_TYPE_REQUIRED,
                ResultCode::BILLING_BANK_ACCOUNT_TYPE_UNKNOWN,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getBillingBankNameErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BILLING_BANK_NAME_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getBillingBankRoutingNumberErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BILLING_BANK_ROUTING_NUMBER_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getBillingCcCodeErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BILLING_CC_CODE_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getBillingCcExpDateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::BILLING_CC_EXP_DATE_INVALID,
                ResultCode::BILLING_CC_EXP_DATE_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getBillingCcNumberErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::BILLING_CC_NUMBER_INVALID,
                ResultCode::BILLING_CC_NUMBER_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getBillingCcTypeErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::BILLING_CC_TYPE_NOT_FOUND,
                ResultCode::BILLING_CC_TYPE_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getBillingEmailErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BILLING_EMAIL_INVALID]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getBillingCityErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BILLING_CITY_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getBillingContactTypeErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::BILLING_CONTACT_TYPE_REQUIRED,
                ResultCode::BILLING_CONTACT_TYPE_UNKNOWN,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getBillingCountryErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::BILLING_COUNTRY_REQUIRED,
                ResultCode::BILLING_COUNTRY_UNKNOWN,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getBillingFaxErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BILLING_FAX_INVALID]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getBillingFirstNameErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BILLING_FIRST_NAME_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getBillingLastNameErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BILLING_LAST_NAME_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getBillingPhoneErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::BILLING_PHONE_INVALID,
                ResultCode::BILLING_PHONE_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getBillingStateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BILLING_STATE_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getBillingZipErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BILLING_ZIP_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getBuyersPremiumsHybridErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BUYERS_PREMIUMS_HYBRID_VALIDATION_FAILED]
        );
        return $intersected;
    }

    /**
     * @return BuyersPremiumRangesValidationResult|null
     */
    public function getBuyersPremiumsHybridErrorPayload(): ?BuyersPremiumRangesValidationResult
    {
        return $this->getErrorPayload(ResultCode::BUYERS_PREMIUMS_HYBRID_VALIDATION_FAILED);
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getBuyersPremiumsLiveErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BUYERS_PREMIUMS_LIVE_VALIDATION_FAILED]
        );
        return $intersected;
    }

    /**
     * @return BuyersPremiumRangesValidationResult|null
     */
    public function getBuyersPremiumsLiveErrorPayload(): ?BuyersPremiumRangesValidationResult
    {
        return $this->getErrorPayload(ResultCode::BUYERS_PREMIUMS_LIVE_VALIDATION_FAILED);
    }

    /**
     * @return int[]
     */
    public function getBuyersPremiumsTimedErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::BUYERS_PREMIUMS_TIMED_VALIDATION_FAILED]
        );
        return $intersected;
    }

    /**
     * @return BuyersPremiumRangesValidationResult|null
     */
    public function getBuyersPremiumsTimedErrorPayload(): ?BuyersPremiumRangesValidationResult
    {
        return $this->getErrorPayload(ResultCode::BUYERS_PREMIUMS_TIMED_VALIDATION_FAILED);
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getCompanyNameErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::COMPANY_NAME_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorSalesTaxErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::CONSIGNOR_SALES_TAX_INVALID]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorTaxErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::CONSIGNOR_TAX_INVALID]
        );
        return $intersected;
    }

    /**
     * Get customFields errors
     * @return array
     */
    public function getCustomFieldsErrors(): array
    {
        return $this->customFieldsErrors;
    }

    /**
     * @return int[]
     */
    public function getCustomerNoErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::CUSTOMER_NO_AS_BIDDER_NO_EXIST,
                ResultCode::CUSTOMER_NO_EXIST,
                ResultCode::CUSTOMER_NO_HIGHER_MAX_AVAILABLE_VALUE,
                ResultCode::CUSTOMER_NO_INVALID,
                ResultCode::CUSTOMER_NO_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getEmailErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::EMAIL_EXIST,
                ResultCode::EMAIL_INVALID,
                ResultCode::EMAIL_REQUIRED
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getEmailConfirmationErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::EMAIL_CONFIRM_NOT_MATCH,
                ResultCode::EMAIL_CONFIRM_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getFirstNameErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::FIRST_NAME_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getIdentificationErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::IDENTIFICATION_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getIdentificationTypeErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::IDENTIFICATION_TYPE_REQUIRED,
                ResultCode::IDENTIFICATION_TYPE_UNKNOWN,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getLastNameErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::LAST_NAME_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getMaxOutstandingErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::MAX_OUTSTANDING_INVALID]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getPasswordErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::PASSWORD_INVALID,
                ResultCode::PASSWORD_NOT_MATCH,
                ResultCode::PASSWORD_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getPasswordConfirmErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::PASSWORD_CONFIRM_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getPayTraceCustIdErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::PAY_TRACE_CUST_ID_EXIST]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getPhoneErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::PHONE_INVALID,
                ResultCode::PHONE_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getPhoneTypeErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::PHONE_TYPE_REQUIRED,
                ResultCode::PHONE_TYPE_UNKNOWN,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getReferrerErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::REFERRER_INVALID]
        );
        return $intersected;
    }

    /** --------------------------------------
     * Start "Consignor Commission & Fee" related logic
     */
    protected function validateConsignorCommission(): void
    {
        $inputDto = $this->getInputDto();
        $this->checkConsignorCommissionFeeId('consignorCommissionId', ResultCode::CONSIGNOR_COMMISSION_ID_INVALID);
        $consignorCommissionId = $inputDto->consignorCommissionId;
        if (!$consignorCommissionId) {
            $this->checkConsignorCommissionFeeRanges('consignorCommissionRanges', ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID);
            $this->checkConsignorCommissionFeeCalculationMethod('consignorCommissionCalculationMethod', ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID);
        }
    }

    protected function validateConsignorSoldFee(): void
    {
        $inputDto = $this->getInputDto();
        $this->checkConsignorCommissionFeeId('consignorSoldFeeId', ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID);
        $consignorSoldFeeId = $inputDto->consignorSoldFeeId;
        if (!$consignorSoldFeeId) {
            $this->checkConsignorCommissionFeeRanges('consignorSoldFeeRanges', ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID);
            $this->checkConsignorCommissionFeeCalculationMethod('consignorSoldFeeCalculationMethod', ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID);
            $this->checkConsignorCommissionFeeReference('consignorSoldFeeReference', ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_INVALID);
        }
    }

    protected function validateConsignorUnsoldFee(): void
    {
        $inputDto = $this->getInputDto();
        $this->checkConsignorCommissionFeeId('consignorUnsoldFeeId', ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID);
        $consignorUnsoldFeeId = $inputDto->consignorUnsoldFeeId;
        if (!$consignorUnsoldFeeId) {
            $this->checkConsignorCommissionFeeRanges('consignorUnsoldFeeRanges', ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID);
            $this->checkConsignorCommissionFeeCalculationMethod('consignorUnsoldFeeCalculationMethod', ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID);
            $this->checkConsignorCommissionFeeReference('consignorUnsoldFeeReference', ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID);
        }
    }

    /**
     *
     * @return bool
     */
    protected function checkCreditCardInfoExistAndNotAssigned(): bool
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->id) {
            return false;
        }
        if (
            isset($inputDto->billingCcNumber)
            || isset($inputDto->billingCcType)
            || isset($inputDto->billingCcExpDate)
        ) {
            return false;
        }

        $userBilling = $this->getUserLoader()->loadUserBilling(Cast::toInt($inputDto->id));
        return $userBilling
            && $userBilling->CcExpDate
            && $userBilling->CcNumber
            && $userBilling->CcType;
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkConsignorCommissionFeeId(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $consignorCommissionId = $inputDto->$field;
        if ($consignorCommissionId) {
            $accountId = $inputDto->consignorCommissionFeeAccountId ?: $this->entityContextAccountId();
            $isExist = $this->createConsignorCommissionFeeExistenceChecker()->existByIdAndAccountId((int)$consignorCommissionId, $accountId);
            if (!$isExist) {
                $this->addError($error);
            }
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkConsignorCommissionFeeCalculationMethod(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $calculationMethod = $inputDto->$field;
        if ($calculationMethod) {
            $isValidCalculationMethod = $this->createConsignorCommissionFeeValidator()->isValidCalculationMethod($calculationMethod);
            if (!$isValidCalculationMethod) {
                $this->addError($error);
            }
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkConsignorCommissionFeeReference(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $feeReference = $inputDto->$field;
        if ($feeReference) {
            $isValidFeeReference = $this->createConsignorCommissionFeeValidator()->isValidFeeReference($feeReference);
            if (!$isValidFeeReference) {
                $this->addError($error);
            }
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkConsignorCommissionFeeRanges(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $consignorCommissionRanges = $inputDto->$field;
        if ($consignorCommissionRanges !== null) {
            $collection = $this->createConsignorCommissionFeeRangeDtoConverter()->fromRanges($consignorCommissionRanges);
            $rangesValidator = $this->createConsignorCommissionFeeRangesValidator();
            $validationResult = $rangesValidator->validate($collection, $configDto->mode);
            if ($validationResult->hasError()) {
                $rangeErrors = $validationResult->getErrors();
                $this->addError(
                    $error,
                    $this->buildConsignorCommissionFeeRangeErrorMessage($rangeErrors)
                );
            }
        }
    }

    /**
     * Create error message with detailed info that elaborates message in CSV and SOAP outputs.
     * @param RangeValidationResultStatus[] $rangeErrors
     * @return string
     */
    protected function buildConsignorCommissionFeeRangeErrorMessage(array $rangeErrors): string
    {
        $configDto = $this->getConfigDto();
        $glue = $configDto->mode->isCsv() ? '<br />' : "\n";
        $errorLines[] = $this->getErrorMessage(ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID);

        if ($configDto->mode->isCsv() || $configDto->mode->isSoap()) {
            foreach ($rangeErrors as $error) {
                $errorLines[] = $error->getMessage() . composeSuffix(['Row#' => $error->rangeIndex, 'Property' => $error->property]);
            }
        }
        $message = implode($glue, $errorLines);
        return $message;
    }

    /**
     * @return int[]
     */
    public function getConsignorCommissionIdErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_COMMISSION_ID_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorCommissionCalculationMethodErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorCommissionRangeErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_COMMISSION_RANGE_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorSoldFeeIdErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_SOLD_FEE_ID_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorSoldFeeCalculationMethodErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorSoldFeeRangeErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_SOLD_FEE_RANGE_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorSoldFeeReferenceErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_SOLD_FEE_REFERENCE_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorUnsoldFeeIdErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_UNSOLD_FEE_ID_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorUnsoldFeeCalculationMethodErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorUnsoldFeeRangeErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_UNSOLD_FEE_RANGE_INVALID]);
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getConsignorUnsoldFeeReferenceErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID]);
        return $intersected;
    }

    /** --------------------------------------
     * Start "Sales Commissions" related logic
     */

    protected function validateSalesCommissions(): void
    {
        $inputDto = $this->getInputDto();
        $input = $inputDto->salesCommissions;
        if (!$input) {
            return;
        }

        $result = $this->createSalesCommissionRangesValidator()->validate($input);
        if ($result->hasError()) {
            $this->addError(
                ResultCode::SALES_COMMISSIONS_VALIDATION_FAILED,
                $this->buildSalesCommissionsErrorMessage($result),
                null,
                $result
            );
        }
    }

    /**
     * @return int[]
     */
    public function getSalesCommissionsErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::SALES_COMMISSIONS_VALIDATION_FAILED]
        );
        return $intersected;
    }

    /**
     * @return SalesCommissionRangesValidationResult|null
     */
    public function getSalesCommissionsErrorPayload(): ?SalesCommissionRangesValidationResult
    {
        return $this->getErrorPayload(ResultCode::SALES_COMMISSIONS_VALIDATION_FAILED);
    }

    /**
     * Create error message with detailed info that elaborates message in CSV and SOAP outputs.
     * @param SalesCommissionRangesValidationResult $result
     * @return string
     */
    protected function buildSalesCommissionsErrorMessage(SalesCommissionRangesValidationResult $result): string
    {
        $mode = $this->getConfigDto()->mode;
        $glue = "\n";
        $errorLines[] = $this->getErrorMessage(ResultCode::SALES_COMMISSIONS_VALIDATION_FAILED);
        if (
            $mode->isSoap()
            || $mode->isCsv()
        ) {
            foreach ($result->errorRowResults() as $rowResult) {
                if ($rowResult->hasFormatError()) {
                    $errorLines[] = $rowResult->getFormatErrorMessage()
                        . composeSuffix(['Row#' => $rowResult->index, 'Values' => $rowResult->row]);
                }
                if ($rowResult->hasStartAmountError()) {
                    $errorLines[] = $rowResult->getStartAmountErrorMessage()
                        . composeSuffix(['Row#' => $rowResult->index, 'Amount' => $rowResult->toStringStartAmount()]);
                }
                if ($rowResult->hasPercentValueError()) {
                    $errorLines[] = $rowResult->getPercentValueErrorMessage()
                        . composeSuffix(['Row#' => $rowResult->index, 'Percent' => $rowResult->toStringPercentValue()]);
                }
            }
            if ($mode->isCsv()) {
                $glue = '<br />';
            }
        }
        $message = implode($glue, $errorLines);
        return $message;
    }

    // --- End: Sales Commissions ------------------------------------------------------

    /**
     * @return int[]
     */
    public function getSalesTaxErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::SALES_TAX_INVALID]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getShippingAddressErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::SHIPPING_ADDRESS_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getShippingCityErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::SHIPPING_CITY_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getShippingContactTypeErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::SHIPPING_CONTACT_TYPE_REQUIRED,
                ResultCode::SHIPPING_CONTACT_TYPE_UNKNOWN,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getShippingCountryErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::SHIPPING_COUNTRY_REQUIRED,
                ResultCode::SHIPPING_COUNTRY_UNKNOWN,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getShippingFaxErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::SHIPPING_FAX_INVALID]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getShippingFirstNameErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::SHIPPING_FIRST_NAME_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getShippingLastNameErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::SHIPPING_LAST_NAME_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getShippingPhoneErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::SHIPPING_PHONE_INVALID,
                ResultCode::SHIPPING_PHONE_REQUIRED,
            ]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getShippingStateErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::SHIPPING_STATE_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     * @noinspection PhpUnused
     */
    public function getShippingZipErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [ResultCode::SHIPPING_ZIP_REQUIRED]
        );
        return $intersected;
    }

    /**
     * @return int[]
     */
    public function getUsernameErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::USERNAME_EXIST,
                ResultCode::USERNAME_INVALID,
                ResultCode::USERNAME_REQUIRED,
            ]
        );
        return $intersected;
    }

    public function getTimezoneErrors(): array
    {
        return [ResultCode::TIMEZONE_INVALID];
    }

    public function getLocationErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::LOCATION_ID_NOT_FOUND,
                ResultCode::LOCATION_INVALID_ENCODING,
                ResultCode::LOCATION_NOT_FOUND,
            ]
        );
        return $intersected;
    }

    /* User validation rules */

    protected function checkBuyersPremiumShortName(): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->bpRule) {
            return;
        }

        $isBpNotDefault = $this->createBuyersPremiumExistenceChecker()
            ->existNotDefault($inputDto->bpRule, $this->userDirectAccountId());
        $this->addErrorIfFail(ResultCode::BP_RULE_NOT_FOUND, $isBpNotDefault);
    }

    protected function checkCollateralAccount(): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->collateralAccountId) {
            return;
        }

        $isAccountById = $this->getAccountExistenceChecker()->existById((int)$inputDto->collateralAccountId);
        $this->addErrorIfFail(ResultCode::COLLATERAL_ACCOUNT_ID_NOT_FOUND, $isAccountById);
    }

    protected function checkCreditCardExpiryDate(): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->billingCcExpDate) {
            return;
        }

        $isValidExpiredDate = $this->getCreditCardValidator()->validateExpiredDateFormatted((string)$inputDto->billingCcExpDate);
        $this->addErrorIfFail(ResultCode::BILLING_CC_EXP_DATE_INVALID, $isValidExpiredDate);
    }

    /**
     * Is dto field value valid credit card number
     */
    protected function checkCreditCardCcNumber(): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->billingCcNumber) {
            return;
        }

        $validator = $this->getCreditCardValidator();
        if (
            $validator->isFullCcNumber((string)$inputDto->billingCcNumber) // Validate if not 4 cc digit number
            && !$validator->validateNumber((string)$inputDto->billingCcNumber, $inputDto->billingCcType)
        ) {
            $this->addError(ResultCode::BILLING_CC_NUMBER_INVALID);
        }
    }

    /**
     * Is dto field value valid credit card number hash
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkCreditCardCcNumberHash(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }
        if (!$this->getCreditCardValidator()->validateHash(
            (string)$inputDto->$field,
            (string)$inputDto->billingCcNumber
        )) {
            $this->addError($error);
        }
    }

    /**
     * Check, if it is allowed to change user's direct account.
     */
    protected function checkDirectAccount(): bool
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $input = DirectAccountChangeValidationInput::new()
            ->fromMakerDto($inputDto, $configDto, $this->getUser());
        $result = $this->createDirectAccountChangeValidator()->validate($input);
        if ($result->hasError()) {
            log_error('Direct account change failed' . composeSuffix($input->logData() + $result->logData()));
            if ($result->hasAccountNotFoundError()) {
                $this->addError(ResultCode::ACCOUNT_ID_NOT_FOUND);
                return false;
            }
            $this->addError(ResultCode::ACCOUNT_NO_RIGHTS);
            return false;
        }
        return true;
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkExistCreditCardName(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }
        $isFound = $this->getCreditCardExistenceChecker()->existByName($inputDto->$field);
        $this->addErrorIfFail($error, $isFound);
    }

    protected function checkExistViewLanguageId(): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->viewLanguageId) {
            return;
        }

        $isViewLanguageById = $this->getViewLanguageExistenceChecker()
            ->existByIdAndAccountId((int)$inputDto->viewLanguageId, $this->userDirectAccountId());
        $this->addErrorIfFail(ResultCode::VIEW_LANGUAGE_NOT_FOUND, $isViewLanguageById);
    }

    protected function checkExistViewLanguageName(): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->viewLanguage) {
            return;
        }

        $isViewLanguageByName = $this->getViewLanguageExistenceChecker()
            ->existByNameAndAccountId($inputDto->viewLanguage, $this->userDirectAccountId());
        $this->addErrorIfFail(ResultCode::VIEW_LANGUAGE_NOT_FOUND, $isViewLanguageByName);
    }

    protected function checkLocation(): void
    {
        $inputDto = $this->getInputDto();

        if ($inputDto->locationId) {
            $isLocationById = $this->getLocationExistenceChecker()->existById((int)$inputDto->locationId);
            $this->addErrorIfFail(ResultCode::LOCATION_ID_NOT_FOUND, $isLocationById);
        }

        if ($inputDto->location) {
            $isLocationByName = $this->getLocationExistenceChecker()
                ->existByName($inputDto->location, $this->userDirectAccountId());
            $this->addErrorIfFail(ResultCode::LOCATION_NOT_FOUND, $isLocationByName);
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkNotExistUserCcHash(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }
        $userIds = $this->getUserLoader()->loadUserIdsByCcHash(
            $inputDto->$field,
            null, // search among all accounts
            null,
            Cast::toInt($inputDto->id)
        );
        if ($userIds) {
            $this->addError($error, $this->errorMessages[$error] . implode(', ', $userIds));
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkNotExistUserCustomerNo(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }
        if ($this->getUserExistenceChecker()->existByCustomerNo((int)$inputDto->$field, [(int)$inputDto->id])) {
            $this->addError($error);
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkNotExistUserCustomerNoAsBidderNo(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }
        $id = Cast::toInt($inputDto->id);
        if ($this->getAuctionBidderChecker()->existBidderNo($inputDto->$field, null, (array)$id)) {
            $this->addError($error);
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkNotExistUserEmail(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }
        if ($this->getUserExistenceChecker()->existByEmail($inputDto->$field, [(int)$inputDto->id])) {
            $this->addError($error);
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkNotExistUserName(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }
        if ($this->getUserExistenceChecker()->existByUsername($inputDto->$field, [(int)$inputDto->id])) {
            $this->addError($error);
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkNotExistUserNmiVaultId(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }
        if ($this->getUserExistenceChecker()->existByNmiVaultId((string)$inputDto->$field, [(int)$inputDto->id])) {
            $this->addError($error);
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkNotExistUserPayTraceCustId(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }
        if ($this->getUserExistenceChecker()->existByPayTraceCustId((string)$inputDto->$field, [(int)$inputDto->id])) {
            $this->addError($error);
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkPassword(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }
        $pwInfo = password_get_info($field);
        if (isset($pwInfo['algo']) && $pwInfo['algo']) {
            // accept valid hashed password
            return;
        }
        $passwordStrength = $this->getPasswordStrengthValidator()->initBySystemParametersOfMainAccount();
        if (!$passwordStrength->validate($inputDto->$field)) {
            $this->addError($error, $passwordStrength->getErrorMessage());
        }
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkPasswordConfirmed(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (
            !$inputDto->$field
            || !isset($inputDto->pwordConfirmation)
        ) {
            return;
        }
        if ($inputDto->$field !== $inputDto->pwordConfirmation) {
            $this->addError($error);
        }
    }

    /**
     * @param string $key
     * @return string
     */
    protected function translate(string $key): string
    {
        return $this->getTranslator()->translate($key, 'user');
    }

    /**
     * Support logging of found errors or success
     */
    protected function log(): void
    {
        $inputDto = $this->getInputDto();
        if (empty($this->errors)) {
            log_debug('User validation done' . composeSuffix(['u' => $inputDto->id]));
        } else {
            // detect names of constants for error codes
            [$foundNamesToCodes, $notFoundCodes] = ConstantNameResolver::new()
                ->construct()
                ->resolveManyFromClass($this->errors, ResultCode::class);
            $foundNamesWithCodes = array_map(
                static function ($v) {
                    return "{$v[1]} ({$v[0]})";
                },
                $foundNamesToCodes
            );
            $logData = [
                'u' => $inputDto->id,
                'errors' => array_merge(array_values($foundNamesWithCodes), $notFoundCodes),
            ];
            log_debug('User validation failed' . composeSuffix($logData));
        }
    }

    /**
     * Detect or get cached direct account of target user.
     */
    protected function userDirectAccountId(): int
    {
        $userDirectAccountId = $this->fetchOptional(self::OP_TARGET_USER_DIRECT_ACCOUNT_ID);
        if ($userDirectAccountId === null) {
            $userDirectAccountId = $this->detectUserDirectAccountId();
            $this->setOptional(self::OP_TARGET_USER_DIRECT_ACCOUNT_ID, $userDirectAccountId);
        }
        return $userDirectAccountId;
    }

    /**
     * Detects user's direct account id for assigning to user.account_id
     */
    protected function detectUserDirectAccountId(): int
    {
        $input = UserDirectAccountDetectionInput::new()
            ->fromMakerDto($this->getInputDto(), $this->getConfigDto());
        $optionals = $this->getUserId()
            ? [UserDirectAccountDetector::OP_TARGET_USER => $this->getUser()]
            : [];
        $detector = $this->getUserDirectAccountDetector()->construct($optionals);
        return $detector->detect($input);
    }

    protected function entityContextAccountId(): int
    {
        return $this->getConfigDto()->serviceAccountId ?? $this->userDirectAccountId();
    }

    protected function mainAccountId(): int
    {
        return $this->cfg()->get('core->portal->mainAccountId');
    }

    protected function initOptionals(array $optionals): void
    {
        $this->setOptionals($optionals);
    }
}
