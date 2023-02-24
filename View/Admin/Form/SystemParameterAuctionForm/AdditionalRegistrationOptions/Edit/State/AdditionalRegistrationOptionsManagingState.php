<?php
/**
 * SAM-10185: Hide information about current state of entity in value-object for code that handles /admin/manage-system-parameter/auction page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-09, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SystemParameterAuctionForm\AdditionalRegistrationOptions\Edit\State;

use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;

/**
 * Class AdditionalRegistrationOptionsManagingState
 * @package Sam\View\Admin\Form\SystemParameterAuctionForm\AdditionalRegistrationOptions\Edit\State
 */
class AdditionalRegistrationOptionsManagingState extends CustomizableClass
{
    use FormStateLongevityAwareTrait;

    protected const ST_ADD = 1;
    protected const ST_EDIT = 2;
    protected const ST_NONE = 3;

    protected ?int $editingOptionId = null;
    /**
     * Can be in three states: "Add new auction registration option" or "Edit existing auction registration option" or "None" (by default)
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
     * Change state to "Edit existing auction registration option".
     * @param int $optionId
     * @return $this
     */
    public function toEdit(int $optionId): static
    {
        $this->editingOptionId = $optionId;
        $this->status = self::ST_EDIT;
        return $this;
    }

    /**
     * Change state to "Add new auction registration option".
     * @return $this
     */
    public function toAddNew(): static
    {
        $this->editingOptionId = null;
        $this->status = self::ST_ADD;
        return $this;
    }

    public function toNone(): static
    {
        $this->editingOptionId = null;
        $this->status = self::ST_NONE;
        return $this;
    }

    // --- Query state ---

    /**
     * Check, if current state is "Edit existing auction registration option" and this is concrete ARO defined by argument.
     * @param int|null $checkingOptionId
     * @return bool
     */
    public function isEditingOptionId(?int $checkingOptionId): bool
    {
        return $checkingOptionId
            && $this->isEditingExisting()
            && $this->editingOptionId === $checkingOptionId;
    }

    /**
     * Check, if current state is "Edit existing auction registration option".
     * @return bool
     */
    public function isEditingExisting(): bool
    {
        return $this->status === self::ST_EDIT;
    }

    /**
     * Check, if current state is "Add new auction registration option".
     * @return bool
     */
    public function isAddingNew(): bool
    {
        return $this->status === self::ST_ADD;
    }

    /**
     * Return id of editing auction registration option.
     * @return int|null - null during creating of new message.
     */
    public function editingOptionId(): ?int
    {
        return $this->editingOptionId;
    }
}
