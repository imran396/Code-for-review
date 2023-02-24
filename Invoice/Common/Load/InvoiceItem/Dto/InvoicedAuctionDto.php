<?php
/**
 * SAM-6408: Invoice auction data loading optimization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 26, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Load\InvoiceItem\Dto;


use DateTime;
use Exception;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;

/**
 * Data class that contains all the necessary information about the auction in the invoice.
 * Used in invoice views.
 *
 * Class InvoicedAuctionDto
 * @package Sam\Invoice\Common\Load\InvoiceItem\Dto
 */
class InvoicedAuctionDto extends CustomizableClass
{
    use AuctionRendererAwareTrait;

    public ?int $accountId = null;
    public ?int $auctionEventType = null;
    public ?int $auctionId = null;
    public ?int $auctionTimezoneId = null;
    public ?int $locationId = null;
    public ?int $saleNum = null;
    public bool $isTestAuction = false;
    public string $auctionEndDateIso = '';
    public string $auctionInfoLink = '';
    public string $auctionName = '';
    public string $auctionSeoUrl = '';
    public string $auctionStartClosingDateIso = '';
    public string $auctionType = '';
    public string $saleDateIso = '';
    public string $saleNumExt = '';

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
     * @return static
     */
    public function fromDbRow(array $row): static
    {
        $this->accountId = (int)$row['account_id'];
        $this->auctionEndDateIso = (string)$row['end_date'];
        $this->auctionEventType = (int)$row['event_type'];
        $this->auctionId = (int)$row['id'];
        $this->auctionInfoLink = (string)$row['auction_info_link'];
        $this->auctionName = (string)$row['name'];
        $this->auctionSeoUrl = (string)$row['auction_seo_url'];
        $this->auctionStartClosingDateIso = (string)$row['start_closing_date'];
        $this->auctionTimezoneId = (int)$row['timezone_id'];
        $this->auctionType = (string)$row['auction_type'];
        $this->isTestAuction = (bool)$row['test_auction'];
        $this->locationId = (int)$row['invoice_location_id'];
        $this->saleDateIso = (string)$row['sale_date'];
        $this->saleNum = (int)$row['sale_num'];
        $this->saleNumExt = (string)$row['sale_num_ext'];
        return $this;
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
     * @return string
     */
    public function makeSaleNo(): string
    {
        return $this->getAuctionRenderer()->makeSaleNo($this->saleNum, $this->saleNumExt);
    }

    /**
     * @return DateTime|null
     * @throws Exception
     */
    public function detectSaleDate(): ?DateTime
    {
        if ($this->saleDateIso) {
            $auctionDateIso = $this->saleDateIso;
        } else {
            $auctionStatusPureChecker = AuctionStatusPureChecker::new();
            $auctionDateIso = $auctionStatusPureChecker->isTimed($this->auctionType)
                ? $this->auctionEndDateIso
                : $this->auctionStartClosingDateIso;
        }
        return $auctionDateIso ? new DateTime($auctionDateIso) : null;
    }
}
