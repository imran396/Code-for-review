<?php
/**
 * SAM-10203: Hide information about current state of entity in value-object for code that handles /admin/manage-settlements/view/id/<SettlementId> page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-16, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementItemForm\Actions\State;

use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;

/**
 * Class SettlementItemFormManagingState
 * @package Sam\View\Admin\Form\SettlementItemForm\Actions\State
 */
class SettlementItemFormManagingState extends CustomizableClass
{
    use FormStateLongevityAwareTrait;

    protected const ST_EDIT = 1;
    protected const ST_VIEW = 2;

    /**
     * We block settlement actions, when it contains deleted user, lots, auctions. SAM-4829
     * @var bool
     */
    protected bool $isOperable = false;

    /**
     * View is for reading state of page, Edit is for editing state of page.
     * @var int
     */
    protected int $status = self::ST_VIEW;

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isOperable is settlement available or not for user actions, e.g. editing, e-mailing.
     * @return $this
     */
    public function construct(bool $isOperable): static
    {
        $this->enableOperable($isOperable);
        return $this;
    }

    // --- Mutate logic ---

    public function enableOperable(bool $isOperable): static
    {
        $this->isOperable = $isOperable;
        if (!$isOperable) {
            $this->toView();
        }
        return $this;
    }

    /**
     * Change state to "View", when settlement values are available for read.
     * @return $this
     */
    public function toView(): static
    {
        $this->status = self::ST_VIEW;
        return $this;
    }

    /**
     * Change state to "Editing" entity.
     * @return $this
     */
    public function toEdit(): static
    {
        $this->status = self::ST_EDIT;
        return $this;
    }

    // --- Query logic ---

    /**
     * Check, if settlement is available for user actions.
     * We block settlement actions, when it contains deleted user, lots, auctions. SAM-4829
     * @return bool
     */
    public function isOperable(): bool
    {
        return $this->isOperable;
    }

    /**
     * Check if settlement page in view state. (by default)
     * @return bool
     */
    public function isViewing(): bool
    {
        return $this->status === self::ST_VIEW;
    }

    /**
     * Checks if settlement page is in editing state.
     * @return bool
     */
    public function isEditing(): bool
    {
        if (!$this->isOperable) {
            // JIC. When settlement is non-operable, it cannot be editable.
            return false;
        }

        return $this->status === self::ST_EDIT;
    }
}
