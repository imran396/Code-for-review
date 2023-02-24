<?php
/**
 * SAM-8207: Apply DTOs for loaded data at Auction List page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionListForm\Load;


use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionListDto
 * @package Sam\View\Admin\Form\AuctionListForm\Load
 */
class AuctionListDto extends CustomizableClass
{
    public readonly int $auctionStatusId;
    public readonly string $accountName;
    public readonly string $auctionType;
    public readonly int $createdBy;
    public readonly string $endDate;
    public readonly int $eventType;
    public readonly int $id;
    public readonly bool $isPublished;
    public readonly string $name;
    public readonly int $saleNum;
    public readonly string $saleNumExt;
    public readonly string $startClosingDate;
    public readonly string $timezoneLocation;
    public readonly int $totalLots;
    public readonly array $customFields;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionStatusId
     * @param string $accountName
     * @param string $auctionType
     * @param int $createdBy
     * @param string $endDate
     * @param int $eventType
     * @param int $id
     * @param bool $isPublished
     * @param string $name
     * @param int $saleNum
     * @param string $saleNumExt
     * @param string $startClosingDate
     * @param string $timezoneLocation
     * @param int $totalLots
     * @param array $customFields
     * @return $this
     */
    public function construct(
        int $auctionStatusId,
        string $accountName,
        string $auctionType,
        int $createdBy,
        string $endDate,
        int $eventType,
        int $id,
        bool $isPublished,
        string $name,
        int $saleNum,
        string $saleNumExt,
        string $startClosingDate,
        string $timezoneLocation,
        int $totalLots,
        array $customFields
    ): static {
        $this->auctionStatusId = $auctionStatusId;
        $this->accountName = $accountName;
        $this->auctionType = $auctionType;
        $this->createdBy = $createdBy;
        $this->endDate = $endDate;
        $this->eventType = $eventType;
        $this->id = $id;
        $this->isPublished = $isPublished;
        $this->name = $name;
        $this->saleNum = $saleNum;
        $this->saleNumExt = $saleNumExt;
        $this->startClosingDate = $startClosingDate;
        $this->timezoneLocation = $timezoneLocation;
        $this->totalLots = $totalLots;
        $this->customFields = $customFields;
        return $this;
    }

    /**
     * @param array $row
     * @param array $custFields
     * @return $this
     */
    public function fromDbRow(array $row, array $custFields): static
    {
        $customFields = [];
        foreach ($custFields as $customField) {
            $customFields[$customField] = $row[$customField] ?? null;
        }

        return $this->construct(
            (int)$row['auction_status_id'],
            (string)$row['account_name'],
            (string)$row['auction_type'],
            (int)$row['created_by'],
            (string)$row['end_date'],
            (int)$row['event_type'],
            (int)$row['id'],
            (bool)$row['is_published'],
            (string)$row['name'],
            (int)$row['sale_num'],
            (string)$row['sale_num_ext'],
            (string)$row['start_closing_date'],
            (string)$row['timezone_location'],
            (int)$row['total_lots'],
            $customFields
        );
    }
}
