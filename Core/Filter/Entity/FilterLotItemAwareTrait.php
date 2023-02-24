<?php
/**
 * SAM-4616: Reports code refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/28/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\Entity;

use LotItem;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Storage\Entity\Aggregate\LotItemAggregate;

/**
 * Trait FilterLotItemAwareTrait
 * @package Sam\Core\Filter\Entity
 */
trait FilterLotItemAwareTrait
{
    /** @var LotItemAggregate[] */
    protected array $filterLotItemAggregates = [];

    /**
     * Return LotItem id as int or ids as int[]
     * @return int|int[]|null
     */
    public function getFilterLotItemId(): int|array|null
    {
        $lotItemIds = [];
        foreach ($this->getFilterLotItemAggregates() as $lotItemAggregate) {
            $lotItemIds[] = $lotItemAggregate->getLotItemId();
        }
        $returnValue = empty($lotItemIds) ? null
            : (count($lotItemIds) === 1 ? $lotItemIds[0] : $lotItemIds);
        return $returnValue;
    }

    /**
     * @param int|int[]|null $lotItemIds
     * @return static
     */
    public function filterLotItemId(int|array|null $lotItemIds): static
    {
        $this->initFilterLotItemAggregates($lotItemIds);
        return $this;
    }

    /**
     * @return LotItem|LotItem[]|null
     */
    public function getFilterLotItem(): LotItem|array|null
    {
        $lotItems = [];
        foreach ($this->getFilterLotItemAggregates() as $lotItemAggregate) {
            $lotItems[] = $lotItemAggregate->getLotItem();
        }
        $returnValue = empty($lotItems) ? null
            : (count($lotItems) === 1 ? $lotItems[0] : $lotItems);
        return $returnValue;
    }

    /**
     * @param LotItem|LotItem[]|null $lotItems
     * @return static
     */
    public function filterLotItem(LotItem|array|null $lotItems = null): static
    {
        $this->initFilterLotItemAggregates($lotItems);
        return $this;
    }

    // --- LotItemAggregate ---

    /**
     * @return LotItemAggregate[]
     */
    protected function getFilterLotItemAggregates(): array
    {
        return $this->filterLotItemAggregates;
    }

    /**
     * @param int|int[]|LotItem|LotItem[]|null $lotItems
     */
    protected function initFilterLotItemAggregates(LotItem|int|array|null $lotItems): void
    {
        $this->filterLotItemAggregates = [];
        if (empty($lotItems)) {
            return;
        }
        $lotItems = is_array($lotItems) ? $lotItems : [$lotItems];
        if (!($lotItems[0] instanceof LotItem)) {
            $lotItemIds = ArrayCast::makeIntArray($lotItems);
            foreach ($lotItemIds as $lotItemId) {
                $this->filterLotItemAggregates[$lotItemId] = LotItemAggregate::new()->setLotItemId($lotItemId);
            }
        } else {
            foreach ($lotItems as $lotItem) {
                $this->filterLotItemAggregates[$lotItem->Id] = LotItemAggregate::new()->setLotItem($lotItem);
            }
        }
    }
}
