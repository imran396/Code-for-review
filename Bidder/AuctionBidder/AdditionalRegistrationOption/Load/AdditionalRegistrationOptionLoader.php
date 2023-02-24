<?php
/**
 * SAM-4038:Refactor Additional Registration Options logic
 * https://bidpath.atlassian.net/browse/SAM-4038
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/11/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\AdditionalRegistrationOption\Load;

use AuctionBidderOption;
use AuctionBidderOptionSelection;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionBidderOption\AuctionBidderOptionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionBidderOptionSelection\AuctionBidderOptionSelectionReadRepositoryCreateTrait;

/**
 * Class AdditionalRegistrationOptionLoader
 * @package Sam\Bidder\AuctionBidder\AdditionalRegistrationOption\Load
 */
class AdditionalRegistrationOptionLoader extends EntityLoaderBase
{
    use AuctionBidderOptionReadRepositoryCreateTrait;
    use AuctionBidderOptionSelectionReadRepositoryCreateTrait;
    use UserAwareTrait;
    use AuctionAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Class method
     * Method to get AuctionBidderOption
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return AuctionBidderOption[]
     */
    public function loadByAccount(int $accountId, bool $isReadOnlyDb = false): array
    {
        $auctionBidderOptions = $this->createAuctionBidderOptionReadRepository()
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->orderByOrder()
            ->loadEntities();
        return $auctionBidderOptions;
    }

    /**
     * Class method
     * Method to get AuctionBidderSelection by $userId,$auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionBidderOptionSelection|null
     */
    public function loadByUserAndAuction(bool $isReadOnlyDb = false): ?AuctionBidderOptionSelection
    {
        $auctionBidderOptionSelection = $this->createAuctionBidderOptionSelectionReadRepository()
            ->filterAuctionId($this->getAuctionId())
            ->filterUserId($this->getUserId())
            ->enableReadOnlyDb($isReadOnlyDb)
            ->loadEntity();
        return $auctionBidderOptionSelection;
    }
}
