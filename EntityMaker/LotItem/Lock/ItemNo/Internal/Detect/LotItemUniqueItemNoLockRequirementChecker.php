<?php
/**
 * SAM-10557: Supply uniqueness of lot item fields: item#, unique lot custom fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Lock\ItemNo\Internal\Detect;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Lock\ItemNo\Internal\Detect\Internal\Load\DataProviderCreateTrait;
use Sam\EntityMaker\LotItem\Lock\ItemNo\Internal\Detect\LotItemUniqueItemNoLockRequirementCheckingResult as Result;
use Sam\Lot\Load\Exception\CouldNotFindLotItem;
use Sam\EntityMaker\LotItem\Lock\ItemNo\Internal\Detect\LotItemUniqueItemNoLockRequirementCheckingInput as Input;

/**
 * @package Sam\EntityMaker\LotItem
 */
class LotItemUniqueItemNoLockRequirementChecker extends CustomizableClass
{
    use DataProviderCreateTrait;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    public function check(Input $input): Result
    {
        $result = Result::new()->construct($input);

        if (!$input->lotItemId) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_NEW_LOT_ITEM_CREATED);
        }

        if ($this->isAbsentInInput($input)) {
            return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_ITEM_NO_FIELDS_ABSENT_IN_INPUT);
        }

        if ($this->isEmptyInInput($input)) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_ITEM_NO_MUST_BE_GENERATED);
        }

        $result = $this->willChange($input);
        return $result;
    }

    /**
     * Check if item# fields are not assigned in input.
     * @param LotItemUniqueItemNoLockRequirementCheckingInput $input
     * @return bool
     */
    protected function isAbsentInInput(Input $input): bool
    {
        return !$input->isSetItemNum
            && !$input->isSetItemNumExtension
            && !$input->isSetItemFullNum;
    }

    /**
     * Check, if item# fields are filled with empty values in input.
     * @param Input $input
     * @return bool
     */
    protected function isEmptyInInput(Input $input): bool
    {
        return ($input->itemNum === '' || $input->itemNum === '0')
            && ($input->itemFullNum === '' || $input->itemFullNum === '0');
    }

    /**
     * Check, if the input data must modify the existing values of item#.
     * @param Input $input
     * @return Result
     */
    protected function willChange(Input $input): Result
    {
        $result = Result::new()->construct($input);

        $dataProvider = $this->createDataProvider();
        $lotItem = $dataProvider->loadLotItem($input->lotItemId);
        if (!$lotItem) {
            throw CouldNotFindLotItem::withId($input->lotItemId);
        }

        if (
            $input->isSetItemFullNum
            && $input->itemFullNum !== ''
        ) {
            if (!$dataProvider->validateItemNo($input->itemFullNum)) {
                return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_ITEM_NO_VALIDATION_FAILED);
            }

            $itemNoParsed = $dataProvider->parseItemNo($input->itemFullNum);
            $willChange = $lotItem->ItemNum !== $itemNoParsed->itemNum
                || $lotItem->ItemNumExt !== $itemNoParsed->itemNumExtension;
            if ($willChange) {
                return $result->addSuccess(Result::OK_LOCK_BECAUSE_CONCATENATED_ITEM_NO_DIFFERS);
            }

            return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_ITEM_NO_INPUT_EQUAL_TO_EXISTING);
        }

        if (
            isset($input->itemNum)
            && $lotItem->ItemNum !== Cast::toInt($input->itemNum)
        ) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_ITEM_NUM_DIFFERS);
        }

        if (
            isset($input->itemNumExtension)
            && $lotItem->ItemNumExt !== $input->itemNumExtension
        ) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_ITEM_NUM_EXTENSION_DIFFERS);
        }

        return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_SEPARATED_ITEM_NO_INPUT_EQUAL_TO_EXISTING);
    }
}
