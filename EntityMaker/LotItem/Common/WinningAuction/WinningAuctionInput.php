<?php
/**
 * SAM-10106: Supply lot winning info correspondence for winning auction and winning bidder fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Common\WinningAuction;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;

/**
 * Class AuctionSoldInput
 * @package Sam\EntityMaker\LotItem
 */
class WinningAuctionInput extends CustomizableClass
{
    /**
     * @var int|null
     */
    public ?int $id = null;
    /**
     * @var int|null
     */
    public ?int $saleNo = null;
    /**
     * @var string|null
     */
    public ?string $syncKey = '';
    /**
     * @var bool
     */
    protected bool $isSet = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function fromDto(LotItemMakerInputDto $inputDto): static
    {
        $this->id = Cast::toInt($inputDto->auctionSoldId);
        $this->saleNo = Cast::toInt($inputDto->auctionSoldName);
        $this->syncKey = $inputDto->auctionSoldSyncKey;
        $this->isSet = isset($inputDto->auctionSoldId)
            || isset($inputDto->auctionSoldName)
            || isset($inputDto->auctionSoldSyncKey);
        return $this;
    }

    public function isSet(): bool
    {
        return $this->isSet;
    }
}
