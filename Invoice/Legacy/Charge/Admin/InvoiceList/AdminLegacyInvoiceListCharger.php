<?php
/**
 * Moved from Invoice_Factory. Needs refactoring.
 * Currently, it is called from admin Invoice List action only.
 *
 * SAM-10909: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. General adjustments
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceList;

use Email_Template;
use Eway\Rapid\Enum\PaymentMethod;
use Eway\Rapid\Enum\TransactionType;
use Exception;
use Invoice;
use Payment_Eway;
use Payment_NmiCustomerVault;
use Payment_NmiDirectPost;
use Payment_Opayo;
use Payment_PayTrace;
use RuntimeException;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Billing\CreditCard\Validate\CreditCardValidatorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\Common\Calculate\Basic\AnyInvoiceCalculatorCreateTrait;
use Sam\Invoice\Common\Charge\Common\Total\InvoiceTotalsUpdaterCreateTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserBilling\UserBillingWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Render\UserRendererAwareTrait;
use Sam\User\Validate\UserBillingCheckerAwareTrait;
use User_CIM;

class AdminLegacyInvoiceListCharger extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use AnyInvoiceCalculatorCreateTrait;
    use BlockCipherProviderCreateTrait;
    use CcExpiryDateBuilderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CreditCardLoaderAwareTrait;
    use CreditCardValidatorAwareTrait;
    use CurrencyLoaderAwareTrait;
    use DbConnectionTrait;
    use InvoiceAdditionalChargeManagerAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceLoaderAwareTrait;
    use InvoicePaymentManagerAwareTrait;
    use InvoiceTotalsUpdaterCreateTrait;
    use InvoiceUserLoaderAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use LotItemLoaderAwareTrait;
    use ServerRequestReaderAwareTrait;
    use ServerRequestReaderAwareTrait;
    use SettingsManagerAwareTrait;
    use UserBillingCheckerAwareTrait;
    use UserBillingWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;
    use UserRendererAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $invoiceId
     * @param int $editorUserId
     * @return bool|string
     */
    public function chargeInvoiceThroughPayTrace(int $invoiceId, int $editorUserId): bool|string
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            $this->handleChargeErrorInvoiceNotFound($invoiceId);
        }

        $invoiceAccountId = $invoice->AccountId;
        $isNmiVault = (bool)$this->getSettingsManager()->get(Constants\Setting::NMI_VAULT, $invoiceAccountId);
        $isPayTraceCim = (bool)$this->getSettingsManager()->get(Constants\Setting::PAY_TRACE_CIM, $invoiceAccountId);
        $amount = $this->calcRoundedBalanceDue($invoice);
        $userLoader = $this->getUserLoader();

        $userBilling = $userLoader->loadUserBillingOrCreate($invoice->BidderId);
        $this->getDb()->TransactionBegin();
        $amount = $this->getInvoiceAdditionalChargeManager()->addCcSurchargeToTheAmount(
            $userBilling->CcType,
            $amount,
            $invoice,
            $editorUserId
        );

        $description = 'Charging Invoice ' . $invoice->InvoiceNo;
        $invoicedAuctionItemDtos = $this->getInvoiceItemLoader()->loadInvoicedAuctionDtos($invoice->Id);
        if ($invoicedAuctionItemDtos) {
            $salesNoList = [];
            foreach ($invoicedAuctionItemDtos as $invoicedAuctionItemDto) {
                $salesNoList[] = $invoicedAuctionItemDto->makeSaleNo();
            }
            $description .= ' Sales(' . implode(',', $salesNoList) . ')';
        }

        if (Floating::gt($amount, 0)) {
            $ccType = '';
            $isCim = $isPayTraceCim;
            $payTraceManager = new Payment_PayTrace($invoiceAccountId);
            $user = $userLoader->load($invoice->BidderId);
            if (!$user) {
                $this->getDb()->TransactionRollback();
                $this->handleChargeErrorInvoiceWinnerNotFound($invoice->Id, $invoice->BidderId);
            }

            $invoiceBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreatePersisted($invoice->Id, $editorUserId);
            $blockCipher = $this->createBlockCipherProvider()->construct();
            $ccNumber = $blockCipher->decrypt($userBilling->CcNumber);

            if ($isCim) { // USE CIM
                $payTraceCustId = $blockCipher->decrypt($userBilling->PayTraceCustId);
                if ($payTraceCustId === '') {
                    try {
                        $params = $this->getParams($invoice, $editorUserId);
                    } catch (Exception $e) {
                        $this->getDb()->TransactionRollback();
                        return $e->getMessage();
                    }

                    // When recording customer for paytrace we use main account paytrace settings
                    $response = User_CIM::recordPayTraceCim($this->cfg()->get('core->portal->mainAccountId'), $user, $params);
                    if (!$response['success']) {
                        $errorMessage = 'Failed to create entry in cim' .
                            (is_array($response) && isset($response['errorMessage'])
                                ? ' ' . $response['errorMessage'] : '');
                        $this->getDb()->TransactionRollback();
                        return $errorMessage;
                    }

                    $result = $response['result'];
                    if (isset($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID])) {
                        $userBilling->PayTraceCustId = $blockCipher->encrypt($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID]);
                        $payTraceCustId = $result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID];
                    }

                    if ($isNmiVault) {
                        $nmiResponse = User_CIM::recordNmiVault($this->cfg()->get('core->portal->mainAccountId'), $user, $params);
                        if (!$nmiResponse['success']) {
                            $errorMessage = 'Failed to create entry in cim' .
                                (is_array($nmiResponse) && isset($nmiResponse['errorMessage'])
                                    ? ' ' . $nmiResponse['errorMessage']
                                    : '');
                            $this->getDb()->TransactionRollback();
                            return $errorMessage;
                        }
                        $result = $nmiResponse['result'];
                        if (isset($result[Constants\BillingNmi::P_NMI_VAULT_ID])) {
                            $userBilling->NmiVaultId = $blockCipher->encrypt($result[Constants\BillingNmi::P_NMI_VAULT_ID]);
                        }
                    }
                    $this->getUserBillingWriteRepository()->saveWithModifier($userBilling, $editorUserId);
                }

                $payTraceManager->setParameter('CUSTID', $payTraceCustId);
                $payTraceManager->setParameter('AMOUNT', sprintf("%01.2F", $amount));
            } else {
                if (
                    $blockCipher->decrypt($userBilling->CcNumber) === ''
                    || !$this->getCreditCardValidator()->validateExpiredDateFormatted($userBilling->CcExpDate)
                ) {
                    $this->getDb()->TransactionRollback();
                    return 'Bidder has no credit card info';
                }

                [$ccExpMonth, $ccExpYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
                $ccType = $userBilling->CcType;

                $payTraceManager->setParameter('BNAME', UserPureRenderer::new()->renderFullName($invoiceBilling));
                $payTraceManager->setParameter('BADDRESS', $invoiceBilling->Address);
                $payTraceManager->setParameter('BCITY', $invoiceBilling->City);
                if (strlen($invoiceBilling->State) === 2) {
                    $payTraceManager->setParameter('BSTATE', $invoiceBilling->State);
                }
                $payTraceManager->setParameter('BZIP', $invoiceBilling->Zip);
                if (strlen($invoiceBilling->Country) === 2) {
                    $payTraceManager->setParameter('BCOUNTRY', $invoiceBilling->Country);
                }
                //$payTraceManager->setParameter('PHONE', $invoiceBilling->Phone); Exclude phone/fax because it has different formatting
                //$payTraceManager->setParameter('FAX', $invoiceBilling->Fax);
                $payTraceManager->transaction($ccNumber, $ccExpMonth, $ccExpYear, $amount);
            }

            $payTraceManager->setMethod('ProcessTranx');
            $payTraceManager->setTransactionType('Sale');
            $payTraceManager->setParameter('DESCRIPTION', $description);
            $payTraceManager->setParameter('INVOICE', $invoice->InvoiceNo);
            $email = $this->getUserRenderer()->renderAccountingEmail($user, $userBilling, $invoiceBilling);
            $payTraceManager->setParameter('EMAIL', $email);
            $payTraceManager->process(2);

            $errorMessage = '';
            if (!$payTraceManager->isError()) {
                if ($payTraceManager->isDeclined()) {
                    $errorMessage .= 'Credit Card Declined. <br />';
                    $errorMessage .= $payTraceManager->getErrorReport();
                }
            } else {
                $errorMessage .= 'Problem encountered in your credit card validation. <br />';
                $errorMessage .= $payTraceManager->getErrorReport();
            }

            if ($errorMessage !== '') {
                log_error($errorMessage);
                $this->getDb()->TransactionRollback();
                return $errorMessage;
            }

            // PayTrace always return transaction id even in test mode
            $note = 'Trans.:' . $payTraceManager->getTransactionId() . ' CC:' . substr($ccNumber, -4);

            /*
             * Add a payment record in the database if
             * payment transaction successfully executed.
             */
            $this->getInvoicePaymentManager()->add(
                $invoice->Id,
                Constants\Payment::PM_CC,
                $amount,
                $editorUserId,
                $note,
                null,
                null,
                $ccType
            );

            // Change invoice status to paid if status was not shipped
            if (!$invoice->isShipped()) {
                $invoice->toPaid();
            }

            $invoice = $this->createInvoiceTotalsUpdater()->calcAndAssign($invoice);
            $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
            $this->getDb()->TransactionCommit();

            // Email customer as a proof of their payment
            $emailManager = Email_Template::new()->construct(
                $invoice->AccountId,
                Constants\EmailKey::PAYMENT_CONF,
                $editorUserId,
                [$invoice]
            );
            $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);

            log_info(
                'Sending payment confirmation for invoice'
                . composeSuffix(['i' => $invoice->Id, 'invoice#' => $invoice->InvoiceNo])
            );
        }

        return true;
    }

    /**
     * @param int $invoiceId
     * @param int $editorUserId
     * @return bool|string
     */
    public function chargeInvoiceThroughEway(int $invoiceId, int $editorUserId): bool|string
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            $this->handleChargeErrorInvoiceNotFound($invoiceId);
        }

        $accountId = $invoice->AccountId;
        $ewayEncryptionKey = $this->getSettingsManager()->get(Constants\Setting::EWAY_ENCRYPTION_KEY, $accountId);
        $userLoader = $this->getUserLoader();
        $amount = $this->calcRoundedBalanceDue($invoice);

        $userBilling = $userLoader->loadUserBillingOrCreate($invoice->BidderId);
        $this->getDb()->TransactionBegin();
        $amount = $this->getInvoiceAdditionalChargeManager()->addCcSurchargeToTheAmount(
            $userBilling->CcType,
            $amount,
            $invoice,
            $editorUserId
        );

        $description = 'Charging Invoice ' . $invoice->InvoiceNo;
        $invoicedAuctionItemDtos = $this->getInvoiceItemLoader()->loadInvoicedAuctionDtos($invoice->Id);
        if ($invoicedAuctionItemDtos) {
            $salesNoList = [];
            foreach ($invoicedAuctionItemDtos as $invoicedAuctionItemDto) {
                $salesNoList[] = $invoicedAuctionItemDto->makeSaleNo();
            }
            $description .= ' Sales(' . implode(',', $salesNoList) . ')';
        }

        if (Floating::gt($amount, 0)) {
            $ewayManager = Payment_Eway::new()->init($accountId);
            $user = $userLoader->load($invoice->BidderId);
            if (!$user) {
                $this->getDb()->TransactionRollback();
                $this->handleChargeErrorInvoiceWinnerNotFound($invoice->Id, $invoice->BidderId);
            }

            $ccNumber = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->CcNumber);
            if ($ewayEncryptionKey !== '') {
                $ccNumber = $userBilling->CcNumberEway;
            }
            $invoiceBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreatePersisted($invoice->Id, $editorUserId);

            if (!$this->getUserBillingChecker()->isCcInfoExists($userBilling)) {
                $this->getDb()->TransactionRollback();
                return 'Bidder has no credit card info';
            }

            [$ccExpMonth, $ccExpYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
            $ccType = $userBilling->CcType;

            $params = [];
            $params["Customer"]["CardDetails"]["Name"] = UserPureRenderer::new()->renderFullName($invoiceBilling);
            $params["Customer"]["Reference"] = $user->CustomerNo;
            $params["Customer"]["FirstName"] = $invoiceBilling->FirstName;
            $params["Customer"]["LastName"] = $invoiceBilling->LastName;
            $params["Customer"]["Street1"] = $invoiceBilling->Address;
            $params["Customer"]["City"] = $invoiceBilling->City;
            $params["Customer"]["State"] = $invoiceBilling->State;
            $params["Customer"]["PostalCode"] = $invoiceBilling->Zip;
            $params["Customer"]["Country"] = $invoiceBilling->Country;
            $params["Customer"]["Phone"] = $invoiceBilling->Phone;
            $params["Customer"]["Fax"] = $invoiceBilling->Fax;

            $ewayManager->setMethod(PaymentMethod::PROCESS_PAYMENT);
            $ewayManager->transaction($ccNumber, $ccExpMonth, $ccExpYear, $amount);

            $params["Payment"]["InvoiceNumber"] = $invoice->InvoiceNo;
            $params["Payment"]["InvoiceDescription"] = $description;
            $email = $this->getUserRenderer()->renderAccountingEmail($user, $userBilling, $invoiceBilling);
            $params["Customer"]["Email"] = $email;
            $ewayManager->setParameter($params);
            $ewayManager->setTransactionType(TransactionType::MOTO);
            $ewayManager->process();

            $errorMessage = '';
            if (!$ewayManager->isError()) {
                if ($ewayManager->isDeclined()) {
                    $errorMessage .= 'Credit Card Declined. <br />';
                    $errorMessage .= $ewayManager->getResultResponse() . ':' . $ewayManager->getResponseText();
                }
            } else {
                $errorMessage .= 'Problem encountered in your credit card validation. <br />';
                $errorMessage .= $ewayManager->getResultResponse() . ':' . $ewayManager->getResponseText();
            }

            if ($errorMessage !== '') {
                log_error($errorMessage);
                $this->getDb()->TransactionRollback();
                return $errorMessage;
            }

            // PayTrace always return transaction id even in test mode
            $note = 'Trans.:' . $ewayManager->getTransactionId() . ' CC:' . substr($ccNumber, -4);

            /*
             * Add a payment record in the database if
             * payment transaction successfully executed.
             */
            $this->getInvoicePaymentManager()->add(
                $invoice->Id,
                Constants\Payment::PM_CC,
                $amount,
                $editorUserId,
                $note,
                null,
                null,
                $ccType
            );

            // Change invoice status to paid if status was not shipped
            if (!$invoice->isShipped()) {
                $invoice->toPaid();
            }

            $invoice = $this->createInvoiceTotalsUpdater()->calcAndAssign($invoice);
            $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

            $this->getDb()->TransactionCommit();
            // Email customer as a proof of their payment
            $emailManager = Email_Template::new()->construct(
                $invoice->AccountId,
                Constants\EmailKey::PAYMENT_CONF,
                $editorUserId,
                [$invoice]
            );
            $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);

            log_info(
                'Sending payment confirmation for invoice'
                . composeSuffix(['i' => $invoice->Id, 'invoice#' => $invoice->InvoiceNo])
            );
        }

        return true;
    }

    /**
     * @param int $invoiceId
     * @param int $editorUserId
     * @return bool|string
     */
    public function chargeInvoiceThroughNmi(int $invoiceId, int $editorUserId): bool|string
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            $this->handleChargeErrorInvoiceNotFound($invoiceId);
        }

        $invoiceAccountId = $invoice->AccountId;
        $isAuthNetCim = (bool)$this->getSettingsManager()->get(Constants\Setting::AUTH_NET_CIM, $invoiceAccountId);
        $isPayTraceCim = (bool)$this->getSettingsManager()->get(Constants\Setting::PAY_TRACE_CIM, $invoiceAccountId);
        $isNmiVault = (bool)$this->getSettingsManager()->get(Constants\Setting::NMI_VAULT, $invoiceAccountId);
        $userLoader = $this->getUserLoader();
        $amount = $this->calcRoundedBalanceDue($invoice);
        $description = 'Charging Invoice ' . $invoice->InvoiceNo;

        $userBilling = $userLoader->loadUserBillingOrCreate($invoice->BidderId);
        $this->getDb()->TransactionBegin();
        $amount = $this->getInvoiceAdditionalChargeManager()->addCcSurchargeToTheAmount(
            $userBilling->CcType,
            $amount,
            $invoice,
            $editorUserId
        );

        if (Floating::gt($amount, 0)) {
            $ccType = '';

            $user = $userLoader->load($invoice->BidderId);
            if (!$user) {
                $this->getDb()->TransactionRollback();
                $this->handleChargeErrorInvoiceWinnerNotFound($invoice->Id, $invoice->BidderId);
            }

            $blockCipher = $this->createBlockCipherProvider()->construct();
            $ccNumber = $blockCipher->decrypt($userBilling->CcNumber);

            if ($isNmiVault) { // USE Vault
                $billingZip = $userBilling->Zip;
                $nmiVaultId = $blockCipher->decrypt($userBilling->NmiVaultId);
                if ($nmiVaultId === '') {
                    try {
                        $params = $this->getParams($invoice, $editorUserId);
                    } catch (Exception $e) {
                        $this->getDb()->TransactionRollback();
                        return $e->getMessage();
                    }

                    // When recording customer for Nmi we use main account Nmi settings
                    $nmiInfo = User_CIM::recordNmiVault($this->cfg()->get('core->portal->mainAccountId'), $user, $params);
                    if (!$nmiInfo['success']) {
                        $errorMessage = 'Failed to create entry in cim' .
                            (is_array($nmiInfo) && isset($nmiInfo['errorMessage']) ? ' ' . $nmiInfo['errorMessage']
                                : '');
                        $this->getDb()->TransactionRollback();
                        return $errorMessage;
                    }

                    $result = $nmiInfo['result'];
                    if (isset($result[Constants\BillingNmi::P_NMI_VAULT_ID])) {
                        $userBilling->NmiVaultId = $blockCipher->encrypt($result[Constants\BillingNmi::P_NMI_VAULT_ID]);
                        $nmiVaultId = $result[Constants\BillingNmi::P_NMI_VAULT_ID];
                    }

                    if ($isPayTraceCim) {
                        $payTraceResults = User_CIM::recordPayTraceCim(
                            $this->cfg()->get('core->portal->mainAccountId'),
                            $user,
                            $params
                        );
                        if (!$payTraceResults['success']) {
                            $errorMessage = 'Failed to create entry in cim' .
                                (is_array($payTraceResults) && isset($payTraceResults['errorMessage'])
                                    ? ' ' . $payTraceResults['errorMessage'] : '');
                            $this->getDb()->TransactionRollback();
                            return $errorMessage;
                        }

                        $result = $payTraceResults['result'];
                        if (isset($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID])) {
                            $userBilling->PayTraceCustId = $blockCipher->encrypt($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID]);
                        }
                    } elseif ($isAuthNetCim) {
                        $authNetResults = User_CIM::recordAuthNetCim($invoiceAccountId, $user, $params);
                        if (!$authNetResults['success']) {
                            $errorMessage = 'Failed to create entry in cim' .
                                (is_array($authNetResults) && isset($authNetResults['errorMessage'])
                                    ? ' ' . $authNetResults['errorMessage'] : '');
                            $this->getDb()->TransactionRollback();
                            return $errorMessage;
                        }

                        $result = $authNetResults['result'];
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

                $nmiVaultManager = new Payment_NmiCustomerVault($invoiceAccountId);
                $nmiVaultManager->setParameter('customer_vault_id', $nmiVaultId);
                $nmiVaultManager->setParameter('amount', sprintf("%01.2F", $amount));
                $nmiVaultManager->setParameter('zip', $billingZip);
            } else {
                $invoiceBilling = $this->getInvoiceUserLoader()
                    ->loadInvoiceUserBillingOrCreatePersisted($invoice->Id, $editorUserId);
                if (
                    $blockCipher->decrypt($userBilling->CcNumber) === ''
                    || !$this->getCreditCardValidator()->validateExpiredDateFormatted($userBilling->CcExpDate)
                ) {
                    $this->getDb()->TransactionRollback();
                    return 'Bidder has no credit card info';
                }

                [$ccExpMonth, $ccExpYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
                $ccType = $userBilling->CcType;

                $nmiVaultManager = new Payment_NmiDirectPost($invoiceAccountId);
                $nmiVaultManager->setTransactionType('sale');
                $nmiVaultManager->setParameter('firstname', $invoiceBilling->FirstName);
                $nmiVaultManager->setParameter('lastname', $invoiceBilling->LastName);
                if (strlen($invoiceBilling->Country) === 2) {
                    $nmiVaultManager->setParameter('country', $invoiceBilling->Country);
                }
                $nmiVaultManager->setParameter('address1', $invoiceBilling->Address);
                $nmiVaultManager->setParameter('city', $invoiceBilling->City);
                if (strlen($invoiceBilling->State) === 2) {
                    $nmiVaultManager->setParameter('state', $invoiceBilling->State);
                }
                $nmiVaultManager->setParameter('zip', $invoiceBilling->Zip);
                $nmiVaultManager->setParameter('phone', $invoiceBilling->Phone);
                $email = $this->getUserRenderer()->renderAccountingEmail($user, $userBilling, $invoiceBilling);
                $nmiVaultManager->setParameter('email', $email);
                $nmiVaultManager->transaction($ccNumber, $ccExpMonth, $ccExpYear, $amount);
            }
            $nmiVaultManager->setParameter('orderdescription', $description);
            $nmiVaultManager->process(2);

            $errorMessage = '';
            if (!$nmiVaultManager->isError()) {
                if ($nmiVaultManager->isDeclined()) {
                    $errorMessage .= 'Credit Card Declined. <br />';
                    $errorMessage .= $nmiVaultManager->getErrorReport();
                }
            } else {
                $errorMessage .= 'Problem encountered in your credit card validation. <br />';
                $errorMessage .= $nmiVaultManager->getErrorReport();
            }

            if ($errorMessage !== '') {
                log_error($errorMessage);
                $this->getDb()->TransactionRollback();
                return $errorMessage;
            }

            // Nmi always return transaction id even in test mode
            $note = 'Trans.:' . $nmiVaultManager->getTransactionId() . ' CC:' . substr($ccNumber, -4);

            /*
             * Add a payment record in the database if
             * payment transaction successfully executed.
             */
            $this->getInvoicePaymentManager()->add(
                $invoice->Id,
                Constants\Payment::PM_CC,
                $amount,
                $editorUserId,
                $note,
                null,
                null,
                $ccType
            );

            // Change invoice status to paid if status was not shipped
            if (!$invoice->isShipped()) {
                $invoice->toPaid();
            }

            $invoice = $this->createInvoiceTotalsUpdater()->calcAndAssign($invoice);
            $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

            $this->getDb()->TransactionCommit();
            // Email customer as a proof of their payment
            $emailManager = Email_Template::new()->construct(
                $invoice->AccountId,
                Constants\EmailKey::PAYMENT_CONF,
                $editorUserId,
                [$invoice]
            );
            $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);

            log_info(
                'Sending payment confirmation for invoice id'
                . composeSuffix(['i' => $invoice->Id, 'invoice#' => $invoice->InvoiceNo])
            );
        }

        return true;
    }

    /**
     * Function that process payment of invoices
     * through Opayo API Gateway
     *
     * @param int $invoiceId (int)
     * @param int $editorUserId
     * @return bool|string
     */
    public function chargeInvoiceThroughOpayo(int $invoiceId, int $editorUserId): bool|string
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            $this->handleChargeErrorInvoiceNotFound($invoiceId);
        }

        $userLoader = $this->getUserLoader();

        $user = $userLoader->load($invoice->BidderId);
        if (!$user) {
            $this->handleChargeErrorInvoiceWinnerNotFound($invoice->Id, $invoice->BidderId);
        }

        $amount = $this->calcRoundedBalanceDue($invoice);

        $userBilling = $userLoader->loadUserBillingOrCreate($invoice->BidderId);
        $this->getDb()->TransactionBegin();
        $amount = $this->getInvoiceAdditionalChargeManager()->addCcSurchargeToTheAmount(
            $userBilling->CcType,
            $amount,
            $invoice,
            $editorUserId
        );

        if (Floating::lt($amount, 0)) {
            return true;
        }

        $invoiceAccountId = $invoice->AccountId;
        $sm = $this->getSettingsManager();
        $isAuthNetCim = (bool)$sm->get(Constants\Setting::AUTH_NET_CIM, $invoiceAccountId);
        $isPayTraceCim = (bool)$sm->get(Constants\Setting::PAY_TRACE_CIM, $invoiceAccountId);
        $isNmiVault = (bool)$sm->get(Constants\Setting::NMI_VAULT, $invoiceAccountId);
        $isOpayoToken = (bool)$sm->get(Constants\Setting::OPAYO_TOKEN, $invoiceAccountId);
        $isVault = $isOpayoToken;

        $ccType = '';
        $invoiceBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreatePersisted($invoice->Id, $editorUserId);
        $blockCipher = $this->createBlockCipherProvider()->construct();
        $ccNumber = $blockCipher->decrypt($userBilling->CcNumber);

        $opayoManager = Payment_Opayo::new()->init($invoiceAccountId)
            ->disableCVVCheck()
            ->disableThreeDSecure();
        $opayoManager->setTransactionType('PAYMENT');
        $opayoManager->setParameter('BillingFirstnames', $invoiceBilling->FirstName);
        $opayoManager->setParameter('BillingSurname', $invoiceBilling->LastName);
        $opayoManager->setParameter('BillingCountry', $invoiceBilling->Country);
        $opayoManager->setParameter('BillingAddress1', $invoiceBilling->Address);
        $opayoManager->setParameter('BillingCity', $invoiceBilling->City);
        if (strlen($invoiceBilling->State) === 2) {
            $opayoManager->setParameter('BillingState', $invoiceBilling->State);
        }
        $opayoManager->setParameter('BillingPostCode', $invoiceBilling->Zip);
        $opayoManager->setParameter('BillingPhone', $invoiceBilling->Phone);
        $email = $this->getUserRenderer()->renderAccountingEmail($user, $userBilling, $invoiceBilling);
        $opayoManager->setParameter('CustomerEMail', $email);

        if ($isVault) { // USE Vault
            $opayoTokenId = $blockCipher->decrypt($userBilling->OpayoTokenId);
            if ($opayoTokenId === '') {
                try {
                    $params = $this->getParams($invoice, $editorUserId);
                } catch (Exception $e) {
                    $this->getDb()->TransactionRollback();
                    return $e->getMessage();
                }

                // When recording customer for Opayo we use main account Opayo settings
                $opayoResults = User_CIM::recordOpayoToken($this->cfg()->get('core->portal->mainAccountId'), $user, $params);
                if (!$opayoResults['success']) {
                    $errorMessage = 'Failed to create entry in cim' .
                        (is_array($opayoResults) && isset($opayoResults['errorMessage'])
                            ? ' ' . $opayoResults['errorMessage'] : '');
                    $this->getDb()->TransactionRollback();
                    return $errorMessage;
                }

                $result = $opayoResults['result'];
                if (isset($result[Constants\BillingOpayo::P_OPAYO_TOKEN_ID])) {
                    $userBilling->OpayoTokenId = $blockCipher->encrypt($result[Constants\BillingOpayo::P_OPAYO_TOKEN_ID]);
                    $opayoTokenId = $result[Constants\BillingOpayo::P_OPAYO_TOKEN_ID];
                }

                if ($isPayTraceCim) {
                    $payTraceResults = User_CIM::recordPayTraceCim(
                        $this->cfg()->get('core->portal->mainAccountId'),
                        $user,
                        $params
                    );
                    if (!$payTraceResults['success']) {
                        $errorMessage = 'Failed to create entry in cim' .
                            (is_array($payTraceResults) && isset($payTraceResults['errorMessage'])
                                ? ' ' . $payTraceResults['errorMessage'] : '');
                        $this->getDb()->TransactionRollback();
                        return $errorMessage;
                    }

                    $result = $payTraceResults['result'];
                    if (isset($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID])) {
                        $userBilling->PayTraceCustId = $blockCipher->encrypt($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID]);
                    }
                } elseif ($isAuthNetCim) {
                    $authNetResults = User_CIM::recordAuthNetCim($invoiceAccountId, $user, $params);
                    if (!$authNetResults['success']) {
                        $errorMessage = 'Failed to create entry in cim' .
                            (is_array($authNetResults) && isset($authNetResults['errorMessage'])
                                ? ' ' . $authNetResults['errorMessage'] : '');
                        $this->getDb()->TransactionRollback();
                        return $errorMessage;
                    }

                    $result = $authNetResults['result'];
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
                        $errorMessage = 'Failed to create entry in cim' .
                            (is_array($nmiInfo) && isset($nmiInfo['errorMessage']) ? ' ' . $nmiInfo['errorMessage']
                                : '');
                        $this->getDb()->TransactionRollback();
                        return $errorMessage;
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
            $opayoManager->setParameter('Amount', sprintf("%00.2F", $amount));
        } else {
            if (
                $ccNumber === ''
                || !$this->getCreditCardValidator()->validateExpiredDateFormatted($userBilling->CcExpDate)
            ) {
                $this->getDb()->TransactionRollback();
                return 'Bidder has no credit card info';
            }

            [$ccExpMonth, $ccExpYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
            $ccType = $userBilling->CcType;
            $creditCard = $this->getCreditCardLoader()->load($ccType);
            $cardType = $creditCard ? $opayoManager->getCardType($creditCard->Name) : '';

            $fullName = UserPureRenderer::new()->renderFullName($invoiceBilling);
            $opayoManager->setParameter('CustomerName', $fullName);
            $opayoManager->setParameter('CardHolder', $fullName);
            $opayoManager->setParameter('CardType', $cardType);
            $opayoManager->transaction($ccNumber, $ccExpMonth, $ccExpYear, $amount);
        }

        $opayoManager->setParameter('Description', sprintf('Charging Invoice %s', $invoice->InvoiceNo));
        $opayoManager->process(2);

        $errorMessage = '';
        if (!$opayoManager->isError()) {
            if ($opayoManager->isDeclined()) {
                $errorMessage .= 'Credit Card Declined. <br />';
                $errorMessage .= $opayoManager->getErrorReport();
            }
        } else {
            $errorMessage .= 'Problem encountered in your credit card validation. <br />';
            $errorMessage .= $opayoManager->getErrorReport();
        }

        if ($errorMessage !== '') {
            log_error($errorMessage);
            $this->getDb()->TransactionRollback();
            return $errorMessage;
        }

        // Opayo always return transaction id even in test mode
        $note = 'Trans.:' . $opayoManager->getTransactionId() . ' CC:' . substr($ccNumber, -4);

        /*
         * Add a payment record in the database if
         * payment transaction successfully executed.
         */
        $this->getInvoicePaymentManager()->add(
            $invoice->Id,
            Constants\Payment::PM_CC,
            $amount,
            $editorUserId,
            $note,
            null,
            null,
            $ccType
        );

        // Change invoice status to paid if status was not shipped
        if (!$invoice->isShipped()) {
            $invoice->toPaid();
        }

        $invoice = $this->createInvoiceTotalsUpdater()->calcAndAssign($invoice);
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

        $this->getDb()->TransactionCommit();
        // Email customer as a proof of their payment
        $emailManager = Email_Template::new()->construct(
            $invoice->AccountId,
            Constants\EmailKey::PAYMENT_CONF,
            $editorUserId,
            [$invoice]
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);

        log_info(
            'Sending payment confirmation for invoice'
            . composeSuffix(['i' => $invoice->Id, 'invoice#' => $invoice->InvoiceNo])
        );
        return true;
    }

    /**
     * @param Invoice $invoice
     * @param int $editorUserId
     * @return array
     */
    protected function getParams(Invoice $invoice, int $editorUserId): array
    {
        $bidderBilling = $this->getUserLoader()->loadUserBillingOrCreate($invoice->BidderId);
        if (
            $this->createBlockCipherProvider()->construct()->decrypt($bidderBilling->CcNumber) === ''
            || $bidderBilling->CcExpDate === ''
        ) {
            throw new RuntimeException('Bidder has no CIM and has no credit card info');
        }
        $params = [];
        $params[Constants\BillingParam::ACCOUNT_ID] = $invoice->AccountId;

        $invoiceBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreatePersisted($invoice->Id, $editorUserId);
        $params[Constants\BillingParam::BILLING_COMPANY_NAME] = $invoiceBilling->CompanyName;
        $params[Constants\BillingParam::BILLING_FIRST_NAME] = $invoiceBilling->FirstName;
        $params[Constants\BillingParam::BILLING_LAST_NAME] = $invoiceBilling->LastName;
        $params[Constants\BillingParam::BILLING_ADDRESS] = $invoiceBilling->Address;
        $params[Constants\BillingParam::BILLING_ADDRESS_2] = $invoiceBilling->Address2;
        $params[Constants\BillingParam::BILLING_ADDRESS_3] = $invoiceBilling->Address3;
        $params[Constants\BillingParam::BILLING_CITY] = $invoiceBilling->City;
        $params[Constants\BillingParam::BILLING_STATE] = $invoiceBilling->State;
        $params[Constants\BillingParam::BILLING_COUNTRY] = $invoiceBilling->Country;
        $params[Constants\BillingParam::BILLING_ZIP] = $invoiceBilling->Zip;
        $params[Constants\BillingParam::BILLING_PHONE] = $invoiceBilling->Phone;
        $params[Constants\BillingParam::BILLING_FAX] = $invoiceBilling->Fax;
        $params[Constants\BillingParam::BILLING_EMAIL] = $invoiceBilling->Email;

        $invoiceShipping = $this->getInvoiceUserLoader()
            ->loadInvoiceUserShippingOrCreatePersisted($invoice->Id, $editorUserId);
        $params[Constants\BillingParam::SHIPPING_COMPANY_NAME] = $invoiceShipping->CompanyName;
        $params[Constants\BillingParam::SHIPPING_FIRST_NAME] = $invoiceShipping->FirstName;
        $params[Constants\BillingParam::SHIPPING_LAST_NAME] = $invoiceShipping->LastName;
        $params[Constants\BillingParam::SHIPPING_ADDRESS] = $invoiceShipping->Address;
        $params[Constants\BillingParam::SHIPPING_ADDRESS_2] = $invoiceShipping->Address2;
        $params[Constants\BillingParam::SHIPPING_ADDRESS_3] = $invoiceShipping->Address3;
        $params[Constants\BillingParam::SHIPPING_CITY] = $invoiceShipping->City;
        $params[Constants\BillingParam::SHIPPING_STATE] = $invoiceShipping->State;
        $params[Constants\BillingParam::SHIPPING_COUNTRY] = $invoiceShipping->Country;
        $params[Constants\BillingParam::SHIPPING_ZIP] = $invoiceShipping->Zip;
        $params[Constants\BillingParam::SHIPPING_PHONE] = $invoiceShipping->Phone;
        $params[Constants\BillingParam::SHIPPING_FAX] = $invoiceShipping->Fax;

        [$ccExpMonth, $ccExpYear] = $this->createCcExpiryDateBuilder()->explode($bidderBilling->CcExpDate);

        $params[Constants\BillingParam::CC_TYPE] = $bidderBilling->CcType;
        $params[Constants\BillingParam::CC_NUMBER] = $this->createBlockCipherProvider()->construct()->decrypt($bidderBilling->CcNumber);
        $params[Constants\BillingParam::CC_EXP_MONTH] = $ccExpMonth;
        $params[Constants\BillingParam::CC_EXP_YEAR] = $ccExpYear;

        return $params;
    }

    /**
     * @param int $invoiceId
     */
    protected function handleChargeErrorInvoiceNotFound(int $invoiceId): void
    {
        $message = "Cannot charge payment - active invoice not found" . composeSuffix(['i' => $invoiceId]);
        log_error($message);
        throw new RuntimeException($message);
    }

    /**
     * @param int $invoiceId
     * @param int $userId
     */
    protected function handleChargeErrorInvoiceWinnerNotFound(int $invoiceId, int $userId): void
    {
        $message = "Cannot charge payment - active user of invoice winner not found"
            . composeSuffix(['i' => $invoiceId, 'u' => $userId]);
        log_error($message);
        throw new RuntimeException($message);
    }

    protected function calcRoundedBalanceDue(Invoice $invoice): float
    {
        return $this->createAnyInvoiceCalculator()->calcRoundedBalanceDue($invoice);
    }
}
