<?php
/**
 * SAM-6799: Refactor consignor pdf report
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Consignor\Pdf\Internal\Load;

use Auction;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * Class DataLoader
 * @package Sam\Report\Consignor\Pdf\Internal\Load
 */
class DataLoader extends CustomizableClass
{
    use AuctionLotLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadAuction(?int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb);
    }

    public function loadUser(int $userId, bool $isReadOnlyDb = false): ?User
    {
        return $this->getUserLoader()->load($userId, $isReadOnlyDb);
    }

    public function loadAuctionLots(?int $auctionId, int $consignorUserId, ?int $lotStatus, bool $isReadOnlyDb = false): array
    {
        $loader = $this->getAuctionLotLoader();
        if ($lotStatus) {
            $loader->filterLotStatusId($lotStatus);
        }
        return $loader->loadByAuctionAndConsignor($auctionId, $consignorUserId, $isReadOnlyDb);
    }
}
