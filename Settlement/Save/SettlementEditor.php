<?php
/**
 * SAM-4704: Settlement Editor
 *
 * You should set Settlement property, currently we don't allow to create settlements from admin side manually
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 18, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Save;

use DateTime;
use Sam\Application\RequestParam\ParamFetcherForPostAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Date\DateHelperAwareTrait;
use Sam\Settlement\Calculate\SettlementCalculatorAwareTrait;
use Sam\Settlement\Load\SettlementItemLoaderAwareTrait;
use Sam\Settlement\Validate\SettlementExistenceCheckerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\SettlementAwareTrait;
use Sam\Storage\WriteRepository\Entity\Settlement\SettlementWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\SettlementItem\SettlementItemWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use SettlementItem;

/**
 * Class SettlementEditor
 * @package Sam\Settlement\Save
 */
class SettlementEditor extends CustomizableClass
{
    use AccountAwareTrait;
    use DateHelperAwareTrait;
    use NumberFormatterAwareTrait;
    use ParamFetcherForPostAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SettlementAwareTrait;
    use SettlementCalculatorAwareTrait;
    use SettlementExistenceCheckerAwareTrait;
    use SettlementItemLoaderAwareTrait;
    use SettlementItemWriteRepositoryAwareTrait;
    use SettlementWriteRepositoryAwareTrait;

    public const ERR_SETTLEMENT_NO_REQUIRED = 1;
    public const ERR_SETTLEMENT_NO_INVALID = 2;
    public const ERR_SETTLEMENT_NO_EXIST = 3;
    public const ERR_CODE_FEE = 4;
    public const ERR_CODE_HAMMER_PRICE = 5;
    public const ERR_CODE_COMMISSION = 6;
    public const ERR_SETTLEMENT_NO_POSITIVE = 7;

    public const CHANGES_SAVED = 1;

    /** @var int[] */
    protected array $commissionErrors = [self::ERR_CODE_COMMISSION];
    /** @var int[] */
    protected array $feeErrors = [self::ERR_CODE_FEE];
    /** @var int[] */
    protected array $hammerPriceErrors = [self::ERR_CODE_HAMMER_PRICE];
    /** @var int[] */
    protected array $settlementNoErrors = [
        self::ERR_SETTLEMENT_NO_REQUIRED,
        self::ERR_SETTLEMENT_NO_INVALID,
        self::ERR_SETTLEMENT_NO_EXIST,
        self::ERR_SETTLEMENT_NO_POSITIVE,
    ];

    protected float|string|null $consignorTax = null;
    protected bool $isConsignorTaxHp = false;
    protected ?DateTime $settlementDate = null;
    protected ?string $settlementNo = null;
    protected bool $isConsignorTaxComm = false;
    protected bool $isConsignorTaxServices = false;
    protected ?int $consignorTaxHpType = null;

    /**
     * @var SettlementItem[]
     */
    protected array $settlementItems = [];
    protected array $settlementItemsData = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $errorMessages = [
            self::ERR_SETTLEMENT_NO_REQUIRED => 'Settlement No required',
            self::ERR_SETTLEMENT_NO_INVALID => 'Settlement No not numeric',
            self::ERR_SETTLEMENT_NO_EXIST => 'Settlement No exists',
            self::ERR_CODE_FEE => 'Invalid fee',
            self::ERR_CODE_HAMMER_PRICE => 'Invalid hammer price',
            self::ERR_CODE_COMMISSION => 'Invalid commission',
            self::ERR_SETTLEMENT_NO_POSITIVE => 'Settlement No should be positive integer',
        ];
        $successMessages = [
            self::CHANGES_SAVED => 'Changes saved!',
        ];
        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
        return $this;
    }

    /**
     * @param int $editorUserId
     * @return void
     */
    public function update(int $editorUserId): void
    {
        $settlement = $this->getSettlement();
        if (!$settlement) {
            log_error('Settlement object absent' . composeSuffix(['s' => $this->getSettlementId()]));
            return;
        }

        $numberFormatter = $this->getNumberFormatter();
        $settlementItemsData = $this->getSettlementItemsData();
        foreach ($this->getSettlementItems() as $settlementItem) {
            $settlementItem->LotName = (string)$settlementItemsData[$settlementItem->Id]['lotName'];
            $settlementItem->HammerPrice = $numberFormatter->parse($settlementItemsData[$settlementItem->Id]['hp']);
            $settlementItem->Fee = $numberFormatter->parse($settlementItemsData[$settlementItem->Id]['fee']);
            $settlementItem->Commission = $numberFormatter->parse($settlementItemsData[$settlementItem->Id]['commission']);
            $settlementItem->Subtotal = $this->getSettlementCalculator()->calcSubtotal($settlementItem, ['settlement' => $settlement]);
            if (array_key_exists('Commission', $settlementItem->__Modified)) {
                $settlementItem->CommissionId = null;
            }
            if (array_key_exists('Fee', $settlementItem->__Modified)) {
                $settlementItem->FeeId = null;
            }
            $this->getSettlementItemWriteRepository()->saveWithModifier($settlementItem, $editorUserId);
        }
        $settlement->SettlementNo = $this->normalizeSettlementNo();
        $settlementDate = $this->getSettlementDate();
        if ($settlementDate) {
            $settlementDate = $settlementDate->setTime(12, 0);
            $settlementDate = $this->getDateHelper()->convertSysToUtc($settlementDate);
        }
        $settlement->SettlementDate = $settlementDate;
        $settlement->ConsignorTax = $this->normalizeConsignorTax();
        $settlement->ConsignorTaxHp = $this->isConsignorTaxHp();
        if ($this->getConsignorTaxHpType()) {
            $settlement->ConsignorTaxHpType = $this->getConsignorTaxHpType();
        }
        $settlement->ConsignorTaxComm = $this->isConsignorTaxComm();
        $settlement->ConsignorTaxServices = $this->isConsignorTaxServices();
        $this->getSettlementWriteRepository()->saveWithModifier($settlement, $editorUserId);
        $this->getResultStatusCollector()->addSuccess(self::CHANGES_SAVED);
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        // $this->getResultStatusCollector()->initAll($this->errorMessages);
        $this->getResultStatusCollector()->clear();
        $this->validateSettlementItems();
        $this->validateSettlementNo();
        $isValid = !$this->getResultStatusCollector()->hasError();
        return $isValid;
    }

    /**
     * @return string|float
     */
    public function getConsignorTax(): string|float
    {
        return $this->consignorTax;
    }

    /**
     * @param string|float $consignorTax
     * @return static
     */
    public function setConsignorTax(string|float $consignorTax): static
    {
        $this->consignorTax = is_string($consignorTax)
            ? trim($consignorTax)
            : $consignorTax;
        return $this;
    }

    /**
     * @return float|null
     */
    protected function normalizeConsignorTax(): ?float
    {
        return Cast::toFloat($this->getConsignorTax());
    }

    /**
     * @param bool $enabled
     * @return static
     */
    public function enableConsignorTaxHp(bool $enabled): static
    {
        $this->isConsignorTaxHp = $enabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConsignorTaxHp(): bool
    {
        return $this->isConsignorTaxHp;
    }

    /**
     * @param DateTime|null $settlementDate
     * @return static
     */
    public function setSettlementDate(?DateTime $settlementDate): static
    {
        $this->settlementDate = $settlementDate;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getSettlementDate(): ?DateTime
    {
        return $this->settlementDate;
    }

    /**
     * @param string $settlementNo
     * @return static
     */
    public function setSettlementNo(string $settlementNo): static
    {
        $this->settlementNo = trim($settlementNo);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSettlementNo(): ?string
    {
        return $this->settlementNo;
    }

    /**
     * @return int|null
     */
    protected function normalizeSettlementNo(): ?int
    {
        return Cast::toInt($this->getSettlementNo(), Constants\Type::F_INT_POSITIVE);
    }

    /**
     * @param bool $enabled
     * @return static
     */
    public function enableConsignorTaxComm(bool $enabled): static
    {
        $this->isConsignorTaxComm = $enabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConsignorTaxComm(): bool
    {
        return $this->isConsignorTaxComm;
    }

    /**
     * @param bool $enabled
     * @return static
     */
    public function enableConsignorTaxServices(bool $enabled): static
    {
        $this->isConsignorTaxServices = $enabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConsignorTaxServices(): bool
    {
        return $this->isConsignorTaxServices;
    }

    /**
     * @param int $type
     * @return static
     */
    public function setConsignorTaxHpType(int $type): static
    {
        $this->consignorTaxHpType = $type;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getConsignorTaxHpType(): ?int
    {
        return $this->consignorTaxHpType;
    }

    /**
     * @param SettlementItem[] $settlementItems
     * @return static
     */
    public function setSettlementItems(array $settlementItems): static
    {
        $this->settlementItems = $settlementItems;
        return $this;
    }

    /**
     * @return SettlementItem[]
     */
    public function getSettlementItems(): array
    {
        return $this->settlementItems;
    }

    /**
     * @param array $data
     * @return static
     */
    public function setSettlementItemsData(array $data): static
    {
        $this->settlementItemsData = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getSettlementItemsData(): array
    {
        return $this->settlementItemsData;
    }

    /**
     * @return bool
     */
    public function hasFeeError(): bool
    {
        return $this->getResultStatusCollector()
            ->hasConcreteError($this->feeErrors);
    }

    /**
     * @return ResultStatus[]
     */
    public function feeResultStatuses(): array
    {
        return $this->getResultStatusCollector()->findErrorResultStatusesByCodes($this->feeErrors);
    }

    /**
     * @return bool
     */
    public function hasHammerPriceError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->hammerPriceErrors);
    }

    /**
     * @return ResultStatus[]
     */
    public function getHammerPriceErrors(): array
    {
        return $this->getResultStatusCollector()->findErrorResultStatusesByCodes($this->hammerPriceErrors);
    }

    /**
     * @return bool
     */
    public function hasCommissionError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->commissionErrors);
    }

    /**
     * @return ResultStatus[]
     */
    public function commissionResultStatuses(): array
    {
        return $this->getResultStatusCollector()->findErrorResultStatusesByCodes($this->commissionErrors);
    }

    /**
     * @return bool
     */
    public function hasSettlementNoError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->settlementNoErrors);
    }

    /**
     * @return string
     */
    public function settlementNoErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->settlementNoErrors);
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    /**
     * Validate settlement items input
     */
    protected function validateSettlementItems(): void
    {
        $collector = $this->getResultStatusCollector();
        $settlementItemsData = $this->getSettlementItemsData();
        $numberFormatter = $this->getNumberFormatter();
        $numberValidator = NumberValidator::new();
        foreach ($this->getSettlementItems() as $settlementItem) {
            $hpValue = $numberFormatter->removeFormat($settlementItemsData[$settlementItem->Id]['hp']);
            if (
                $hpValue !== ''
                && !$numberValidator->isRealPositiveOrZero($hpValue)
            ) {
                $collector->addError(self::ERR_CODE_HAMMER_PRICE, null, ['id' => $settlementItem->Id]);
            }

            $feeValue = $numberFormatter->removeFormat($settlementItemsData[$settlementItem->Id]['fee']);
            if (
                $feeValue !== ''
                && !$numberValidator->isRealPositiveOrZero($feeValue)
            ) {
                $collector->addError(self::ERR_CODE_FEE, null, ['id' => $settlementItem->Id]);
            }

            $commissionValue = $numberFormatter->removeFormat($settlementItemsData[$settlementItem->Id]['commission']);
            if (
                $commissionValue !== ''
                && !$numberValidator->isRealPositiveOrZero($commissionValue)
            ) {
                $collector->addError(self::ERR_CODE_COMMISSION, null, ['id' => $settlementItem->Id]);
            }
        }
    }

    /**
     * Validate Settlement No input
     */
    protected function validateSettlementNo(): void
    {
        $collector = $this->getResultStatusCollector();
        $settlementNo = $this->getSettlementNo();
        if ($settlementNo === '') {
            $collector->addError(self::ERR_SETTLEMENT_NO_REQUIRED);
        } elseif (!is_numeric($settlementNo)) {
            $collector->addError(self::ERR_SETTLEMENT_NO_INVALID);
        } elseif (!Cast::toInt($settlementNo, Constants\Type::F_INT_POSITIVE)) {
            $collector->addError(self::ERR_SETTLEMENT_NO_POSITIVE);
        } else {
            if ((int)$settlementNo !== $this->getSettlement()->SettlementNo) {
                $isFound = $this->getSettlementExistenceChecker()
                    ->existBySettlementNo((int)$settlementNo, $this->getAccountId(), [$this->getSettlementId()]);
                if ($isFound) {
                    $collector->addError(self::ERR_SETTLEMENT_NO_EXIST);
                }
            }
        }
    }
}
