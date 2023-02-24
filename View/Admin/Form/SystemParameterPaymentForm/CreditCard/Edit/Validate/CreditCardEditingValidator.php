<?php
/**
 * SAM-10335: Allow to adjust CC surcharge per account: Implementation (Dev)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SystemParameterPaymentForm\CreditCard\Edit\Validate;

use Sam\Core\Validate\Number\NumberValidator;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\SystemParameterPaymentForm\CreditCard\Edit\Common\CreditCardEditingInput as Input;
use Sam\View\Admin\Form\SystemParameterPaymentForm\CreditCard\Edit\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\View\Admin\Form\SystemParameterPaymentForm\CreditCard\Edit\Validate\CreditCardEditingValidationResult as Result;
use Sam\Core\Service\CustomizableClass;


class CreditCardEditingValidator extends CustomizableClass
{
    use DataProviderCreateTrait;
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(Input $input): Result
    {
        $result = Result::new()->construct();

        if ($input->mode->isMain()) {
            $result = $this->validateName($input, $result);
        }

        $result = $this->validateSurcharge($input, $result);

        if ($result->hasSuccess()) {
            $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
        }

        $this->log($input, $result);
        return $result;
    }

    protected function validateName(Input $input, Result $result): Result
    {
        $dataProvider = $this->createDataProvider();
        if (trim($input->name) === '') {
            $result->addError(Result::ERR_NAME_REQUIRED);
        }

        $creditCard = $dataProvider->loadCreditCard($input->creditCardId, true);
        if (
            (
                !$creditCard
                || $creditCard->Name !== $input->name
            )
            && $dataProvider->existCreditCardByName($input->name)
        ) {
            $result->addError(Result::ERR_NAME_EXISTS);
        }
        return $result;
    }

    protected function validateSurcharge(Input $input, Result $result): Result
    {
        $numberFormatValidationResult = $this->getNumberFormatter()
            ->validateNumberFormat($input->surcharge, $input->systemAccountId);
        if (
            $input->surcharge !== ''
            &&
            (
                $numberFormatValidationResult->hasError()
                || !NumberValidator::new()->isRealPositiveOrZero($input->surcharge)
            )
        ) {
            $result->addError(Result::ERR_SURCHARGE_NOT_POSITIVE_NUMBER);
        }
        return $result;
    }

    protected function log(Input $input, Result $result): void
    {
        if ($result->hasError()) {
            $logData = [
                'name' => $input->name,
                'surcharge' => $input->surcharge,
                'creditCardId' => $input->creditCardId,
                'accountId' => $input->systemAccountId,
                'editorUserId' => $input->editorUserId
            ];
            log_error($result->errorMessage() . composeSuffix($logData));
        }
    }
}
