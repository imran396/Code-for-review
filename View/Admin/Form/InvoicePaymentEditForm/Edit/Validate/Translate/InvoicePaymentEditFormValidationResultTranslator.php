<?php
/**
 * SAM-11127: Stacked Tax. New Invoice Edit page: Payment Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Validate\Translate;

use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Validate\InvoicePaymentEditFormValidationResult as Result;

/**
 * Class InvoicePaymentEditFormValidationResultTranslator
 * @package Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Validate\Translate
 */
class InvoicePaymentEditFormValidationResultTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATION_KEYS = [
        Result::ERR_METHOD_REQUIRED => 'validation.method.required',
        Result::ERR_METHOD_INVALID => 'validation.method.invalid',
        Result::ERR_CREDIT_CARD_REQUIRED => 'validation.credit_card.required',
        Result::ERR_CREDIT_CARD_INVALID => 'validation.credit_card.invalid',
        Result::ERR_DATE_REQUIRED => 'validation.date.required',
        Result::ERR_DATE_INVALID => 'validation.date.invalid',
        Result::ERR_AMOUNT_REQUIRED => 'validation.amount.required',
        Result::ERR_AMOUNT_INVALID => 'validation.amount.invalid',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function translate(Result $result, string $language, string $domain = 'admin_invoice_payment_edit'): Result
    {
        $translator = $this->getAdminTranslator();
        foreach ($result->getErrors() as $error) {
            $key = self::TRANSLATION_KEYS[$error->getCode()] ?? '';
            if ($key) {
                $error->setMessage(
                    $translator->trans($key, [], $domain, $language)
                );
            }
        }
        return $result;
    }
}
