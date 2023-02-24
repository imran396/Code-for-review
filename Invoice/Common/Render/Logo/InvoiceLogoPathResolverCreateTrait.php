<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           5/5/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Render\Logo;

/**
 * Trait InvoiceLogoPathResolverCreateTrait
 * @package Sam\Invoice\Common\Render\Logo
 */
trait InvoiceLogoPathResolverCreateTrait
{
    /**
     * @var InvoiceLogoPathResolver|null
     */
    protected ?InvoiceLogoPathResolver $invoiceLogoPathResolver = null;

    /**
     * @return InvoiceLogoPathResolver
     */
    protected function createInvoiceLogoPathResolver(): InvoiceLogoPathResolver
    {
        return $this->invoiceLogoPathResolver ?: InvoiceLogoPathResolver::new();
    }

    /**
     * @param InvoiceLogoPathResolver $invoiceLogoPathResolver
     * @return $this
     * @internal
     */
    public function setInvoiceLogoPathResolver(InvoiceLogoPathResolver $invoiceLogoPathResolver): static
    {
        $this->invoiceLogoPathResolver = $invoiceLogoPathResolver;
        return $this;
    }
}
