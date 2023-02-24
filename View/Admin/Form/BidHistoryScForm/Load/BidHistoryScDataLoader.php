<?php
/**
 * Bid History Sc Data Loader
 *
 * SAM-5937: Refactor bid history sc page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 23, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BidHistoryScForm\Load;

use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepositoryCreateTrait;

/**
 * Class BidHistoryScDataLoader
 */
class BidHistoryScDataLoader extends CustomizableClass
{
    use AuctionBidderReadRepositoryCreateTrait;
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
     * Return array of bidding history
     * @return BidHistoryScDto[] - return values for Bid History Scs
     */
    public function load(): array
    {
        $repo = $this->createAuctionBidderReadRepository()
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->joinUserInfo()
            ->filterAuctionId($this->getFilterAuctionId())
            ->orderByBidderNum(false)
            ->select(
                [
                    "u.id",
                    "aub.bidder_num",
                    "ui.first_name",
                    "ui.last_name",
                ]
            );

        $dtos = [];
        foreach ($repo->loadRows() as $row) {
            $dtos[] = BidHistoryScDto::new()->fromDbRow($row);
        }
        return $dtos;
    }
}
