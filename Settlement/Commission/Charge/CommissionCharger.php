<?php

namespace Sam\Settlement\Commission\Charge;

use Email_Template;
use Eway\Rapid\Enum\PaymentMethod;
use Eway\Rapid\Enum\TransactionType;
use Exception;
use Payment_AuthorizeNet;
use Payment_AuthorizeNetCim;
use Payment_Eway;
use Payment_NmiCustomerVault;
use Payment_NmiDirectPost;
use Payment_PayTrace;
use Payment_Opayo;
use RuntimeException;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Billing\CreditCard\Validate\CreditCardValidatorAwareTrait;
use Sam\Billing\Gate\Opayo\Common\TransactionParameter\TransactionParameterCollection;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settlement\Calculate\SettlementCalculatorAwareTrait;
use Sam\Settlement\Load\SettlementLoaderAwareTrait;
use Sam\Settlement\Payment\Update\SettlementPayment;
use Sam\Settlement\Payment\Update\SettlementPaymentProducerCreateTrait;
use Sam\Storage\WriteRepository\Entity\Settlement\SettlementWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserBilling\UserBillingWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Render\UserRendererAwareTrait;
use Sam\User\Validate\UserBillingCheckerAwareTrait;
use Settlement;
use User_CIM;

/**
 * Class Sam\Settlement\Commission\Charge\CommissionCharger
 */
class CommissionCharger extends CustomizableClass
{
    use BlockCipherProviderCreateTrait;
    use CcExpiryDateBuilderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CreditCardLoaderAwareTrait;
    use CreditCardValidatorAwareTrait;
    use CurrencyLoaderAwareTrait;
    use ServerRequestReaderAwareTrait;
    use SettingsManagerAwareTrait;
    use SettlementCalculatorAwareTrait;
    use SettlementLoaderAwareTrait;
    use SettlementPaymentProducerCreateTrait;
    use SettlementWriteRepositoryAwareTrait;
    use TranslatorAwareTrait;
    use UserBillingCheckerAwareTrait;
    use UserBillingWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;
    use UserRendererAwareTrait;

    protected const CC_NUMBER_ABSENT = 'Consignor has no credit card info';
    protected const CC_DATE_EXPIRED = 'Consignor\'s CC date expired';

    /**
     * Class instantiation method
     *
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Function that process payment of commissions
     * through Authorize.net API payment gateway
     *
     * @param int $settlementId
     * @param int $editorUserId
     * @return bool|string
     */
    public function chargeCommissionThroughAuthNet(int $settlementId, int $editorUserId): bool|string
    {
        $userLoader = $this->getUserLoader();
        $settlement = $this->getSettlementLoader()->load($settlementId);
        if (!$settlement) {
            $message = "Available settlement not found for charging commission through authorize.net"
                . composeSuffix(['s' => $settlementId]);
            log_error($message);
            return $message;
        }

        $accountId = $settlement->AccountId;
        $isAuthNetCim = (bool)$this->getSettingsManager()->get(Constants\Setting::AUTH_NET_CIM, $accountId);
        $isNmiVault = (bool)$this->getSettingsManager()->get(Constants\Setting::NMI_VAULT, $accountId);
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign();
        $amount = $this->getSettlementCalculator()->calcRoundedBalanceDue($settlement->Id);

        if (Floating::gt($amount, 0)) {
            $paymentMethodId = Constants\Payment::PM_CC; // Credit card charges
            $note = '';

            $user = $userLoader->load($settlement->ConsignorId, true);
            if (!$user) {
                $message = "Available settlement consignor user not found, when charging commission through AuthorizeNet"
                    . composeSuffix(['u' => $settlement->ConsignorId, 's' => $settlement->Id]);
                log_error($message);
                return $message;
            }

            $userBilling = $userLoader->loadUserBillingOrCreate($settlement->ConsignorId);
            $description = 'Commission charge on Settlement ' . $settlement->Id;

            $isCim = $isAuthNetCim;
            $blockCipher = $this->createBlockCipherProvider()->construct();
            if ($isCim) { // USE CIM
                $authNetCpi = $blockCipher->decrypt($userBilling->AuthNetCpi);
                $authNetCppi = $blockCipher->decrypt($userBilling->AuthNetCppi);
                $authNetCai = $blockCipher->decrypt($userBilling->AuthNetCai);
                if (
                    $authNetCpi === ''
                    || $authNetCppi === ''
                ) {
                    try {
                        $params = $this->getParams($settlement);
                    } catch (Exception $e) {
                        return $e->getMessage();
                    }

                    $authNetInfo = User_CIM::recordAuthNetCim($accountId, $user, $params);
                    if (!$authNetInfo['success']) {
                        $message = 'Failed to create entry in cim' .
                            (is_array($authNetInfo)
                            && isset($authNetInfo['errorMessage'])
                                ? ' ' . $authNetInfo['errorMessage'] : '');
                        return $message;
                    }

                    $result = $authNetInfo['result'];
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

                    if ($isNmiVault) {
                        $nmiInfo = User_CIM::recordNmiVault($this->cfg()->get('core->portal->mainAccountId'), $user, $params);
                        if (!$nmiInfo['success']) {
                            $message = 'Failed to create entry in cim' .
                                (is_array($nmiInfo) && isset($nmiInfo['errorMessage']) ? ' ' . $nmiInfo['errorMessage']
                                    : '');
                            return $message;
                        }
                        $result = $nmiInfo['result'];
                        if (isset($result[Constants\BillingNmi::P_NMI_VAULT_ID])) {
                            $userBilling->NmiVaultId = $blockCipher->encrypt($result[Constants\BillingNmi::P_NMI_VAULT_ID]);
                        }
                    }

                    $this->getUserBillingWriteRepository()->saveWithModifier($userBilling, $editorUserId);
                }

                $authNetCimManager = new Payment_AuthorizeNetCim($accountId);
                $authNetCimManager->setParameter('order_description', $description);
                // Up to 4 digits with a decimal (required)
                $transactionAmount = sprintf("%1.2F", Floating::roundOutput($amount));
                $authNetCimManager->setParameter('transaction_amount', $transactionAmount);
                $authNetCimManager->setParameter('transactionType', 'profileTransAuthCapture'); // see options above
                $authNetCimManager->setParameter('customerProfileId', $authNetCpi); // Numeric (required)
                $authNetCimManager->setParameter('customerPaymentProfileId', $authNetCppi);
                if ($authNetCai !== '') {
                    $authNetCimManager->setParameter('customerShippingAddressId', $authNetCai);
                }
                $authNetCimManager->createCustomerProfileTransactionRequest();

                if (!$authNetCimManager->isSuccessful()) {
                    $errorMessage = (((string)$authNetCimManager->directResponse !== '')
                        ? $authNetCimManager->directResponseErr .
                        ' ' . $authNetCimManager->text : '');
                    return 'Problem encountered consignor CIM payment.' . ' ' .
                        //$authNetCimManager->code . ': ' . $authNetCimManager->text;
                        '<br>' . $authNetCimManager->code . ': ' . $errorMessage;
                }
            } else {
                $ccNumber = $blockCipher->decrypt($userBilling->CcNumber);
                if ($ccNumber === '') {
                    return self::CC_NUMBER_ABSENT;
                }

                $ccExpiration = $userBilling->CcExpDate;
                if (!$this->getCreditCardValidator()->validateExpiredDateFormatted($ccExpiration)) {
                    return self::CC_DATE_EXPIRED;
                }

                $authNetManager = new Payment_AuthorizeNet($accountId);
                $authNetManager->setTransactionType($this->cfg()->get('core->billing->gate->authorizeNet->type'));
                $authNetManager->setParameter('x_description', $description);
                $authNetManager->setParameter('x_first_name', $userBilling->FirstName);
                $authNetManager->setParameter('x_last_name', $userBilling->LastName);
                $authNetManager->setParameter('x_address', $userBilling->Address);
                $authNetManager->setParameter('x_city', $userBilling->City);
                $authNetManager->setParameter('x_state', $userBilling->State);
                $authNetManager->setParameter('x_zip', $userBilling->Zip);
                $country = AddressRenderer::new()->countryName($userBilling->Country);
                $authNetManager->setParameter('x_country', $country);
                $authNetManager->setParameter('x_phone', $userBilling->Phone);
                $authNetManager->setParameter('x_fax', $userBilling->Fax);
                $email = $this->getUserRenderer()->renderAccountingEmail($user, $userBilling);
                $authNetManager->setParameter('x_email', $email);
                $authNetManager->setParameter('x_cust_id', $user->CustomerNo);

                $authNetManager->transaction($ccNumber, $ccExpiration, $amount);
                $authNetManager->process(1);

                $message = '';
                if (!$authNetManager->isError()) {
                    if ($authNetManager->isDeclined()) {
                        $message .= 'Consignor credit card declined.' . " ";
                        $message .= 'Code : ' . $authNetManager->getResponseCode() . ' ' .
                            $authNetManager->getResponseText() . " ";
                        $message .= ($authNetManager->getCardCodeResponse() !== '')
                            ? 'Credit Card :' . $authNetManager->getCardCodeResponse() . " "
                            : '';
                    }
                } else {
                    $message .= 'Problem encountered consignor card.' . " ";
                    $message .= 'Code : ' . $authNetManager->getResponseCode() . ' ' .
                        $authNetManager->getResponseText() . " ";
                    $message .= ($authNetManager->getCardCodeResponse() !== '')
                        ? 'Credit Card :' . $authNetManager->getCardCodeResponse() . " "
                        : '';
                }

                if ($message !== '') {
                    log_error($message);
                    return $message;
                }

                if (!$authNetManager->isTestMode()) {
                    $note = $authNetManager->getTransactionId();
                }
            }

            /**
             * Add a payment record in the database if
             * payment transaction successfully executed.
             */
            $this->createSettlementPaymentProducer()->produce(
                SettlementPayment::new()->constructNew($settlement->Id, $paymentMethodId, $amount, $note),
                $editorUserId
            );

            $note = $settlement->Note;
            $langSettlementsChargeOf = $this->getTranslator()->translate('SETTLEMENTS_CHARGE_OF', 'mysettlements');

            //change settlements status to paid
            $settlement->toPaid();
            $settlement->Note = $note . "\n$langSettlementsChargeOf {$currencySign}" . Floating::roundOutput($amount);
            $this->getSettlementWriteRepository()->saveWithModifier($settlement, $editorUserId);

            $emailManager = Email_Template::new()->construct(
                $settlement->AccountId,
                Constants\EmailKey::SETTLEMENT_PAYMENT_CONF,
                $editorUserId,
                [$settlement]
            );
            $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);

            log_info(
                'Sending payment confirmation for settlement'
                . composeSuffix(['s' => $settlement->Id, 'settlement#' => $settlement->SettlementNo])
            );
        }

        return true;
    }

    /**
     * Function that process payment of commissions
     * through PayTrace API payment gateway
     *
     * @param int $settlementId (int)
     * @param int $editorUserId
     * @return bool
     */
    public function chargeCommissionThroughPayTrace(int $settlementId, int $editorUserId): bool|string
    {
        $userLoader = $this->getUserLoader();
        $settlement = $this->getSettlementLoader()->load($settlementId);
        if (!$settlement) {
            $message = "Available settlement not found for charging commission through pay trace"
                . composeSuffix(['s' => $settlementId]);
            log_error($message);
            return $message;
        }

        $settlementAccountId = $settlement->AccountId;
        $isNmiVault = (bool)$this->getSettingsManager()->get(Constants\Setting::NMI_VAULT, $settlementAccountId);
        $isPayTraceCim = (bool)$this->getSettingsManager()->get(Constants\Setting::PAY_TRACE_CIM, $settlementAccountId);
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign();
        $amount = $this->getSettlementCalculator()->calcRoundedBalanceDue($settlement->Id);

        if (Floating::gt($amount, 0)) {
            $user = $userLoader->load($settlement->ConsignorId, true);
            if (!$user) {
                $message = "Available settlement consignor user not found, when charging commission through PayTrace"
                    . composeSuffix(['u' => $settlement->ConsignorId, 's' => $settlement->Id]);
                log_error($message);
                return $message;
            }

            $userBilling = $userLoader->loadUserBillingOrCreate($settlement->ConsignorId);
            $description = 'Commission charge on Settlement ' . $settlement->Id;

            $isCim = $isPayTraceCim;
            $payTraceManager = new Payment_PayTrace($settlementAccountId);

            $blockCipher = $this->createBlockCipherProvider()->construct();
            if ($isCim) { // USE CIM
                $payTraceCustId = $blockCipher->decrypt($userBilling->PayTraceCustId);
                if ($payTraceCustId === '') {
                    try {
                        $params = $this->getParams($settlement);
                    } catch (Exception $e) {
                        return $e->getMessage();
                    }

                    // When recording customer for paytrace we use main account paytrace settings
                    $payTraceInfo = User_CIM::recordPayTraceCim(
                        $this->cfg()->get('core->portal->mainAccountId'),
                        $user,
                        $params
                    );
                    if (!$payTraceInfo['success']) {
                        $message = 'Failed to create entry in cim' .
                            (is_array($payTraceInfo) && isset($payTraceInfo['errorMessage'])
                                ? ' ' . $payTraceInfo['errorMessage'] : '');
                        return $message;
                    }

                    $result = $payTraceInfo['result'];
                    if (isset($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID])) {
                        $userBilling->PayTraceCustId = $blockCipher->encrypt($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID]);
                        $payTraceCustId = $result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID];
                    }

                    if ($isNmiVault) {
                        $nmiInfo = User_CIM::recordNmiVault($this->cfg()->get('core->portal->mainAccountId'), $user, $params);
                        if (!$nmiInfo['success']) {
                            $message = 'Failed to create entry in cim' .
                                (is_array($nmiInfo) && isset($nmiInfo['errorMessage']) ? ' ' . $nmiInfo['errorMessage']
                                    : '');
                            return $message;
                        }
                        $result = $nmiInfo['result'];
                        if (isset($result[Constants\BillingNmi::P_NMI_VAULT_ID])) {
                            $userBilling->NmiVaultId = $blockCipher->encrypt($result[Constants\BillingNmi::P_NMI_VAULT_ID]);
                        }
                    }

                    $this->getUserBillingWriteRepository()->saveWithModifier($userBilling, $editorUserId);
                }

                $payTraceManager->setParameter('CUSTID', $payTraceCustId);
                $payTraceManager->setParameter('AMOUNT', Floating::roundOutput($amount));
            } else {
                $ccNumber = $blockCipher->decrypt($userBilling->CcNumber);
                if ($ccNumber === '') {
                    return self::CC_NUMBER_ABSENT;
                }

                if (!$this->getCreditCardValidator()->validateExpiredDateFormatted($userBilling->CcExpDate)) {
                    return self::CC_DATE_EXPIRED;
                }

                [$expMonth, $expYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
                $payTraceManager->setParameter('BNAME', UserPureRenderer::new()->renderFullName($userBilling));
                $payTraceManager->setParameter('BADDRESS', $userBilling->Address);
                $payTraceManager->setParameter('BCITY', $userBilling->City);
                if (strlen($userBilling->State) === 2) {
                    $payTraceManager->setParameter('BSTATE', $userBilling->State);
                }
                $payTraceManager->setParameter('BZIP', $userBilling->Zip);
                if (strlen($userBilling->Country) === 2) {
                    $payTraceManager->setParameter('BCOUNTRY', $userBilling->Country);
                }
                $payTraceManager->transaction($ccNumber, $expMonth, $expYear, $amount);
            }

            $payTraceManager->setMethod('ProcessTranx');
            $payTraceManager->setTransactionType('Sale');
            $payTraceManager->setParameter('DESCRIPTION', $description);
            $email = $this->getUserRenderer()->renderAccountingEmail($user, $userBilling);
            $payTraceManager->setParameter('EMAIL', $email);
            $payTraceManager->process(1);

            $message = '';
            if (!$payTraceManager->isError()) {
                if ($payTraceManager->isDeclined()) {
                    $message .= 'Consignor credit card declined.' . " ";
                    $message .= $payTraceManager->getErrorReport();
                }
            } else {
                $message .= 'Problem encountered consignor card.' . " ";
                $message .= $payTraceManager->getErrorReport();
            }

            if ($message !== '') {
                log_error($message);
                return $message;
            }

            $paymentMethodId = Constants\Payment::PM_CC; // Credit card charges
            $note = $payTraceManager->getTransactionId();

            /*
             * Add a payment record in the database if
             * payment transaction successfully executed.
             */
            $this->createSettlementPaymentProducer()->produce(
                SettlementPayment::new()->constructNew($settlement->Id, $paymentMethodId, $amount, $note),
                $editorUserId
            );

            $note = $settlement->Note;
            $langSettlementsChargeOf = $this->getTranslator()->translate('SETTLEMENTS_CHARGE_OF', 'mysettlements');

            //change settlements status to paid
            $settlement->toPaid();
            $settlement->Note = $note . "\n$langSettlementsChargeOf {$currencySign}" . Floating::roundOutput($amount);
            $this->getSettlementWriteRepository()->saveWithModifier($settlement, $editorUserId);

            $emailManager = Email_Template::new()->construct(
                $settlement->AccountId,
                Constants\EmailKey::SETTLEMENT_PAYMENT_CONF,
                $editorUserId,
                [$settlement]
            );
            $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);

            log_info(
                'Sending payment confirmation for settlement'
                . composeSuffix(['s' => $settlement->Id, 'settlement#' => $settlement->SettlementNo])
            );
        }

        return true;
    }

    /**
     * Function that process payment of commissions
     * through Eway API payment gateway
     *
     * @param int $settlementId (int)
     * @param int $editorUserId
     * @param array $addParams
     * @return bool
     */
    public function chargeCommissionThroughEway(int $settlementId, int $editorUserId, array $addParams = []): bool|string
    {
        $userLoader = $this->getUserLoader();
        $settlement = $this->getSettlementLoader()->load($settlementId);
        if (!$settlement) {
            $message = "Available settlement not found for charging commission through eway"
                . composeSuffix(['s' => $settlementId]);
            log_error($message);
            return $message;
        }
        $accountId = $settlement->AccountId;
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign();
        $amount = $this->getSettlementCalculator()->calcRoundedBalanceDue($settlement->Id);

        if (Floating::gt($amount, 0)) {
            $user = $userLoader->load($settlement->ConsignorId, true);
            if (!$user) {
                $message = "Available settlement consignor user not found, when charging commission through Eway"
                    . composeSuffix(['u' => $settlement->ConsignorId, 's' => $settlement->Id]);
                log_error($message);
                return $message;
            }
            $userBilling = $userLoader->loadUserBillingOrCreate($settlement->ConsignorId);
            if (!$this->getUserBillingChecker()->isCcInfoExists($userBilling)) {
                $message = self::CC_NUMBER_ABSENT;
                log_error($message);
                return $message;
            }

            [$expMonth, $expYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
            $ccNumber = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->CcNumber);

            $description = 'Commission charge on Settlement ' . $settlement->Id;

            $ewayManager = Payment_Eway::new()->init($accountId);

            $params = [];
            $params["Customer"]["CardDetails"]["Name"] = UserPureRenderer::new()->renderFullName($userBilling);
            $params["Customer"]["Reference"] = $user->CustomerNo;
            $params["Customer"]["FirstName"] = $userBilling->FirstName;
            $params["Customer"]["LastName"] = $userBilling->LastName;
            $params["Customer"]["Street1"] = $userBilling->Address;
            $params["Customer"]["City"] = $userBilling->City;
            $params["Customer"]["State"] = $userBilling->State;
            $params["Customer"]["PostalCode"] = $userBilling->Zip;
            $params["Customer"]["Country"] = $userBilling->Country;
            $params["Customer"]["Phone"] = $userBilling->Phone;
            $params["Customer"]["Fax"] = $userBilling->Fax;
            $ccCode = '';
            if (count($addParams) > 0) {
                $ccCode = $addParams[Constants\BillingParam::CC_CODE] ?? '';
            }
            $ewayManager->setMethod(PaymentMethod::PROCESS_PAYMENT);
            $ewayManager->transaction($ccNumber, $expMonth, $expYear, $amount, $ccCode);

            $params["Payment"]["InvoiceDescription"] = $description;
            $email = $this->getUserRenderer()->renderAccountingEmail($user, $userBilling);
            $params["Customer"]["Email"] = $email;
            $ewayManager->setParameter($params);
            $ewayManager->setTransactionType(TransactionType::PURCHASE);
            $ewayManager->process();

            $message = '';
            if (!$ewayManager->isError()) {
                if ($ewayManager->isDeclined()) {
                    $message .= 'Consignor credit card declined.' . " ";
                    $message .= $ewayManager->getResultResponse() . ':' . $ewayManager->getResponseText();
                }
            } else {
                $message .= 'Problem encountered consignor card.' . " ";
                $message .= $ewayManager->getResultResponse() . ':' . $ewayManager->getResponseText();
            }

            if ($message !== '') {
                log_error($message);
                return $message;
            }

            $paymentMethodId = Constants\Payment::PM_CC; // Credit card charges
            $note = $ewayManager->getTransactionId() ? (string)$ewayManager->getTransactionId() : '';

            /*
             * Add a payment record in the database if
             * payment transaction successfully executed.
             */
            $this->createSettlementPaymentProducer()->produce(
                SettlementPayment::new()->constructNew($settlement->Id, $paymentMethodId, $amount, $note),
                $editorUserId
            );

            $note = $settlement->Note;
            $langSettlementsChargeOf = $this->getTranslator()->translate('SETTLEMENTS_CHARGE_OF', 'mysettlements');

            //change settlements status to paid
            $settlement->toPaid();
            $settlement->Note = $note . "\n$langSettlementsChargeOf {$currencySign}" . Floating::roundOutput($amount);
            $this->getSettlementWriteRepository()->saveWithModifier($settlement, $editorUserId);

            $emailManager = Email_Template::new()->construct(
                $settlement->AccountId,
                Constants\EmailKey::SETTLEMENT_PAYMENT_CONF,
                $editorUserId,
                [$settlement]
            );
            $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);

            log_info(
                'Sending payment confirmation for settlement'
                . composeSuffix(['s' => $settlement->Id, 'settlement#' => $settlement->SettlementNo])
            );
        }

        return true;
    }

    /**
     * Function that process payment of commissions
     * through Nmi API payment gateway
     *
     * @param int $settlementId (int)
     * @param int $editorUserId
     * @return bool|string
     */
    public function chargeCommissionThroughNmi(int $settlementId, int $editorUserId): bool|string
    {
        $userLoader = $this->getUserLoader();
        $settlement = $this->getSettlementLoader()->load($settlementId);
        if (!$settlement) {
            $message = "Available settlement not found for charging commission through nmi"
                . composeSuffix(['s' => $settlementId]);
            log_error($message);
            return $message;
        }

        $settlementAccountId = $settlement->AccountId;
        $isAuthNetCim = (bool)$this->getSettingsManager()->get(Constants\Setting::AUTH_NET_CIM, $settlementAccountId);
        $isPayTraceCim = (bool)$this->getSettingsManager()->get(Constants\Setting::PAY_TRACE_CIM, $settlementAccountId);
        $isNmiVault = (bool)$this->getSettingsManager()->get(Constants\Setting::NMI_VAULT, $settlementAccountId);
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign();
        $amount = $this->getSettlementCalculator()->calcRoundedBalanceDue($settlement->Id);

        if (Floating::gt($amount, 0)) {
            $user = $userLoader->load($settlement->ConsignorId, true);
            if (!$user) {
                $message = "Available settlement consignor user not found, when charging commission through NMI"
                    . composeSuffix(['u' => $settlement->ConsignorId, 's' => $settlement->Id]);
                log_error($message);
                return $message;
            }

            $userBilling = $userLoader->loadUserBillingOrCreate($settlement->ConsignorId, true);
            $description = 'Commission charge on Settlement ' . $settlement->Id;

            $isVault = $isNmiVault;
            $blockCipher = $this->createBlockCipherProvider()->construct();
            if ($isVault) { // USE CIM
                $nmiVaultId = $blockCipher->decrypt($userBilling->NmiVaultId);
                if ($nmiVaultId === '') {
                    try {
                        $params = $this->getParams($settlement);
                    } catch (Exception $e) {
                        return $e->getMessage();
                    }

                    // When recording customer for Nmi we use main account Nmi settings
                    $nmiInfo = User_CIM::recordNmiVault($this->cfg()->get('core->portal->mainAccountId'), $user, $params);
                    if (!$nmiInfo['success']) {
                        $message = 'Failed to create entry in cim' .
                            (is_array($nmiInfo) && isset($nmiInfo['errorMessage']) ? ' ' . $nmiInfo['errorMessage']
                                : '');
                        return $message;
                    }

                    $result = $nmiInfo['result'];
                    if (isset($result[Constants\BillingNmi::P_NMI_VAULT_ID])) {
                        $userBilling->NmiVaultId = $blockCipher->encrypt($result[Constants\BillingNmi::P_NMI_VAULT_ID]);
                        $nmiVaultId = $result[Constants\BillingNmi::P_NMI_VAULT_ID];
                    }

                    if ($isPayTraceCim) {
                        $payTraceInfo = User_CIM::recordPayTraceCim($this->cfg()->get('core->portal->mainAccountId'), $user, $params);
                        if (!$payTraceInfo['success']) {
                            $message = 'Failed to create entry in cim' .
                                (is_array($payTraceInfo) && isset($payTraceInfo['errorMessage'])
                                    ? ' ' . $payTraceInfo['errorMessage'] : '');
                            return $message;
                        }

                        $result = $payTraceInfo['result'];
                        if (isset($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID])) {
                            $userBilling->PayTraceCustId = $blockCipher->encrypt($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID]);
                        }
                    } elseif ($isAuthNetCim) {
                        $authNetInfo = User_CIM::recordAuthNetCim($settlementAccountId, $user, $params);
                        if (!$authNetInfo['success']) {
                            $message = 'Failed to create entry in cim' .
                                (is_array($authNetInfo) && isset($authNetInfo['errorMessage'])
                                    ? ' ' . $authNetInfo['errorMessage'] : '');
                            return $message;
                        }

                        $result = $authNetInfo['result'];
                        if (isset($result[Constants\BillingAuthNet::P_AUTH_NET_CPI])) {
                            $userBilling->AuthNetCpi = $blockCipher->encrypt($result[Constants\BillingAuthNet::P_AUTH_NET_CPI]);
                        }
                        if (isset($result[Constants\BillingAuthNet::P_AUTH_NET_CPPI])) {
                            $userBilling->AuthNetCppi = $blockCipher->encrypt($result[Constants\BillingAuthNet::P_AUTH_NET_CPPI]);
                        }
                        if (isset($result[Constants\BillingAuthNet::P_AUTH_NET_CAI])) {
                            $userBilling->AuthNetCai = $blockCipher->encrypt($result[Constants\BillingAuthNet::P_AUTH_NET_CAI]);
                        }
                    }
                    $this->getUserBillingWriteRepository()->saveWithModifier($userBilling, $editorUserId);
                }

                $nmiVaultManager = new Payment_NmiCustomerVault($settlementAccountId);
                $nmiVaultManager->setParameter('customer_vault_id', $nmiVaultId);
                $nmiVaultManager->setParameter('amount', Floating::roundOutput($amount));
                $nmiVaultManager->setParameter('zip', $userBilling->Zip);
            } else {
                $ccNumber = $blockCipher->decrypt($userBilling->CcNumber);
                if ($ccNumber === '') {
                    return self::CC_NUMBER_ABSENT;
                }

                if (!$this->getCreditCardValidator()->validateExpiredDateFormatted($userBilling->CcExpDate)) {
                    return self::CC_DATE_EXPIRED;
                }

                [$expMonth, $expYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);

                $nmiVaultManager = new Payment_NmiDirectPost($settlementAccountId);
                $nmiVaultManager->setTransactionType('sale');
                $nmiVaultManager->setParameter('firstname', $userBilling->FirstName);
                $nmiVaultManager->setParameter('lastname', $userBilling->LastName);
                if (strlen($userBilling->Country) === 2) {
                    $nmiVaultManager->setParameter('country', $userBilling->Country);
                }
                $nmiVaultManager->setParameter('address1', $userBilling->Address);
                $nmiVaultManager->setParameter('city', $userBilling->City);
                if (strlen($userBilling->State) === 2) {
                    $nmiVaultManager->setParameter('state', $userBilling->State);
                }
                $nmiVaultManager->setParameter('zip', $userBilling->Zip);
                $nmiVaultManager->setParameter('phone', $userBilling->Phone);
                $email = $this->getUserRenderer()->renderAccountingEmail($user, $userBilling);
                $nmiVaultManager->setParameter('email', $email);
                $nmiVaultManager->transaction($ccNumber, $expMonth, $expYear, $amount);
            }
            $nmiVaultManager->setParameter('orderdescription', $description);
            $nmiVaultManager->process(1);

            $message = '';
            if (!$nmiVaultManager->isError()) {
                if ($nmiVaultManager->isDeclined()) {
                    $message .= 'Consignor credit card declined.' . " ";
                    $message .= $nmiVaultManager->getErrorReport();
                }
            } else {
                $message .= 'Problem encountered consignor card.' . " ";
                $message .= $nmiVaultManager->getErrorReport();
            }

            if ($message !== '') {
                log_error($message);
                return $message;
            }

            $paymentMethodId = Constants\Payment::PM_CC; // Credit card charges
            $note = $nmiVaultManager->getTransactionId();

            /**
             * Add a payment record in the database if
             * payment transaction successfully executed.
             */
            $this->createSettlementPaymentProducer()->produce(
                SettlementPayment::new()->constructNew($settlement->Id, $paymentMethodId, $amount, $note),
                $editorUserId
            );

            $note = $settlement->Note;
            $langSettlementsChargeOf = $this->getTranslator()->translate('SETTLEMENTS_CHARGE_OF', 'mysettlements');

            //change settlements status to paid
            $settlement->toPaid();
            $settlement->Note = $note . "\n$langSettlementsChargeOf {$currencySign}" . Floating::roundOutput($amount);
            $this->getSettlementWriteRepository()->saveWithModifier($settlement, $editorUserId);

            $emailManager = Email_Template::new()->construct(
                $settlement->AccountId,
                Constants\EmailKey::SETTLEMENT_PAYMENT_CONF,
                $editorUserId,
                [$settlement]
            );
            $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);

            log_info(
                'Sending payment confirmation for settlement'
                . composeSuffix(['s' => $settlement->Id, 'settlement#' => $settlement->SettlementNo])
            );
        }

        return true;
    }

    /**
     * Function that process payment of commissions
     * through Opayo API payment gateway
     *
     * @param int $settlementId (int)
     * @param int $editorUserId
     * @param array $addParams
     * @return bool|string
     */
    public function chargeCommissionThroughOpayo(int $settlementId, int $editorUserId, array $addParams = []): bool|string
    {
        $userLoader = $this->getUserLoader();
        $settlement = $this->getSettlementLoader()->load($settlementId);
        if (!$settlement) {
            $message = "Available settlement not found for charging commission through sage pay"
                . composeSuffix(['s' => $settlementId]);
            log_error($message);
            return $message;
        }

        $settlementAccountId = $settlement->AccountId;
        $isAuthNetCim = (bool)$this->getSettingsManager()->get(Constants\Setting::AUTH_NET_CIM, $settlementAccountId);
        $isPayTraceCim = (bool)$this->getSettingsManager()->get(Constants\Setting::PAY_TRACE_CIM, $settlementAccountId);
        $isNmiVault = (bool)$this->getSettingsManager()->get(Constants\Setting::NMI_VAULT, $settlementAccountId);
        $isOpayoToken = (bool)$this->getSettingsManager()->get(Constants\Setting::OPAYO_TOKEN, $settlementAccountId);
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign();
        $amount = $this->getSettlementCalculator()->calcRoundedBalanceDue($settlement->Id);

        if (Floating::gt($amount, 0)) {
            $user = $userLoader->load($settlement->ConsignorId, true);
            if (!$user) {
                $message = "Available settlement consignor user not found, when charging commission through Opayo"
                    . composeSuffix(['u' => $settlement->ConsignorId, 's' => $settlement->Id]);
                log_error($message);
                return $message;
            }

            $userBilling = $userLoader->loadUserBillingOrCreate($settlement->ConsignorId);
            $description = 'Commission charge on Settlement ' . $settlement->Id;

            $blockCipher = $this->createBlockCipherProvider()->construct();
            if ($blockCipher->decrypt($userBilling->CcNumber) === '') {
                return self::CC_NUMBER_ABSENT;
            }

            if (!$this->getCreditCardValidator()->validateExpiredDateFormatted($userBilling->CcExpDate)) {
                return self::CC_DATE_EXPIRED;
            }

            $opayoManager = Payment_Opayo::new()->init($settlementAccountId);
            $opayoManager->setTransactionType('PAYMENT');
            $opayoManager->setParameter('BillingFirstnames', $userBilling->FirstName);
            $opayoManager->setParameter('BillingSurname', $userBilling->LastName);
            $opayoManager->setParameter('BillingCountry', $userBilling->Country);
            $opayoManager->setParameter('BillingAddress1', $userBilling->Address);
            $opayoManager->setParameter('BillingCity', $userBilling->City);
            if (strlen($userBilling->State) === 2) {
                $opayoManager->setParameter('BillingState', $userBilling->State);
            }
            $opayoManager->setParameter('BillingPostCode', $userBilling->Zip);
            $opayoManager->setParameter('BillingPhone', $userBilling->Phone);
            $email = $this->getUserRenderer()->renderAccountingEmail($user, $userBilling);
            $opayoManager->setParameter('CustomerEMail', $email);

            $ccCode = '';
            if (count($addParams) > 0) {
                $ccCode = $addParams[Constants\BillingParam::CC_CODE] ?? '';
            }

            if ($isOpayoToken) { // USE TOKEN
                $opayoTokenId = $blockCipher->decrypt($userBilling->OpayoTokenId);
                if ($opayoTokenId === '') {
                    try {
                        $params = $this->getParams($settlement);
                        $params[Constants\BillingParam::CC_CODE] = $ccCode;
                    } catch (Exception $e) {
                        return $e->getMessage();
                    }

                    // When recording customer for Opayo we use main account Opayo settings
                    $opayoResults = User_CIM::recordOpayoToken($this->cfg()->get('core->portal->mainAccountId'), $user, $params);
                    if (!$opayoResults['success']) {
                        $message = 'Failed to create entry in cim' .
                            (is_array($opayoResults)
                            && isset($opayoResults['errorMessage'])
                                ? ' ' . $opayoResults['errorMessage'] : '');
                        return $message;
                    }

                    $result = $opayoResults['result'];
                    if (isset($result[Constants\BillingOpayo::P_OPAYO_TOKEN_ID])) {
                        $userBilling->OpayoTokenId = $blockCipher->encrypt($result[Constants\BillingOpayo::P_OPAYO_TOKEN_ID]);
                        $opayoTokenId = $result[Constants\BillingOpayo::P_OPAYO_TOKEN_ID];
                    }

                    if ($isPayTraceCim) {
                        $payTraceInfo = User_CIM::recordPayTraceCim($this->cfg()->get('core->portal->mainAccountId'), $user, $params);
                        if (!$payTraceInfo['success']) {
                            $message = 'Failed to create entry in cim' .
                                (is_array($payTraceInfo)
                                && isset($payTraceInfo['errorMessage'])
                                    ? ' ' . $payTraceInfo['errorMessage'] : '');
                            return $message;
                        }

                        $result = $payTraceInfo['result'];
                        if (isset($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID])) {
                            $userBilling->PayTraceCustId = $blockCipher->encrypt($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID]);
                        }
                    } elseif ($isAuthNetCim) {
                        $authNetInfo = User_CIM::recordAuthNetCim($settlementAccountId, $user, $params);
                        if (!$authNetInfo['success']) {
                            $message = 'Failed to create entry in cim' .
                                (is_array($authNetInfo) && isset($authNetInfo['errorMessage'])
                                    ? ' ' . $authNetInfo['errorMessage'] : '');
                            return $message;
                        }

                        $result = $authNetInfo['result'];
                        if (isset($result[Constants\BillingAuthNet::P_AUTH_NET_CPI])) {
                            $userBilling->AuthNetCpi = $blockCipher->encrypt($result[Constants\BillingAuthNet::P_AUTH_NET_CPI]);
                        }
                        if (isset($result[Constants\BillingAuthNet::P_AUTH_NET_CPPI])) {
                            $userBilling->AuthNetCppi = $blockCipher->encrypt($result[Constants\BillingAuthNet::P_AUTH_NET_CPPI]);
                        }
                        if (isset($result[Constants\BillingAuthNet::P_AUTH_NET_CAI])) {
                            $userBilling->AuthNetCai = $blockCipher->encrypt($result[Constants\BillingAuthNet::P_AUTH_NET_CAI]);
                        }
                    }

                    if ($isNmiVault) {
                        // When recording customer for Nmi we use main account Nmi settings
                        $nmiInfo = User_CIM::recordNmiVault($this->cfg()->get('core->portal->mainAccountId'), $user, $params);
                        if (!$nmiInfo['success']) {
                            $message = 'Failed to create entry in cim' .
                                (is_array($nmiInfo) && isset($nmiInfo['errorMessage']) ? ' ' . $nmiInfo['errorMessage']
                                    : '');
                            return $message;
                        }

                        $result = $nmiInfo['result'];
                        if (isset($result[Constants\BillingNmi::P_NMI_VAULT_ID])) {
                            $userBilling->NmiVaultId = $blockCipher->encrypt($result[Constants\BillingNmi::P_NMI_VAULT_ID]);
                        }
                    }
                    $this->getUserBillingWriteRepository()->saveWithModifier($userBilling, $editorUserId);
                }

                $opayoManager->setParameter('VendorTxCode', $opayoManager->vendorTxCode());
                $opayoManager->setParameter('Token', $opayoTokenId);
                $opayoManager->setParameter('StoreToken', 1);
                $opayoManager->setParameter('Amount', Floating::roundOutput($amount));
            } else {
                [$expMonth, $expYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
                $ccType = $userBilling->CcType;
                $creditCard = $this->getCreditCardLoader()->load($ccType);
                $ccNumber = $blockCipher->decrypt($userBilling->CcNumber);
                $userBillingFullName = UserPureRenderer::new()->renderFullName($userBilling);
                $opayoManager->setParameter('CustomerName', $userBillingFullName);
                $opayoManager->setParameter('CardHolder', $userBillingFullName);
                $cardType = $creditCard && $creditCard->Name
                    ? $opayoManager->getCardType($creditCard->Name)
                    : '';
                $opayoManager->setParameter('CardType', $cardType);
                $opayoManager->transaction($ccNumber, $expMonth, $expYear, $amount, $ccCode);
            }

            $opayoManager->setParameter('Description', $description);
            $opayoManager->process(2);

            $message = '';
            if (!$opayoManager->isError()) {
                if ($opayoManager->isDeclined()) {
                    $message .= 'Consignor credit card declined.' . " ";
                    $message .= $opayoManager->getErrorReport();
                }

                if (
                    $opayoManager->getResultResponse() === Constants\BillingOpayo::STATUS_3D_AUTH
                    && $opayoManager->get3dStatusResponse() === Constants\BillingOpayo::STATUS_OK
                ) {
                    $params = $this->getParams($settlement);
                    $tpc = TransactionParameterCollection::new()
                        ->setParams($transactionParams)
                        ->setVpsTxId($opayoManager->getTransactionId())
                        ->setAmount(Floating::roundOutput($amount))
                        ->setPaymentItemId($settlement->Id)
                        ->setPaymentType(Constants\BillingOpayo::PT_CHARGE_SETTLEMENT)
                        ->setPaymentUrl($this->getServerRequestReader()->currentUrl())
                        ->setSessionId(session_id())
                        ->setUserId($settlement->ConsignorId);
                    $opayoManager->saveAsPaymentPending($tpc, $editorUserId);

                    $opayoManager->forwardTo3DSecure();
                }
            } else {
                $message .= 'Problem encountered consignor card.' . " ";
                $message .= $opayoManager->getErrorReport();
            }

            if ($message !== '') {
                log_error($message);
                return $message;
            }

            $paymentMethodId = Constants\Payment::PM_CC;
            $note = $opayoManager->getTransactionId();

            /*
             * Add a payment record in the database if
             * payment transaction successfully executed.
             */
            $this->createSettlementPaymentProducer()->produce(
                SettlementPayment::new()->constructNew($settlement->Id, $paymentMethodId, $amount, $note),
                $editorUserId
            );

            $note = $settlement->Note;
            $langSettlementsChargeOf = $this->getTranslator()->translate('SETTLEMENTS_CHARGE_OF', 'mysettlements');

            //change settlements status to paid
            $settlement->toPaid();
            $settlement->Note = $note . "\n$langSettlementsChargeOf {$currencySign}" . Floating::roundOutput($amount);
            $this->getSettlementWriteRepository()->saveWithModifier($settlement, $editorUserId);

            $emailManager = Email_Template::new()->construct(
                $settlement->AccountId,
                Constants\EmailKey::SETTLEMENT_PAYMENT_CONF,
                $editorUserId,
                [$settlement]
            );
            $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);

            log_info(
                'Sending payment confirmation for settlement'
                . composeSuffix(['s' => $settlement->Id, 'settlement#' => $settlement->SettlementNo])
            );
        } else {
            $message = 'Balance Due is 0';
            return $message;
        }

        return true;
    }

    /**
     * @param Settlement $settlement
     * @return array
     */
    protected function getParams(Settlement $settlement): array
    {
        $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($settlement->ConsignorId);
        if (!$this->getUserBillingChecker()->isCcInfoExists($userBilling)) {
            throw new RuntimeException(self::CC_NUMBER_ABSENT);
        }

        $params = [];
        $params[Constants\BillingParam::ACCOUNT_ID] = $settlement->AccountId;
        $params[Constants\BillingParam::BILLING_FIRST_NAME] = $userBilling->FirstName;
        $params[Constants\BillingParam::BILLING_LAST_NAME] = $userBilling->LastName;
        $params[Constants\BillingParam::BILLING_ADDRESS] = $userBilling->Address;
        $params[Constants\BillingParam::BILLING_CITY] = $userBilling->City;
        $params[Constants\BillingParam::BILLING_STATE] = $userBilling->State;
        $params[Constants\BillingParam::BILLING_COUNTRY] = $userBilling->Country;
        $params[Constants\BillingParam::BILLING_ZIP] = $userBilling->Zip;
        $params[Constants\BillingParam::BILLING_PHONE] = $userBilling->Phone;
        $params[Constants\BillingParam::BILLING_FAX] = $userBilling->Fax;
        $params[Constants\BillingParam::BILLING_EMAIL] = $userBilling->Email;
        [$expMonth, $expYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
        $ccNumber = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->CcNumber);
        $params[Constants\BillingParam::CC_TYPE] = $userBilling->CcType;
        $params[Constants\BillingParam::CC_NUMBER] = $ccNumber;
        $params[Constants\BillingParam::CC_NUMBER_HASH] = $ccNumber;
        $params[Constants\BillingParam::CC_EXP_MONTH] = $expMonth;
        $params[Constants\BillingParam::CC_EXP_YEAR] = $expYear;
        return $params;
    }
}
