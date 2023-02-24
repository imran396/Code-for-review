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

namespace Sam\Invoice\Common\Notify\Multiple\Translate;

/**
 * Trait MultipleInvoiceNotificationTranslatorCreateTrait
 * @package Sam\Invoice\Common\Notify\Multiple\Translate
 */
trait MultipleInvoiceNotificationTranslatorCreateTrait
{
    protected ?MultipleInvoiceNotificationTranslator $multipleInvoiceNotificationTranslator = null;

    /**
     * @return MultipleInvoiceNotificationTranslator
     */
    protected function createMultipleInvoiceNotificationTranslator(): MultipleInvoiceNotificationTranslator
    {
        return $this->multipleInvoiceNotificationTranslator ?: MultipleInvoiceNotificationTranslator::new();
    }

    /**
     * @param MultipleInvoiceNotificationTranslator $multipleInvoiceNotificationTranslator
     * @return $this
     * @internal
     */
    public function setMultipleInvoiceNotificationTranslator(
        MultipleInvoiceNotificationTranslator $multipleInvoiceNotificationTranslator
    ): self {
        $this->multipleInvoiceNotificationTranslator = $multipleInvoiceNotificationTranslator;
        return $this;
    }
}
