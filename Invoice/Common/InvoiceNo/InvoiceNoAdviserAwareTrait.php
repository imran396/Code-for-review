<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           22.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Common\InvoiceNo;

/**
 * Trait InvoiceNoAdviserAwareTrait
 * @package Sam\Invoice\Common\InvoiceNo
 */
trait InvoiceNoAdviserAwareTrait
{
    /**
     * @var InvoiceNoAdviser|null
     */
    protected ?InvoiceNoAdviser $invoiceNoAdviser = null;

    /**
     * @return InvoiceNoAdviser
     */
    protected function getInvoiceNoAdviser(): InvoiceNoAdviser
    {
        if ($this->invoiceNoAdviser === null) {
            $this->invoiceNoAdviser = InvoiceNoAdviser::new();
        }
        return $this->invoiceNoAdviser;
    }

    /**
     * @param InvoiceNoAdviser $invoiceNoAdviser
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setInvoiceNoAdviser(InvoiceNoAdviser $invoiceNoAdviser): static
    {
        $this->invoiceNoAdviser = $invoiceNoAdviser;
        return $this;
    }
}
