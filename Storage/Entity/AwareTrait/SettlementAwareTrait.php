<?php
/**
 * SAM-4819: Entity aware traits
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/24/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\AwareTrait;

use Settlement;
use SettlementItem;
use Sam\Storage\Entity\Aggregate\SettlementAggregate;

/**
 * Trait SettlementAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
trait SettlementAwareTrait
{
    protected ?SettlementAggregate $settlementAggregate = null;

    /**
     * Return Id of Settlement
     * @return int|null
     */
    public function getSettlementId(): ?int
    {
        return $this->getSettlementAggregate()->getSettlementId();
    }

    /**
     * Set Id of Settlement
     * @param int|null $settlementId null means absent entity
     * @return static
     */
    public function setSettlementId(?int $settlementId): static
    {
        $this->getSettlementAggregate()->setSettlementId($settlementId);
        return $this;
    }

    /**
     * Return Settlement object
     * @param bool $isReadOnlyDb
     * @return Settlement|null
     */
    public function getSettlement(bool $isReadOnlyDb = false): ?Settlement
    {
        return $this->getSettlementAggregate()->getSettlement($isReadOnlyDb);
    }

    /**
     * Set Settlement object
     * @param Settlement|null $settlement
     * @return static
     */
    public function setSettlement(?Settlement $settlement): static
    {
        $this->getSettlementAggregate()->setSettlement($settlement);
        return $this;
    }

    // --- SettlementItem[] ---

    /**
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function getSettlementItemIds(bool $isReadOnlyDb = false): array
    {
        return $this->getSettlementAggregate()->getSettlementItemIds($isReadOnlyDb);
    }

    /**
     * Return array of active settlement items. By default it loads all items, including deleted
     * @param bool $isReadOnlyDb
     * @return SettlementItem[]
     */
    public function getSettlementItems(bool $isReadOnlyDb = false): array
    {
        return $this->getSettlementAggregate()->getSettlementItems($isReadOnlyDb);
    }

    /**
     * Find SettlementItem in array by id
     * @param int $settlementItemId
     * @param bool $isReadOnlyDb
     * @return SettlementItem|null
     */
    public function getSettlementItemById(int $settlementItemId, bool $isReadOnlyDb = false): ?SettlementItem
    {
        return $this->getSettlementAggregate()->getSettlementItemById($settlementItemId, $isReadOnlyDb);
    }

    /**
     * @param array|null $settlementItems
     * @return static
     */
    public function setSettlementItems(?array $settlementItems): static
    {
        $this->getSettlementAggregate()->setSettlementItems($settlementItems);
        return $this;
    }

    // --- SettlementAggregate ---

    /**
     * @return SettlementAggregate
     */
    protected function getSettlementAggregate(): SettlementAggregate
    {
        if ($this->settlementAggregate === null) {
            $this->settlementAggregate = SettlementAggregate::new();
        }
        return $this->settlementAggregate;
    }
}
