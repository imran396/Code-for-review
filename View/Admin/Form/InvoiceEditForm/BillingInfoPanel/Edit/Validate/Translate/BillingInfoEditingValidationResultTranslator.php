<?php
/**
 * SAM-10996: Stacked Tax. New Invoice Edit page: Invoiced user billing and shipping sections
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceEditForm\BillingInfoPanel\Edit\Validate\Translate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\Form\InvoiceEditForm\BillingInfoPanel\Edit\Validate\BillingInfoEditingValidationResult;

/**
 * Class BillingInfoEditingValidationResultTranslator
 * @package Sam\View\Admin\Form\InvoiceEditForm\BillingInfoPanel\Edit\Validate\Translate
 */
class BillingInfoEditingValidationResultTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATION_KEYS = [
        BillingInfoEditingValidationResult::ERR_BILLING_EMAIL_FORMAT => 'billing.validation.email_format_invalid',
        BillingInfoEditingValidationResult::WARN_PHONE_FORMAT => 'billing.validation.phone_invalid',
        BillingInfoEditingValidationResult::WARN_FAX_FORMAT => 'billing.validation.fax_invalid',
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
     * Translate error message for the result status error code
     *
     * @param ResultStatus $resultStatus
     * @return string
     */
    public function trans(ResultStatus $resultStatus): string
    {
        $code = $resultStatus->getCode();
        $translationKey = self::TRANSLATION_KEYS[$code] ?? '';
        if (!$translationKey) {
            log_error("Can't find translation for error with code {$code}");
            return '';
        }
        return $this->getAdminTranslator()->trans($translationKey, [], self::TRANSLATION_DOMAIN);
    }
}
