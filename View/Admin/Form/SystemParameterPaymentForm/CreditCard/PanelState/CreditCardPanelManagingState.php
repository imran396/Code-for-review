<?php
/**
 * SAM-10335: Allow to adjust CC surcharge per account: Implementation (Dev)
 * https://bidpath.atlassian.net/browse/SAM-10335
 *
 * @author        Oleh Kovalov
 * @since         Apr 29, 2022
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>*
 */

namespace Sam\View\Admin\Form\SystemParameterPaymentForm\CreditCard\PanelState;

use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;


class CreditCardPanelManagingState extends CustomizableClass
{
    use FormStateLongevityAwareTrait;

    private const CC_NONE = 0;
    private const CC_ADDING = 1;
    private const CC_EDITING = 2;

    protected int $status = self::CC_NONE;
    protected ?int $creditCardId = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Mutate ---

    public function toNone(): static
    {
        $this->creditCardId = null;
        $this->setStatus(self::CC_NONE);
        return $this;
    }

    public function toEditing(int $creditCardId): static
    {
        $this->creditCardId = $creditCardId;
        $this->setStatus(self::CC_EDITING);
        return $this;
    }

    public function toAdding(): static
    {
        $this->creditCardId = null;
        $this->setStatus(self::CC_ADDING);
        return $this;
    }


    protected function setStatus(int $status): static
    {
        $this->status = $status;
        return $this;
    }

    // --- Query ---

    public function isNone(): bool
    {
        return $this->isStatus(self::CC_NONE);
    }

    public function isEditingExistingOrAddingNew(?int $creditCardId): bool
    {
        return $this->creditCardId === $creditCardId;
    }

    public function isAdding(): bool
    {
        return $this->isStatus(self::CC_ADDING);
    }

    public function editingCreditCardId(): ?int
    {
        return $this->creditCardId;
    }

    protected function isStatus(int $status): bool
    {
        return $this->status === $status;
    }
}
