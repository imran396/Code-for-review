<?php
/**
 * SAM-5796: Single CLI application for data integrity checkers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\DataIntegrityChecker\Cli\Command;

use Sam\DataIntegrityChecker\Cli\Command\Base\AccountRelatedCheckerCommandBase;
use Sam\Invoice\Common\Validate\InvoiceDataIntegrityCheckerAwareTrait;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Command calls a checker that detects lots that active more than once for one invoice
 *
 * Class InvoiceUniqueForLotCheckerCommand
 * @package Sam\DataIntegrityChecker\Cli
 */
class InvoiceUniqueForLotCheckerCommand extends AccountRelatedCheckerCommandBase
{
    use InvoiceDataIntegrityCheckerAwareTrait;

    public const NAME = 'InvoiceUniqueForLot';
    private const MESSAGE_LOT = 'Lot \'%s\' (id: %s) is in %s invoices (%s)';

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Shows lots active more than once for one invoice');
    }

    /**
     * @inheritDoc
     */
    protected function makeProblematicItemMessage(array $row): string
    {
        return vsprintf(self::MESSAGE_LOT, $row);
    }

    /**
     * @inheritDoc
     */
    protected function prepareRepository(?int $accountId): ReadRepositoryBase
    {
        $repo = $this->getInvoiceDataIntegrityChecker();
        if ($accountId !== null) {
            $repo->filterAccountId($accountId);
        }
        return $repo->prepareInvoiceDuplicateSearch();
    }
}
