<?php
/**
 * SAM-8832: Apply DTOs for Auction Phone Bidder page at admin side
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

namespace Sam\View\Admin\Form\AuctionPhoneBidderForm\Load;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionPhoneBidderLastLotDto
 * @package Sam\View\Admin\Form\AuctionPhoneBidderForm\Load
 */
class AuctionPhoneBidderLastLotDto extends CustomizableClass
{
    public readonly ?int $lastAssigned;
    public readonly ?int $lotNum;
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

    /**
     * @param int|null $lastAssigned
     * @param int|null $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @return $this
     */
    public function construct(
        ?int $lastAssigned,
        ?int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix
    ): static {
        $this->lastAssigned = $lastAssigned;
        $this->lotNum = $lotNum;
        $this->lotNumExt = $lotNumExt;
        $this->lotNumPrefix = $lotNumPrefix;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            isset($row['last_assigned']) ? Cast::toInt($row['last_assigned'], Constants\Type::F_INT_POSITIVE) : null,
            isset($row['lot_num']) ? Cast::toInt($row['lot_num'], Constants\Type::F_INT_POSITIVE) : null,
            isset($row['lot_num_ext']) ? (string)$row['lot_num_ext'] : '',
            isset($row['lot_num_prefix']) ? (string)$row['lot_num_prefix'] : '',
        );
    }
}
