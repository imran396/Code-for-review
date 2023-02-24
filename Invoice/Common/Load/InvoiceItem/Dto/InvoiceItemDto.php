<?php
/**
 * DTO for invoice item and related data.
 * Properties have correct type.
 * Few useful rendering methods.
 *
 * SAM-6004: Invoice data loading optimization
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

namespace Sam\Invoice\Common\Load\InvoiceItem\Dto;

use DateTime;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Render\LotRendererAwareTrait;

/**
 * Class InvoiceItemData
 * @package
 */
class InvoiceItemDto extends CustomizableClass
{
    use AuctionRendererAwareTrait;
    use LotRendererAwareTrait;

    public readonly ?int $itemNum;
    public readonly ?int $lotNum;
    public readonly ?int $saleNum;
    public readonly bool $isRelease;
    public readonly bool $isTestAuction;
    public readonly float $buyersPremium;
    public readonly float $hammerPrice;
    public readonly float $quantity;
    public readonly float $salesTaxPercent;
    public readonly int $accountId;
    public readonly int $auctionId;
    public readonly int $auctionStatusId;
    public readonly int $auctionTimezoneId;
    public readonly int $eventType;
    public readonly int $id;
    public readonly int $lotItemId;
    public readonly int $lotStatusId;
    public readonly int $quantityScale;
    public readonly int $taxApplication;
    public readonly string $auctionEndDateIso;
    public readonly string $auctionName;
    public readonly string $auctionStartClosingDateIso;
    public readonly string $auctionType;
    public readonly string $bidderNum;
    public readonly string $itemNumExt;
    public readonly string $lotName;
    public readonly string $lotNumExt;
    public readonly string $lotNumPrefix;
    public readonly string $saleDateIso;
    public readonly string $saleNumExt;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): InvoiceItemDto
    {
        $this->accountId = (int)$row['account_id'];
        $this->auctionEndDateIso = (string)$row['end_date'];
        $this->auctionId = (int)$row['auction_id'];
        $this->auctionName = (string)$row['name'];
        $this->auctionStartClosingDateIso = (string)$row['start_closing_date'];
        $this->auctionStatusId = (int)$row['auction_status_id'];
        $this->auctionTimezoneId = (int)$row['timezone_id'];
        $this->auctionType = (string)$row['auction_type'];
        $this->buyersPremium = (float)$row['buyers_premium'];
        $this->eventType = (int)$row['event_type'];
        $this->hammerPrice = (float)$row['hammer_price'];
        $this->id = (int)$row['id'];
        $this->isRelease = (bool)$row['release'];
        $this->isTestAuction = (bool)$row['test_auction'];
        $this->itemNum = Cast::toInt($row['item_num']);
        $this->itemNumExt = (string)$row['item_num_ext'];
        $this->lotItemId = (int)$row['lot_item_id'];
        $this->lotName = (string)$row['lot_name'];
        $this->lotNum = Cast::toInt($row['lot_num']);
        $this->lotNumExt = (string)$row['lot_num_ext'];
        $this->lotNumPrefix = (string)$row['lot_num_prefix'];
        $this->lotStatusId = (int)$row['lot_status_id'];
        $this->quantity = (float)$row['quantity'];
        $this->quantityScale = (int)$row['quantity_scale'];
        $this->saleDateIso = (string)$row['sale_date'];
        $this->saleNum = Cast::toInt($row['sale_num']);
        $this->saleNumExt = (string)$row['sale_num_ext'];
        $this->salesTaxPercent = (float)$row['sales_tax'];
        $this->taxApplication = (int)$row['tax_application'];
        return $this;
    }

    /**
     * @return string
     */
    public function makeItemNo(): string
    {
        return $this->getLotRenderer()->makeItemNo($this->itemNum, $this->itemNumExt);
    }

    /**
     * @return string
     */
    public function makeLotNo(): string
    {
        return $this->getLotRenderer()->makeLotNo($this->lotNum, $this->lotNumExt, $this->lotNumPrefix);
    }

    /**
     * @return string
     */
    public function makeSaleNo(): string
    {
        return $this->getAuctionRenderer()->makeSaleNo($this->saleNum, $this->saleNumExt);
    }

    /**
     * @return string
     */
    public function makeLotName(): string
    {
        return $this->getLotRenderer()->makeName($this->lotName, $this->isTestAuction);
    }

    /**
     * @param bool $isHtml
     * @return string
     */
    public function makeAuctionName(bool $isHtml = false): string
    {
        return $this->getAuctionRenderer()->makeName($this->auctionName, $this->isTestAuction, $isHtml);
    }

    /**
     * @return float
     */
    public function calcSubTotal(): float
    {
        return $this->hammerPrice + $this->buyersPremium;
    }

    /**
     * @return DateTime
     * @throws \Exception
     */
    public function detectAuctionDate(): DateTime
    {
        if ($this->saleDateIso) {
            $auctionDateIso = $this->saleDateIso;
        } else {
            $auctionDateIso = $this->auctionStartClosingDateIso;
        }
        return new DateTime($auctionDateIso);
    }
}
