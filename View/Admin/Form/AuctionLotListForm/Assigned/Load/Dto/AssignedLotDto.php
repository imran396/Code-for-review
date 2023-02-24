<?php
/**
 * SAM-6475: Apply DTO for assigned to auction lots and assign-ready lots used at Auction Lot List page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 05, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load\Dto;


use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\AuctionLotListForm\CreatorAndModifierAwareInterface;

/**
 * Class AssignedLotDto
 * @package Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load\Dto
 */
class AssignedLotDto extends CustomizableClass implements CreatorAndModifierAwareInterface
{
    public int $accountId;
    public int $bidId;
    public int $auctionLotId;
    public ?int $lotNum;
    public string $lotNumExt;
    public string $lotNumPrefix;
    public int $lotStatusId;
    public int $order;
    public int $groupId;
    public float $lotQuantity;
    public int $lotQuantityScale;
    public int $bidCount;
    public ?float $currentBid;
    public ?float $currentMaxBid;
    public string $currentBidPlacedIso;
    public string $lotSeoUrl;
    public int $viewCount;
    public int $invoiceId;
    public int $auctionIdWon;
    public int $consignorUserId;
    public ?float $hammerPrice;
    public ?float $highEst;
    public int $lotItemId;
    public bool $isInternetBid;
    public ?int $itemNum;
    public string $itemNumExt;
    public ?float $lowEst;
    public string $lotName;
    public float $reserve;
    public ?float $startBid;
    public int $winningBidderId;
    public string $consignor;
    public string $consignorCompany;
    public string $consignorEmail;
    public string $createdUsername;
    public string $companyName;
    public string $modifiedUsername;
    public string $winningBidder;
    public string $winningBidderCompany;
    public string $winningBidderEmail;
    public string $highBidderCompany;
    public string $highBidderEmail;
    public bool $isHighBidderHouse;
    public string $highBidderUsername;
    public int $highBidderUserId;
    public int $imageCount;
    public int $timezoneId;
    public string $lotStartDateIso;
    public string $lotEndDateIso;
    public int $rtbCurrentLotId;
    public ?string $rtbCurrentLotEndDate;
    public ?string $rtbCurrentPauseDate;
    public int $rtbCurrentLotStartGapTime;
    public int $rtbCurrentExtendTime;
    public array $customFields = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function fromDbRow(array $row, array $customFields = []): static
    {
        $dto = self::new();
        $dto->accountId = (int)$row['account_id'];
        $dto->bidId = (int)$row['bid_id'];
        $dto->auctionLotId = (int)$row['auc_lot_id'];
        $dto->lotNum = Cast::toInt($row['lot_num']);
        $dto->lotNumExt = (string)$row['lot_num_ext'];
        $dto->lotNumPrefix = (string)$row['lot_num_prefix'];
        $dto->lotStatusId = (int)$row['lot_status_id'];
        $dto->order = (int)$row['order_num'];
        $dto->groupId = (int)$row['group_id'];
        $dto->lotQuantity = (float)$row['lot_quantity'];
        $dto->lotQuantityScale = (int)$row['lot_quantity_scale'];
        $dto->bidCount = (int)$row['bid_count'];
        $dto->currentBid = Cast::toFloat($row['current_bid']);
        $dto->currentMaxBid = Cast::toFloat($row['current_max_bid']);
        $dto->currentBidPlacedIso = (string)$row['current_bid_placed'];
        $dto->lotSeoUrl = (string)$row['lot_seo_url'];
        $dto->viewCount = (int)$row['view_count'];
        $dto->invoiceId = (int)$row['inv_id'];
        $dto->auctionIdWon = (int)$row['auction_id_won'];
        $dto->consignorUserId = (int)$row['consignor_id'];
        $dto->hammerPrice = Cast::toFloat($row['hammer_price']);
        $dto->highEst = Cast::toFloat($row['high_est']);
        $dto->lotItemId = (int)$row['lot_id'];
        $dto->isInternetBid = (bool)$row['internet_bid'];
        $dto->itemNum = Cast::toInt($row['item_num']);
        $dto->itemNumExt = (string)$row['item_num_ext'];
        $dto->lowEst = Cast::toFloat($row['low_est']);
        $dto->lotName = (string)$row['lot_name'];
        $dto->reserve = (float)$row['reserve'];
        $dto->startBid = Cast::toFloat($row['start_bid']);
        $dto->winningBidderId = (int)$row['winning_bidder_id'];
        $dto->consignor = (string)$row['consignor'];
        $dto->consignorCompany = (string)$row['consignor_company'];
        $dto->consignorEmail = (string)$row['consignor_email'];
        $dto->createdUsername = (string)$row['created_username'];
        $dto->companyName = (string)$row['company_name'];
        $dto->modifiedUsername = (string)$row['modified_username'];
        $dto->winningBidder = (string)$row['winning_bidder'];
        $dto->winningBidderCompany = (string)$row['winning_bidder_company'];
        $dto->winningBidderEmail = (string)$row['winning_bidder_email'];
        $dto->highBidderCompany = (string)$row['high_bidder_company'];
        $dto->highBidderEmail = (string)$row['high_bidder_email'];
        $dto->isHighBidderHouse = (bool)$row['high_bidder_house'];
        $dto->highBidderUsername = (string)$row['high_bidder_username'];
        $dto->highBidderUserId = (int)$row['high_bidder_user_id'];
        $dto->imageCount = (int)$row['image_count'];
        $dto->timezoneId = (int)$row['timezone_id'];
        $dto->lotStartDateIso = (string)$row['lot_start_date'];
        $dto->lotEndDateIso = (string)$row['lot_end_date'];
        $dto->rtbCurrentLotId = (int)$row['rtb_current_lot_id'];
        $dto->rtbCurrentLotEndDate = $row['rtb_current_lot_end_date'];
        $dto->rtbCurrentPauseDate = $row['rtb_current_pause_date'];
        $dto->rtbCurrentLotStartGapTime = (int)$row['rtb_current_lot_start_gap_time'];
        $dto->rtbCurrentExtendTime = (int)$row['rtb_current_extend_time'];

        foreach ($customFields as $customField) {
            $dto->customFields[$customField] = $row[$customField] ?? null;
        }

        return $dto;
    }

    /**
     * @inheritDoc
     */
    public function getCreatorUsername(): string
    {
        return $this->createdUsername;
    }

    /**
     * @inheritDoc
     */
    public function getModifierUsername(): string
    {
        return $this->modifiedUsername;
    }
}
