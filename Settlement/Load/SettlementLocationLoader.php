<?php
/**
 * SAM-8016: Add 'City' as an attribute of Location
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Location\LocationReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\SettlementItem\SettlementItemReadRepositoryCreateTrait;

/**
 * Class SettlementLocationLoader
 * @package Sam\Settlement\Load
 */
class SettlementLocationLoader extends CustomizableClass
{
    use LocationReadRepositoryCreateTrait;
    use SettlementItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function load(int $settlementId, bool $isReadOnlyDb = false): array
    {
        $auctionId = $this->detectSettlementAuctionId($settlementId, $isReadOnlyDb);
        if (!$auctionId) {
            return [];
        }
        $locationRow = $this->createLocationReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->select(['loc.*'])
            ->joinAuctionFilterId($auctionId)
            ->loadRow();
        return $locationRow;
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    protected function detectSettlementAuctionId(int $settlementId, bool $isReadOnlyDb = false): ?int
    {
        $select = [
            'si.auction_id',
            'COUNT(si.auction_id) AS ctr',
        ];
        $row = $this->createSettlementItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterSettlementId($settlementId)
            ->groupByAuctionId()
            ->order('ctr', false)
            ->skipAuctionId(null)
            ->select($select)
            ->loadRow();
        return $row ? (int)$row['auction_id'] : null;
    }
}
