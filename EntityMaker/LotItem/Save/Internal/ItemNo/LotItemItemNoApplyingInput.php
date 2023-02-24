<?php
/**
 * SAM-10599: Supply uniqueness of lot item fields: item# - Adjust item# auto-assignment with internal locking
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Save\Internal\ItemNo;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;

/**
 * Class LotItemItemNoApplyingInput
 * @package Sam\EntityMaker\LotItem\Save\Internal\ItemNo
 */
class LotItemItemNoApplyingInput extends CustomizableClass
{
    public bool $isSetItemFullNum;
    public bool $isSetItemNum;
    public bool $isSetItemNumExtension;
    public string $itemFullNum;
    public string $itemNum;
    public string $itemNumExtension;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $itemNum,
        string $itemNumExtension,
        string $itemFullNum,
        bool $isSetItemNum,
        bool $isSetItemNumExtension,
        bool $isSetItemFullNum
    ): static {
        $this->itemNum = $itemNum;
        $this->itemNumExtension = $itemNumExtension;
        $this->itemFullNum = $itemFullNum;
        $this->isSetItemNum = $isSetItemNum;
        $this->isSetItemNumExtension = $isSetItemNumExtension;
        $this->isSetItemFullNum = $isSetItemFullNum;
        return $this;
    }

    public function fromLotItemMakerInputDto(LotItemMakerInputDto $inputDto): static
    {
        return $this->construct(
            (string)$inputDto->itemNum,
            (string)$inputDto->itemNumExt,
            (string)$inputDto->itemFullNum,
            isset($inputDto->itemNum),
            isset($inputDto->itemNumExt),
            isset($inputDto->itemFullNum)
        );
    }

    public function logData(): array
    {
        $logData = [
            'itemNum' => $this->itemNum,
            'itemNumExtension' => $this->itemNumExtension,
            'itemFullNum' => $this->itemFullNum,
            'isSetItemNum' => $this->isSetItemNum,
            'isSetItemNumExtension' => $this->isSetItemNumExtension,
            'isSetItemFullNum' => $this->isSetItemFullNum,
        ];
        return $logData;
    }
}
