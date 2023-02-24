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

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Lock\ItemNo\Internal\Detect\LotItemUniqueItemNoLockRequirementCheckingInput as Input;

class LotItemUniqueItemNoLockRequirementCheckingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const INFO_DO_NOT_LOCK_BECAUSE_ITEM_NO_FIELDS_ABSENT_IN_INPUT = 1;
    public const INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_ITEM_NO_VALIDATION_FAILED = 2;
    public const INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_ITEM_NO_INPUT_EQUAL_TO_EXISTING = 3;
    public const INFO_DO_NOT_LOCK_BECAUSE_SEPARATED_ITEM_NO_INPUT_EQUAL_TO_EXISTING = 4;

    // When create new LotItem, then item# must be assigned.
    public const OK_LOCK_BECAUSE_NEW_LOT_ITEM_CREATED = 11;
    public const OK_LOCK_BECAUSE_CONCATENATED_ITEM_NO_DIFFERS = 12;
    public const OK_LOCK_BECAUSE_ITEM_NUM_DIFFERS = 13;
    public const OK_LOCK_BECAUSE_ITEM_NUM_EXTENSION_DIFFERS = 14;
    // Empty item# in input must cause new item# generation when adding new lot and editing the existing one.
    public const OK_LOCK_BECAUSE_ITEM_NO_MUST_BE_GENERATED = 15;

    protected const INFO_MESSAGES = [
        self::INFO_DO_NOT_LOCK_BECAUSE_ITEM_NO_FIELDS_ABSENT_IN_INPUT => 'Do not lock for unique item# constraint, because item# fields are absent in input',
        self::INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_ITEM_NO_VALIDATION_FAILED => 'Do not lock for unique item# constraint, because concatenated item# validation failed',
        self::INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_ITEM_NO_INPUT_EQUAL_TO_EXISTING => 'Do not lock for unique item# constraint, because concatenated item# input is equal to the existing value',
        self::INFO_DO_NOT_LOCK_BECAUSE_SEPARATED_ITEM_NO_INPUT_EQUAL_TO_EXISTING => 'Do not lock for unique item# constraint, because separated item# input is equal to the existing value',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_LOCK_BECAUSE_NEW_LOT_ITEM_CREATED => 'Lock for unique item# constraint, because new lot item will be created',
        self::OK_LOCK_BECAUSE_CONCATENATED_ITEM_NO_DIFFERS => 'Lock for unique item# constraint, because item# will be changed by concatenated item# input',
        self::OK_LOCK_BECAUSE_ITEM_NUM_DIFFERS => 'Lock for unique item# constraint, because item# will be changed by item num input',
        self::OK_LOCK_BECAUSE_ITEM_NUM_EXTENSION_DIFFERS => 'Lock for unique item# constraint, because item# will be changed by item num extension input',
        self::OK_LOCK_BECAUSE_ITEM_NO_MUST_BE_GENERATED => 'Lock for unique item# constraint, because new item# will be generated'
    ];

    protected Input $input;

    // --- Constructors ---

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    public function construct(Input $input): static
    {
        $this->input = $input;
        $this->getResultStatusCollector()->construct([], self::SUCCESS_MESSAGES, [], self::INFO_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
        return $this;
    }

    public function addInfo(int $code): static
    {
        $this->getResultStatusCollector()->addInfo($code);
        return $this;
    }

    // --- Query ---

    public function mustLock(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function statusCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstStatusCode();
    }

    public function message(): string
    {
        if ($this->getResultStatusCollector()->hasSuccess()) {
            return $this->getResultStatusCollector()->getConcatenatedSuccessMessage() . composeSuffix($this->logData());
        }

        if ($this->getResultStatusCollector()->hasInfo()) {
            return $this->getResultStatusCollector()->getConcatenatedInfoMessage() . composeSuffix($this->logData());
        }

        return '';
    }

    public function logData(): array
    {
        $logData['li'] = $this->input->lotItemId;
        if ($this->input->isSetItemNum) {
            $logData['item num'] = $this->input->itemNum;
        }
        if ($this->input->isSetItemNumExtension) {
            $logData['item num ext'] = $this->input->itemNumExtension;
        }
        if ($this->input->isSetItemFullNum) {
            $logData['full item#'] = $this->input->itemFullNum;
        }
        return $logData;
    }
}
