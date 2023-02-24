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

use Sam\Bidding\BidIncrement\Validate\BidIncrementDataIntegrityCheckerAwareTrait;
use Sam\DataIntegrityChecker\Cli\Command\Base\AccountRelatedCheckerCommandBase;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Command calls a checker that detects duplicated bid increments for one account
 *
 * Class BidIncrementUniqueCheckerCommandCommand
 * @package Sam\DataIntegrityChecker\Cli
 */
class BidIncrementUniqueCheckerCommand extends AccountRelatedCheckerCommandBase
{
    use BidIncrementDataIntegrityCheckerAwareTrait;

    public const NAME = 'BidIncrementUnique';
    private const MESSAGE_BID_INCREMENT = 'Missing 0 range (ids: %s): (amounts %s) for %s';

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Shows duplicate bid increments for one account');
    }

    /**
     * @inheritDoc
     */
    protected function makeProblematicItemMessage(array $row): string
    {
        if ($row['auction_id']) {
            $duplicateEntity = 'auction id: ' . $row['auction_id'];
        } elseif ($row['lot_item_id']) {
            $duplicateEntity = 'lot item id: ' . $row['lot_item_id'];
        } else {
            $duplicateEntity = 'auction type: ' . $row['auction_type'];
        }
        return sprintf(self::MESSAGE_BID_INCREMENT, $row['ids'], $row['amounts'], $duplicateEntity);
    }

    /**
     * @inheritDoc
     */
    protected function prepareRepository(?int $accountId): ReadRepositoryBase
    {
        $repo = $this->getBidIncrementDataIntegrityChecker();
        if ($accountId !== null) {
            $repo->filterAccountId($accountId);
        }
        return $repo->prepareBidIncrementDuplicateSearch();
    }
}
