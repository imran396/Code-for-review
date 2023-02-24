<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\TaxDefinitionEditForm\RatePanel;

use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;
use Sam\Tax\StackedTax\Definition\Edit\Dto\TaxDefinitionRangeDto;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\TaxDefinitionEditForm\RatePanel\Internal\Load\DataProviderCreateTrait;
use TaxDefinitionRange;

/**
 * Class TaxDefinitionRatePanelRangeTableStateManager
 * @package Sam\View\Admin\Form\TaxDefinitionForm
 */
class TaxDefinitionRatePanelRangeTableStateManager extends CustomizableClass
{
    use DataProviderCreateTrait;
    use FormStateLongevityAwareTrait;
    use NumberFormatterAwareTrait;

    protected array $dataState = [];
    protected bool $initialized = false;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function init(?int $taxDefinitionId, int $taxDefinitionAccountId): static
    {
        $this->initialized = true;
        if (!$taxDefinitionId) {
            return $this;
        }

        $this->dataState = [];
        $rows = $this->createDataProvider()->loadRangeRows($taxDefinitionId);
        $numberFormatter = $this->getNumberFormatter();
        foreach ($rows as $index => $row) {
            $this->dataState[$index] = [
                'amount' => $numberFormatter->formatMoneyNto((float)$row['amount'], $taxDefinitionAccountId),
                'fixed' => $numberFormatter->formatMoneyNto((float)$row['fixed'], $taxDefinitionAccountId),
                'percent' => $numberFormatter->formatPercent((float)$row['percent'], $taxDefinitionAccountId),
                'mode' => $row['mode']
            ];
        }

        return $this;
    }

    public function isInitialized(): bool
    {
        return $this->initialized;
    }

    public function addEmptyRow(): void
    {
        $amount = $this->count() ? '' : '0';
        $this->dataState[] = [
            'amount' => $amount,
            'fixed' => '',
            'percent' => '',
            'mode' => TaxDefinitionRange::MODE_DEFAULT
        ];
    }

    public function removeRow(int $index): void
    {
        unset($this->dataState[$index]);
        $this->dataState = array_values($this->dataState);
    }

    public function setStartAmount(int $index, string $startAmountFormatted): void
    {
        $this->dataState[$index]['amount'] = $startAmountFormatted;
    }

    public function setPercentValue(int $index, string $percentValueFormatted): void
    {
        $this->dataState[$index]['percent'] = $percentValueFormatted;
    }

    public function setFixedAmount(int $index, string $fixedAmountFormatted): void
    {
        $this->dataState[$index]['fixed'] = $fixedAmountFormatted;
    }

    public function setMode(int $index, string $modeValue): void
    {
        $this->dataState[$index]['mode'] = $modeValue;
    }

    public function getDataState(): array
    {
        return $this->dataState;
    }

    public function count(): int
    {
        return count($this->dataState);
    }

    public function indexes(): array
    {
        return array_keys($this->dataState);
    }

    public function startAmountFormatted(int $index): string
    {
        return $this->dataState[$index]['amount'] ?? '';
    }

    public function percentValueFormatted(int $index): string
    {
        return $this->dataState[$index]['percent'] ?? '';
    }

    public function fixedAmountFormatted(int $index): string
    {
        return $this->dataState[$index]['fixed'] ?? (string)TaxDefinitionRange::MODE_DEFAULT;
    }

    public function modeValue(int $index): string
    {
        return $this->dataState[$index]['mode'] ?? TaxDefinitionRange::MODE_DEFAULT;
    }

    /**
     * @return TaxDefinitionRangeDto[]
     */
    public function toTaxDefinitionRangeDtos(): array
    {
        $dtos = array_map(
            static function (array $stateRow): TaxDefinitionRangeDto {
                return TaxDefinitionRangeDto::new()->construct(
                    amount: $stateRow['amount'],
                    fixed: $stateRow['fixed'],
                    percent: $stateRow['percent'],
                    mode: $stateRow['mode']
                );
            },
            $this->dataState
        );
        return $dtos;
    }
}
