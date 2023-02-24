<?php
/**
 * SAM-8724: Projector console - Extract image response building logic to separate service
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 23, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Projector\Image\Internal\Load;


use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class Dto
 * @package Sam\Rtb\Projector\Image\Internal\Load
 */
class Dto extends CustomizableClass
{
    public readonly ?int $imagesDefaultId;
    public readonly array $imageIds;
    public readonly int $lotItemAccountId;
    public readonly int $lotItemId;
    public readonly string $lotItemName;
    public readonly int $lotNum;
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
     * @param int|null $imagesDefaultId
     * @param array $imageIds
     * @param int $lotItemAccountId
     * @param int $lotItemId
     * @param string $lotItemName
     * @param int $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @return $this
     */
    public function construct(
        ?int $imagesDefaultId,
        array $imageIds,
        int $lotItemAccountId,
        int $lotItemId,
        string $lotItemName,
        int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix
    ): static {
        $this->imagesDefaultId = $imagesDefaultId;
        $this->imageIds = $imageIds;
        $this->lotItemAccountId = $lotItemAccountId;
        $this->lotItemId = $lotItemId;
        $this->lotItemName = $lotItemName;
        $this->lotNum = $lotNum;
        $this->lotNumExt = $lotNumExt;
        $this->lotNumPrefix = $lotNumPrefix;
        return $this;
    }

    /**
     * @param array $row
     * @return static
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            Cast::toInt($row['image_default_id']),
            $row['image_ids'],
            (int)$row['lot_item_account_id'],
            (int)$row['lot_item_id'],
            (string)$row['lot_item_name'],
            (int)$row['lot_num'],
            (string)$row['lot_num_ext'],
            (string)$row['lot_num_prefix'],
        );
    }
}
