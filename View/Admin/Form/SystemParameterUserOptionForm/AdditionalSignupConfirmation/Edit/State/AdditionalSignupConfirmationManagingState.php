<?php
/**
 * SAM-10184: Hide  information about current state of entity in value-object for code that  handles /admin/manage-system-parameter/user-option page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-08, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SystemParameterUserOptionForm\AdditionalSignupConfirmation\Edit\State;

use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;

/**
 * Class AdditionalConfirmationsManagingState
 * @package Sam\View\Admin\Form\SystemParameterUserOptionForm\AdditionalSignupConfirmation\Edit\State
 */
class AdditionalSignupConfirmationManagingState extends CustomizableClass
{

    use FormStateLongevityAwareTrait;

    protected const ST_ADD = 1;
    protected const ST_EDIT = 2;
    protected const ST_NONE = 3;

    protected ?int $editingConfirmationId = null;
    /**
     * Can be in three states: "Add new confirmation message" or "Edit existing confirmation message" or "None" (by default)
     */
    protected int $status = self::ST_NONE;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Mutate state ---

    /**
     * Change state to "Edit existing confirmation message".
     * @param int $confirmationId
     * @return $this
     */
    public function toEdit(int $confirmationId): static
    {
        $this->editingConfirmationId = $confirmationId;
        $this->status = self::ST_EDIT;
        return $this;
    }

    /**
     * Change state to "Add new confirmation message".
     * @return $this
     */
    public function toAddNew(): static
    {
        $this->editingConfirmationId = null;
        $this->status = self::ST_ADD;
        return $this;
    }

    public function toNone(): static
    {
        $this->editingConfirmationId = null;
        $this->status = self::ST_NONE;
        return $this;
    }

    // --- Query state ---

    /**
     * Check, if current state is "Edit existing confirmation message" and this is concrete message defined by argument.
     * @param int|null $checkingConfirmationId
     * @return bool
     */
    public function isEditingConfirmationId(?int $checkingConfirmationId): bool
    {
        return $checkingConfirmationId
            && $this->isEditingExisting()
            && $this->editingConfirmationId === $checkingConfirmationId;
    }

    /**
     * Check, if current state is "Edit existing confirmation message".
     * @return bool
     */
    public function isEditingExisting(): bool
    {
        return $this->status === self::ST_EDIT;
    }

    /**
     * Check, if current state is "Add new confirmation message".
     * @return bool
     */
    public function isAddingNew(): bool
    {
        return $this->status === self::ST_ADD;
    }

    /**
     * Return id of editing confirmation message.
     * @return int|null - null during creating of new confirmation message.
     */
    public function editingConfirmationId(): ?int
    {
        return $this->editingConfirmationId;
    }
}
