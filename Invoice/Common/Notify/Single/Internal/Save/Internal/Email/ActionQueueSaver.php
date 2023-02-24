<?php
/**
 * SAM-11778: Refactor Invoice Notifier for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Notify\Single\Internal\Save\Internal\Email;

use Email_Template;
use Invoice;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ActionQueueSaver
 * @package Sam\Invoice\Common\Notify\Single\Internal\Save\Internal\Email
 */
class ActionQueueSaver extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function addToActionQueue(Invoice $invoice, string $emailKey, int $priority, int $editorUserId): void
    {
        $emailManager = Email_Template::new()->construct(
            $invoice->AccountId,
            $emailKey,
            $editorUserId,
            [$invoice]
        );
        $emailManager->addToActionQueue($priority);
    }
}
