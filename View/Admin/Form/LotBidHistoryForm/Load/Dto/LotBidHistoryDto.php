<?php
/**
 * SAM-6684: Merge the two admin bidding histories and Improvement for Lot bidding History
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/29/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotBidHistoryForm\Load\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotBidHistoryDto
 * @package Sam\View\Admin\Form\LotBidHistory\Load\Dto
 */
class LotBidHistoryDto extends CustomizableClass
{
    public readonly int $auctionId;
    public readonly int $bidTransactionId;
    public readonly bool $failed;
    public readonly string $createdOn;
    public readonly int $userId;
    public readonly bool $floorBidder;
    public readonly float $bid;
    public readonly bool $isBuyNow;
    public readonly float $maxBid;
    public readonly string $modifiedOn;
    public readonly int $modifiedBy;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int $bidTransactionId
     * @param bool $failed
     * @param string $createdOn
     * @param int $userId
     * @param bool $floorBidder
     * @param float $bid
     * @param bool $isBuyNow
     * @param float $maxBid
     * @param string $modifiedOn
     * @param int $modifiedBy
     * @return $this
     */
    public function construct(
        int $auctionId,
        int $bidTransactionId,
        bool $failed,
        string $createdOn,
        int $userId,
        bool $floorBidder,
        float $bid,
        bool $isBuyNow,
        float $maxBid,
        string $modifiedOn,
        int $modifiedBy
    ): static {
        $this->auctionId = $auctionId;
        $this->bidTransactionId = $bidTransactionId;
        $this->failed = $failed;
        $this->createdOn = $createdOn;
        $this->userId = $userId;
        $this->floorBidder = $floorBidder;
        $this->bid = $bid;
        $this->isBuyNow = $isBuyNow;
        $this->maxBid = $maxBid;
        $this->modifiedOn = $modifiedOn;
        $this->modifiedBy = $modifiedBy;
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
            (int)$row['id'],
            (bool)$row['failed'],
            (string)$row['created_on'],
            (int)$row['user_id'],
            (bool)$row['floor_bidder'],
            (float)$row['bid'],
            (bool)$row['is_buy_now'],
            (float)$row['max_bid'],
            (string)$row['modified_on'],
            (int)$row['modified_by']
        );
    }
}
