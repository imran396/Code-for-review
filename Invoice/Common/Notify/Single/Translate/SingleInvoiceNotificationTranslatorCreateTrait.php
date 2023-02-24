<?php
/**
 *
 * SAM-11778: Refactor Invoice Notifier for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 24, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Notify\Single\Translate;

/**
 * Trait SingleInvoiceNotificationTranslatorCreateTrait
 * @package Sam\Invoice\Common\Notify\Single\Translate
 */
trait SingleInvoiceNotificationTranslatorCreateTrait
{
    protected ?SingleInvoiceNotificationTranslator $singleInvoiceNotificationTranslator = null;

    /**
     * @return SingleInvoiceNotificationTranslator
     */
    protected function createSingleInvoiceNotificationTranslator(): SingleInvoiceNotificationTranslator
    {
        return $this->singleInvoiceNotificationTranslator ?: SingleInvoiceNotificationTranslator::new();
    }

    /**
     * @param SingleInvoiceNotificationTranslator $singleInvoiceNotificationTranslator
     * @return $this
     * @internal
     */
    public function setSingleInvoiceNotificationTranslator(
        SingleInvoiceNotificationTranslator $singleInvoiceNotificationTranslator
    ): self {
        $this->singleInvoiceNotificationTranslator = $singleInvoiceNotificationTranslator;
        return $this;
    }
}
