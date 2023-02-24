<?php
/**
 * SAM-6503: Even when the Limit Bidding Info Permission is set to admin it shows asking bid for others User as well
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\Load;

use Sam\Bidder\AuctionBidder\Query\AuctionBidderQueryBuilderHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Database\SimpleMysqliDatabaseAwareTrait;

/**
 * Contains methods for loading data that are used to determine the user's access role.
 *
 * Class SyncLotAccessCheckerLoader
 * @package Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\Load
 * @internal
 */
class SyncLotAccessCheckerLoader extends CustomizableClass
{
    use AuctionBidderQueryBuilderHelperCreateTrait;
    use SimpleMysqliDatabaseAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isProfilingEnabled
     * @return static
     */
    public function construct(bool $isProfilingEnabled = false): static
    {
        $this->getSimpleMysqliDatabase()->construct($isProfilingEnabled);
        return $this;
    }

    /**
     * @param int $userId
     * @param array $amongIds
     * @return array
     */
    public function loadAuctionIdsApprovedIn(int $userId, array $amongIds): array
    {
        $amongIds = ArrayCast::castInt($amongIds, Constants\Type::F_INT_POSITIVE, [ArrayCast::OP_KEEP_NULLS => false]);
        if (!$amongIds) {
            return [];
        }

        $approvedBidderWhereClause = $this->createAuctionBidderQueryBuilderHelper()->makeApprovedBidderWhereClause('ab');
        $amongIdsPrepared = implode(', ', $amongIds);
        $query = <<<SQL
SELECT ab.auction_id
FROM auction_bidder ab
WHERE {$approvedBidderWhereClause}
  AND ab.user_id = {$userId}
  AND ab.auction_id IN ({$amongIdsPrepared})
SQL;
        $rows = $this->getSimpleMysqliDatabase()
            ->query($query)
            ->fetch_all(MYSQLI_NUM);

        $ids = ArrayHelper::flattenArray($rows);
        $ids = ArrayCast::castInt($ids);
        return $ids;
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function isUserAdmin(int $userId): bool
    {
        $query = "SELECT COUNT(*) FROM admin WHERE user_id = {$userId}";
        $row = $this->getSimpleMysqliDatabase()
            ->query($query)
            ->fetch_row();
        return $row[0] > 0;
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function isUserConsignor(int $userId): bool
    {
        $query = "SELECT COUNT(*) FROM consignor WHERE user_id = {$userId}";
        $row = $this->getSimpleMysqliDatabase()
            ->query($query)
            ->fetch_row();
        return $row[0] > 0;
    }
}
