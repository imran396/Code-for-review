<?php
/**
 * SAM-11110: Stacked Tax. New Invoice Edit page: Service Fee Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Validate\Translate;

use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Validate\ServiceFeeEditFormValidationResult;

/**
 * Class ServiceFeeEditFormValidationResultTranslator
 * @package Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Validate\Translate
 */
class ServiceFeeEditFormValidationResultTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    public const TRANSLATION_KEYS = [
        ServiceFeeEditFormValidationResult::ERR_TYPE_REQUIRED => 'stacked_tax.service_fee.type.required',
        ServiceFeeEditFormValidationResult::ERR_AMOUNT_INVALID => 'stacked_tax.service_fee.amount.invalid',
        ServiceFeeEditFormValidationResult::ERR_AMOUNT_REQUIRED => 'stacked_tax.service_fee.amount.required',
        ServiceFeeEditFormValidationResult::ERR_NAME_REQUIRED => 'stacked_tax.service_fee.name.required',
        ServiceFeeEditFormValidationResult::ERR_TAX_SCHEMA_INVALID => 'stacked_tax.service_fee.tax_schema.invalid',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function translate(ServiceFeeEditFormValidationResult $result, string $language): ServiceFeeEditFormValidationResult
    {
        $translator = $this->getAdminTranslator();
        foreach ($result->getErrors() as $error) {
            $key = self::TRANSLATION_KEYS[$error->getCode()] ?? '';
            if ($key) {
                $error->setMessage(
                    $translator->trans(
                        $key,
                        [],
                        'admin_validation',
                        $language
                    )
                );
            }
        }
        return $result;
    }
}
