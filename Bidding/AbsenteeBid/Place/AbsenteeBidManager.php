<?php
/**
 * Places/updates absentee bid.
 * We have one absentee bid record per user and auction lot.
 *
 * SAM-4152: Absentee bid manager and validator
 *
 * @author        Igors Kotlevskis
 * @since         Mar 19, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidding\AbsenteeBid\Place;

use AbsenteeBid;
use DateTime;
use InvalidArgumentException;
use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Application\HttpReferrer\HttpReferrerParserAwareTrait;
use Sam\Bidding\AbsenteeBid\Detect\HighAbsenteeBidDetectorCreateTrait;
use Sam\Bidding\AbsenteeBid\Load\AbsenteeBidLoaderAwareTrait;
use Sam\Bidding\AbsenteeBid\Notify\AbsenteeBidNotifierCreateTrait;
use Sam\Bidding\BidTransaction\Place\BidDateAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\WriteRepository\Entity\AbsenteeBid\AbsenteeBidWriteRepositoryAwareTrait;
use Sam\User\Watchlist\WatchlistManagerAwareTrait;

/**
 * Class AbsenteeBidManager
 * @package Sam\Bidding\AbsenteeBid\Place
 */
class AbsenteeBidManager extends CustomizableClass
{
    use AbsenteeBidLoaderAwareTrait;
    use AbsenteeBidNotifierCreateTrait;
    use AbsenteeBidWriteRepositoryAwareTrait;
    use BidDateAwareTrait;
    use CookieHelperCreateTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use HighAbsenteeBidDetectorCreateTrait;
    use HttpReferrerParserAwareTrait;
    use WatchlistManagerAwareTrait;

    protected ?AbsenteeBid $absenteeBid = null;
    protected ?AbsenteeBid $previousHighAbsentee = null;

    protected ?int $auctionId = null;
    protected ?int $lotItemId = null;
    protected ?int $userId = null;
    protected ?int $editorUserId = null;
    protected ?int $orNum = null;
    protected ?float $maxBid = null;
    protected ?DateTime $createdOn = null;
    protected ?DateTime $placedOn = null;
    protected ?int $bidType = null;
    protected ?string $httpReferrer = null;
    protected ?bool $isNew = null;
    /**
     * Auto-add lot to user's watchlist
     */
    protected bool $shouldAddToWatchlist = true;
    /**
     * Notify related high/outbid bidders, consignor
     */
    protected bool $shouldNotifyUsers = true;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Places new or updates existed absentee bid.
     * We may also add to watchlist and notify users by email/sms here.
     * @return AbsenteeBid
     */
    public function place(): AbsenteeBid
    {
        // Initially find high absentee before bid
        $this->previousHighAbsentee = $this->createHighAbsenteeBidDetector()
            ->detectFirstHigh($this->getLotItemId(), $this->getAuctionId());
        // Register/update absentee bid
        $absenteeBid = $this->saveBid();
        // Add to watchlist of bidder
        if ($this->shouldAddToWatchlist) {
            $this->getWatchlistManager()->autoAdd(
                $this->getUserId(),
                $this->getLotItemId(),
                $this->getAuctionId(),
                $this->getEditorUserId()
            );
        }
        // Send email/sms notifications to users
        if ($this->shouldNotifyUsers) {
            $notifier = $this->createAbsenteeBidNotifier()->construct(
                $absenteeBid,
                $this->getPreviousHighAbsentee(),
                $this->getEditorUserId()
            );
            $notifier->notify();
        }
        return $absenteeBid;
    }

    /**
     * Wrapper for place()
     * @param int $userId
     * @param int $lotItemId
     * @param int $auctionId
     * @param float $maxBid
     * @param int $editorUserId
     * @param int|null $orNum
     * @param int|null $bidType
     * @param string $httpReferrer
     * @param DateTime|null $bidDateUtc
     * @param DateTime|null $createdOn
     * @param bool $shouldNotifyUsers
     * @param bool $shouldAddToWatchlist
     * @return AbsenteeBid
     * @noinspection PhpUnused
     */
    public function placeBid(
        int $userId,
        int $lotItemId,
        int $auctionId,
        float $maxBid,
        int $editorUserId,
        ?int $orNum = null,
        ?int $bidType = null,
        string $httpReferrer = '',
        ?DateTime $bidDateUtc = null,
        ?DateTime $createdOn = null,
        bool $shouldNotifyUsers = false,
        bool $shouldAddToWatchlist = false
    ): AbsenteeBid {
        $this
            ->enableAddToWatchlist($shouldAddToWatchlist)
            ->enableNotifyUsers($shouldNotifyUsers)
            ->setAuctionId($auctionId)
            ->setBidDateUtc($bidDateUtc)
            ->setBidType($bidType)
            ->setCreatedOn($createdOn)
            ->setEditorUserId($editorUserId)
            ->setHttpReferrer($httpReferrer)
            ->setLotItemId($lotItemId)
            ->setMaxBid($maxBid)
            ->setOrNum($orNum)
            ->setUserId($userId);
        $absenteeBid = $this->place();
        return $absenteeBid;
    }

    /**
     * Check if new bid was placed from this user to this lot, not updated previous bid amount
     * @return bool
     */
    public function isNew(): bool
    {
        if ($this->isNew === null) {
            $this->isNew = !$this->getAbsenteeBid()
                || !$this->getAbsenteeBid()->__Restored;
        }
        return $this->isNew;
    }

    /**
     * we don't check there, if bid belongs to the same user or another of previously highest bid
     * @return bool
     */
    public function isPlacedBelowCurrent(): bool
    {
        $isPlacedBelowCurrent = $this->previousHighAbsentee
            && Floating::lteq($this->getMaxBid(), $this->previousHighAbsentee->MaxBid);
        return $isPlacedBelowCurrent;
    }

    /**
     * @return AbsenteeBid
     */
    protected function saveBid(): AbsenteeBid
    {
        $absenteeBid = $this->getAbsenteeBid();
        if ($this->isNew()) {
            $absenteeBid = $this->createEntityFactory()->absenteeBid();
            $absenteeBid->AuctionId = $this->getAuctionId();
            $absenteeBid->BidType = Constants\Bid::ABT_REGULAR;
            $absenteeBid->CreatedOn = $this->getCreatedOn()
                ? $this->getCreatedOn()->format(Constants\Date::ISO)
                : null;
            $absenteeBid->LotItemId = $this->getLotItemId();
            $absenteeBid->UserId = $this->getUserId();
        }
        if ($this->getBidType()) {
            $absenteeBid->BidType = $this->getBidType();
        }
        if ($this->getMaxBid() !== null) {
            $absenteeBid->MaxBid = $this->getMaxBid();
        }
        if ($this->getOrNum()) {
            $absenteeBid->OrId = $this->getOrNum();
        }
        $absenteeBid->PlacedOn = $this->getPlacedOn();
        $absenteeBid = $this->assignReferrerInfo($absenteeBid);
        $this->getAbsenteeBidWriteRepository()->saveWithModifier($absenteeBid, $this->getEditorUserId());
        $logData = [
            'u' => $this->getUserId(),
            'li' => $this->getLotItemId(),
            'a' => $this->getAuctionId(),
            'max bid' => $this->getMaxBid(),
            'placed on' => $absenteeBid->PlacedOn->format(Constants\Date::ISO)
        ];
        log_debug("Absentee bid " . ($this->isNew() ? "placed" : "updated") . composeSuffix($logData));
        return $absenteeBid;
    }

    /**
     * @param AbsenteeBid $absenteeBid
     * @return AbsenteeBid
     */
    protected function assignReferrerInfo(AbsenteeBid $absenteeBid): AbsenteeBid
    {
        [$referrer, $referrerHost] = $this->getHttpReferrerParser()->parse($this->getHttpReferrer());
        if ($referrer) {
            $absenteeBid->Referrer = $referrer;
        }
        if ($referrerHost) {
            $absenteeBid->ReferrerHost = $referrerHost;
        }
        return $absenteeBid;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableAddToWatchlist(bool $enable): static
    {
        $this->shouldAddToWatchlist = $enable;
        return $this;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableNotifyUsers(bool $enable): static
    {
        $this->shouldNotifyUsers = $enable;
        return $this;
    }

    /**
     * @return AbsenteeBid|null
     */
    public function getAbsenteeBid(): ?AbsenteeBid
    {
        if ($this->absenteeBid === null) {
            $this->absenteeBid = $this->getAbsenteeBidLoader()->load(
                $this->getLotItemId(),
                $this->getAuctionId(),
                $this->getUserId()
            );
        }
        return $this->absenteeBid;
    }

    /**
     * @param AbsenteeBid|null $absenteeBid
     * @return static
     */
    public function setAbsenteeBid(?AbsenteeBid $absenteeBid): static
    {
        $this->absenteeBid = $absenteeBid;
        return $this;
    }

    /**
     * @return int
     */
    public function getAuctionId(): int
    {
        if ($this->auctionId === null) {
            if ($this->absenteeBid) {
                $this->auctionId = $this->absenteeBid->AuctionId;
            }
        }
        if ($this->auctionId === null) {
            throw new InvalidArgumentException("AuctionId not defined");
        }
        return $this->auctionId;
    }

    /**
     * @param int $auctionId
     * @return static
     */
    public function setAuctionId(int $auctionId): static
    {
        $this->auctionId = $auctionId;
        return $this;
    }

    /**
     * @return int
     */
    public function getLotItemId(): int
    {
        if ($this->lotItemId === null) {
            if ($this->absenteeBid) {
                $this->lotItemId = $this->absenteeBid->LotItemId;
            }
        }
        if ($this->lotItemId === null) {
            throw new InvalidArgumentException("LotItemId not defined");
        }
        return $this->lotItemId;
    }

    /**
     * @param int $lotItemId
     * @return static
     */
    public function setLotItemId(int $lotItemId): static
    {
        $this->lotItemId = $lotItemId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        if ($this->userId === null) {
            if ($this->absenteeBid) {
                $this->userId = $this->absenteeBid->UserId;
            }
        }
        if ($this->userId === null) {
            $this->userId = $this->getEditorUserId();
        }
        return $this->userId;
    }

    /**
     * @param int|null $userId
     * @return static
     */
    public function setUserId(?int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOrNum(): ?int
    {
        return $this->orNum;
    }

    /**
     * @param int|null $orNum
     * @return static
     */
    public function setOrNum(?int $orNum): static
    {
        $this->orNum = $orNum;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getMaxBid(): ?float
    {
        return $this->maxBid;
    }

    /**
     * @param float $maxBid
     * @return static
     */
    public function setMaxBid(float $maxBid): static
    {
        $this->maxBid = $maxBid;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getBidType(): ?int
    {
        return $this->bidType;
    }

    /**
     * @param int|null $bidType
     * @return static
     */
    public function setBidType(?int $bidType): static
    {
        $this->bidType = $bidType;
        return $this;
    }

    /**
     * @return string
     */
    public function getHttpReferrer(): string
    {
        if ($this->httpReferrer === null) {
            $this->httpReferrer = $this->createCookieHelper()->getHttpReferer();
        }
        return $this->httpReferrer;
    }

    /**
     * @param string $httpReferrer
     * @return static
     */
    public function setHttpReferrer(string $httpReferrer): static
    {
        $this->httpReferrer = $httpReferrer;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getEditorUserId(): ?int
    {
        return $this->editorUserId;
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

    /**
     * @return DateTime|null
     */
    public function getCreatedOn(): ?DateTime
    {
        if ($this->createdOn === null) {
            $this->createdOn = $this->getBidDateUtc();
        }
        return $this->createdOn;
    }

    /**
     * @param DateTime|null $createdOn
     * @return static
     */
    public function setCreatedOn(?DateTime $createdOn): static
    {
        $this->createdOn = $createdOn;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getPlacedOn(): ?DateTime
    {
        if ($this->placedOn === null) {
            $this->placedOn = $this->getBidDateUtc();
        }
        return $this->placedOn;
    }

    /**
     * @param DateTime|null $placedOn
     * @return static
     */
    public function setPlacedOn(?DateTime $placedOn): static
    {
        $this->placedOn = $placedOn;
        return $this;
    }

    /**
     * @return AbsenteeBid|null
     */
    public function getPreviousHighAbsentee(): ?AbsenteeBid
    {
        return $this->previousHighAbsentee;
    }

    /**
     * @param AbsenteeBid|null $previousHighAbsentee
     * @return static
     * @noinspection PhpUnused
     */
    public function setPreviousHighAbsentee(?AbsenteeBid $previousHighAbsentee): static
    {
        $this->previousHighAbsentee = $previousHighAbsentee;
        return $this;
    }
}
