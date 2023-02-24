<?php
/**
 * SAM-10238: Hide information about current state of entity in value-object for code that handles /admin/manage-inventory/edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-30, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoForm\Edit\State;

use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;

/**
 * Class LotInfoFormManagingState
 * @package Sam\View\Admin\Form\LotInfoForm\Edit\State
 */
class LotInfoFormManagingState extends CustomizableClass
{
    use FormStateLongevityAwareTrait;

    protected const ST_ADD = 1;
    protected const ST_EDIT = 2;
    protected const ST_NONE = 3;

    protected ?int $editingLotItemId;

    /**
     * Can be in three states: "Add new lot item" or "Edit existing lot item" or "None" (by default)
     * @var int
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
     * Change state to "Edit existing lot item".
     * @param int $lotItemId
     * @return $this
     */
    public function toEdit(int $lotItemId): static
    {
        $this->editingLotItemId = $lotItemId;
        $this->status = self::ST_EDIT;
        return $this;
    }

    /**
     * Change state to "Add new lot item".
     * @return $this
     */
    public function toAddNew(): static
    {
        $this->editingLotItemId = null;
        $this->status = self::ST_ADD;
        return $this;
    }

    public function toNone(): static
    {
        $this->editingLotItemId = null;
        $this->status = self::ST_NONE;
        return $this;
    }

    // --- Query state ---

    /**
     * Check, if current state is "Edit existing lot item" and this is concrete lot item defined by argument.
     * @param int|null $checkingLotItemId
     * @return bool
     */
    public function isEditingLotItem(?int $checkingLotItemId): bool
    {
        return $checkingLotItemId
            && $this->isEditingExisting()
            && $this->editingLotItemId === $checkingLotItemId;
    }

    /**
     * Check, if current state is "Edit existing lot item".
     * @return bool
     */
    public function isEditingExisting(): bool
    {
        return $this->status === self::ST_EDIT;
    }

    /**
     * Check, if current state is "Add new lot item".
     * @return bool
     */
    public function isAddingNew(): bool
    {
        return $this->status === self::ST_ADD;
    }

    /**
     * Return id of editing confirmation message.
     * @return int|null - null during creating of new lot item.
     */
    public function editingLotItemId(): ?int
    {
        return $this->editingLotItemId;
    }
}
