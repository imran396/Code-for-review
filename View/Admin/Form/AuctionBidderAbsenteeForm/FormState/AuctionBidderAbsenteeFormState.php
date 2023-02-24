<?php
/**
 * Value-object that describes state changing of the "Auction Bidder Absentee Bids" form.
 * Form can be in two states: "Add new bid" or "Edit existing bid". When existing bid is edited, we must define its id.
 *
 * SAM-9530: "User Absentee Bid" page - extract logic and cover with unit test for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm\FormState;

use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;

/**
 * Class AuctionBidderAbsenteeFormState
 * @package Sam\View\Admin\Form\AuctionBidderAbsenteeForm\FormState
 */
class AuctionBidderAbsenteeFormState extends CustomizableClass
{
    use FormStateLongevityAwareTrait;

    protected const ST_ADD = 1;
    protected const ST_EDIT = 2;

    protected ?int $editingAbsenteeBidId = null;
    /**
     * Form can be in two states: "Add new bid" or "Edit existing bid"
     */
    protected int $status = self::ST_ADD;

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
     * Change state to "Edit existing bid".
     * @param int $absenteeBidId
     * @return $this
     */
    public function toEdit(int $absenteeBidId): static
    {
        $this->editingAbsenteeBidId = $absenteeBidId;
        $this->status = self::ST_EDIT;
        return $this;
    }

    /**
     * Change state to "Add new bid".
     * @return $this
     */
    public function toAddNew(): static
    {
        $this->editingAbsenteeBidId = null;
        $this->status = self::ST_ADD;
        return $this;
    }

    // --- Query state ---

    /**
     * Check, if current state is "Edit existing bid" and this is concrete bid defined by argument.
     * @param int|null $checkingAbsenteeBidId
     * @return bool
     */
    public function isEditingAbsenteeBidId(?int $checkingAbsenteeBidId): bool
    {
        return $checkingAbsenteeBidId
            && $this->isEditingExisting()
            && $this->editingAbsenteeBidId === $checkingAbsenteeBidId;
    }

    /**
     * Check, if current state is "Edit existing bid".
     * @return bool
     */
    public function isEditingExisting(): bool
    {
        return $this->status === self::ST_EDIT;
    }

    /**
     * Check, if current state is "Add new bid".
     * @return bool
     */
    public function isAddingNew(): bool
    {
        return $this->status === self::ST_ADD;
    }

    /**
     * Return id of editing bid.
     * @return int|null null when no bid is editing.
     */
    public function editingAbsenteeBidId(): ?int
    {
        return $this->editingAbsenteeBidId;
    }

}
