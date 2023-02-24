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

namespace Sam\EntityMaker\LotItem\Common\WinningBidder;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;

/**
 * Class WinningBidderInput
 * @package Sam\EntityMaker\LotItem
 */
class WinningBidderInput extends CustomizableClass
{
    /**
     * @var int|null
     */
    public ?int $id = null;
    /**
     * @var string|null
     */
    public ?string $name = '';
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
        $this->id = Cast::toInt($inputDto->winningBidderId);
        $this->name = $inputDto->winningBidderName;
        $this->syncKey = $inputDto->winningBidderSyncKey;
        $this->isSet = isset($inputDto->winningBidderId)
            || isset($inputDto->winningBidderName)
            || isset($inputDto->winningBidderSyncKey);
        return $this;
    }

    public function isSet(): bool
    {
        return $this->isSet;
    }
}
