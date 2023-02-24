<?php
/**
 * Auction Spending Report Data Loader
 *
 * SAM-5841: Refactor data loader for Auction Spending Report page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 21, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionSpendingReportForm\Load;

use Sam\Core\Constants;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepositoryCreateTrait;
use Sam\View\Admin\Form\AuctionSpendingReportForm\AuctionSpendingReportConstants;

/**
 * Class AuctionSpendingReportDataLoader
 */
class AuctionSpendingReportDataLoader extends CustomizableClass
{
    use AuctionBidderReadRepositoryCreateTrait;
    use FilterAuctionAwareTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return array of auction bidders
     * @return AuctionSpendingReportDto[]
     */
    public function load(): array
    {
        $auctionBidderRepository = $this->prepareAuctionBidderRepository();
        $ascending = $this->isAscendingOrder();
        switch ($this->getSortColumn()) {
            case AuctionSpendingReportConstants::CID_ORD_USERNAME:
                $auctionBidderRepository->joinUserOrderByUsername($ascending);
                break;
            case AuctionSpendingReportConstants::CID_ORD_FIRST_LAST_NAME:
                $auctionBidderRepository
                    ->joinUserInfoOrderByFirstName($ascending)
                    ->joinUserInfoOrderByLastName($ascending);
                break;
            case AuctionSpendingReportConstants::CID_ORD_BIDDER_NUM:
                $auctionBidderRepository->orderByBidderNum($ascending);
                break;
            case AuctionSpendingReportConstants::CID_ORD_SPENT:
                $auctionBidderRepository->orderBySpent($ascending);
                break;
            case AuctionSpendingReportConstants::CID_ORD_COLLECTED:
                $auctionBidderRepository->orderByCollected($ascending);
                break;
            case AuctionSpendingReportConstants::CID_ORD_MAX_OUTSTANDING:
                $auctionBidderRepository->order(AuctionSpendingReportConstants::CID_ORD_MAX_OUTSTANDING, $ascending);
                break;
            case AuctionSpendingReportConstants::CID_ORD_OUTSTANDING:
                $auctionBidderRepository->order(AuctionSpendingReportConstants::CID_ORD_OUTSTANDING, $ascending);
                break;
        }

        if ($this->getOffset()) {
            $auctionBidderRepository->offset($this->getOffset());
        }

        if ($this->getLimit()) {
            $auctionBidderRepository->limit($this->getLimit());
        }

        $dtos = [];
        foreach ($auctionBidderRepository->loadRows() as $row) {
            $dtos[] = AuctionSpendingReportDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * Return count of Auction Bidders
     * @return int
     */
    public function count(): int
    {
        return $this->prepareAuctionBidderRepository()->count();
    }

    /**
     * @return AuctionBidderReadRepository
     */
    private function prepareAuctionBidderRepository(): AuctionBidderReadRepository
    {
        return $this->createAuctionBidderReadRepository()
            ->filterApproved(true)
            ->filterAuctionId($this->getFilterAuctionId())
            ->joinAuction()
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->joinUserInfo()
            ->select(
                [
                    'aub.spent AS spent',
                    'aub.collected AS collected',
                    'aub.spent - IFNULL(aub.collected, 0) AS outstanding',
                    'aub.bidder_num AS bidder_num',
                    'u.id AS user_id',
                    'u.username AS username',
                    'ui.first_name AS fname',
                    'ui.last_name AS lname',
                    'IF(ui.`max_outstanding` IS NULL, a.`max_outstanding`, ui.`max_outstanding`) AS max_outstanding',
                ]
            );
    }
}
