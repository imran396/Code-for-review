<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Generate\Produce\Internal\Note;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Legacy\Generate\Note\LegacyInvoiceNoteBuilderAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;

/**
 * Class InvoiceNoteUpdater
 * @package Sam\Invoice\Legacy\Generate\Produce\Internal\Note
 */
class InvoiceNoteUpdater extends CustomizableClass
{
    use InvoiceLoaderAwareTrait;
    use LegacyInvoiceNoteBuilderAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function update(int $invoiceId, int $editorUserId, bool $isReadOnlyDb = false): void
    {
        // After updating summary columns need to update invoice notes:
        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            log_error(
                "Available invoice not found, when calculating summary columns"
                . composeSuffix(['i' => $invoiceId])
            );
            return;
        }

        $note = $this->getLegacyInvoiceNoteBuilder()->build($invoice->Id)
            . PHP_EOL
            . PHP_EOL
            . $invoice->Note;
        $invoice->Note = trim($note);
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
    }
}
