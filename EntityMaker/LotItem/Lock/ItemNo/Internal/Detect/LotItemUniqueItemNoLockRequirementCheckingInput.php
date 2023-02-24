<?php
/**
 * SAM-10557: Supply uniqueness of lot item fields: item#, unique lot custom fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Lock\ItemNo\Internal\Detect;

use Sam\Core\Service\CustomizableClass;

/**
 * @package Sam\EntityMaker\LotItem
 */
class LotItemUniqueItemNoLockRequirementCheckingInput extends CustomizableClass
{
    public ?int $lotItemId;
    public bool $isSetItemFullNum;
    public bool $isSetItemNum;
    public bool $isSetItemNumExtension;
    public string $itemFullNum;
    public string $itemNum;
    public string $itemNumExtension;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    public function construct(
        ?int $lotItemId,
        string $itemNum,
        string $itemNumExtension,
        string $itemFullNum,
        bool $isSetItemNum,
        bool $isSetItemNumExtension,
        bool $isSetItemFullNum
    ): static {
        $this->lotItemId = $lotItemId;
        $this->itemNum = $itemNum;
        $this->itemNumExtension = $itemNumExtension;
        $this->itemFullNum = $itemFullNum;
        $this->isSetItemNum = $isSetItemNum;
        $this->isSetItemNumExtension = $isSetItemNumExtension;
        $this->isSetItemFullNum = $isSetItemFullNum;
        return $this;
    }

    public function logData(): array
    {
        $logData['li'] = $this->lotItemId;
        if ($this->isSetItemNum) {
            $logData['item num'] = $this->itemNum;
        }
        if ($this->isSetItemNumExtension) {
            $logData['item num ext'] = $this->itemNumExtension;
        }
        if ($this->isSetItemFullNum) {
            $logData['full item#'] = $this->itemFullNum;
        }
        return $logData;
    }
}
