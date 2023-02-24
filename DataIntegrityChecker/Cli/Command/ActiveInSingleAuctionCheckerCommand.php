<?php
/**
 * SAM-5796: Single CLI application for data integrity checkers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\DataIntegrityChecker\Cli\Command;

use Sam\AuctionLot\Validate\AuctionLotDataIntegrityCheckerAwareTrait;
use Sam\DataIntegrityChecker\Cli\Command\Base\AccountRelatedCheckerCommandBase;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Command calls a checker that detects lot items that are active in more than one auction
 *
 * Class ActiveInSingleAuctionCheckerCommand
 * @package Sam\DataIntegrityChecker\Cli
 */
class ActiveInSingleAuctionCheckerCommand extends AccountRelatedCheckerCommandBase
{
    use AuctionLotDataIntegrityCheckerAwareTrait;

    public const NAME = 'ActiveInSingleAuction';
    private const MESSAGE_LOT = 'Lot %s is active %s times in %s auction lot ids';

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Shows lots active more than in one auction');
    }

    /**
     * @inheritDoc
     */
    protected function prepareRepository(?int $accountId): ReadRepositoryBase
    {
        $repo = $this->getAuctionLotDataIntegrityChecker();
        if ($accountId !== null) {
            $repo->filterAccountId($accountId);
        }
        return $repo->prepareDuplicateSearch();
    }

    /**
     * @inheritDoc
     */
    protected function makeProblematicItemMessage(array $row): string
    {
        return vsprintf(self::MESSAGE_LOT, $row);
    }
}
