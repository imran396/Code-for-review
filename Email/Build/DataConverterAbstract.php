<?php
/**
 * SAM-5018 : Refactor Email_Template to sub classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Apr 1, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Build;

use AbsenteeBid;
use Auction;
use AuctionBidder;
use AuctionLotItem;
use BidTransaction;
use Sam\Core\Service\CustomizableClass;
use InvalidArgumentException;
use Invoice;
use LotItem;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use User;

/**
 * This class converts mix $data variable to a specific datatype and retrieve additional data for each specific case.
 * Class DataConverter
 * @package Sam\Email\Build
 */
abstract class DataConverterAbstract extends CustomizableClass implements DataConverterInterface
{
    protected const EDITOR_USER_ID_MISSED = 'Input data must contain editor user id';

    protected ?User $user = null;
    protected ?Auction $auction = null;
    protected ?LotItem $lotItem = null;
    protected ?AuctionLotItem $auctionLot = null;
    protected ?Invoice $invoice = null;
    protected ?AuctionBidder $auctionBidder = null;
    protected ?BidTransaction $bidTransaction = null;
    protected ?AbsenteeBid $absenteeBid = null;
    protected ?float $initialOfferAmount = null;
    protected ?float $counterOfferAmount = null;
    protected ?float $offerAmount = null;
    protected ?float $bidAmount = null;
    protected ?int $editorUserId = null;
    protected ?int $viewLanguageId = null;

    /**
     * @return User
     */
    public function getUser(): User
    {
        if (!$this->user instanceof User) {
            throw new InvalidArgumentException('Not an instance of User given');
        }

        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return LotItem
     */
    public function getLotItem(): LotItem
    {
        if (!$this->lotItem instanceof LotItem) {
            throw new InvalidArgumentException('Not an instance of LotItem given');
        }
        return $this->lotItem;
    }

    /**
     * @param LotItem $lotItem
     */
    public function setLotItem(LotItem $lotItem): void
    {
        $this->lotItem = $lotItem;
    }

    /**
     * @return AuctionLotItem
     */
    public function getAuctionLot(): AuctionLotItem
    {
        if (!$this->auctionLot instanceof AuctionLotItem) {
            throw new InvalidArgumentException('Not an instance of AuctionLotItem given');
        }

        return $this->auctionLot;
    }

    /**
     * @param AuctionLotItem $auctionLot
     */
    public function setAuctionLot(AuctionLotItem $auctionLot): void
    {
        $this->auctionLot = $auctionLot;
    }

    /**
     * @return Invoice
     */
    public function getInvoice(): Invoice
    {
        if (!$this->invoice instanceof Invoice) {
            throw new InvalidArgumentException('Object must be instance of Invoice');
        }

        return $this->invoice;
    }

    /**
     * @return AuctionBidder
     */
    public function getAuctionBidder(): AuctionBidder
    {
        if (!$this->auctionBidder instanceof AuctionBidder) {
            throw new InvalidArgumentException('Object must be instance of AuctionBidder');
        }

        return $this->auctionBidder;
    }

    /**
     * @param AuctionBidder $auctionBidder
     */
    public function setAuctionBidder(AuctionBidder $auctionBidder): void
    {
        $this->auctionBidder = $auctionBidder;
    }

    /**
     * @param Invoice $invoice
     */
    public function setInvoice(Invoice $invoice): void
    {
        $this->invoice = $invoice;
    }

    /**
     * @return float
     */
    public function getInitialOfferAmount(): float
    {
        return $this->initialOfferAmount;
    }

    /**
     * @param float $initialOfferAmount
     */
    public function setInitialOfferAmount(float $initialOfferAmount): void
    {
        $this->initialOfferAmount = Cast::toFloat($initialOfferAmount, Constants\Type::F_FLOAT_POSITIVE);
    }

    /**
     * @return float
     */
    public function getCounterOfferAmount(): float
    {
        return $this->counterOfferAmount;
    }

    /**
     * @param float $counterOfferAmount
     */
    public function setCounterOfferAmount(float $counterOfferAmount): void
    {
        $this->counterOfferAmount = Cast::toFloat($counterOfferAmount, Constants\Type::F_FLOAT_POSITIVE);
    }

    /**
     * @return float
     */
    public function getOfferAmount(): float
    {
        return $this->offerAmount;
    }

    /**
     * @param float $offerAmount
     */
    public function setOfferAmount(float $offerAmount): void
    {
        $this->offerAmount = Cast::toFloat($offerAmount, Constants\Type::F_FLOAT_POSITIVE);
    }

    /**
     * @return BidTransaction
     */
    public function getBidTransaction(): BidTransaction
    {
        if (!$this->bidTransaction instanceof BidTransaction) {
            throw new InvalidArgumentException('Not an instance of BidTransaction given');
        }
        return $this->bidTransaction;
    }

    /**
     * @param BidTransaction $bidTransaction
     */
    public function setBidTransaction(BidTransaction $bidTransaction): void
    {
        $this->bidTransaction = $bidTransaction;
    }

    /**
     * @return float
     */
    public function getBidAmount(): float
    {
        return $this->bidAmount;
    }

    /**
     * @param float $bidAmount
     */
    public function setBidAmount(float $bidAmount): void
    {
        $this->bidAmount = Cast::toFloat($bidAmount, Constants\Type::F_FLOAT_POSITIVE);
    }

    /**
     * @return Auction
     */
    public function getAuction(): Auction
    {
        return $this->auction;
    }

    /**
     * @param Auction $auction
     */
    public function setAuction(Auction $auction): void
    {
        $this->auction = $auction;
    }

    /**
     * @return AbsenteeBid
     */
    public function getAbsenteeBid(): AbsenteeBid
    {
        if (!$this->absenteeBid instanceof AbsenteeBid) {
            throw new InvalidArgumentException('Object must be instance of AbsenteeBid');
        }

        return $this->absenteeBid;
    }

    /**
     * @param AbsenteeBid $absenteeBid
     */
    public function setAbsenteeBid(AbsenteeBid $absenteeBid): void
    {
        $this->absenteeBid = $absenteeBid;
    }

    /**
     * @return int
     */
    public function getEditorUserId(): int
    {
        return $this->editorUserId;
    }

    /**
     * @param int $editorUserId
     */
    public function setEditorUserId(int $editorUserId): void
    {
        $this->editorUserId = $editorUserId;
    }

    public function getViewLanguageId(): ?int
    {
        return $this->viewLanguageId;
    }

    public function setViewLanguageId(int $viewLanguageId): void
    {
        $this->viewLanguageId = $viewLanguageId;
    }
}
