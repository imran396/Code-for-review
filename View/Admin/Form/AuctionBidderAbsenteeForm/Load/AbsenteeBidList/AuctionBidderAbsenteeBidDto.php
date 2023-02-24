<?php
/**
 * SAM-9530: "User Absentee Bid" page - extract logic and cover with unit test for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Load\AbsenteeBidList;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionBidderAbsenteeBidDto
 * @package Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Load\AbsenteeBidList
 */
class AuctionBidderAbsenteeBidDto extends CustomizableClass
{
    public ?int $id;
    public ?int $auctionId;
    public ?int $lotItemId;
    public ?float $maxBid;
    public ?string $placedOn;
    public ?int $bidType;
    public ?int $accountId;
    public ?int $lotNum;
    public string $lotNumPrefix = '';
    public string $lotNumExt = '';
    public ?string $lotSeoUrl;
    public string $name = '';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $id
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @param float|null $maxBid
     * @param string|null $placedOn
     * @param int|null $bidType
     * @param int|null $accountId
     * @param int|null $lotNum
     * @param string $lotNumPrefix
     * @param string $lotNumExt
     * @param string|null $lotSeoUrl
     * @param string $name
     * @return $this
     */
    public function construct(
        ?int $id,
        ?int $auctionId,
        ?int $lotItemId,
        ?float $maxBid,
        ?string $placedOn,
        ?int $bidType,
        ?int $accountId,
        ?int $lotNum,
        string $lotNumPrefix,
        string $lotNumExt,
        ?string $lotSeoUrl,
        string $name
    ): static {
        $this->id = $id;
        $this->auctionId = $auctionId;
        $this->lotItemId = $lotItemId;
        $this->maxBid = $maxBid;
        $this->placedOn = $placedOn;
        $this->bidType = $bidType;
        $this->accountId = $accountId;
        $this->lotNum = $lotNum;
        $this->lotNumPrefix = $lotNumPrefix;
        $this->lotNumExt = $lotNumExt;
        $this->lotSeoUrl = $lotSeoUrl;
        $this->name = $name;
        return $this;
    }

    /**
     * @param array $row
     * @return static
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            Cast::toInt($row['id'], Constants\Type::F_INT_POSITIVE),
            Cast::toInt($row['auction_id'], Constants\Type::F_INT_POSITIVE),
            Cast::toInt($row['lot_item_id'], Constants\Type::F_INT_POSITIVE),
            Cast::toFloat($row['max_bid']),
            Cast::toString($row['placed_on']),
            Cast::toInt($row['bid_type'], Constants\Type::F_INT_POSITIVE),
            Cast::toInt($row['account_id'], Constants\Type::F_INT_POSITIVE),
            Cast::toInt($row['lot_num'], Constants\Type::F_INT_POSITIVE),
            (string)$row['lot_num_prefix'],
            (string)$row['lot_num_ext'],
            Cast::toString($row['lot_seo_url']),
            (string)$row['name']
        );
    }
}
