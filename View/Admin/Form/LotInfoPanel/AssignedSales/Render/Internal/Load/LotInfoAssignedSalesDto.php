<?php
/**
 * SAM-6717: Refactor assigned sales label at Lot Item Edit page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\AssignedSales\Render\Internal\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotInfoAssignedSalesDto
 * @package Sam\View\Admin\Form\LotInfoPanel\AssignedSales\Render
 */
class LotInfoAssignedSalesDto extends CustomizableClass
{
    public readonly int $auctionId;
    public readonly int $saleNum;
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
     * @param int $auctionId
     * @param int $saleNum
     * @param string $saleNumExt
     * @return $this
     */
    public function construct(int $auctionId, int $saleNum, string $saleNumExt): static
    {
        $this->auctionId = $auctionId;
        $this->saleNum = $saleNum;
        $this->saleNumExt = $saleNumExt;
        return $this;
    }

    /**
     * Constructor based on data loaded from DB
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (int)$row['auction_id'],
            (int)$row['sale_num'],
            (string)$row['sale_num_ext']
        );
    }
}
