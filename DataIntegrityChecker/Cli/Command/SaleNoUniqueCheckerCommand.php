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

use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Auction\Validate\AuctionDataIntegrityCheckerAwareTrait;
use Sam\DataIntegrityChecker\Cli\Command\Base\AccountRelatedCheckerCommandBase;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Command calls a checker that detects duplicate auctions sale numbers for one account
 *
 * Class SaleNoUniqueCheckerCommand
 * @package Sam\DataIntegrityChecker\Cli
 */
class SaleNoUniqueCheckerCommand extends AccountRelatedCheckerCommandBase
{
    use AuctionDataIntegrityCheckerAwareTrait;
    use AuctionRendererAwareTrait;

    public const NAME = 'SaleNoUnique';
    private const MESSAGE_SALE_NUMBERS = 'Duplicate sale number %s, %s times: auctions (ids: %s, name: %s)';

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Shows duplicate auctions sale numbers for one account');
    }

    /**
     * @inheritDoc
     */
    protected function makeProblematicItemMessage(array $row): string
    {
        return sprintf(
            self::MESSAGE_SALE_NUMBERS,
            $this->getAuctionRenderer()->makeSaleNo($row['sale_num'], $row['sale_num_ext']),
            $row['count_records'],
            $row['auction_ids'],
            $row['auction_names']
        );
    }

    /**
     * @inheritDoc
     */
    protected function prepareRepository(?int $accountId): ReadRepositoryBase
    {
        $repo = $this->getAuctionDataIntegrityChecker();
        if ($accountId !== null) {
            $repo->filterAccountId($accountId);
        }
        return $repo->prepareSaleNoDuplicateSearch();
    }
}
