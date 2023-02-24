<?php
/**
 * SAM-5796: Single CLI application for data integrity checkers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\DataIntegrityChecker\Cli\Command;

use Sam\DataIntegrityChecker\Cli\Command\Base\AccountRelatedCheckerCommandBase;
use Sam\Lot\Validate\LotDataIntegrityCheckerAwareTrait;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Command calls a checker that detects lots with duplicated lot numbers for one account
 *
 * Class ItemNumUniqueForAccountCheckerCommand
 * @package Sam\DataIntegrityChecker\Cli
 */
class ItemNumUniqueForAccountCheckerCommand extends AccountRelatedCheckerCommandBase
{
    use LotDataIntegrityCheckerAwareTrait;

    public const NAME = 'ItemNumUniqueForAccount';
    private const MESSAGE_LOT = 'Lots \'%s\' (%s) have the same lot number \'%s\'';

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Shows lots with duplicated lot numbers for one account');
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
        $repo = $this->getLotDataIntegrityChecker();
        if ($accountId !== null) {
            $repo->filterAccountId($accountId);
        }
        return $repo->prepareLotNumbersDuplicateSearch();
    }
}
