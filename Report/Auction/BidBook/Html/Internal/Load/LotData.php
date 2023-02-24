<?php
/**
 * SAM-5753: Refactor "Bid Book" report
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\BidBook\Html\Internal\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotData
 * @package Sam\Report\Auction\BidBook\Html\Internal\Load
 */
class LotData extends CustomizableClass
{
    public readonly ?float $cost;
    public readonly ?float $hammerPrice;
    public readonly ?float $highEstimate;
    public readonly ?float $lowEstimate;
    public readonly ?float $reservePrice;
    public readonly ?float $startingBid;
    public readonly ?int $auctionIdSold;
    public readonly ?int $imageId;
    public readonly int $auctionId;
    public readonly int $lotItemId;
    public readonly string $consignorCompanyName;
    public readonly string $consignorFirstname;
    public readonly string $consignorLastName;
    public readonly string $consignorPhone;
    public readonly string $lotDescription;
    public readonly string $lotName;
    public readonly string $lotNum;
    public readonly string $lotNumExt;
    public readonly string $lotNumPrefix;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        ?float $cost,
        ?float $hammerPrice,
        ?float $highEstimate,
        ?float $lowEstimate,
        ?float $reservePrice,
        ?float $startingBid,
        ?int $auctionIdSold,
        ?int $imageId,
        int $auctionId,
        int $lotItemId,
        string $consignorCompanyName,
        string $consignorFirstname,
        string $consignorLastName,
        string $consignorPhone,
        string $lotDescription,
        string $lotName,
        string $lotNum,
        string $lotNumExt,
        string $lotNumPrefix
    ): static {
        $this->cost = $cost;
        $this->hammerPrice = $hammerPrice;
        $this->highEstimate = $highEstimate;
        $this->lowEstimate = $lowEstimate;
        $this->reservePrice = $reservePrice;
        $this->startingBid = $startingBid;
        $this->auctionIdSold = $auctionIdSold;
        $this->imageId = $imageId;
        $this->auctionId = $auctionId;
        $this->lotItemId = $lotItemId;
        $this->consignorCompanyName = $consignorCompanyName;
        $this->consignorFirstname = $consignorFirstname;
        $this->consignorLastName = $consignorLastName;
        $this->consignorPhone = $consignorPhone;
        $this->lotDescription = $lotDescription;
        $this->lotName = $lotName;
        $this->lotNum = $lotNum;
        $this->lotNumExt = $lotNumExt;
        $this->lotNumPrefix = $lotNumPrefix;
        return $this;
    }

    public function fromDbRow(array $row): static
    {
        return $this->construct(
            Cast::toFloat($row['cost']),
            Cast::toFloat($row['hammer_price']),
            Cast::toFloat($row['high_estimate']),
            Cast::toFloat($row['low_estimate']),
            Cast::toFloat($row['reserve_price']),
            Cast::toFloat($row['starting_bid']),
            Cast::toInt($row['auction_id_sold']),
            Cast::toInt($row['image_id']),
            (int)$row['aid'],
            (int)$row['lot_id'],
            (string)$row['ccompany_name'],
            (string)$row['cfirst_name'],
            (string)$row['clast_name'],
            (string)$row['cphone'],
            (string)$row['lot_description'],
            (string)$row['lot_name'],
            (string)$row['lot_num'],
            (string)$row['lot_num_ext'],
            (string)$row['lot_num_prefix']
        );
    }
}
