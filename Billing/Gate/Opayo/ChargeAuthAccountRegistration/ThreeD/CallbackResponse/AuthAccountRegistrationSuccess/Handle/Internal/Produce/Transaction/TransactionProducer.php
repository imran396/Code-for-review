<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationSuccess\Handle\Internal\Produce\Transaction;


use Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationSuccess\Handle\Internal\Produce\Transaction\TransactionResult as Result;
use Sam\Billing\Gate\Opayo\Payment\OpayoGateManagerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

class TransactionProducer extends CustomizableClass
{
    use OpayoGateManagerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produce(
        int $accountId,
        string $firstName,
        string $lastName,
        int $opayoAuthTransactionType,
        string $transactionId,
        string $threeDStatusResponse,
        string $vtx,
        string $securityKey,
        float $amount
    ): Result {
        if (
            $opayoAuthTransactionType === Constants\BillingOpayo::OPAYO_AUTH_NONE
            || $opayoAuthTransactionType === Constants\BillingOpayo::OPAYO_AUTH_DEFERRED
        ) {
            return Result::new()->construct('', '', '')
                ->addSuccess(Result::OK_SUCCESS_TRANSACTION);
        }

        if ($threeDStatusResponse === Constants\BillingOpayo::STATUS_ATTEMPTONLY) {
            log_info(
                'Opayo 3DSecure=ATTEMPTONLY for account registration'
                . composeSuffix(['firstName' => $firstName, 'lastName' => $lastName])
            );
        }
        $gateManager = $this->createOpayoGateManager()->init($accountId);

        if ($opayoAuthTransactionType === Constants\BillingOpayo::OPAYO_AUTH_AUTHENTICATE_AUTHORISE) {
            $gateManager->authorize($transactionId, $vtx, $securityKey, $amount);
            log_debug('authorised account registration');
        } elseif ($opayoAuthTransactionType === Constants\BillingOpayo::OPAYO_AUTH_AUTHENTICATE) {
            $gateManager->cancel($transactionId, $vtx, $securityKey);
            log_debug('authenticated account registration');
        }

        if (
            !$gateManager->isError()
            && !$gateManager->isDeclined()
        ) {
            //The credit card transaction has been approved
            log_debug('success ' . $gateManager->getResponseText());
            return Result::new()->construct('', '', '')->addSuccess(Result::OK_SUCCESS_TRANSACTION);
        }

        return Result::new()->construct(
            $gateManager->getResponseCode(),
            $gateManager->getCardCodeResponse(),
            $gateManager->getResponseText(),
        )->addError(Result::ERR_TRANSACTION_HAS_BEEN_DECLINED);
    }
}
