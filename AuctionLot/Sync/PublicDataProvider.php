<?php
/**
 * Class for sending auction lot data via auto-sync script at public side (catalog, search, my items pages)
 *
 * Related tickets:
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 * SAM-3742: Refactor data provider classes
 * SAM-2814: Auto-update admin lots page
 * SAM-2978: Improve auction lot data sync scripts
 * SAM-3182: Responsive side: Data sync module for client side functionality
 *
 * @copyright  2018 Bidpath, Inc.
 * @author     Tom Blondeau, Igors Kotlevskis, Igor Mironyak
 * @package    com.swb.sam2
 * @version    $Id$
 * @since      Aug 21, 2015
 * @copyright  Copyright 2018 by Bidpath, Inc. All rights reserved.
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\AuctionLot\Sync;

use Sam\AuctionLot\Sync\Internal\SyncDataCacheCreateTrait;
use Sam\AuctionLot\Sync\Internal\SyncDataResponseRendererCreateTrait;
use Sam\AuctionLot\Sync\Response\Concrete\PublicData\PublicDataProducerCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Return Protobuf message with information for list of auction_lot_item.id's
 * This is used for auto update of catalog, search results, my items pages
 *
 * We don't want to expose current_max_bid, reserve_price, current_bidder_id values via ajax request, hence
 * we send Reserve Not Meet instead reserve_price and current_max_bid values,
 * and Is High Bidder instead of current_bidder_id value.
 *
 * Class PublicDataProvider
 * @package Sam\AuctionLot\Sync
 */
class PublicDataProvider extends CustomizableClass
{
    use PublicDataProducerCreateTrait;
    use SyncDataCacheCreateTrait;
    use SyncDataResponseRendererCreateTrait;

    protected const CACHE_NAMESPACE = 'sync-lots';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $systemAccountId
     * @param int|null $editorUserId
     * @param array $auctionLotIds
     * @param bool $isProfilingEnabled
     */
    public function run(int $systemAccountId, ?int $editorUserId, array $auctionLotIds, bool $isProfilingEnabled = false): void
    {
        $responseRenderer = $this->createSyncDataResponseRenderer()->construct();
        $cache = null;
        if (!$editorUserId) {
            $cache = $this->createSyncDataCache()->construct(
                [$systemAccountId, $editorUserId, $auctionLotIds],
                self::CACHE_NAMESPACE
            );
            $cachedData = $cache->get();
            if ($cachedData) {
                $responseRenderer->output($cachedData, $isProfilingEnabled);
                return;
            }
        }

        $publicDataProducer = $this->createPublicDataProducer()->construct($isProfilingEnabled);
        $publicDataResponseMessage = $publicDataProducer->produce($systemAccountId, $editorUserId, $auctionLotIds);
        $output = $responseRenderer->render($publicDataResponseMessage);

        $cache?->set($output);
    }
}
