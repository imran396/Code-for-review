<?php
/**
 * SAM-10915: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Authorize.Net invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\AuthorizeNet\Internal\Notify;

use Email_Template;
use Invoice;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

class Notifier extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function notify(Invoice $invoice, int $editorUserId): bool
    {
        $emailManager = Email_Template::new()->construct(
            $invoice->AccountId,
            Constants\EmailKey::PAYMENT_CONF,
            $editorUserId,
            [$invoice]
        );
        return $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);
    }
}
