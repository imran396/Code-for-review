<?php
/**
 * SAM-10995: Stacked Tax. New Invoice Edit page: Initial layout and header section
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Validate\Translate;

use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Validate\InvoiceHeaderValidationResult;

/**
 * Class InvoiceHeaderValidationErrorTranslator
 * @package Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Validate
 */
class InvoiceHeaderValidationErrorTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATIONS = [
        InvoiceHeaderValidationResult::ERR_INVOICE_NO_REQUIRED => 'invoice.invoice_no.required',
        InvoiceHeaderValidationResult::ERR_INVOICE_NO_INVALID => 'invoice.invoice_no.invalid',
        InvoiceHeaderValidationResult::ERR_INVOICE_NO_EXISTS => 'invoice.invoice_no.exists',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function trans(int $errorCode): string
    {
        if (!array_key_exists($errorCode, self::TRANSLATIONS)) {
            return '';
        }
        return $this->getAdminTranslator()->trans(self::TRANSLATIONS[$errorCode], [], 'admin_validation');
    }
}
