<?php
/**
 * SAM-5753: Refactor "Bid Book" report
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BidBookReportForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionLotSelectData
 * @package Sam\View\Admin\Form\BidBookReportForm\Load
 */
class AuctionLotSelectData extends CustomizableClass
{
    public readonly string $name;
    public readonly int $order;
    public readonly string $lotNum;
    public readonly string $lotNumExt;
    public readonly string $lotNumPrefix;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $name,
        int $order,
        string $lotNum,
        string $lotNumExt,
        string $lotNumPrefix
    ): static {
        $this->name = $name;
        $this->order = $order;
        $this->lotNum = $lotNum;
        $this->lotNumExt = $lotNumExt;
        $this->lotNumPrefix = $lotNumPrefix;
        return $this;
    }

    public function fromDbRow(array $row): static
    {
        return self::new()->construct(
            $row['name'],
            (int)$row['order'],
            $row['lot_num'],
            $row['lot_num_ext'],
            $row['lot_num_prefix']
        );
    }
}
