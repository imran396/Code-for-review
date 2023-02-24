<?php
/**
 * Data state manager for table of User Sales Commission ranges.
 *
 * SAM-8106: Improper validations displayed for invalid inputs
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\UserEditForm\UserSalesCommissionPanel\RangeTable;

use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\UserEditForm\UserSalesCommissionPanel\Load\UserSalesCommissionPanelLoaderCreateTrait;

/**
 * Class UserSalesCommissionPanelDataStateManager
 * @package Sam\View\Admin\Form\UserEditForm\UserSalesCommissionPanel\DataState
 */
class UserSalesCommissionPanelRangeTableStateManager extends CustomizableClass
{
    use FormStateLongevityAwareTrait;
    use NumberFormatterAwareTrait;
    use UserSalesCommissionPanelLoaderCreateTrait;

    protected array $dataState = [];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Command methods ---

    public function init(?int $targetUserId): static
    {
        if (!$targetUserId) {
            return $this;
        }

        $this->dataState = [];
        $rows = $this->createUserSalesCommissionPanelLoader()->loadRowsByUserId($targetUserId);
        $numberFormatter = $this->getNumberFormatter();
        foreach ($rows as $index => $row) {
            $this->dataState[$index] = [
                'id' => (int)$row['id'],
                'amount' => $numberFormatter->formatMoneyNto((float)$row['amount']),
                'percent' => $numberFormatter->formatPercent((float)$row['percent'])
            ];
        }

        return $this;
    }

    public function addEmptyRow(): void
    {
        $amount = $this->count() ? '' : '0';
        $this->dataState[] = ['id' => null, 'amount' => $amount, 'percent' => ''];
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

    // --- Query methods ---

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

    public function id(int $index): ?int
    {
        return $this->dataState[$index]['id'] ?? null;
    }

    public function startAmountFormatted(int $index): string
    {
        return $this->dataState[$index]['amount'] ?? '';
    }

    public function percentValueFormatted(int $index): string
    {
        return $this->dataState[$index]['percent'] ?? '';
    }

    public function valuePairs(): array
    {
        $pairs = [];
        $numberFormatter = $this->getNumberFormatter();
        foreach ($this->dataState as $row) {
            $pairs[] = [
                $numberFormatter->removeFormat($row['amount']),
                $numberFormatter->removeFormat($row['percent'])
            ];
        }
        return $pairs;
    }
}
