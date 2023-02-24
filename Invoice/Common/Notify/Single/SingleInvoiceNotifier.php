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

namespace Sam\Invoice\Common\Notify\Single;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Notify\Single\Internal\Save\InvoiceNotificationUpdater;
use Sam\Invoice\Common\Notify\Single\Internal\Validate\InvoiceNotificationValidator;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class SingleInvoiceNotifier
 * @package Sam\Invoice\Common\Notify\Single
 */
class SingleInvoiceNotifier extends CustomizableClass
{
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function notify(
        int $invoiceId,
        int $priority,
        int $editorUserId,
        string $language,
        bool $isReadOnlyDb = false
    ): SingleInvoiceNotificationResult {
        $validator = InvoiceNotificationValidator::new();
        $result = $validator->validate($invoiceId, $language, $isReadOnlyDb);
        if ($result->hasSuccess()) {
            $result = InvoiceNotificationUpdater::new()->update($result, $priority, $editorUserId);
        }
        $result->logData();
        return $result;
    }
}
