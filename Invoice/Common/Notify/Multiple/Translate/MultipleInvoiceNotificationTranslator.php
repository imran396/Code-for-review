<?php
/**
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

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Notify\Multiple\MultipleInvoiceNotificationResult;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class MultipleInvoiceNotificationTranslator
 * @package Sam\Invoice\Common\Notify\Multiple\Translate
 */
class MultipleInvoiceNotificationTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATION_KEYS = [
        MultipleInvoiceNotificationResult::ERR_WITH_LOCKED_INVOICES => 'locked.invoices.error.message',
        MultipleInvoiceNotificationResult::ERR_NO_INVOICE_SPECIFIED => 'no.invoice.specified.error.message',
        MultipleInvoiceNotificationResult::ERR_BIDDER_EMAIL_ABSENT => 'bidder.email.absent.error.message',
        MultipleInvoiceNotificationResult::ERR_BIDDER_USER_NOT_FOUND => 'bidder.user.not_found.error.message',
        MultipleInvoiceNotificationResult::ERR_NO_ACTIVATE_USER => 'no.activate.error.message',
        MultipleInvoiceNotificationResult::OK_NOTIFIED => 'notified.success.message',
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $statusCode
     * @return string
     */
    public function trans(int $statusCode, array $params = [], string $language = null, string $domain = 'admin_invoice_list'): string
    {
        $translation = '';
        $key = self::TRANSLATION_KEYS[$statusCode] ?? null;
        if ($key) {
            $translation = $this->getAdminTranslator()->trans($key, $params, $domain, $language);
        }
        return $translation;
    }
}
