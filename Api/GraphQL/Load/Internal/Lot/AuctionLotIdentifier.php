<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\Lot;

use Sam\Core\Service\CustomizableClass;

/**
 * Class Identifier
 * @package Sam\Api\GraphQL\Load\Internal\Lot
 */
class AuctionLotIdentifier extends CustomizableClass
{
    public readonly int $lotItemId;
    public readonly int $auctionId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $lotItemId, int $auctionId): static
    {
        $this->lotItemId = $lotItemId;
        $this->auctionId = $auctionId;
        return $this;
    }

    public function __toString(): string
    {
        return "{$this->lotItemId}_{$this->auctionId}";
    }
}
