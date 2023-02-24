<?php
/**
 * SAM-5338: Auction bidder loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           08/08/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\EntityLoader;

use Account;
use Auction;
use Sam\Core\Constants;
use Sam\Core\Filter\Availability\FilterAccountAvailabilityAwareTrait;
use Sam\Core\Filter\Availability\FilterAuctionAvailabilityAwareTrait;
use Sam\Core\Filter\Availability\FilterUserAvailabilityAwareTrait;
use Sam\Core\Filter\Conformity\FilterDescriptor;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepositoryCreateTrait;
use User;

/**
 * Trait AuctionBidderAllFilterTrait
 * @package Sam\Core\Filter\EntityLoader
 */
trait AuctionBidderAllFilterTrait
{
    use AuctionBidderReadRepositoryCreateTrait;
    use FilterAccountAvailabilityAwareTrait;
    use FilterAuctionAvailabilityAwareTrait;
    use FilterUserAvailabilityAwareTrait;

    /**
     * @return static
     */
    public function initFilter(): static
    {
        $this->filterAccountActive(true);
        $this->filterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses);
        $this->filterUserStatusId(Constants\User::US_ACTIVE);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterAccount();
        $this->clearFilterAuction();
        $this->clearFilterUser();
        return $this;
    }

    /**
     * @return FilterDescriptor[]
     */
    public function collectFilterDescriptors(): array
    {
        $descriptors = [];
        if ($this->getFilterAccountActive()) {
            $descriptors[] = FilterDescriptor::new()->init(Account::class, 'Active', $this->getFilterAccountActive());
        }
        if ($this->getFilterAuctionStatusId()) {
            $descriptors[] = FilterDescriptor::new()->init(Auction::class, 'AuctionStatusId', $this->getFilterAuctionStatusId());
        }
        if ($this->getFilterUserStatusId()) {
            $descriptors[] = FilterDescriptor::new()->init(User::class, 'UserStatusId', $this->getFilterUserStatusId());
        }
        return $descriptors;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AuctionBidderReadRepository
     */
    protected function prepareAuctionBidderRepository(bool $isReadOnlyDb): AuctionBidderReadRepository
    {
        $repo = $this->createAuctionBidderReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterAccountActive()) {
            $repo->joinAccountFilterActive($this->getFilterAccountActive());
        }
        if ($this->hasFilterAuctionStatusId()) {
            $repo->joinAuctionFilterAuctionStatusId($this->getFilterAuctionStatusId());
        }
        if ($this->hasFilterUserStatusId()) {
            $repo->joinUserFilterUserStatusId($this->getFilterUserStatusId());
        }
        return $repo;
    }
}
