<?php
/**
 * SAM-10477: Reject assigning both BP rules on the same level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyersPremiumPanel;

use BuyersPremiumRange;
use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Core\Constants;

/**
 * Class BuyersPremiumRangeTableStateManager
 * @package Sam\View\Admin\Form\BuyersPremiumPanel
 */
class BuyersPremiumRangeTableStateManager extends CustomizableClass
{
    use BuyersPremiumRangeNormalizerCreateTrait;
    use FormStateLongevityAwareTrait;
    use NumberFormatterAwareTrait;

    protected array $dataState = [];
    protected int $accountId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param BuyersPremiumRange[] $ranges
     * @param int $accountId
     * @return static
     */
    public function construct(array $ranges, int $accountId): static
    {
        $numberFormatter = $this->getNumberFormatter();
        foreach ($ranges as $index => $range) {
            $this->dataState[$index] = [
                'amount' => $numberFormatter->formatMoneyNto($range->Amount, $accountId),
                'fixed' => $numberFormatter->formatMoneyNto($range->Fixed, $accountId),
                'percent' => $numberFormatter->formatPercent($range->Percent, $accountId),
                'mode' => $range->Mode
            ];
        }
        $this->accountId = $accountId;
        return $this;
    }

    public function addEmptyRow(): void
    {
        $amount = $this->count() ? '' : '0';
        $this->dataState[] = ['amount' => $amount, 'fixed' => '', 'percent' => '', 'mode' => Constants\BuyersPremium::MODE_GREATER];
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
        return $this->dataState[$index]['fixed'] ?? '';
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
    public function toNormalizedArray(): array
    {
        $normalizedRanges = array_map(function (array $range) {
            $normalizer = $this->createBuyersPremiumRangeNormalizer();
            return [
                $normalizer->normalizeFloat($range['amount'], $this->accountId),
                $normalizer->normalizeFloat($range['fixed'], $this->accountId),
                $normalizer->normalizeFloat($range['percent'], $this->accountId),
                $range['mode'],
            ];
        }, $this->getDataState());
        return $normalizedRanges;
    }
}
