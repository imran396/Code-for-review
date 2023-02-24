<?php
/**
 * Internal DTO object with data required for on-increment bid validation.
 *
 * SAM-6909: Refactor on-increment bid validator for v3.6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\OnIncrementBid\Internal\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class Dto
 * @package Sam\Bidding\OnIncrementBid\Internal\Load
 */
class Dto extends CustomizableClass
{
    public readonly ?int $accountId;
    public readonly ?float $currentBid;
    public readonly ?float $startingBidNormalized;
    public readonly ?float $askingBid;
    public readonly bool $isReverseAuction;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $accountId
     * @param float|null $currentBid
     * @param float|null $startingBidNormalized
     * @param float|null $askingBid
     * @param bool $isReverseAuction
     * @return $this
     */
    public function construct(
        ?int $accountId,
        ?float $currentBid,
        ?float $startingBidNormalized,
        ?float $askingBid,
        bool $isReverseAuction
    ): static {
        $this->accountId = $accountId;
        $this->currentBid = $currentBid;
        $this->startingBidNormalized = $startingBidNormalized;
        $this->askingBid = $askingBid;
        $this->isReverseAuction = $isReverseAuction;
        return $this;
    }

    /**
     * @param array $row
     * @return static
     */
    public function fromRow(array $row): static
    {
        return $this->construct(
            Cast::toInt($row['account_id'] ?? null),
            Cast::toFloat($row['current_bid'] ?? null),
            Cast::toFloat($row['starting_bid_normalized'] ?? null),
            Cast::toFloat($row['asking_bid'] ?? null),
            (bool)($row['reverse'] ?? false)
        );
    }
}
