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

namespace Sam\Email\Build\AbsenteeBid;


use AbsenteeBid;
use Auction;
use AuctionLotItem;
use InvalidArgumentException;
use LotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use UnexpectedValueException;
use User;

/**
 * Class DataPrepare
 * @package Sam\Email\Build\BidderInfo
 */
class DataProvider extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use UserLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use AccountAwareTrait;

    protected ?User $user = null;
    protected ?Auction $auction = null;
    protected ?LotItem $lotItem = null;
    protected ?AuctionLotItem $auctionLot = null;
    protected ?AbsenteeBid $absenteeBid = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Dto $dto
     */
    public function prepare(Dto $dto): void
    {
        $absenteeBid = $dto->getAbsenteeBid();
        $user = $this->getUserLoader()->load($absenteeBid->UserId, true);
        if (!$user) {
            throw new UnexpectedValueException(
                "Available user not found for Absentee Bid email"
                . composeSuffix(['u' => $absenteeBid->UserId, 'ab' => $absenteeBid->Id])
            );
        }

        $auctionLot = $this->getAuctionLotLoader()->load($absenteeBid->LotItemId, $absenteeBid->AuctionId, true);
        if (!$auctionLot) {
            throw new UnexpectedValueException(
                "Available auction Lot not found for Absentee Bid email"
                . composeSuffix(['li' => $absenteeBid->LotItemId, 'a' => $absenteeBid->AuctionId, 'ab' => $absenteeBid->Id])
            );
        }

        $auction = $this->getAuctionLoader()->load($absenteeBid->AuctionId, true);
        if (!$auction) {
            throw new UnexpectedValueException(
                "Available auction not found for Absentee Bid email"
                . composeSuffix(['a' => $absenteeBid->AuctionId, 'ab' => $absenteeBid->Id])
            );
        }

        $lotItem = $this->getLotItemLoader()->load($absenteeBid->LotItemId, true);
        if (!$lotItem) {
            log_error(
                "Available lot item not found for Absentee Bid email"
                . composeSuffix(['li' => $absenteeBid->LotItemId, 'ab' => $absenteeBid->Id])
            );
            throw new UnexpectedValueException('Available auction lot not found for  Absentee Bid email');
        }

        $this->setUser($user);
        $this->setAuctionLot($auctionLot);
        $this->setAuction($auction);
        $this->setLotItem($lotItem);
        $this->setAbsenteeBid($absenteeBid);
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
     * @return AbsenteeBid
     */
    public function getAbsenteeBid(): AbsenteeBid
    {
        return $this->absenteeBid;
    }

    /**
     * @param AbsenteeBid $absenteeBid
     * @return static
     */
    public function setAbsenteeBid(AbsenteeBid $absenteeBid): static
    {
        $this->absenteeBid = $absenteeBid;
        return $this;
    }
}
