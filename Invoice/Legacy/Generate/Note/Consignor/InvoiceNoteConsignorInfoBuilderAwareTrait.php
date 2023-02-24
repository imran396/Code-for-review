<?php
/**
 * SAM-4377: Invoice producer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           15.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Generate\Note\Consignor;

/**
 * Trait InvoiceNoteConsignorInfoBuilderAwareTrait
 * @package Sam\Invoice\Legacy\Generate\Note\Consignor
 */
trait InvoiceNoteConsignorInfoBuilderAwareTrait
{
    /**
     * @var InvoiceNoteConsignorInfoBuilder|null
     */
    protected ?InvoiceNoteConsignorInfoBuilder $invoiceNoteConsignorInfoBuilder = null;

    /**
     * @return InvoiceNoteConsignorInfoBuilder
     */
    protected function getInvoiceNoteConsignorInfoBuilder(): InvoiceNoteConsignorInfoBuilder
    {
        if ($this->invoiceNoteConsignorInfoBuilder === null) {
            $this->invoiceNoteConsignorInfoBuilder = InvoiceNoteConsignorInfoBuilder::new();
        }
        return $this->invoiceNoteConsignorInfoBuilder;
    }

    /**
     * @param InvoiceNoteConsignorInfoBuilder $invoiceNoteConsignorInfoBuilder
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setInvoiceNoteConsignorInfoBuilder(InvoiceNoteConsignorInfoBuilder $invoiceNoteConsignorInfoBuilder): static
    {
        $this->invoiceNoteConsignorInfoBuilder = $invoiceNoteConsignorInfoBuilder;
        return $this;
    }
}
