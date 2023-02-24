<?php
/**
 * SAM-6481: Add bidding options on the admin - auction - lots - added lots table
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\PlaceBid\LiveOrHybrid;

use Sam\Application\Controller\Admin\PlaceBid\PlaceBidCommand;
use Sam\Application\Controller\Admin\PlaceBid\PlaceBidHandlerInterface;
use Sam\Bidding\AbsenteeBid\Place\AbsenteeBidManagerCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Command handler to place live or hybrid absentee bid by admin
 *
 * Class LiveOrHybridPlaceBidHandler
 * @package Sam\Application\Controller\Admin\PlaceBid\LiveOrHybrid
 */
class LiveOrHybridPlaceBidHandler extends CustomizableClass implements PlaceBidHandlerInterface
{
    use AbsenteeBidManagerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param PlaceBidCommand $command
     */
    public function handle(PlaceBidCommand $command): void
    {
        $absenteeBidManager = $this->createAbsenteeBidManager()
            ->enableAddToWatchlist(true)
            ->enableNotifyUsers(true)
            ->setAuctionId($command->auctionId)
            ->setEditorUserId($command->bidderId)
            ->setLotItemId($command->lotItemId)
            ->setMaxBid($command->maxBidAmount)
            ->setUserId($command->bidderId);
        $absenteeBidManager->place();
    }
}
