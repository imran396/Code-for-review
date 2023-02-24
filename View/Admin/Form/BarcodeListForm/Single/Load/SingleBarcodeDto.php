<?php
/**
 * SAM-8836: Apply DTOs for Barcode List page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BarcodeListForm\Single\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SingleBarcodeDto
 * @package Sam\View\Admin\Form\BarcodeListForm\Single\Load
 */
class SingleBarcodeDto extends CustomizableClass
{
    public readonly string $auctionName;
    public readonly int $lotItemId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $auctionName
     * @param int $lotItemId
     * @return $this
     */
    public function construct(
        string $auctionName,
        int $lotItemId
    ): static {
        $this->auctionName = $auctionName;
        $this->lotItemId = $lotItemId;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (string)$row['auction_name'],
            (int)$row['lot_item_id']
        );
    }
}
