<?php
/**
 * SAM-11091: Stacked Tax. New Invoice Edit page: Invoice Item Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Validate\Translate;

use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Validate\InvoiceItemEditFormValidationResult;

/**
 * Class InvoiceItemEditFormValidationResultTranslator
 * @package Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Validate\Translate
 */
class InvoiceItemEditFormValidationResultTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    public const TRANSLATION_KEYS = [
        InvoiceItemEditFormValidationResult::ERR_QUANTITY_INVALID => 'stacked_tax.invoice_item.quantity.invalid',
        InvoiceItemEditFormValidationResult::ERR_QUANTITY_TOO_BIG => 'stacked_tax.invoice_item.quantity.too_big',
        InvoiceItemEditFormValidationResult::ERR_QUANTITY_INVALID_FRACTIONAL_PART_LENGTH => 'stacked_tax.invoice_item.quantity.invalid_fractional_part_length',
        InvoiceItemEditFormValidationResult::ERR_QUANTITY_INVALID_PRECISION => 'stacked_tax.invoice_item.quantity.invalid_precision',
        InvoiceItemEditFormValidationResult::ERR_HP_TAX_SCHEMA_INVALID => 'stacked_tax.invoice_item.hp_tax_schema.invalid',
        InvoiceItemEditFormValidationResult::ERR_BP_TAX_SCHEMA_INVALID => 'stacked_tax.invoice_item.bp_tax_schema.invalid',
        InvoiceItemEditFormValidationResult::ERR_HAMMER_PRICE_INVALID => 'stacked_tax.invoice_item.hp.invalid',
        InvoiceItemEditFormValidationResult::ERR_BUYERS_PREMIUM_INVALID => 'stacked_tax.invoice_item.bp.invalid'
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function translate(InvoiceItemEditFormValidationResult $result, string $language): InvoiceItemEditFormValidationResult
    {
        $translator = $this->getAdminTranslator();
        foreach ($result->getErrors() as $error) {
            $key = self::TRANSLATION_KEYS[$error->getCode()] ?? '';
            if ($key) {
                $error->setMessage(
                    $translator->trans(
                        $key,
                        [
                            'quantityMaxPrecision' => $result->quantityMaxPrecision,
                            'quantityMaxIntegerDigits' => $result->quantityMaxIntegerDigits,
                            'quantityScale' => $result->quantityScale,
                        ],
                        'admin_validation',
                        $language
                    )
                );
            }
        }
        return $result;
    }
}
