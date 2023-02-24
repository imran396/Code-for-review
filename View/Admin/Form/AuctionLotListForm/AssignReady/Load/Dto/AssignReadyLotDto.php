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

namespace Sam\View\Admin\Form\AuctionLotListForm\AssignReady\Load\Dto;


use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\AuctionLotListForm\CreatorAndModifierAwareInterface;

/**
 * Class AssignReadyLotDto
 * @package Sam\View\Admin\Form\AuctionLotListForm\AssignReady\Load\Dto
 */
class AssignReadyLotDto extends CustomizableClass implements CreatorAndModifierAwareInterface
{
    public int $lotItemId;
    public ?int $itemNum;
    public string $itemNumExt;
    public string $lotName;
    public int $accountId;
    public int $auctionId;
    public ?float $lowEstimate;
    public ?float $highEstimate;
    public ?float $startBid;
    public ?float $hammerPrice;
    public string $auctionInfo;
    public string $consignor;
    public int $consignorUserId;
    public string $winningBidder;
    public string $createdOnIso;
    public string $currencySign;
    public string $companyName;
    public string $createdUsername;
    public string $modifiedUsername;
    public array $customFields = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $row
     * @param array $customFields
     * @return static
     */
    public function fromDbRow(array $row, array $customFields = []): static
    {
        $dto = self::new();
        $dto->accountId = (int)$row['account_id'];
        $dto->auctionId = (int)$row['auc_id'];
        $dto->auctionInfo = (string)$row['auction_info'];
        $dto->companyName = (string)$row['company_name'];
        $dto->consignor = (string)$row['consignor'];
        $dto->consignorUserId = (int)$row['consignor_id'];
        $dto->createdOnIso = (string)$row['created_on'];
        $dto->createdUsername = (string)$row['created_username'];
        $dto->currencySign = (string)$row['curr_sign'];
        $dto->hammerPrice = Cast::toFloat($row['hammer_price']);
        $dto->highEstimate = Cast::toFloat($row['high_est']);
        $dto->itemNum = Cast::toInt($row['item_num']);
        $dto->itemNumExt = (string)$row['item_num_ext'];
        $dto->lotItemId = (int)$row['lot_id'];
        $dto->lotName = (string)$row['lot_name'];
        $dto->lowEstimate = Cast::toFloat($row['low_est']);
        $dto->modifiedUsername = (string)$row['modified_username'];
        $dto->startBid = Cast::toFloat($row['start_bid']);
        $dto->winningBidder = (string)$row['winning_bidder'];
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
