<?php
/**
 * SAM-10802: Supply uniqueness of auction lot fields: lot#
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

namespace Sam\EntityMaker\AuctionLot\Lock\LotNo\Internal\Detect;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Lock\LotNo\Internal\Detect\AuctionLotUniqueLotNoLockRequirementCheckingInput as Input;

class AuctionLotUniqueLotNoLockRequirementCheckingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const INFO_DO_NOT_LOCK_BECAUSE_LOT_NO_FIELDS_ABSENT_IN_INPUT = 1;
    public const INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_LOT_NO_VALIDATION_FAILED = 2;
    public const INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_LOT_NO_INPUT_EQUAL_TO_EXISTING = 3;
    public const INFO_DO_NOT_LOCK_BECAUSE_SEPARATED_LOT_NO_INPUT_EQUAL_TO_EXISTING = 4;

    // When create new AuctionLot, then lot# must be assigned.
    public const OK_LOCK_BECAUSE_NEW_AUCTION_LOT_CREATED = 11;
    public const OK_LOCK_BECAUSE_CONCATENATED_LOT_NO_DIFFERS = 12;
    public const OK_LOCK_BECAUSE_LOT_NUM_DIFFERS = 13;
    public const OK_LOCK_BECAUSE_LOT_NUM_EXTENSION_DIFFERS = 14;
    public const OK_LOCK_BECAUSE_LOT_NUM_PREFIX_DIFFERS = 15;
    // Empty lot# in input must cause new lot# generation when adding new lot and editing the existing one.
    public const OK_LOCK_BECAUSE_LOT_NO_MUST_BE_GENERATED = 16;

    protected const INFO_MESSAGES = [
        self::INFO_DO_NOT_LOCK_BECAUSE_LOT_NO_FIELDS_ABSENT_IN_INPUT => 'Do not lock for unique lot# constraint, because lot# fields are absent in input',
        self::INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_LOT_NO_VALIDATION_FAILED => 'Do not lock for unique lot# constraint, because concatenated lot# validation failed',
        self::INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_LOT_NO_INPUT_EQUAL_TO_EXISTING => 'Do not lock for unique lot# constraint, because concatenated lot# input is equal to the existing value',
        self::INFO_DO_NOT_LOCK_BECAUSE_SEPARATED_LOT_NO_INPUT_EQUAL_TO_EXISTING => 'Do not lock for unique lot# constraint, because separated lot# input is equal to the existing value',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_LOCK_BECAUSE_NEW_AUCTION_LOT_CREATED => 'Lock for unique lot# constraint, because new auction lot will be created',
        self::OK_LOCK_BECAUSE_CONCATENATED_LOT_NO_DIFFERS => 'Lock for unique lot# constraint, because lot# will be changed by concatenated lot# input',
        self::OK_LOCK_BECAUSE_LOT_NUM_DIFFERS => 'Lock for unique lot# constraint, because lot# will be changed by lot num input',
        self::OK_LOCK_BECAUSE_LOT_NUM_EXTENSION_DIFFERS => 'Lock for unique lot# constraint, because lot# will be changed by lot num extension input',
        self::OK_LOCK_BECAUSE_LOT_NUM_PREFIX_DIFFERS => 'Lock for unique lot# constraint, because lot# will be changed by lot num prefix input',
        self::OK_LOCK_BECAUSE_LOT_NO_MUST_BE_GENERATED => 'Lock for unique lot# constraint, because new lot# will be generated'
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
        $logData['ali'] = $this->input->auctionLotId;
        if ($this->input->isSetLotNum) {
            $logData['lot num'] = $this->input->lotNum;
        }
        if ($this->input->isSetLotNumExtension) {
            $logData['lot num ext'] = $this->input->lotNumExtension;
        }
        if ($this->input->isSetLotNumPrefix) {
            $logData['lot num prefix'] = $this->input->lotNumPrefix;
        }
        if ($this->input->isSetLotFullNum) {
            $logData['full lot#'] = $this->input->lotFullNum;
        }
        return $logData;
    }
}
