<?php
/**
 * Lot Item Offer Bid Data Loader
 *
 * SAM-6248: Refactor Lot Info Panel at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\OfferBid\Load;

use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\AuctionLotAwareTrait;
use Sam\Storage\ReadRepository\Entity\TimedOnlineOfferBid\TimedOnlineOfferBidReadRepository;
use Sam\Storage\ReadRepository\Entity\TimedOnlineOfferBid\TimedOnlineOfferBidReadRepositoryCreateTrait;
use TimedOnlineOfferBid;

/**
 * Class LotItemOfferBidDataLoader
 */
class LotItemOfferBidDataLoader extends CustomizableClass
{
    use AuctionLotAwareTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;
    use TimedOnlineOfferBidReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int - return value of Timed Online Lot Item Offer Bids count
     */
    public function count(): int
    {
        return $this->prepareTimedOnlineOfferBidRepository()->count();
    }

    /**
     * @return TimedOnlineOfferBid[]|array - return values for Timed Online Offer Bids
     */
    public function load(): array
    {
        $repo = $this->prepareTimedOnlineOfferBidRepository();

        if ($this->getSortColumn()) {
            $repo->orderByBid($this->isAscendingOrder());
        } else {
            $repo->orderByDateAdded(false);
        }

        if ($this->getOffset()) {
            $repo->offset($this->getOffset());
        }

        if ($this->getLimit()) {
            $repo->limit($this->getLimit());
        }

        return $repo->loadEntities();
    }

    /**
     * @return TimedOnlineOfferBidReadRepository
     */
    protected function prepareTimedOnlineOfferBidRepository(): TimedOnlineOfferBidReadRepository
    {
        return $this->createTimedOnlineOfferBidReadRepository()
            ->enableReadOnlyDb(true)
            ->filterAuctionLotItemId($this->getAuctionLotId());
    }
}
