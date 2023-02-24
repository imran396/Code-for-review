<?php
/**
 * SAM-10615: Supply uniqueness of auction fields: sale#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Lock\SaleNo\Internal\Detect;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionUniqueSaleNoLockRequirementCheckerInput
 * @package Sam\EntityMaker\Auction\Lock\SaleNo\Internal\Detect
 */
class AuctionUniqueSaleNoLockRequirementCheckerInput extends CustomizableClass
{
    public readonly ?int $auctionId;
    public readonly ?string $saleNum;
    public readonly ?string $saleNumExt;
    public readonly ?string $saleFullNo;
    public readonly bool $isSetSaleNum;
    public readonly bool $isSetSaleNumExt;
    public readonly bool $isSetSaleFullNo;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        ?int $auctionId,
        ?string $saleNum,
        ?string $saleNumExt,
        ?string $saleFullNo,
        bool $isSetSaleNum,
        bool $isSetSaleNumExt,
        bool $isSetSaleFullNo
    ): static {
        $this->auctionId = $auctionId;
        $this->saleNum = $saleNum;
        $this->saleNumExt = $saleNumExt;
        $this->saleFullNo = $saleFullNo;
        $this->isSetSaleNum = $isSetSaleNum;
        $this->isSetSaleNumExt = $isSetSaleNumExt;
        $this->isSetSaleFullNo = $isSetSaleFullNo;
        return $this;
    }
}
