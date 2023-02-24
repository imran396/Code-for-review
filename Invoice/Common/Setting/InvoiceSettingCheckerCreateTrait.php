<?php
/**
 * SAM-10997: Stacked Tax. New Invoice Edit page: Goods section (Invoice Items)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Setting;

/**
 * Trait InvoiceSettingCheckerCreateTrait
 * @package Sam\Invoice\Common\Setting
 */
trait InvoiceSettingCheckerCreateTrait
{
    protected ?InvoiceSettingChecker $invoiceSettingChecker = null;

    /**
     * @return InvoiceSettingChecker
     */
    protected function createInvoiceSettingChecker(): InvoiceSettingChecker
    {
        return $this->invoiceSettingChecker ?: InvoiceSettingChecker::new();
    }

    /**
     * @param InvoiceSettingChecker $invoiceSettingChecker
     * @return $this
     * @internal
     */
    public function setInvoiceSettingChecker(InvoiceSettingChecker $invoiceSettingChecker): static
    {
        $this->invoiceSettingChecker = $invoiceSettingChecker;
        return $this;
    }
}
