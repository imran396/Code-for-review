<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Handle\Internal\Produce\Transaction;


use Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Handle\Internal\Produce\Transaction\TransactionResult as Result;
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
        int $userId,
        int $auctionId,
        int $accountId,
        int $opayoAuthTransactionType,
        string $transactionId,
        string $threeDStatusResponse,
        string $vtx,
        string $securityKey,
        float $amount
    ): Result {
        $result = Result::new()->construct();
        if (
            $opayoAuthTransactionType === Constants\BillingOpayo::OPAYO_AUTH_NONE
            || $opayoAuthTransactionType === Constants\BillingOpayo::OPAYO_AUTH_DEFERRED
        ) {
            return $result->addSuccess(Result::OK_SUCCESS_TRANSACTION);
        }

        if ($threeDStatusResponse === Constants\BillingOpayo::STATUS_ATTEMPTONLY) {
            log_info(
                'Opayo 3DSecure=ATTEMPTONLY for auction registration'
                . composeSuffix(['a' => $auctionId, 'u' => $userId])
            );
        }
        $gateManager = $this->createOpayoGateManager()->init($accountId);

        if ($opayoAuthTransactionType === Constants\BillingOpayo::OPAYO_AUTH_AUTHENTICATE_AUTHORISE) {
            $gateManager->authorize($transactionId, $vtx, $securityKey, $amount);
            log_debug(
                'authorised auction registration for bidder'
                . composeSuffix(['u' => $userId, 'a' => $auctionId])
            );
        } elseif ($opayoAuthTransactionType === Constants\BillingOpayo::OPAYO_AUTH_AUTHENTICATE) {
            $gateManager->cancel($transactionId, $vtx, $securityKey);
            log_debug(
                'authenticated auction registration for bidder'
                . composeSuffix(['u' => $userId, 'a' => $auctionId])
            );
        }

        if (!$gateManager->isError() && !$gateManager->isDeclined()) {
            //The credit card transaction has been approved
            log_debug('success ' . $gateManager->getResponseText());
            return $result->addSuccess(Result::OK_SUCCESS_TRANSACTION);
        }

        //The credit card transaction has been declined or error processing credit card transaction.
        $declinedError = !$gateManager->isError() && $gateManager->isDeclined() ? 'declined' : 'error';
        $capturedError = $declinedError . ' '
            . $gateManager->getResponseCode() . ':'
            . $gateManager->getResponseText();
        log_debug($capturedError);
        return $result->addError(Result::ERR_TRANSACTION_HAS_BEEN_DECLINED, $capturedError);
    }
}
