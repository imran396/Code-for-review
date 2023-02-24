<?php
/**
 * SAM-6005: Settlement data loading optimization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           4/19/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Load\Dto;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementItemDto
 * @package
 */
class SettlementItemDto extends CustomizableClass
{
    public readonly int $accountId;
    public readonly string $auctionEndDateIso;
    public readonly int $auctionId;
    public readonly string $auctionName;
    public readonly string $auctionStartClosingDateIso;
    public readonly int $auctionStatusId;
    public readonly int $auctionTimezoneId;
    public readonly string $auctionType;
    public readonly float $commission;
    public readonly int $eventType;
    public readonly float $fee;
    public readonly ?float $hammerPrice;
    public readonly float $highEstimate;
    public readonly int $id;
    public readonly bool $isTestAuction;
    public readonly ?int $lotItemNum;
    public readonly string $lotItemNumExt;
    public readonly int $lotItemId;
    public readonly string $lotName;
    public readonly ?int $lotNum;
    public readonly string $lotNumExt;
    public readonly string $lotNumPrefix;
    public readonly int $lotStatusId;
    public readonly float $lowEstimate;
    public readonly float $quantity;
    public readonly int $quantityScale;
    public readonly ?int $saleNum;
    public readonly string $saleNumExt;
    public readonly int $settlementId;
    public readonly float $subtotal;
    public readonly float $consignorTax;
    public readonly float $consignorTaxHp;
    public readonly int $consignorTaxHpType;
    public readonly float $consignorTaxComm;
    public readonly int $commissionLevel;
    public readonly string $commissionName;
    public readonly int $feeLevel;
    public readonly string $feeName;
    public readonly int $winningBidderId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function fromDbRow(array $row): static
    {
        $this->accountId = (int)$row['account_id'];
        $this->auctionEndDateIso = (string)$row['end_date'];
        $this->auctionId = (int)$row['auction_id'];
        $this->auctionName = (string)$row['name'];
        $this->auctionStartClosingDateIso = (string)$row['start_closing_date'];
        $this->auctionStatusId = (int)$row['auction_status_id'];
        $this->auctionTimezoneId = (int)$row['timezone_id'];
        $this->auctionType = (string)$row['auction_type'];
        $this->commission = (float)$row['commission'];
        $this->consignorTax = (float)$row['consignor_tax'];
        $this->consignorTaxComm = (float)$row['consignor_tax_comm'];
        $this->consignorTaxHp = (float)$row['consignor_tax_hp'];
        $this->consignorTaxHpType = (int)$row['consignor_tax_hp_type'];
        $this->eventType = (int)$row['event_type'];
        $this->fee = (float)$row['fee'];
        $this->hammerPrice = Cast::toFloat($row['hammer_price']);
        $this->highEstimate = (float)$row['high_estimate'];
        $this->id = (int)$row['id'];
        $this->isTestAuction = (bool)$row['test_auction'];
        $this->lotItemId = (int)$row['lot_item_id'];
        $this->lotItemNum = Cast::toInt($row['item_num']);
        $this->lotItemNumExt = (string)$row['item_num_ext'];
        $this->lotName = (string)$row['lot_name'];
        $this->lotNum = Cast::toInt($row['lot_num']);
        $this->lotNumExt = (string)$row['lot_num_ext'];
        $this->lotNumPrefix = (string)$row['lot_num_prefix'];
        $this->lotStatusId = (int)$row['lot_status_id'];
        $this->lowEstimate = (float)$row['low_estimate'];
        $this->quantity = (float)$row['quantity'];
        $this->quantityScale = (int)$row['quantity_scale'];
        $this->saleNum = Cast::toInt($row['sale_num']);
        $this->saleNumExt = (string)$row['sale_num_ext'];
        $this->settlementId = (int)$row['settlement_id'];
        $this->subtotal = (float)$row['subtotal'];
        $this->commissionLevel = (int)$row['commission_level'];
        $this->commissionName = (string)$row['commission_name'];
        $this->feeLevel = (int)$row['fee_level'];
        $this->feeName = (string)$row['fee_name'];
        $this->winningBidderId = (int)$row['winning_bidder_id'];
        return $this;
    }
}
