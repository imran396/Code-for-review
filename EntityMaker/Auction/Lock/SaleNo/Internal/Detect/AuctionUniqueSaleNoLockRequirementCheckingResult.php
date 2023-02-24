<?php
/**
 * SAM-10615: Supply uniqueness of auction fields: sale#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Lock\SaleNo\Internal\Detect;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Lock\SaleNo\Internal\Detect\AuctionUniqueSaleNoLockRequirementCheckerInput as Input;

/**
 * Class AuctionUniqueSaleNoLockRequirementCheckingResult
 * @package Sam\EntityMaker\Auction\Lock\SaleNo\Internal\Detect
 */
class AuctionUniqueSaleNoLockRequirementCheckingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const INFO_DO_NOT_LOCK_BECAUSE_SALE_NO_FIELDS_ABSENT_IN_INPUT = 1;
    public const INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_SALE_NO_VALIDATION_FAILED = 2;
    public const INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_SALE_NO_INPUT_EQUAL_TO_EXISTING = 3;
    public const INFO_DO_NOT_LOCK_BECAUSE_SEPARATED_SALE_NO_INPUT_EQUAL_TO_EXISTING = 4;

    // When create new LotItem, then item# must be assigned.
    public const OK_LOCK_BECAUSE_NEW_AUCTION_CREATED = 11;
    public const OK_LOCK_BECAUSE_CONCATENATED_SALE_NO_DIFFERS = 12;
    public const OK_LOCK_BECAUSE_SALE_NUM_DIFFERS = 13;
    public const OK_LOCK_BECAUSE_SALE_NUM_EXTENSION_DIFFERS = 14;
    // Empty sale# in input must cause new sale# generation when adding auction and editing the existing one.
    public const OK_LOCK_BECAUSE_SALE_NO_MUST_BE_GENERATED = 15;

    protected const INFO_MESSAGES = [
        self::INFO_DO_NOT_LOCK_BECAUSE_SALE_NO_FIELDS_ABSENT_IN_INPUT => 'Do not lock for unique sale# constraint, because sale# fields are absent in input',
        self::INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_SALE_NO_VALIDATION_FAILED => 'Do not lock for unique sale# constraint, because concatenated sale# validation failed',
        self::INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_SALE_NO_INPUT_EQUAL_TO_EXISTING => 'Do not lock for unique sale# constraint, because concatenated sale# input is equal to the existing value',
        self::INFO_DO_NOT_LOCK_BECAUSE_SEPARATED_SALE_NO_INPUT_EQUAL_TO_EXISTING => 'Do not lock for unique sale# constraint, because separated sale# input is equal to the existing value',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_LOCK_BECAUSE_NEW_AUCTION_CREATED => 'Lock for unique sale# constraint, because new auction will be created',
        self::OK_LOCK_BECAUSE_CONCATENATED_SALE_NO_DIFFERS => 'Lock for unique sale# constraint, because sale# will be changed by concatenated sale# input',
        self::OK_LOCK_BECAUSE_SALE_NUM_DIFFERS => 'Lock for unique sale# constraint, because sale# will be changed by sale num input',
        self::OK_LOCK_BECAUSE_SALE_NUM_EXTENSION_DIFFERS => 'Lock for unique sale# constraint, because sale# will be changed by sale num extension input',
        self::OK_LOCK_BECAUSE_SALE_NO_MUST_BE_GENERATED => 'Lock for unique sale# constraint, because new sale# will be generated'
    ];

    protected array $logData;

    // --- Constructors ---

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    public function construct(Input $input): static
    {
        $this->logData = $this->makeLogData($input);
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

    public function message(): string
    {
        if ($this->getResultStatusCollector()->hasSuccess()) {
            return $this->getResultStatusCollector()->getConcatenatedSuccessMessage() . composeSuffix($this->logData);
        }

        if ($this->getResultStatusCollector()->hasInfo()) {
            return $this->getResultStatusCollector()->getConcatenatedInfoMessage() . composeSuffix($this->logData);
        }

        return '';
    }

    public function makeLogData(Input $input): array
    {
        $logData['auction'] = $input->auctionId;
        if ($input->saleNum !== null) {
            $logData['sale num'] = $input->saleNum;
        }
        if ($input->saleNumExt !== null) {
            $logData['sale num ext'] = $input->saleNumExt;
        }
        if ($input->saleFullNo !== null) {
            $logData['full sale_no'] = $input->saleFullNo;
        }
        return $logData;
    }
}
