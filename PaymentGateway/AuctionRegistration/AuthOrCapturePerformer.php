<?php
/**
 * Handles AuthOrCapture operation On Auction Registration
 *
 * SAM-3893: Refactor auction bidder related functionality
 * SAM-3904: Auction bidder registration class
 *
 * @author        Igors Kotlevskis
 * @since         Nov 30, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\PaymentGateway\AuctionRegistration;

use DateInterval;
use Payment_Opayo;
use QMySqli5DatabaseResult;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Billing\AuctionRegistration\AuthAmountDetectorAwareTrait;
use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Billing\CreditCard\Build\CcNumberEncrypterAwareTrait;
use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Billing\CreditCard\Validate\CreditCardValidatorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserBilling\UserBillingWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserInfo\UserInfoWriteRepositoryAwareTrait;
use Sam\User\Info\UserInfoNoteManagerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use User_CIM;
use User_OnAuctionRegistration;

/**
 * Class AuthOrCapturePerformer
 * @package Sam\PaymentGateway\AuctionRegistration
 */
class AuthOrCapturePerformer extends CustomizableClass
{
    use AuctionBidderHelperAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use AuthAmountDetectorAwareTrait;
    use BlockCipherProviderCreateTrait;
    use CcExpiryDateBuilderCreateTrait;
    use CcNumberEncrypterAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CreditCardLoaderAwareTrait;
    use CreditCardValidatorAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use DbConnectionTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UserBillingWriteRepositoryAwareTrait;
    use UserInfoNoteManagerAwareTrait;
    use UserInfoWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * AUTH or CAPTURE amount for user for auction registration
     * Use payment gateway depending on system settings
     *
     * @param int $transactionType User_OnAuctionRegistration::AUTH, User_OnAuctionRegistration::CAPTURE
     * @param float $authAmount
     * @param int $userId user.id
     * @param int $auctionId auction.id
     * @param string $cvv
     * @param int $editorUserId
     * @param string $carrierMethod
     * @return array array('success' => boolean, 'errorMessage' => string, 'response' => string);
     */
    public function doAuthOrCapture(
        int $transactionType,
        float $authAmount,
        int $userId,
        int $auctionId,
        string $cvv,
        int $editorUserId,
        string $carrierMethod = '',
    ): array {
        if (!in_array(
            $transactionType,
            [Constants\Billing::CCV_AUTH, Constants\Billing::CCV_CAPTURE],
            true
        )
        ) {
            return [
                'success' => false,
                'errorMessage' => 'Transaction type invalid',
                'response' => 'Transaction type invalid',
            ];
        }
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            $message = "Available auction not found for AuthOrCapture action" . composeSuffix(['a' => $auctionId]);
            log_error($message);
            return [
                'success' => false,
                'errorMessage' => $message,
                'response' => $message,
            ];
        }
        $auctionAccountId = $auction->AccountId;
        $sm = $this->getSettingsManager();
        $authorizationUse = $sm->get(Constants\Setting::AUTHORIZATION_USE, $auctionAccountId);
        $ewayEncryptionKey = $sm->get(Constants\Setting::EWAY_ENCRYPTION_KEY, $auctionAccountId);
        $isNoAutoAuthorization = (bool)$sm->get(Constants\Setting::NO_AUTO_AUTHORIZATION, $auctionAccountId);
        $isAuthNetCim = (bool)$sm->get(Constants\Setting::AUTH_NET_CIM, $auctionAccountId);
        $isNmiVault = (bool)$sm->get(Constants\Setting::NMI_VAULT, $auctionAccountId);
        $isPayTraceCim = (bool)$sm->get(Constants\Setting::PAY_TRACE_CIM, $auctionAccountId);
        $isOpayoToken = (bool)$sm->get(Constants\Setting::OPAYO_TOKEN, $auctionAccountId);
        $isResult = ['success' => false, 'errorMessage' => '', 'response' => ''];
        if ($isNoAutoAuthorization) {
            log_debug('No auto authorization is enabled for account' . composeSuffix(['acc' => $auctionAccountId]));
            return ['success' => true, 'errorMessage' => '', 'response' => ''];
        }
        $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($userId);
        $userShipping = $this->getUserLoader()->loadUserShippingOrCreate($userId);
        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($userId);
        $user = $this->getUserLoader()->load($userId);
        if (!$user) {
            log_error(
                "Available user not found, when performing auth or capture"
                . composeSuffix(['u' => $userId])
            );
            return ['success' => false, 'errorMessage' => '', 'response' => ''];
        }
        $userNoteManager = $this->getUserInfoNoteManager();

        $isCim = false;
        $isCimProfile = false;
        $blockCipher = $this->createBlockCipherProvider()->construct();
        $authNetCpi = $blockCipher->decrypt($userBilling->AuthNetCpi);
        $authNetCppi = $blockCipher->decrypt($userBilling->AuthNetCppi);
        $payTraceCustId = $blockCipher->decrypt($userBilling->PayTraceCustId);
        $nmiVaultId = $blockCipher->decrypt($userBilling->NmiVaultId);
        $opayoTokenId = $blockCipher->decrypt($userBilling->OpayoTokenId);
        switch ($authorizationUse) {
            case Constants\SettingUser::PAY_AUTHORIZE_NET:
                $isCim = $isAuthNetCim;
                $isCimProfile = $authNetCpi !== ''
                    && $authNetCppi !== '';
                break;
            case Constants\SettingUser::PAY_PAY_TRACE:
                $isCim = $isPayTraceCim;
                $isCimProfile = $payTraceCustId !== '';
                break;
            case Constants\SettingUser::PAY_NMI:
                $isCim = $isNmiVault;
                $isCimProfile = $nmiVaultId !== '';
                break;
            case Constants\SettingUser::PAY_OPAYO:
                $isCim = $isOpayoToken;
                $isCimProfile = $opayoTokenId !== '';
                break;
        }

        if ($isCim) {
            $ccType = $userBilling->CcType;
            $ccNumber = $blockCipher->decrypt($userBilling->CcNumber);
            $authNetCai = $blockCipher->decrypt($userBilling->AuthNetCai);
            if (!$isCimProfile) {
                //well try to create a CIM profile for this user
                //1. Validate his CC number first
                if (!$userBilling->CcNumber) {
                    $errorMessage = "Missing credit card number";
                    $userNoteManager->applyAuctionRegistration($userInfo, $errorMessage, $auction->Id, $userBilling, $editorUserId);
                    $isResult['response'] = $errorMessage;
                    return $isResult;
                }

                if (!$this->getCreditCardValidator()->validateNumber($ccNumber)) {
                    $errorMessage = 'Invalid credit card number';
                    $userNoteManager->applyAuctionRegistration($userInfo, $errorMessage, $auction->Id, $userBilling, $editorUserId);
                    $isResult['response'] = $errorMessage;
                    return $isResult;
                }

                //2. If CC number is valid, create his CIM profile
                [$expMonth, $expYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
                $params = [];
                $params[Constants\BillingParam::SHIPPING_COMPANY_NAME] = $userShipping->CompanyName;
                $params[Constants\BillingParam::SHIPPING_FIRST_NAME] = $userShipping->FirstName;
                $params[Constants\BillingParam::SHIPPING_LAST_NAME] = $userShipping->LastName;
                $params[Constants\BillingParam::SHIPPING_ADDRESS] = $userShipping->Address;
                $params[Constants\BillingParam::SHIPPING_CITY] = $userShipping->City;
                $params[Constants\BillingParam::SHIPPING_STATE] = $userShipping->State;
                $params[Constants\BillingParam::SHIPPING_COUNTRY] = $userShipping->Country;
                $params[Constants\BillingParam::SHIPPING_ZIP] = $userShipping->Zip;
                $params[Constants\BillingParam::SHIPPING_PHONE] = $userShipping->Phone;

                $params[Constants\BillingParam::CC_TYPE] = $ccType;
                $params[Constants\BillingParam::CC_NUMBER] = $ccNumber;
                $params[Constants\BillingParam::CC_NUMBER_HASH] = $ccNumber;
                $params[Constants\BillingParam::CC_EXP_MONTH] = $expMonth;
                $params[Constants\BillingParam::CC_EXP_YEAR] = $expYear;
                $params[Constants\BillingParam::CC_CODE] = '';

                $params[Constants\BillingParam::BILLING_COMPANY_NAME] = $userBilling->CompanyName;
                $params[Constants\BillingParam::BILLING_FIRST_NAME] = $userBilling->FirstName;
                $params[Constants\BillingParam::BILLING_LAST_NAME] = $userBilling->LastName;
                $params[Constants\BillingParam::BILLING_ADDRESS] = $userBilling->Address;
                $params[Constants\BillingParam::BILLING_CITY] = $userBilling->City;
                $params[Constants\BillingParam::BILLING_STATE] = $userBilling->State;
                $params[Constants\BillingParam::BILLING_COUNTRY] = $userBilling->Country;
                $params[Constants\BillingParam::BILLING_ZIP] = $userBilling->Zip;
                $params[Constants\BillingParam::BILLING_PHONE] = $userBilling->Phone;
                $params[Constants\BillingParam::BILLING_EMAIL] = $userBilling->Email;

                $authNetResponse = [];
                $payTraceResponse = [];
                $nmiResponse = [];
                $opayoResponse = [];
                if ($isAuthNetCim) {
                    $params[Constants\BillingAuthNet::P_AUTH_NET_CPI] = $authNetCpi;
                    $params[Constants\BillingAuthNet::P_AUTH_NET_CPPI] = $authNetCppi;
                    $params[Constants\BillingAuthNet::P_AUTH_NET_CAI] = $authNetCai;
                    $authNetResponse = User_CIM::recordAuthNetCim($auctionAccountId, $user, $params);
                } elseif ($isPayTraceCim) {
                    $params[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID] = $payTraceCustId;
                    $payTraceResponse = User_CIM::recordPayTraceCim(
                        $this->cfg()->get('core->portal->mainAccountId'),
                        $user,
                        $params
                    );
                }

                if ($isAuthNetCim) {
                    if (!$authNetResponse['success']) {
                        $error = $this->getTranslator()->translate("GENERAL_ERROR_CIM", "general") .
                            (is_array($authNetResponse) && isset($authNetResponse['errorMessage'])
                                ? ' ' . $authNetResponse['errorMessage'] : '');
                        $errorMessage = "Error creating customer information";
                        $userNoteManager->applyAuctionRegistration($userInfo, $errorMessage, $auction->Id, $userBilling, $editorUserId);
                        $isResult['response'] = $error;
                        return $isResult;
                    }

                    $result = $authNetResponse['result'];
                    if (isset($result[Constants\BillingAuthNet::P_AUTH_NET_CPI])) {
                        $userBilling->AuthNetCpi = $blockCipher->encrypt($result[Constants\BillingAuthNet::P_AUTH_NET_CPI]);
                        $authNetCpi = $result[Constants\BillingAuthNet::P_AUTH_NET_CPI];
                    }
                    if (isset($result[Constants\BillingAuthNet::P_AUTH_NET_CPPI])) {
                        $userBilling->AuthNetCppi = $blockCipher->encrypt($result[Constants\BillingAuthNet::P_AUTH_NET_CPPI]);
                        $authNetCppi = $result[Constants\BillingAuthNet::P_AUTH_NET_CPPI];
                    }
                    if (isset($result[Constants\BillingAuthNet::P_AUTH_NET_CAI])) {
                        $userBilling->AuthNetCai = $blockCipher->encrypt($result[Constants\BillingAuthNet::P_AUTH_NET_CAI]);
                        $authNetCai = $result[Constants\BillingAuthNet::P_AUTH_NET_CAI];
                    }
                } elseif ($isPayTraceCim) {
                    if (!$payTraceResponse['success']) {
                        $error = $this->getTranslator()->translate("GENERAL_ERROR_CIM", "general") .
                            (is_array($payTraceResponse) && isset($payTraceResponse['errorMessage'])
                                ? ' ' . $payTraceResponse['errorMessage'] : '');
                        $errorMessage = "Error creating customer information";
                        $userNoteManager->applyAuctionRegistration($userInfo, $errorMessage, $auction->Id, $userBilling, $editorUserId);
                        $isResult['response'] = $error;
                        return $isResult;
                    }

                    $result = $payTraceResponse['result'];
                    if (isset($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID])) {
                        $userBilling->PayTraceCustId = $blockCipher->encrypt($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID]);
                        $payTraceCustId = $result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID];
                    }
                }

                if ($isNmiVault) {
                    $params[Constants\BillingNmi::P_NMI_VAULT_ID] = $nmiVaultId;
                    $nmiResponse = User_CIM::recordNmiVault($this->cfg()->get('core->portal->mainAccountId'), $user, $params);
                }

                if ($isNmiVault) {
                    if (!$nmiResponse['success']) {
                        $error = $this->getTranslator()->translate("GENERAL_ERROR_CIM", "general") .
                            (is_array($nmiResponse) && isset($nmiResponse['errorMessage'])
                                ? ' ' . $nmiResponse['errorMessage'] : '');
                        $errorMessage = "Error creating customer information";
                        $userNoteManager->applyAuctionRegistration($userInfo, $errorMessage, $auction->Id, $userBilling, $editorUserId);
                        $userBilling->AuthNetCpi = '';
                        $userBilling->AuthNetCppi = '';
                        $userBilling->AuthNetCai = '';
                        $userBilling->PayTraceCustId = '';
                        $userBilling->OpayoTokenId = '';
                        $this->getUserBillingWriteRepository()->saveWithModifier($userBilling, $editorUserId);
                        $isResult['response'] = $error;
                        return $isResult;
                    }

                    $result = $nmiResponse['result'];
                    if (isset($result[Constants\BillingNmi::P_NMI_VAULT_ID])) {
                        $userBilling->NmiVaultId = $blockCipher->encrypt($result[Constants\BillingNmi::P_NMI_VAULT_ID]);
                        $nmiVaultId = $result[Constants\BillingNmi::P_NMI_VAULT_ID];
                    }
                }

                if ($isOpayoToken) {
                    $params[Constants\BillingOpayo::P_OPAYO_TOKEN_ID] = $opayoTokenId;
                    $opayoResponse = User_CIM::recordOpayoToken(
                        $this->cfg()->get('core->portal->mainAccountId'),
                        $user,
                        $params
                    );
                }

                if ($isOpayoToken) {
                    if (!$opayoResponse['success']) {
                        $error = $this->getTranslator()->translate("GENERAL_ERROR_CIM", "general") .
                            (is_array($opayoResponse) && isset($opayoResponse['errorMessage'])
                                ? ' ' . $opayoResponse['errorMessage'] : '');
                        $errorMessage = "Error creating customer information";
                        $userNoteManager->applyAuctionRegistration($userInfo, $errorMessage, $auction->Id, $userBilling, $editorUserId);
                        $isResult['response'] = $error;
                        return $isResult;
                    }

                    $result = $opayoResponse['result'];
                    if (isset($result[Constants\BillingOpayo::P_OPAYO_TOKEN_ID])) {
                        $userBilling->OpayoTokenId = $blockCipher->encrypt($result[Constants\BillingOpayo::P_OPAYO_TOKEN_ID]);
                        $opayoTokenId = $result[Constants\BillingOpayo::P_OPAYO_TOKEN_ID];
                    }
                }

                $this->getUserInfoWriteRepository()->saveWithModifier($userInfo, $editorUserId);

                $userBilling->CcNumber = $this->getCcNumberEncrypter()->encryptLastFourDigits($ccNumber);
                $userBilling->CcNumberHash = $this->getCcNumberEncrypter()->createHash($ccNumber);
                $this->getUserBillingWriteRepository()->saveWithModifier($userBilling, $editorUserId);
            } else { // Has CIM profile

                if (strlen($ccNumber) !== 4) {
                    if (!$userBilling->CcNumber) {
                        $errorMessage = "Missing credit card number";
                        $userNoteManager->applyAuctionRegistration($userInfo, $errorMessage, $auction->Id, $userBilling, $editorUserId);
                        $isResult['response'] = $errorMessage;
                        return $isResult;
                    }

                    if (!$this->getCreditCardValidator()->validateNumber($ccNumber)) {
                        $errorMessage = "Invalid credit card number";
                        $userNoteManager->applyAuctionRegistration($userInfo, $errorMessage, $auction->Id, $userBilling, $editorUserId);
                        $isResult['response'] = $errorMessage;
                        return $isResult;
                    }

                    // Update only when changed
                    [$extMonth, $extYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
                    $params = [];
                    $params[Constants\BillingParam::CC_TYPE] = $ccType;
                    $params[Constants\BillingParam::CC_NUMBER] = $ccNumber;
                    $params[Constants\BillingParam::CC_EXP_MONTH] = $extMonth;
                    $params[Constants\BillingParam::CC_EXP_YEAR] = $extYear;

                    if ($isAuthNetCim) {
                        $params[Constants\BillingAuthNet::P_AUTH_NET_CPI] = $authNetCpi;
                        $params[Constants\BillingAuthNet::P_AUTH_NET_CPPI] = $authNetCppi;
                        $params[Constants\BillingAuthNet::P_AUTH_NET_CAI] = $authNetCai;
                        User_CIM::updateAuthNetCim($auctionAccountId, $params);
                    } elseif ($isPayTraceCim) {
                        $params[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID] = $payTraceCustId;
                        User_CIM::updatePayTraceCim($this->cfg()->get('core->portal->mainAccountId'), $params);
                    }
                    if ($isNmiVault) {
                        $params[Constants\BillingNmi::P_NMI_VAULT_ID] = $nmiVaultId;
                        User_CIM::updateNmiVault($this->cfg()->get('core->portal->mainAccountId'), $params);
                    }
                    if ($isOpayoToken) {
                        $params[Constants\BillingOpayo::P_OPAYO_TOKEN_ID] = $opayoTokenId;
                        $opayoResponse = User_CIM::recordOpayoToken(
                            $this->cfg()->get('core->portal->mainAccountId'),
                            $user,
                            $params
                        );
                        if ($opayoResponse['success']) {
                            $result = $opayoResponse['result'];
                            $opayoTokenId = trim($result[Constants\BillingOpayo::P_OPAYO_TOKEN_ID]);
                            $userBilling->OpayoTokenId = $blockCipher->encrypt($opayoTokenId);
                            $this->getUserBillingWriteRepository()->saveWithModifier($userBilling, $editorUserId);
                        }
                    }
                }
            }

            $auctionStartDateTz = $this->getDateHelper()->convertUtcToTzById($auction->detectScheduledStartDate(), $auction->TimezoneId);
            $auctionStartDateFormatted = $auctionStartDateTz ? $auctionStartDateTz->format(Constants\Date::US_DATE) : '';

            $paramsArray = [
                'amount' => number_format($authAmount, 2),
                'transaction_type' => $transactionType,
                'UserId' => $userId,
                'AuctionId' => $auctionId,
            ];
            $desc = $this->getAuctionRenderer()->renderSaleNo($auction)
                . ' ' . $this->getAuctionRenderer()->renderName($auction)
                . ' ' . $auctionStartDateFormatted;

            switch ($authorizationUse) {
                case 'A':
                    $paramsArray['order_description'] = $desc;
                    $paramsArray[Constants\BillingAuthNet::P_AUTH_NET_CPI] = $authNetCpi;
                    $paramsArray[Constants\BillingAuthNet::P_AUTH_NET_CPPI] = $authNetCppi;
                    $paramsArray[Constants\BillingAuthNet::P_AUTH_NET_CAI] = $authNetCai;
                    break;
                case 'P':
                    $paramsArray['DESCRIPTION'] = $desc;
                    $paramsArray[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID] = $payTraceCustId;
                    break;
                case 'M':
                    $paramsArray['orderdescription'] = $desc;
                    $paramsArray[Constants\BillingNmi::P_NMI_VAULT_ID] = $nmiVaultId;
                    break;
                case 'U':
                    $fullName = UserPureRenderer::new()->makeFullName($userBilling->FirstName, $userBilling->LastName);
                    $paramsArray['Description'] = $desc;
                    $paramsArray[Constants\BillingOpayo::P_OPAYO_TOKEN_ID] = $opayoTokenId;
                    $paramsArray['BillingFirstnames'] = $fullName;
                    $paramsArray['BillingAddress1'] = $userBilling->Address;
                    $paramsArray['BillingPostCode'] = $userBilling->Zip;
                    $paramsArray['BillingSurname'] = $userBilling->LastName;
                    $paramsArray['CustomerName'] = $fullName;
                    $paramsArray['CardHolder'] = $fullName;
                    $paramsArray['BillingCountry'] = $userBilling->Country;
                    $paramsArray['BillingCity'] = $userBilling->City;
                    break;
            }

            $isResult = User_CIM::authOrCapture($auctionAccountId, $paramsArray, $editorUserId);
            if (!$isResult['success']) {
                $userNoteManager->applyAuctionRegistration($userInfo, $isResult['errorMessage'], $auction->Id, $userBilling, $editorUserId);
            }
        } else {
            $ccNumber = $userBilling->CcNumber ? $blockCipher->decrypt($userBilling->CcNumber) : '';
            if ($ccNumber) {
                $auctionStartDateTz = $this->getDateHelper()->convertUtcToTzById($auction->detectScheduledStartDate(), $auction->TimezoneId);
                $auctionStartDateFormatted = $auctionStartDateTz ? $auctionStartDateTz->format(Constants\Date::US_DATE) : '';
                $paramsArray = [
                    'UserId' => $userId,
                    'AuctionId' => $auctionId,
                    'description' => $this->getAuctionRenderer()->renderSaleNo($auction)
                        . ' ' . $this->getAuctionRenderer()->renderName($auction)
                        . ' ' . $auctionStartDateFormatted,
                    'first_name' => $userBilling->FirstName
                        ?: $userInfo->FirstName,
                    'last_name' => $userBilling->LastName
                        ?: $userInfo->LastName,
                    'company' => $userBilling->CompanyName,
                    'address' => $userBilling->Address,
                    'address2' => $userBilling->Address2,
                    'city' => $userBilling->City,
                    'state' => $userBilling->State,
                    'zip' => $userBilling->Zip,
                    'country' => $userBilling->Country,
                    'iso_country' => $userBilling->Country,
                    'phone' => $userInfo->Phone,
                    'fax' => $userBilling->Fax,
                    'email' => $user->Email,
                    'ship_to_first_name' => $userShipping->FirstName,
                    'ship_to_last_name' => $userShipping->LastName,
                    'ship_to_company' => $userShipping->CompanyName,
                    'ship_to_address' => $userShipping->Address,
                    'ship_to_address2' => $userShipping->Address2,
                    'ship_to_city' => $userShipping->City,
                    'ship_to_country' => $userShipping->Country,
                    'iso_ship_to_country' => $userShipping->Country,
                    'ship_to_state' => $userShipping->State,
                    'ship_to_zip' => $userShipping->Zip,
                    'ccnum' => $ccNumber,
                ];

                if (
                    $authorizationUse === Constants\SettingUser::PAY_AUTHORIZE_NET
                    || $isAuthNetCim
                ) {
                    $paramsArray['expiration'] = $userBilling->CcExpDate;
                } else {
                    if (
                        $authorizationUse === Constants\SettingUser::PAY_PAY_TRACE
                        || $isPayTraceCim
                    ) {
                        $paramsArray['expiration'] = $userBilling->CcExpDate;
                    } else {
                        if ($authorizationUse === Constants\SettingUser::PAY_EWAY) {
                            $paramsArray['expiration'] = $userBilling->CcExpDate;
                            if ($ewayEncryptionKey !== '') {
                                $paramsArray['ccnum'] = $userBilling->CcNumberEway;
                            }
                        } else {
                            if ($authorizationUse === Constants\SettingUser::PAY_NMI) {
                                $paramsArray['expiration'] = $userBilling->CcExpDate;
                            } else {
                                if ($authorizationUse === Constants\SettingUser::PAY_OPAYO) {
                                    $creditCard = $this->getCreditCardLoader()->load($userBilling->CcType);
                                    if ($creditCard) {
                                        $paramsArray['card_type'] = Payment_Opayo::new()->getCardType($creditCard->Name);
                                        if ($cvv) {
                                            $paramsArray['ccv'] = $cvv;
                                        }
                                    }
                                    $paramsArray['expiration'] = $userBilling->CcExpDate;
                                }
                            }
                        }
                    }
                }

                if ($authorizationUse !== Constants\SettingUser::PAY_NO_AUTHORIZATION) {
                    $isResult = User_OnAuctionRegistration::AuthOrCapture(
                        $auctionAccountId,
                        $transactionType,
                        $authAmount,
                        $paramsArray,
                        $editorUserId,
                        $carrierMethod
                    );

                    if (!$isResult['success']) {
                        $userNoteManager->applyAuctionRegistration(
                            $userInfo,
                            $isResult['errorMessage'],
                            $auction->Id,
                            $userBilling,
                            $editorUserId
                        );
                    }
                } else {
                    $isResult['success'] = true;
                    log_debug('No authorization is enabled for account' . composeSuffix(['acc' => $auctionAccountId]));
                }
            }
        } // Not CIM

        return $isResult;
    }

    /**
     * Checks if the same amount or a higher amount was authorized
     * for this user within "Expires" days for this or another auction or on registration
     *
     * @param int $auctionId the auction id where the user registered
     * @param int $userId the user's id
     * @param int $accountId optional account id that will be used to retrieve system parameters
     * @return bool
     */
    public function shouldDoAuthOrCapture(
        int $auctionId,
        int $userId,
        int $accountId
    ): bool {
        $sm = $this->getSettingsManager();
        $onRegistrationAmount = $sm->getForMain(Constants\Setting::ON_REGISTRATION_AMOUNT);
        $onAuctionRegistrationExpires = (int)$sm->get(Constants\Setting::ON_AUCTION_REGISTRATION_EXPIRES, $accountId);
        $isNoAutoAuthorization = (bool)$sm->get(Constants\Setting::NO_AUTO_AUTHORIZATION, $accountId);
        if ($isNoAutoAuthorization) {
            log_debug('No auto authorization is enabled for account' . composeSuffix(['acc' => $accountId]));
            return false;
        } // Should not do auto authorization

        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($userId);
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            log_error(
                "Available auction not found, when checking should do auth or capture"
                . composeSuffix(['a' => $auctionId])
            );
            return false;
        }
        $shouldDoAuthOrCapture = false;
        $authAmount = $this->getAuthAmountDetector()->detect($auction);
        if (!$authAmount) {
            log_debug('No auth amount detected' . composeSuffix(['a' => $auctionId]));
            return false;
        }

        $onRegistrationExpires = $sm->getForMain(Constants\Setting::ON_REGISTRATION_EXPIRES);
        switch ($onRegistrationExpires) {
            case 0: //amount will expire immediately
                $shouldDoAuthOrCapture = true;
                break;
            default: //amount will expire in 'expires' days
                if ($userInfo->RegAuthDate) {
                    $regAuthDate = clone $userInfo->RegAuthDate;
                    $regAuthDate->add(new DateInterval('P' . $onRegistrationExpires . 'D'));
                    $currentDateUtc = $this->getCurrentDateUtc();
                    if ($regAuthDate->getTimestamp() < $currentDateUtc->getTimestamp()) {
                        $shouldDoAuthOrCapture = true;
                    }
                } else {
                    $shouldDoAuthOrCapture = true;
                }
            //break;
            case -1: //amount will never expire
                if ($userInfo->RegAuthDate) {
                    if (Floating::lt($onRegistrationAmount, $authAmount)) {
                        $shouldDoAuthOrCapture = true;
                    }
                } else {
                    $shouldDoAuthOrCapture = true;
                }
                break;
        }

        if ($shouldDoAuthOrCapture) {
            switch ($onAuctionRegistrationExpires) {
                case 0:  //amount will expire immediately
                    $shouldDoAuthOrCapture = true;
                    break;
                case -1://amount will never expire
                    $highestAuthAmount = $this->GetHighestAuthAmount($userId);
                    if (Floating::lteq($authAmount, $highestAuthAmount)) {
                        $shouldDoAuthOrCapture = false;
                    }
                    break;
                default://amount will expire in 'expires' days
                    $highestAuthAmount = $this->GetHighestAuthAmount($userId, true, $onAuctionRegistrationExpires);
                    if (Floating::lteq($authAmount, $highestAuthAmount)) {
                        $shouldDoAuthOrCapture = false;
                    }
                    break;
            }
        }

        return $shouldDoAuthOrCapture;
    }

    /**
     * Get the highest amount authorized or captured from a user
     *
     * @param int $userId the user's id
     * @param bool $isBeforeExpiry optional before expiration
     * @param int $onRegistrationExpiryInterval optional day(s) before the amount will expire
     * @return float $maxAmount
     */
    public function GetHighestAuthAmount(
        int $userId,
        bool $isBeforeExpiry = false,
        int $onRegistrationExpiryInterval = 0
    ): float {
        $query = "SELECT MAX( auth_amount ) AS max_amount " .
            "FROM `auction_bidder` " .
            "WHERE user_id = " . $this->escape($userId) . " ";

        $clause = '';
        if ($isBeforeExpiry) {
            $clause = "AND CAST(DATE_ADD(auth_date, INTERVAL " . $this->escape($onRegistrationExpiryInterval) .
                " DAY) AS DATETIME) > CAST(" . $this->escape($this->getCurrentDateUtcIso()) . " AS DATETIME)";
        }

        $query .= $clause;

        log_debug(composeLogData(['User id' => $userId, 'expiry' => $clause]));

        $dbResult = $this->query($query);
        $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        if ($row) {
            return (float)$row['max_amount'];
        }

        return 0.;
    }
}
