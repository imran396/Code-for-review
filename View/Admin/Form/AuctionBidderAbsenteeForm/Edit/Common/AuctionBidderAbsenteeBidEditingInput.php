<?php
/**
 * SAM-9530: "User Absentee Bid" page - extract logic and cover with unit test for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Common;

use Sam\Bidding\BidTransaction\Place\BidDateAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;

/**
 * Class AuctionBidderAbsenteeBidInput
 * @package Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Common
 */
class AuctionBidderAbsenteeBidEditingInput extends CustomizableClass
{
    use BidDateAwareTrait;
    use CurrentDateTrait;

    /**
     * null when adding a new bid
     */
    public ?int $absenteeBidId;
    public ?int $auctionId;
    public ?int $userId;
    public int $bidType;
    public string $maxBid;
    public ?int $editorUserId;
    public bool $isNotify;
    public ?string $lotFullNum;
    public ?string $lotNum;
    public ?string $lotNumPrefix;
    public ?string $lotNumExt;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $absenteeBidId
     * @param int|null $auctionId
     * @param int|null $userId
     * @param int $bidType
     * @param string $maxBid
     * @param int|null $editorUserId
     * @param bool $isNotify
     * @param string|null $lotFullNum
     * @param string|null $lotNum
     * @param string|null $lotNumPrefix
     * @param string|null $lotNumExt
     * @return static
     */
    public function construct(
        ?int $absenteeBidId,
        ?int $auctionId,
        ?int $userId,
        int $bidType,
        string $maxBid,
        ?int $editorUserId,
        bool $isNotify,
        ?string $lotFullNum,
        ?string $lotNum = null,
        ?string $lotNumPrefix = null,
        ?string $lotNumExt = null
    ): static {
        $this->absenteeBidId = $absenteeBidId;
        $this->auctionId = $auctionId;
        $this->userId = $userId;
        $this->bidType = $bidType;
        $this->maxBid = $maxBid;
        $this->editorUserId = $editorUserId;
        $this->isNotify = $isNotify;
        $this->lotFullNum = $lotFullNum;
        $this->lotNum = $lotNum;
        $this->lotNumPrefix = $lotNumPrefix;
        $this->lotNumExt = $lotNumExt;
        return $this;
    }

    /**
     * @param string $lotNum
     * @return static
     */
    public function setLotNum(string $lotNum): static
    {
        $this->lotNum = $lotNum;
        return $this;
    }

    /**
     * @param string $lotNumExt
     * @return static
     */
    public function setLotNumExt(string $lotNumExt): static
    {
        $this->lotNumExt = $lotNumExt;
        return $this;
    }

    /**
     * @param string $lotNumPrefix
     * @return static
     */
    public function setLotNumPrefix(string $lotNumPrefix): static
    {
        $this->lotNumPrefix = $lotNumPrefix;
        return $this;
    }
}
