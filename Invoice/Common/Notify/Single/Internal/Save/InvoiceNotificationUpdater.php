<?php
/**
 * SAM-11778: Refactor Invoice Notifier for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Notify\Single\Internal\Save;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Common\Notify\Single\Internal\Save\Internal\Email\ActionQueueSaverCreateTrait;
use Sam\Invoice\Common\Notify\Single\SingleInvoiceNotificationResult as Result;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;

/**
 * Class InvoiceNotificationUpdater
 * @package Sam\Invoice\Common\Notify\Single\Internal\Save
 */
class InvoiceNotificationUpdater extends CustomizableClass
{
    use ActionQueueSaverCreateTrait;
    use CurrentDateTrait;
    use InvoiceWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function update(Result $result, int $priority, int $editorUserId): Result
    {
        $invoice = $result->invoice;
        $currentDate = $this->getCurrentDateUtc();
        if (!$invoice->FirstSentOn) {
            $invoice->FirstSentOn = $currentDate;
        }
        $invoice->SentOn = $currentDate;
        if ($invoice->isOpen()) {
            $invoice->toPending();
        }
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
        $this->createActionQueueSaver()->addToActionQueue($invoice, Constants\EmailKey::INVOICE, $priority, $editorUserId);
        $result->setInvoice($invoice);
        $result->setEditorUserId($editorUserId);
        return $result;
    }
}
