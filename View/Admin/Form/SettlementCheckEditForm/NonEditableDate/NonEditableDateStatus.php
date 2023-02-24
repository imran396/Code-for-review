<?php
/**
 * SAM-9795: Check Printing for Settlements: Implementation of html layout and view layer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementCheckEditForm\NonEditableDate;

use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;

/**
 * Class NonEditableDateStatus
 * @package  Sam\View\Admin\Form\SettlementCheckEditForm\NonEditableDate
 */
class NonEditableDateStatus extends CustomizableClass
{
    use FormStateLongevityAwareTrait;

    private const DS_UNDEFINED = 0;
    private const DS_ASSIGNED = 1;
    private const DS_DROPPED = 2;
    private const DS_EMPTY = 3;

    /** @var int */
    protected int $status = self::DS_UNDEFINED;
    protected string $name;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $name = ''): static
    {
        $this->name = $name;
        return $this;
    }

    // --- Mutate ---

    public function toUndefined(): static
    {
        $this->setStatus(self::DS_UNDEFINED);
        return $this;
    }

    public function toAssigned(): static
    {
        $this->setStatus(self::DS_ASSIGNED);
        return $this;
    }

    public function toDropped(): static
    {
        $this->setStatus(self::DS_DROPPED);
        return $this;
    }

    public function toEmpty(): static
    {
        $this->setStatus(self::DS_EMPTY);
        return $this;
    }

    public function toggle(): static
    {
        if ($this->isAssigned()) {
            $this->toDropped();
        } elseif ($this->isDropped()) {
            $this->toAssigned();
        }
        return $this;
    }

    protected function setStatus(int $status): static
    {
        $previous = (string)$this;
        $this->status = $status;
        log_trace("{$this->name} changed from \"{$previous}\" to \"{$this}\"");
        return $this;
    }

    // --- Query ---

    public function isUndefined(): bool
    {
        return $this->isStatus(self::DS_UNDEFINED);
    }

    public function isAssigned(): bool
    {
        return $this->isStatus(self::DS_ASSIGNED);
    }

    public function isDropped(): bool
    {
        return $this->isStatus(self::DS_DROPPED);
    }

    public function isEmpty(): bool
    {
        return $this->isStatus(self::DS_EMPTY);
    }

    public function __toString(): string
    {
        $statusNames = [
            self::DS_UNDEFINED => 'Undefined',
            self::DS_ASSIGNED => 'Assigned',
            self::DS_DROPPED => 'Dropped',
            self::DS_EMPTY => 'Empty',
        ];
        return $statusNames[$this->status];
    }

    protected function isStatus(int $status): bool
    {
        return $this->status === $status;
    }
}
