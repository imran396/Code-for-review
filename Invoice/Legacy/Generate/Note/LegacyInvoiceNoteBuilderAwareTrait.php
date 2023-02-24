<?php
/**
 * SAM-4377: Invoice producer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           17.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Generate\Note;

/**
 * Trait InvoiceNoteBuilderAwareTrait
 * @package Sam\Invoice\Legacy\Generate\Note
 */
trait LegacyInvoiceNoteBuilderAwareTrait
{
    /**
     * @var LegacyInvoiceNoteBuilder|null
     */
    protected ?LegacyInvoiceNoteBuilder $legacyInvoiceNoteBuilder = null;

    /**
     * @return LegacyInvoiceNoteBuilder
     */
    protected function getLegacyInvoiceNoteBuilder(): LegacyInvoiceNoteBuilder
    {
        if ($this->legacyInvoiceNoteBuilder === null) {
            $this->legacyInvoiceNoteBuilder = LegacyInvoiceNoteBuilder::new();
        }
        return $this->legacyInvoiceNoteBuilder;
    }

    /**
     * @param LegacyInvoiceNoteBuilder $legacyInvoiceNoteBuilder
     * @return static
     * @internal
     */
    public function setLegacyInvoiceNoteBuilder(LegacyInvoiceNoteBuilder $legacyInvoiceNoteBuilder): static
    {
        $this->legacyInvoiceNoteBuilder = $legacyInvoiceNoteBuilder;
        return $this;
    }
}
