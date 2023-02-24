<?php
/**
 * SAM-10467: Implement a GraphQL nested structure for a single auction
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\AuctionAuctioneer;

use Sam\Auction\Auctioneer\Load\AuctionAuctioneerLoaderAwareTrait;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionAuctioneer\AuctionAuctioneerReadRepositoryCreateTrait;

/**
 * Class AuctionAuctioneerDataLoader
 * @package Sam\Api\GraphQL\Load\Internal\Auctioneer
 */
class AuctionAuctioneerDataLoader extends CustomizableClass
{
    use AuctionAuctioneerLoaderAwareTrait;
    use AuctionAuctioneerReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function load(array $ids, array $fields, bool $isReadOnlyDb = false): array
    {
        if (!in_array('id', $fields, true)) {
            $fields[] = 'id';
        }
        $auctioneers = $this->createAuctionAuctioneerReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($ids)
            ->select($fields)
            ->loadRows();
        return ArrayHelper::produceIndexedArray($auctioneers, 'id');
    }

    public function loadAll(int $accountId, array $fields, bool $isReadOnlyDb = false): array
    {
        if (!in_array('id', $fields, true)) {
            $fields[] = 'id';
        }
        $auctioneers = $this->getAuctionAuctioneerLoader()->loadAllSelected($fields, $accountId, $isReadOnlyDb);
        return ArrayHelper::produceIndexedArray($auctioneers, 'id');
    }
}
