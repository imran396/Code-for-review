<?php
/**
 * SAM-6143: Simplify live and timed bid registration logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           6/01/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BidTransaction\Place\Base;

use BidTransaction;
use LogicException;
use Sam\Application\HttpReferrer\HttpReferrerParser;
use Sam\Bidding\BidTransaction\Place\BidDateAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\WriteRepository\Entity\BidTransaction\BidTransactionWriteRepositoryAwareTrait;

/**
 * Class AnyBidSaver
 */
class AnyBidSaver extends CustomizableClass
{
    use AuctionAwareTrait;
    use BidDateAwareTrait;
    use BidTransactionWriteRepositoryAwareTrait;
    use EntityFactoryCreateTrait;
    use LotItemAwareTrait;
    use UserAwareTrait;

    protected ?float $bidAmount = null;
    protected ?float $maxBidAmount = null;
    protected ?BidTransaction $outbidTransaction = null;
    protected ?BidTransaction $parentBidTransaction = null;
    protected int $bidStatus = Constants\BidTransaction::BS_WINNER;
    protected string $bidType = Constants\BidTransaction::TYPE_REGULAR;
    protected bool $isBidFailed = false;
    protected string $httpReferrer = '';
    protected bool $isFloorBidder = false;
    protected int $editorUserId;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return BidTransaction
     */
    public function create(): BidTransaction
    {
        /**
         * Check invariants
         */
        $lotItem = $this->getLotItem();
        if (!$lotItem) {
            throw new LogicException('LotItem should be defined to save bid transaction');
        }
        $auction = $this->getAuction();
        if (!$auction) {
            throw new LogicException('Auction should be defined to save bid transaction');
        }

        /**
         * Assign values and save
         */
        $bidTransaction = $this->createEntityFactory()->bidTransaction();
        $bidTransaction->AuctionId = $auction->Id;
        $bidTransaction->Bid = $this->bidAmount;
        $bidTransaction->BidStatus = $this->bidStatus;
        $bidTransaction->CreatedOn = $this->getBidDateUtcIso();
        $bidTransaction->Deleted = false;
        $bidTransaction->Failed = $this->isBidFailed;
        $bidTransaction->FloorBidder = $this->isFloorBidder;
        $bidTransaction->IsBuyNow = $this->bidType === Constants\BidTransaction::TYPE_BUY_NOW;
        $bidTransaction->LotItemId = $this->getLotItemId();
        $bidTransaction->MaxBid = $this->maxBidAmount;
        $bidTransaction->OutBidId = $this->outbidTransaction->Id ?? null;
        $bidTransaction->ParentBidId = $this->parentBidTransaction->Id ?? null;
        $bidTransaction->TimedOnlineBid = $auction->isTimed();
        $bidTransaction->UserId = $this->getUserId();
        [$bidTransaction->Referrer, $bidTransaction->ReferrerHost]
            = HttpReferrerParser::new()->parse($this->httpReferrer);

        $this->getBidTransactionWriteRepository()->saveWithModifier($bidTransaction, $this->editorUserId);

        return $bidTransaction;
    }

    /**
     * @param float|null $bidAmount
     * @return $this
     */
    public function setBidAmount(?float $bidAmount): static
    {
        $this->bidAmount = $bidAmount;
        return $this;
    }

    /**
     * @param float|null $maxBidAmount
     * @return $this
     */
    public function setMaxBidAmount(?float $maxBidAmount): static
    {
        $this->maxBidAmount = $maxBidAmount;
        return $this;
    }

    /**
     * @param BidTransaction|null $outbidTransaction null means absent outbid bid transaction
     * @return $this
     */
    public function setOutbidTransaction(?BidTransaction $outbidTransaction): static
    {
        $this->outbidTransaction = $outbidTransaction;
        return $this;
    }

    /**
     * @param BidTransaction|null $parentBidTransaction null means absent parent bid transaction
     * @return $this
     */
    public function setParentBidTransaction(?BidTransaction $parentBidTransaction): static
    {
        $this->parentBidTransaction = $parentBidTransaction;
        return $this;
    }

    /**
     * @param int $bidStatus
     * @return $this
     */
    public function setBidStatus(int $bidStatus): static
    {
        $this->bidStatus = $bidStatus;
        return $this;
    }

    /**
     * @param string $bidType
     * @return $this
     */
    public function setBidType(string $bidType): static
    {
        $this->bidType = $bidType;
        return $this;
    }

    /**
     * @param bool $isBidFailed
     * @return $this
     */
    public function enableBidFailed(bool $isBidFailed): static
    {
        $this->isBidFailed = $isBidFailed;
        return $this;
    }

    /**
     * @param string $httpReferrer
     * @return $this
     */
    public function setHttpReferrer(string $httpReferrer): static
    {
        $this->httpReferrer = $httpReferrer;
        return $this;
    }

    /**
     * @param bool $isFloorBidder
     * @return $this
     */
    public function enableFloorBidder(bool $isFloorBidder): static
    {
        $this->isFloorBidder = $isFloorBidder;
        return $this;
    }

    /**
     * @param int $editorUserId
     * @return static
     */
    public function setEditorUserId(int $editorUserId): static
    {
        $this->editorUserId = $editorUserId;
        return $this;
    }
}
