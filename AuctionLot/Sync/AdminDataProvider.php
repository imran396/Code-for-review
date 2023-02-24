<?php
/**
 * Class for sending lots and auction data via auto-sync script at admin side (auction lots page)
 *
 * Related tickets:
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 * SAM-3742: Refactor data provider classes
 * SAM-2814: Auto-update admin lots page
 * SAM-2978: Improve auction lot data sync scripts
 *
 * @copyright  2018 Bidpath, Inc.
 * @author     Igors Kotlevskis
 * @package    com.swb.sam2
 * @version    $Id$
 * @since      Aug 21, 2015
 * @copyright  Copyright 2018 by Bidpath, Inc. All rights reserved.
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 * Return json array with information for list of auction lots at admin side "Auction Lots" page
 * This is used for auto update
 *
 */

namespace Sam\AuctionLot\Sync;

use Sam\AuctionLot\Sync\Internal\SyncDataResponseRendererCreateTrait;
use Sam\AuctionLot\Sync\Response\Concrete\AdminData\AdminDataProducerCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AdminDataProvider
 * @package Sam\AuctionLot\Sync
 * AuctionCacheLoaderAwareTrait is used for updating aggregated total values
 */
class AdminDataProvider extends CustomizableClass
{
    use AdminDataProducerCreateTrait;
    use SyncDataResponseRendererCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $auctionLotIds
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function run(
        array $auctionLotIds,
        int $auctionId,
        int $editorUserId
    ): void {
        $response = $this->createAdminDataProducer()->produce($auctionLotIds, $auctionId, $editorUserId);
        $this->createSyncDataResponseRenderer()->construct()->render($response);
    }
}
