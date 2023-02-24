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
 * Class AuctionPhoneBiddersDto
 * @package Sam\View\Admin\Form\AuctionPhoneBidderForm\Load
 */
class AuctionPhoneBiddersDto extends CustomizableClass
{
    public readonly int $bidType;
    public readonly int $id;
    public readonly ?int $lotItemId;
    public readonly ?int $lotNum;
    public readonly string $lotNumExt;
    public readonly string $lotNumPrefix;
    public readonly ?int $userId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $bidType
     * @param int $id
     * @param int|null $lotItemId
     * @param int|null $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param int|null $userId
     * @return $this
     */
    public function construct(
        int $bidType,
        int $id,
        ?int $lotItemId,
        ?int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        ?int $userId
    ): static {
        $this->bidType = $bidType;
        $this->id = $id;
        $this->lotItemId = $lotItemId;
        $this->lotNum = $lotNum;
        $this->lotNumExt = $lotNumExt;
        $this->lotNumPrefix = $lotNumPrefix;
        $this->userId = $userId;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (int)$row['bid_type'],
            (int)$row['id'],
            Cast::toInt($row['lot_id'], Constants\Type::F_INT_POSITIVE),
            Cast::toInt($row['lot_num'], Constants\Type::F_INT_POSITIVE),
            (string)$row['lot_num_ext'],
            (string)$row['lot_num_prefix'],
            Cast::toInt($row['user_id'], Constants\Type::F_INT_POSITIVE)
        );
    }
}
