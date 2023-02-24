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

namespace Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationFailed;

use Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationFailed\AuthAccountRegistrationFailedCallbackResponseHandleResult as Result;
use Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationFailed\AuthAccountRegistrationFailedCallbackResponseHandlingInput as Input;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;


class AuthAccountRegistrationFailedCallbackResponseHandler extends CustomizableClass
{
    use TranslatorAwareTrait;

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
        log_debug('Error code ' . $input->threeDStatusResponse);
        $message = $this->buildErrorMessage($input);
        return Result::new()->construct()
            ->addSuccess(Result::OK_SUCCESS, $message);
    }

    protected function buildErrorMessage(Input $input): string
    {
        $translator = $this->getTranslator();

        $langCcProblem = $translator->translate(
            'SIGNUP_ERR_CC_PROBLEM',
            'user',
            $input->systemAccountId,
            $input->languageId
        );

        $langCcCode = $translator->translate(
            'SIGNUP_ERR_CC_CODE',
            'user',
            $input->systemAccountId,
            $input->languageId
        );

        $langCreditCard = $translator->translate(
            'SIGNUP_ERR_CC_CREDITCARD',
            'user',
            $input->systemAccountId,
            $input->languageId
        );

        $errorMessage = $langCcProblem . '<br />'
            . $langCcCode
            . ' : ' . $input->threeDStatusResponse . '<br />';
        if ($input->cardCodeResponse) {
            $errorMessage .= $langCreditCard . ' : ' . $input->cardCodeResponse . '<br />';
        }

        return $errorMessage;
    }
}
