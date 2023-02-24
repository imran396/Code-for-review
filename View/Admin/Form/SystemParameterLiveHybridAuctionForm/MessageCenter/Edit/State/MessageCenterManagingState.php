<?php
/**
 * SAM-10008: Move sections' logic to separate Panel classes at Manage settings system parameters live/hybrid auction page (/admin/manage-system-parameter/live-hybrid-auction)
 *
 * Value-object that describes state changing of the "Message center" data-grid.
 * Data-grid can be in two states: "Add new message" or "Edit existing message". When existing message is edited, we must define its id.
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-03, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SystemParameterLiveHybridAuctionForm\MessageCenter\Edit\State;

use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;

/**
 * Class MessageCenterManagingState
 * @package Sam\View\Admin\Form\SystemParameterLiveHybridAuctionForm\MessageCenter
 */
class MessageCenterManagingState extends CustomizableClass
{
    use FormStateLongevityAwareTrait;

    protected const ST_ADD = 1;
    protected const ST_EDIT = 2;
    protected const ST_NONE = 3;

    protected ?int $editingMessageId = null;
    /**
     * Can be in three states: "Add new message" or "Edit existing message" or "None" (by default)
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
     * Change state to "Edit existing message".
     * @param int $messageId
     * @return $this
     */
    public function toEdit(int $messageId): static
    {
        $this->editingMessageId = $messageId;
        $this->status = self::ST_EDIT;
        return $this;
    }

    /**
     * Change state to "Add new message".
     * @return $this
     */
    public function toAddNew(): static
    {
        $this->editingMessageId = null;
        $this->status = self::ST_ADD;
        return $this;
    }

    public function toNone(): static
    {
        $this->editingMessageId = null;
        $this->status = self::ST_NONE;
        return $this;
    }

    // --- Query state ---

    /**
     * Check, if current state is "Edit existing message" and this is concrete message defined by argument.
     * @param int|null $checkingMessageId
     * @return bool
     */
    public function isEditingMessageId(?int $checkingMessageId): bool
    {
        return $checkingMessageId
            && $this->isEditingExisting()
            && $this->editingMessageId === $checkingMessageId;
    }

    /**
     * Check, if current state is "Edit existing message".
     * @return bool
     */
    public function isEditingExisting(): bool
    {
        return $this->status === self::ST_EDIT;
    }

    /**
     * Check, if current state is "Add new message".
     * @return bool
     */
    public function isAddingNew(): bool
    {
        return $this->status === self::ST_ADD;
    }

    /**
     * Return id of editing message.
     * @return int|null - null during creating of new message.
     */
    public function editingMessageId(): ?int
    {
        return $this->editingMessageId;
    }
}
