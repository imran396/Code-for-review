<?php
/**
 * SAM-10177: Decouple the "Lot status change" function at the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotDto
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Validate\Internal\Load
 */
class LotDto extends CustomizableClass
{
    public readonly int $lotItemId;
    public readonly ?float $hammerPrice;
    public readonly int $itemNum;
    public readonly string $itemNumExt;
    public readonly ?string $lotNum;
    public readonly ?string $lotNumExt;
    public readonly ?string $lotNumPrefix;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $lotItemId,
        ?float $hammerPrice,
        int $itemNum,
        string $itemNumExt,
        ?string $lotNum,
        ?string $lotNumExt,
        ?string $lotNumPrefix
    ): static {
        $this->lotItemId = $lotItemId;
        $this->hammerPrice = $hammerPrice;
        $this->itemNum = $itemNum;
        $this->itemNumExt = $itemNumExt;
        $this->lotNum = $lotNum;
        $this->lotNumExt = $lotNumExt;
        $this->lotNumPrefix = $lotNumPrefix;
        return $this;
    }
}
