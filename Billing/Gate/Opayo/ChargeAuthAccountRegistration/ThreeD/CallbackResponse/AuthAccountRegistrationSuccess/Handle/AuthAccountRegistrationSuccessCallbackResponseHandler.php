<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Oct 3, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationSuccess\Handle;

use Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationSuccess\Handle\AuthAccountRegistrationSuccessCallbackResponseHandleResult as Result;
use Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationSuccess\AuthAccountRegistrationSuccessCallbackResponseHandlingInput as Input;
use Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationSuccess\Handle\Internal\Produce\Transaction\TransactionProducerCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;


class AuthAccountRegistrationSuccessCallbackResponseHandler extends CustomizableClass
{
    use TranslatorAwareTrait;
    use TransactionProducerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function handle(Input $input): Result
    {
        $transactionProducer = $this->createTransactionProducer();
        $transactionResult = $transactionProducer->produce(
            $input->accountId,
            $input->firstName,
            $input->lastName,
            $input->opayoAuthTransactionType,
            $input->transactionId,
            $input->threeDStatusResponse,
            $input->vtx,
            $input->securityKey,
            $input->amount
        );

        if ($transactionResult->hasError()) {
            $errorMessage = $this->buildErrorMessage(
                $transactionResult->getResponseCode(),
                $transactionResult->getCardCodeResponse(),
                $transactionResult->getResponseText(),
                $input->systemAccountId,
                $input->languageId
            );
            return Result::new()->construct()
                ->addError(Result::ERR_TRANSACTION_ERROR, $errorMessage);
        }

        return Result::new()->construct()
            ->addSuccess(Result::OK_SUCCESS);
    }

    protected function buildErrorMessage(
        string $responseCode,
        string $cardCodeResponse,
        string $responseText,
        int $systemAccountId,
        ?int $languageId
    ): string {
        $translator = $this->getTranslator();
        $signupErrorCcProblemTranslation = $translator->translate(
            'SIGNUP_ERR_CC_PROBLEM',
            'user',
            $systemAccountId,
            $languageId
        );

        $signupErrorCcCodeTranslation = $translator->translate(
            'SIGNUP_ERR_CC_CODE',
            'user',
            $systemAccountId,
            $languageId
        );

        $errorMessage = $signupErrorCcProblemTranslation . '<br>'
            . $signupErrorCcCodeTranslation . ' : ' . $responseCode
            . ':' . $responseText . '<br />';

        if ($cardCodeResponse !== '') {
            $signupErrorCreditCardTranslation = $translator->translate(
                'SIGNUP_ERR_CC_CREDITCARD',
                'user',
                $systemAccountId,
                $languageId
            );
            $errorMessage .= $signupErrorCreditCardTranslation . ' :' . $cardCodeResponse . ' <br />';
        }
        return $errorMessage;
    }
}
