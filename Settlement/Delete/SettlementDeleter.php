<?php
/**
 * SAM-4558: Settlement Deleter module
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/18/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Load\SettlementLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\SettlementItem\SettlementItemReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\Settlement\SettlementWriteRepositoryAwareTrait;
use Settlement;

/**
 * Class SettlementDeleter
 * @package Sam\Settlement\Delete
 */
class SettlementDeleter extends CustomizableClass
{
    use SettlementItemDeleterAwareTrait;
    use SettlementItemReadRepositoryCreateTrait;
    use SettlementLoaderAwareTrait;
    use SettlementWriteRepositoryAwareTrait;

    protected bool $isItemsDeleting = true;
    protected ?string $successMessage = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete Settlement
     * @param Settlement $settlement
     * @param int $editorUserId
     */
    public function delete(Settlement $settlement, int $editorUserId): void
    {
        $settlement->toDeleted();
        $this->getSettlementWriteRepository()->saveWithModifier($settlement, $editorUserId);
        if ($this->isItemsDeleting()) {
            $this->deleteItemsBySettlementId($settlement->Id, $editorUserId);
        }
        $this->successMessage = "Settlement [{$settlement->SettlementNo}] has been deleted";
    }

    /**
     * Get Success Message
     * @return string|null
     */
    public function getSuccessMessage(): ?string
    {
        return $this->successMessage;
    }

    /**
     * Delete Settlement By ID
     * @param int $settlementId
     * @param int $editorUserId
     */
    public function deleteById(int $settlementId, int $editorUserId): void
    {
        $settlement = $this->getSettlementLoader()->load($settlementId, true);
        if ($settlement) {
            $this->delete($settlement, $editorUserId);
        }
    }

    /**
     * @return bool
     */
    public function isItemsDeleting(): bool
    {
        return $this->isItemsDeleting;
    }

    /**
     * @param bool $isItemsDeleting
     * @return static
     */
    public function enableItemsDeleting(bool $isItemsDeleting): static
    {
        $this->isItemsDeleting = $isItemsDeleting;
        return $this;
    }

    /**
     * Delete settlement items of settlement
     * @param int $settlementId
     * @param int $editorUserId
     */
    protected function deleteItemsBySettlementId(int $settlementId, int $editorUserId): void
    {
        $settlementItems = $this->createSettlementItemReadRepository()
            ->filterSettlementId($settlementId)
            ->filterActive(true)
            ->loadEntities();
        foreach ($settlementItems as $settlementItem) {
            $this->getSettlementItemDeleter()->delete($settlementItem, $editorUserId);
        }
    }
}
