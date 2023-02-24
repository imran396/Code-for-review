<?php
/**
 * SAM-6377: Separate bulk group related logic to classes
 * https://bidpath.atlassian.net/browse/SAM-6377
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\BulkGroup\Delete;

use AuctionLotItem;
use Sam\AuctionLot\BulkGroup\Load\LotBulkGroupLoaderAwareTrait;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;

/**
 * Class BulkGroupRevoker
 * @package Sam\AuctionLot\BulkGroup
 */
class LotBulkGroupRevoker extends CustomizableClass
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use LotBulkGroupLoaderAwareTrait;

    /**
     * @var AuctionLotItem[]
     */
    protected array $revokedAuctionLots = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Remove piecemeal lots from bulk muster lot
     * @param int $masterAuctionLotId
     * @param int $editorUserId
     */
    public function revokePiecemealLots(int $masterAuctionLotId, int $editorUserId): void
    {
        $piecemealAuctionLots = $this->getLotBulkGroupLoader()->loadPiecemealAuctionLots($masterAuctionLotId);
        foreach ($piecemealAuctionLots as $piecemealAuctionLot) {
            $piecemealAuctionLot->removeFromBulkGroup();
            $this->getAuctionLotItemWriteRepository()->saveWithModifier($piecemealAuctionLot, $editorUserId);
            $this->revokedAuctionLots[] = $piecemealAuctionLot;
        }
        $logData = [
            'master ali' => $masterAuctionLotId,
            'piecemeal ali' => ArrayHelper::toArrayByProperty($this->revokedAuctionLots, 'Id')
        ];
        log_debug('Removed piecemeal lots from bulk group of master auction lot' . composeSuffix($logData));
    }

    /**
     * Return revoked auctionLotItems
     * @return AuctionLotItem[]
     * @internal (for unit tests)
     */
    public function getRevokedAuctionLots(): array
    {
        return $this->revokedAuctionLots;
    }
}
