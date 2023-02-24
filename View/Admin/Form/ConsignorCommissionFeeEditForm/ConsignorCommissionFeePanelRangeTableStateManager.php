<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ConsignorCommissionFeeEditForm;

use ConsignorCommissionFeeRange;
use Sam\Consignor\Commission\Edit\Dto\ConsignorCommissionFeeRangeDto;
use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\ConsignorCommissionFeeEditForm\Internal\Load\ConsignorCommissionFeePanelLoaderCreateTrait;

/**
 * This class is responsible for collect and keep consignor commission fee range form table data
 *
 * Class ConsignorCommissionFeePanelRangeTableStateManager
 * @package Sam\View\Admin\Form\ConsignorCommissionFeeEditForm
 */
class ConsignorCommissionFeePanelRangeTableStateManager extends CustomizableClass
{
    use ConsignorCommissionFeePanelLoaderCreateTrait;
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

    /**
     * @param int|null $consignorCommissionFeeId
     * @return static
     */
    public function init(?int $consignorCommissionFeeId): static
    {
        $this->initialized = true;
        if (!$consignorCommissionFeeId) {
            $this->addEmptyRow();
            return $this;
        }

        $this->dataState = [];
        $rows = $this->createConsignorCommissionFeePanelLoader()->loadConsignorCommissionFeeRangeRows($consignorCommissionFeeId);
        $numberFormatter = $this->getNumberFormatter();
        foreach ($rows as $index => $row) {
            $this->dataState[$index] = [
                'amount' => $numberFormatter->formatMoneyNto((float)$row['amount']),
                'fixed' => $numberFormatter->formatMoneyNto((float)$row['fixed']),
                'percent' => $numberFormatter->formatPercent((float)$row['percent']),
                'mode' => (int)$row['mode']
            ];
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isInitialized(): bool
    {
        return $this->initialized;
    }

    public function addEmptyRow(): void
    {
        $amount = $this->count() ? '' : '0';
        $this->dataState[] = ['amount' => $amount, 'fixed' => '', 'percent' => '', 'mode' => ConsignorCommissionFeeRange::MODE_DEFAULT];
    }

    /**
     * @param int $index
     */
    public function removeRow(int $index): void
    {
        unset($this->dataState[$index]);
        $this->dataState = array_values($this->dataState);
    }

    /**
     * @param int $index
     * @param string $startAmountFormatted
     */
    public function setStartAmount(int $index, string $startAmountFormatted): void
    {
        $this->dataState[$index]['amount'] = $startAmountFormatted;
    }

    /**
     * @param int $index
     * @param string $percentValueFormatted
     */
    public function setPercentValue(int $index, string $percentValueFormatted): void
    {
        $this->dataState[$index]['percent'] = $percentValueFormatted;
    }

    /**
     * @param int $index
     * @param string $fixedAmountFormatted
     */
    public function setFixedAmount(int $index, string $fixedAmountFormatted): void
    {
        $this->dataState[$index]['fixed'] = $fixedAmountFormatted;
    }

    /**
     * @param int $index
     * @param string $modeValue
     */
    public function setMode(int $index, string $modeValue): void
    {
        $this->dataState[$index]['mode'] = $modeValue;
    }

    /**
     * @return array
     */
    public function getDataState(): array
    {
        return $this->dataState;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->dataState);
    }

    /**
     * @return array
     */
    public function indexes(): array
    {
        return array_keys($this->dataState);
    }

    /**
     * @param int $index
     * @return string
     */
    public function startAmountFormatted(int $index): string
    {
        return $this->dataState[$index]['amount'] ?? '';
    }

    /**
     * @param int $index
     * @return string
     */
    public function percentValueFormatted(int $index): string
    {
        return $this->dataState[$index]['percent'] ?? '';
    }

    /**
     * @param int $index
     * @return string
     */
    public function fixedAmountFormatted(int $index): string
    {
        return $this->dataState[$index]['fixed'] ?? (string)ConsignorCommissionFeeRange::MODE_DEFAULT;
    }

    /**
     * @param int $index
     * @return string
     */
    public function modeValue(int $index): string
    {
        return $this->dataState[$index]['mode'] ?? '';
    }

    /**
     * @return array
     */
    public function toConsignorCommissionFeeRangeDtos(): array
    {
        $dtos = array_map(
            static function (array $stateRow): ConsignorCommissionFeeRangeDto {
                return ConsignorCommissionFeeRangeDto::new()->fromArray(
                    [
                        'amount' => $stateRow['amount'],
                        'fixed' => $stateRow['fixed'],
                        'percent' => $stateRow['percent'],
                        'mode' => (int)$stateRow['mode']
                    ]
                );
            },
            $this->dataState
        );
        return $dtos;
    }
}
