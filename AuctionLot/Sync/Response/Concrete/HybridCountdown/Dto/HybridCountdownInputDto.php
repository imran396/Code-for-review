<?php
/**
 * DTO for hybrid countdown input
 *
 * SAM-6388: Active countdown on admin - auction - lots
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/21/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown\Dto;

use Sam\AuctionLot\Sync\Response\Concrete\AdminData\Dto\SyncAdminAuctionLotDto;
use Sam\AuctionLot\Sync\Response\Concrete\PublicData\Dto\SyncAuctionLotDto;
use Sam\Core\Service\CustomizableClass;


/**
 * Class HybridCountdownInputDto
 * @package
 */
class HybridCountdownInputDto extends CustomizableClass
{
    /**
     * @var int
     */
    public int $auctionId;
    /**
     * @var int
     */
    public int $auctionStatusId;
    /**
     * @var string
     */
    public string $auctionStartClosingDate;
    /**
     * @var int|null
     */
    public ?int $rtbCurrentLotId;
    /**
     * @var string
     */
    public string $rtbLotEndDate;
    /**
     * @var string
     */
    public string $rtbPauseDate;
    /**
     * @var int
     */
    public int $extendTime;
    /**
     * @var int
     */
    public int $lotStartGapTime;
    /**
     * @var string
     */
    public string $auctionTzLocation;
    /**
     * @var int
     */
    public int $orderNum;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param SyncAuctionLotDto $auctionLotDto
     * @return static
     */
    public function fromSyncAuctionLotDto(SyncAuctionLotDto $auctionLotDto): static
    {
        $this->auctionId = $auctionLotDto->auctionId;
        $this->auctionStartClosingDate = $auctionLotDto->auctionStartClosingDateIso;
        $this->auctionStatusId = $auctionLotDto->auctionStatus;
        $this->auctionTzLocation = $auctionLotDto->auctionTimezoneLocation;
        $this->extendTime = $auctionLotDto->extendTime;
        $this->lotStartGapTime = $auctionLotDto->lotStartGapTime;
        $this->orderNum = $auctionLotDto->auctionLotItemOrderNum;
        $this->rtbCurrentLotId = $auctionLotDto->rtbCurrentLotItemId ?: null;
        $this->rtbLotEndDate = $auctionLotDto->rtbLotEndDateIso;
        $this->rtbPauseDate = $auctionLotDto->rtbPauseDateIso;
        return $this;
    }

    /**
     * @param SyncAdminAuctionLotDto $auctionLotDto
     * @return static
     */
    public function fromSyncAdminAuctionLotDto(SyncAdminAuctionLotDto $auctionLotDto): static
    {
        $this->auctionId = $auctionLotDto->auctionId;
        $this->auctionStartClosingDate = $auctionLotDto->auctionStartClosingDateIso;
        $this->auctionStatusId = $auctionLotDto->auctionStatusId;
        $this->auctionTzLocation = $auctionLotDto->auctionTimezoneLocation;
        $this->extendTime = $auctionLotDto->extendTime;
        $this->lotStartGapTime = $auctionLotDto->lotStartGapTime;
        $this->orderNum = $auctionLotDto->orderNum;
        $this->rtbCurrentLotId = $auctionLotDto->rtbCurrentLotItemId ?: null;
        $this->rtbLotEndDate = $auctionLotDto->rtbLotEndDateIso;
        $this->rtbPauseDate = $auctionLotDto->rtbPauseDateIso;
        return $this;
    }
}
