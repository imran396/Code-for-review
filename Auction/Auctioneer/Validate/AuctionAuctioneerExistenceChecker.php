<?php
/**
 * SAM-4453 : Apply Auction Auctioneer loader
 * https://bidpath.atlassian.net/browse/SAM-4453
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sept 21, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Auctioneer\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionAuctioneer\AuctionAuctioneerReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionAuctioneer\AuctionAuctioneerReadRepositoryCreateTrait;

/**
 * Class AuctionAuctioneerExistenceChecker
 * @package Sam\Auction\Auctioneer\Validate
 */
class AuctionAuctioneerExistenceChecker extends CustomizableClass
{
    use AuctionAuctioneerReadRepositoryCreateTrait;
    use SystemAccountAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check whether an AuctionAuctioneer exists by Name
     *
     * @param string $name
     * @param int[] $accountIds auction_auctioneer.account_id
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByName(string $name, array $accountIds, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($accountIds)
            ->filterName($name)
            ->exist();
        return $isFound;
    }

    /**
     * Check if exist active, with system account id
     * @param int $auctionAuctioneerId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existSystemById(int $auctionAuctioneerId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($this->getSystemAccountId())
            ->filterId($auctionAuctioneerId)
            ->exist();
        return $isFound;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AuctionAuctioneerReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): AuctionAuctioneerReadRepository
    {
        $repo = $this->createAuctionAuctioneerReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
        return $repo;
    }
}
