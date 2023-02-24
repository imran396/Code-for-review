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

use LotItem;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\EntityMaker\LotItem\Save\Internal\ItemNo\Internal\Load\DataProviderCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class LotItemItemNoApplier
 * @package Sam\EntityMaker\LotItem\Save\Internal\ItemNo
 */
class LotItemItemNoApplier extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * Assign to LotItem entity the item# from input, or generate next available item#, or do nothing.
     * @param LotItem $lotItem
     * @param LotItemMakerInputDto $inputDto
     * @return LotItem
     */
    public function apply(LotItem $lotItem, LotItemMakerInputDto $inputDto): LotItem
    {
        $input = LotItemItemNoApplyingInput::new()->fromLotItemMakerInputDto($inputDto);
        return $this->applyInput($lotItem, $input);
    }

    /**
     * Assign to LotItem entity the item# from input, or generate next available item#, or do nothing.
     * @param LotItem $lotItem
     * @param LotItemItemNoApplyingInput $input
     * @return LotItem
     */
    public function applyInput(LotItem $lotItem, LotItemItemNoApplyingInput $input): LotItem
    {
        $this->assignInput($lotItem, $input);
        if ($this->mustGenerate($lotItem)) {
            $this->assignSuggested($lotItem);
        }
        return $lotItem;
    }

    protected function assignInput(LotItem $lotItem, LotItemItemNoApplyingInput $input): void
    {
        $dataProvider = $this->createDataProvider();
        $isItemNumLock = $dataProvider->isItemNumLock($lotItem->AccountId);
        if ($isItemNumLock) {
            log_trace(
                "Item# modification rejected, when ITEM_NUM_LOCK is enabled"
                . composeSuffix(['acc' => $lotItem->AccountId, 'li' => $lotItem->Id])
            );
            return;
        }

        if ($this->cfg()->get('core->lot->itemNo->concatenated')) {
            if ($input->isSetItemFullNum) {
                [$lotItem->ItemNum, $lotItem->ItemNumExt] = $dataProvider->parseItemNo($input->itemFullNum);
            }
        } else {
            if ($input->isSetItemNum) {
                $lotItem->ItemNum = (int)$input->itemNum;
            }
            if ($input->isSetItemNumExtension) {
                $lotItem->ItemNumExt = $input->itemNumExtension;
            }
        }
    }

    /**
     * Generate and assign item#, when it is empty in LotItem entity (empty means null or 0)
     */
    protected function assignSuggested(LotItem $lotItem): void
    {
        $itemNum = $this->createDataProvider()->suggestItemNo($lotItem->AccountId);
        $lotItem->ItemNum = $itemNum;
        $lotItem->ItemNumExt = '';
    }

    /**
     * LotItem cannot be without item#, thus when field is empty, we must search for next available.
     * @param LotItem $lotItem
     * @return bool
     */
    protected function mustGenerate(LotItem $lotItem): bool
    {
        return !$lotItem->ItemNum; // null, 0
    }
}
