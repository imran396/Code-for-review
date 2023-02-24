<?php
/**
 * SAM-6481: Add bidding options on the admin - auction - lots - added lots table
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\PlaceBid;

use Sam\Core\Service\CustomizableClass;

/**
 * Class that contains all necessary data to place a bid by admin
 *
 * Class PlaceBidCommand
 * @package Sam\Application\Controller\Admin\PlaceBid\Dto
 */
class PlaceBidCommand extends CustomizableClass
{
    public readonly ?int $auctionId;
    public readonly ?int $auctionLotId;
    public readonly ?int $lotItemId;
    public readonly ?int $bidderId;
    public readonly ?float $maxBidAmount;
    public readonly ?float $askingBidAmount;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        ?int $auctionId,
        ?int $auctionLotId,
        ?int $lotItemId,
        ?int $bidderId,
        ?float $maxBidAmount,
        ?float $askingBidAmount
    ): static {
        $this->auctionId = $auctionId;
        $this->auctionLotId = $auctionLotId;
        $this->lotItemId = $lotItemId;
        $this->bidderId = $bidderId;
        $this->maxBidAmount = $maxBidAmount;
        $this->askingBidAmount = $askingBidAmount;
        return $this;
    }
}
