<?php
/**
 * SAM-10115: Refactor invoice bidder autocomplete
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\InvoiceBidder;

/**
 * Trait InvoiceBidderAutocompleteResponseProducerCreateTrait
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\InvoiceBidder
 */
trait InvoiceBidderAutocompleteResponseProducerCreateTrait
{
    /**
     * @var InvoiceBidderAutocompleteResponseProducer|null
     */
    protected ?InvoiceBidderAutocompleteResponseProducer $invoiceBidderAutocompleteResponseProducer = null;

    /**
     * @return InvoiceBidderAutocompleteResponseProducer
     */
    protected function createInvoiceBidderAutocompleteResponseProducer(): InvoiceBidderAutocompleteResponseProducer
    {
        return $this->invoiceBidderAutocompleteResponseProducer ?: InvoiceBidderAutocompleteResponseProducer::new();
    }

    /**
     * @param InvoiceBidderAutocompleteResponseProducer $invoiceBidderAutocompleteResponseProducer
     * @return static
     * @internal
     */
    public function setInvoiceBidderAutocompleteResponseProducer(InvoiceBidderAutocompleteResponseProducer $invoiceBidderAutocompleteResponseProducer): static
    {
        $this->invoiceBidderAutocompleteResponseProducer = $invoiceBidderAutocompleteResponseProducer;
        return $this;
    }
}
