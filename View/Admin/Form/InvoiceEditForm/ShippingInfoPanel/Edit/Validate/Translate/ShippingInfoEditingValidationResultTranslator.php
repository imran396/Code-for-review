<?php
/**
 * SAM-11831: Stacked Tax: Validation is missing at billing email and billing/shipping phone/fax number at invoice edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 10, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceEditForm\ShippingInfoPanel\Edit\Validate\Translate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\Form\InvoiceEditForm\ShippingInfoPanel\Edit\Validate\ShippingInfoEditingValidationResult;

/**
 * Class ShippingInfoEditingValidationResultTranslator
 * @package Sam\View\Admin\Form\InvoiceEditForm\ShippingInfoPanel\Edit\Validate\Translate
 */
class ShippingInfoEditingValidationResultTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATION_KEYS = [
        ShippingInfoEditingValidationResult::WARN_PHONE_FORMAT => 'shipping.validation.phone_invalid',
        ShippingInfoEditingValidationResult::WARN_FAX_FORMAT => 'shipping.validation.fax_invalid',
    ];

    protected const TRANSLATION_DOMAIN = 'admin_invoice_edit';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Translate error message for the result status code
     *
     * @param ResultStatus $resultStatus
     * @return string
     */
    public function trans(ResultStatus $resultStatus): string
    {
        $code = $resultStatus->getCode();
        $translationKey = self::TRANSLATION_KEYS[$code] ?? '';
        if (!$translationKey) {
            log_error("Can't find translation for result status with code {$code}");
            return '';
        }
        return $this->getAdminTranslator()->trans($translationKey, [], self::TRANSLATION_DOMAIN);
    }
}
