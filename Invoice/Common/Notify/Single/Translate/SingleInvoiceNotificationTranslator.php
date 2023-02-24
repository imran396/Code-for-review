<?php
/**
 * SAM-11778: Refactor Invoice Notifier for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Notify\Single\Translate;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Notify\Single\SingleInvoiceNotificationResult;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class SingleInvoiceNotificationTranslator
 * @package Sam\Invoice\Common\Notify\Single\Translate
 */
class SingleInvoiceNotificationTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATION_KEYS = [
        SingleInvoiceNotificationResult::ERR_BIDDER_USER_NOT_FOUND => 'bidder.user.not_found.error.message',
        SingleInvoiceNotificationResult::ERR_LOCKED => 'locked.error.message',
        SingleInvoiceNotificationResult::ERR_BIDDER_EMAIL_ABSENT => 'bidder.email.absent.error.message',
        SingleInvoiceNotificationResult::ERR_NOT_ACTIVE_USER => 'inactive.user.error.message',
        SingleInvoiceNotificationResult::OK_NOTIFIED => 'notified.success.message',
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
    public function trans(int $statusCode, array $params = [], string $language = null, string $domain = 'admin_invoice_item'): string
    {
        $translation = '';
        $key = self::TRANSLATION_KEYS[$statusCode] ?? null;
        if ($key) {
            $translation = $this->getAdminTranslator()->trans($key, $params, $domain, $language);
        }
        return $translation;
    }
}
