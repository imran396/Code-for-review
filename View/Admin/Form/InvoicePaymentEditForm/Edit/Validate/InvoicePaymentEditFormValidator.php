<?php
/**
 * SAM-11127: Stacked Tax. New Invoice Edit page: Payment Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Validate;

use Sam\Billing\CreditCard\Validate\CreditCardExistenceCheckerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Date\Validate\DateFormatValidator;
use Sam\Core\Entity\Model\Payment\Status\PaymentStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Date\DateHelperAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Dto\InvoicePaymentEditFormInput;
use Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Validate\InvoicePaymentEditFormValidationResult as Result;

/**
 * Class InvoicePaymentEditFormValidator
 * @package Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Validate
 */
class InvoicePaymentEditFormValidator extends CustomizableClass
{
    use CreditCardExistenceCheckerAwareTrait;
    use DataProviderCreateTrait;
    use DateHelperAwareTrait;
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(InvoicePaymentEditFormInput $input): Result
    {
        $result = Result::new()->construct();
        if (!$input->paymentMethod) {
            $result->addError(Result::ERR_METHOD_REQUIRED);
        } elseif (!in_array($input->paymentMethod, Constants\Payment::$paymentMethods, true)) {
            $result->addError(Result::ERR_METHOD_INVALID);
        }

        if (PaymentStatusPureChecker::new()->isCcPaymentMethod($input->paymentMethod)) {
            if (!$input->creditCardId) {
                $result->addError(Result::ERR_CREDIT_CARD_REQUIRED);
            } else {
                $paymentCreditCardId = $this->createDataProvider()->loadPaymentCreditCardId($input->paymentId);
                if (
                    $input->creditCardId !== $paymentCreditCardId
                    && !$this->getCreditCardExistenceChecker()->existById($input->creditCardId)
                ) {
                    $result->addError(Result::ERR_CREDIT_CARD_INVALID);
                }
            }
            if (
                $input->paymentMethod !== Constants\Payment::PM_CC_EXTERNALLY
                && !$input->paymentGateway
            ) {
                $result->addError(Result::ERR_PAYMENT_GATEWAY_REQUIRED);
            }
        }

        if (!$input->dateSysIso) {
            $result->addError(Result::ERR_DATE_REQUIRED);
        } elseif (!DateFormatValidator::new()->isValidFormatDateTime($input->dateSysIso, [Constants\Date::ISO])) {
            $result->addError(Result::ERR_DATE_INVALID);
        }

        $numberFormatter = $this->getNumberFormatter();
        if (!$input->netAmount) {
            $result->addError(Result::ERR_AMOUNT_REQUIRED);
        } elseif (
            !$numberFormatter->validateNumberFormat($input->netAmount, $input->systemAccountId)->isValidNumberWithoutThousandSeparator()
            || !NumberValidator::new()->isRealPositive($numberFormatter->removeFormat($input->netAmount, $input->systemAccountId))
        ) {
            $result->addError(Result::ERR_AMOUNT_INVALID);
        }

        if ($result->hasError()) {
            log_debug('Invoice payment editing validation failed - ' . $result->errorMessage());
            return $result;
        }

        return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
    }
}
