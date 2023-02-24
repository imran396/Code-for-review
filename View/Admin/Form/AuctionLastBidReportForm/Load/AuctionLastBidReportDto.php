<?php
/**
 * SAM-8334: Apply DTOs for loaded data at Auction Last Bid Report page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLastBidReportForm\Load;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionLastBidReportDto
 * @package Sam\View\Admin\Form\AuctionLastBidReportForm\Load
 */
class AuctionLastBidReportDto extends CustomizableClass
{
    public readonly int $auctionId;
    public readonly ?float $bid;
    public readonly string $bidderNum;
    public readonly string $createdOn;
    public readonly string $firstName;
    public readonly string $lastName;
    public readonly string $location;
    public readonly int $lotItemId;
    public readonly ?int $lotNum;
    public readonly string $lotNumExt;
    public readonly string $lotNumPrefix;
    public readonly ?float $maxBid;
    public readonly string $name;
    public readonly int $userId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param float|null $bid null means that this auction is not timed
     * @param string $bidderNum
     * @param string $createdOn
     * @param string $firstName
     * @param string $lastName
     * @param string $location
     * @param int $lotItemId
     * @param int|null $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param float|null $maxBid
     * @param string $name
     * @param int $userId
     * @return $this
     */
    public function construct(
        int $auctionId,
        ?float $bid,
        string $bidderNum,
        string $createdOn,
        string $firstName,
        string $lastName,
        string $location,
        int $lotItemId,
        ?int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        ?float $maxBid,
        string $name,
        int $userId
    ): static {
        $this->auctionId = $auctionId;
        $this->bid = $bid;
        $this->bidderNum = $bidderNum;
        $this->createdOn = $createdOn;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->location = $location;
        $this->lotItemId = $lotItemId;
        $this->lotNum = $lotNum;
        $this->lotNumExt = $lotNumExt;
        $this->lotNumPrefix = $lotNumPrefix;
        $this->maxBid = $maxBid;
        $this->name = $name;
        $this->userId = $userId;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (int)$row['auction_id'],
            isset($row['bid']) ? (float)$row['bid'] : null,
            (string)$row['bidder_num'],
            (string)$row['created_on'],
            (string)$row['first_name'],
            (string)$row['last_name'],
            (string)$row['location'],
            (int)$row['lot_item_id'],
            Cast::toInt($row['lot_num'], Constants\Type::F_INT_POSITIVE),
            (string)$row['lot_num_ext'],
            (string)$row['lot_num_prefix'],
            Cast::toFloat($row['max_bid']),
            (string)$row['name'],
            (int)$row['user_id']
        );
    }
}
