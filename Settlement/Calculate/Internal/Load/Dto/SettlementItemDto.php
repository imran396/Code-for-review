<?php
/**
 * SAM-6499: Refactor Settlement Calculator module (2020 year)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Calculate\Internal\Load\Dto;

use InvalidArgumentException;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementItemDto
 * @package Sam\Settlement\Calculate\Internal\Load\Dto
 * @internal
 */
class SettlementItemDto extends CustomizableClass
{
    public readonly float $hp;
    public readonly int $lotItemId;
    public readonly ?int $auctionId;
    public readonly int $winningBidderId;
    public readonly string $auctionCountry;
    public readonly ?int $invoiceId;
    public readonly float $salesTax;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string[] $row
     * @return static
     */
    public function fromDbRow(array $row): static
    {
        if (array_diff(
            ['HP', 'lot_item_id', 'auction_id', 'winning_bidder_id', 'auction_country', 'invoice_id', 'sales_tax'],
            array_keys($row)
        )) {
            throw new InvalidArgumentException(sprintf('Not all keys are set %s', var_export($row, true)));
        }

        $this->hp = (float)$row['HP'];
        $this->lotItemId = (int)$row['lot_item_id'];
        $this->auctionId = Cast::toInt($row['auction_id']);
        $this->winningBidderId = (int)$row['winning_bidder_id'];
        $this->auctionCountry = (string)$row['auction_country'];
        $this->invoiceId = Cast::toInt($row['invoice_id']);
        $this->salesTax = (float)$row['sales_tax'];
        return $this;
    }
}
