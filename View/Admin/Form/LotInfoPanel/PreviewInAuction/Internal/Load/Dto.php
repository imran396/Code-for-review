<?php
/**
 * SAM-6740: "Preview in auction" adjustments for lot item preview link
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\PreviewInAuction\Internal\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class PreviewInAuctionDto
 * @package Sam\View\Admin\Form\LotInfoPanel\PreviewInAuction\Internal\Load
 */
class Dto extends CustomizableClass
{
    public readonly int $auctionId;
    public readonly string $auctionName;
    public readonly int $lotStatusId;
    public readonly int $saleNum;
    public readonly string $saleNumExt;
    public readonly string $startClosingDate;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param string $auctionName
     * @param int $lotStatusId
     * @param int $saleNum
     * @param string $saleNumExt
     * @param string $startClosingDate
     * @return $this
     */
    public function construct(
        int $auctionId,
        string $auctionName,
        int $lotStatusId,
        int $saleNum,
        string $saleNumExt,
        string $startClosingDate
    ): static {
        $this->auctionId = $auctionId;
        $this->auctionName = $auctionName;
        $this->lotStatusId = $lotStatusId;
        $this->saleNum = $saleNum;
        $this->saleNumExt = $saleNumExt;
        $this->startClosingDate = $startClosingDate;
        return $this;
    }

    /**
     * @param array $rows
     * @return $this
     */
    public function fromDbRow(array $rows): static
    {
        return $this->construct(
            (int)$rows['auction_id'],
            (string)$rows['name'],
            (int)$rows['lot_status_id'],
            (int)$rows['sale_num'],
            (string)$rows['sale_num_ext'],
            (string)$rows['start_closing_date']
        );
    }
}
