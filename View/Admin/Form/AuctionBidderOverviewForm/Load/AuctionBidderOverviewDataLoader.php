<?php
/**
 * Auction Bidder Overview Data Loader
 *
 * SAM-5600: Refactor data loader for Auction Bidder Overview page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 25, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderOverviewForm\Load;

use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;

/**
 * Class AuctionBidderOverviewDataLoader
 */
class AuctionBidderOverviewDataLoader extends CustomizableClass
{
    use AbsenteeBidReadRepositoryCreateTrait;
    use BidTransactionReadRepositoryCreateTrait;
    use DbConnectionTrait;
    use FilterAuctionAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array - return values of highest/losing bidders
     */
    public function loadBidders(): array
    {
        $allHighBidderRows = [];
        /** @var AuctionBidderOverviewDto[] $dtos */
        $dtos = [];
        $losingBidderRows = [];
        $tmpLotItemId = 0;
        $filterAuction = $this->getFilterAuction();
        if ($filterAuction->isLiveOrHybrid()) {
            $repo = $this->createAbsenteeBidReadRepository()
                ->enableReadOnlyDb(true)
                ->filterAuctionId($filterAuction->Id)
                ->filterMaxBidGreater(0)
                ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
                ->joinAuctionBidderFilterApproved(true)
                ->joinAuctionBidderSkipBidderNum(null)
                ->orderByLotItemId()
                ->orderByMaxBid(false)
                ->select(['ab.lot_item_id', 'ab.user_id', 'u.username', 'ab.max_bid'])
                ->setChunkSize(200);
            $tmpBid = null;
            while ($rows = $repo->loadRows()) {
                foreach ($rows as $row) {
                    $dtos[] = AuctionBidderOverviewDto::new()->fromDbRow($row);
                }
            }
            foreach ($dtos as $dto) {
                if ($tmpLotItemId === $dto->lotItemId) {
                    if (Floating::eq($tmpBid, $dto->maxBid)) {
                        $allHighBidderRows[] = $dto;
                    } else {
                        $losingBidderRows[] = $dto;
                    }
                } else {
                    $allHighBidderRows[] = $dto;
                }
                $tmpLotItemId = $dto->lotItemId;
                $tmpBid = $dto->maxBid;
            }
        } elseif ($filterAuction->isTimed()) {
            $repo = $this->createBidTransactionReadRepository()
                ->enableReadOnlyDb(true)
                ->filterAuctionId($filterAuction->Id)
                ->filterDeleted(false)
                ->filterBidGreater(0)
                ->groupByLotItemId()
                ->groupByUserId()
                ->joinAuctionBidderFilterApproved(true)
                ->joinAuctionBidderSkipBidderNum(null)
                ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
                ->orderByLotItemId()
                ->order('bid', false)
                ->select(['bt.lot_item_id', 'bt.user_id', 'u.username', 'MAX(bt.bid) as bid'])
                ->setChunkSize(200);
            $tmpBid = 0;
            while ($rows = $repo->loadRows()) {
                foreach ($rows as $row) {
                    $dtos[] = AuctionBidderOverviewDto::new()->fromDbRow($row);
                }
            }
            foreach ($dtos as $dto) {
                if ($tmpLotItemId === $dto->lotItemId) {
                    if (Floating::eq($tmpBid, $dto->bid)) {
                        $allHighBidderRows[] = $dto;
                    } else {
                        $losingBidderRows[] = $dto;
                    }
                } else {
                    $allHighBidderRows[] = $dto;
                }
                $tmpLotItemId = $dto->lotItemId;
                $tmpBid = $dto->bid;
            }
        }

        return [$allHighBidderRows, $losingBidderRows];
    }
}
