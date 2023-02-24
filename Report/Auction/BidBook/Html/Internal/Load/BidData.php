<?php
/**
 * SAM-5753: Refactor "Bid Book" report
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\BidBook\Html\Internal\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BidData
 * @package Sam\Report\Auction\BidBook\Html\Internal\Load
 */
class BidData extends CustomizableClass
{
    public readonly float $maxBid;
    public readonly string $bidderFirstName;
    public readonly string $bidderLastName;
    public readonly string $bidderNum;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        float $maxBid,
        string $bidderFirstName,
        string $bidderLastName,
        string $bidderNum
    ): static {
        $this->maxBid = $maxBid;
        $this->bidderFirstName = $bidderFirstName;
        $this->bidderLastName = $bidderLastName;
        $this->bidderNum = $bidderNum;
        return $this;
    }

    public function fromDbRow(array $row): static
    {
        return $this->construct(
            Cast::toFloat($row['max_bid']),
            (string)$row['bfirst_name'],
            (string)$row['blast_name'],
            (string)$row['bidder_num']
        );
    }
}
