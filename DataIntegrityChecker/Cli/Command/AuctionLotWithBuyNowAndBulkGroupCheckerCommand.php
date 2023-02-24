<?php
/**
 * SAM-5796: Single CLI application for data integrity checkers
 * SAM-3103: bulk vs piecemeal and buy now
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
 * Command calls a checker that detects lot items that have enabled both mutually exclusive features: buy now and bulk grouping.
 *
 * Class ActiveInSingleAuctionCheckerCommand
 * @package Sam\DataIntegrityChecker\Cli
 */
class AuctionLotWithBuyNowAndBulkGroupCheckerCommand extends AccountRelatedCheckerCommandBase
{
    use AuctionLotDataIntegrityCheckerAwareTrait;

    public const NAME = 'AuctionLotWithBuyNowAndBulkGroup';
    private const MESSAGE = 'Auction Lot has buy now and bulk grouping';

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Shows auction lots with buy now and bulk grouping at the same time');
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
        return $repo->prepareAuctionLotsWithBuyNowAndBulkGroupSearch();
    }

    /**
     * @inheritDoc
     */
    protected function makeProblematicItemMessage(array $row): string
    {
        return self::MESSAGE . composeSuffix(['auction lot id' => $row['id'], 'lot item id' => $row['lot_item_id'], 'auction id' => $row['auction_id']]);
    }
}
