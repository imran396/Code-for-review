<?php
/**
 * SAM-9721: Refactor and implement unit test for single invoice producer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Item\Single\Internal\ArtistResaleRight;

/**
 * Trait InvoiceAdditionalSaverCreateTrait
 * @package Sam\Invoice\StackedTax\Generate\Item\Single\Internal\ArtistResaleRight
 */
trait ArtistResaleRightChargeSaverCreateTrait
{
    protected ?ArtistResaleRightChargeSaver $invoiceAdditionalSaver = null;

    /**
     * @return ArtistResaleRightChargeSaver
     */
    protected function createInvoiceAdditionalSaver(): ArtistResaleRightChargeSaver
    {
        return $this->invoiceAdditionalSaver ?: ArtistResaleRightChargeSaver::new();
    }

    /**
     * @param ArtistResaleRightChargeSaver $invoiceAdditionalSaver
     * @return $this
     * @internal
     */
    public function setInvoiceAdditionalSaver(ArtistResaleRightChargeSaver $invoiceAdditionalSaver): static
    {
        $this->invoiceAdditionalSaver = $invoiceAdditionalSaver;
        return $this;
    }
}
