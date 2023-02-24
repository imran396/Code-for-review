<?php
/**
 * SAM-4820: Invoice with deleted related entities behavior
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Validate;

/**
 * Trait InvoiceRelatedEntityValidatorAwareTrait
 * @package Sam\Invoice\Common\Validate
 */
trait InvoiceRelatedEntityValidatorAwareTrait
{
    /**
     * @var InvoiceRelatedEntityValidator|null
     */
    protected ?InvoiceRelatedEntityValidator $invoiceRelatedEntityValidator = null;

    /**
     * @return InvoiceRelatedEntityValidator
     */
    protected function getInvoiceRelatedEntityValidator(): InvoiceRelatedEntityValidator
    {
        if ($this->invoiceRelatedEntityValidator === null) {
            $this->invoiceRelatedEntityValidator = InvoiceRelatedEntityValidator::new();
        }
        return $this->invoiceRelatedEntityValidator;
    }

    /**
     * @param InvoiceRelatedEntityValidator $invoiceRelatedEntityValidator
     * @return static
     * @internal
     */
    public function setInvoiceRelatedEntityValidator(InvoiceRelatedEntityValidator $invoiceRelatedEntityValidator): static
    {
        $this->invoiceRelatedEntityValidator = $invoiceRelatedEntityValidator;
        return $this;
    }
}
