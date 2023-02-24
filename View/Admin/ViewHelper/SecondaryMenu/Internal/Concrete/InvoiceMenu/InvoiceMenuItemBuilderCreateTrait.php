<?php
/**
 * SAM-11079: Stacked Tax. Tax aggregation. Admin Invoice List page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           08-13, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\InvoiceMenu;

trait InvoiceMenuItemBuilderCreateTrait
{
    protected ?InvoiceMenuItemBuilder $invoiceMenuItemBuilder = null;

    /**
     * @return InvoiceMenuItemBuilder
     */
    protected function createInvoiceMenuItemBuilder(): InvoiceMenuItemBuilder
    {
        return $this->invoiceMenuItemBuilder ?: InvoiceMenuItemBuilder::new();
    }

    /**
     * @param InvoiceMenuItemBuilder $invoiceMenuItemBuilder
     * @return $this
     * @internal
     */
    public function setInvoiceMenuItemBuilder(InvoiceMenuItemBuilder $invoiceMenuItemBuilder): static
    {
        $this->invoiceMenuItemBuilder = $invoiceMenuItemBuilder;
        return $this;
    }
}
