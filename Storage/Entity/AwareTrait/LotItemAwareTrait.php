<?php
/**
 * SAM-4819: Entity aware traits
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/14/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\AwareTrait;

use Sam\Storage\Entity\Aggregate\LotItemAggregate;
use LotItem;

/**
 * Trait LotItemAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
trait LotItemAwareTrait
{
    protected ?LotItemAggregate $lotItemAggregate = null;

    /**
     * @return int|null
     */
    public function getLotItemId(): ?int
    {
        return $this->getLotItemAggregate()->getLotItemId();
    }

    /**
     * @param int|null $lotItemId
     * @return static
     */
    public function setLotItemId(?int $lotItemId): static
    {
        $this->getLotItemAggregate()->setLotItemId($lotItemId);
        return $this;
    }

    // --- LotItem ---

    /**
     * Return LotItem|null object
     * @param bool $isReadOnlyDb
     * @return LotItem|null
     */
    public function getLotItem(bool $isReadOnlyDb = false): ?LotItem
    {
        return $this->getLotItemAggregate()->getLotItem($isReadOnlyDb);
    }

    /**
     * @param LotItem|null $lotItem
     * @return static
     */
    public function setLotItem(?LotItem $lotItem): static
    {
        $this->getLotItemAggregate()->setLotItem($lotItem);
        return $this;
    }

    // --- LotItemAggregate ---

    /**
     * @return LotItemAggregate
     */
    protected function getLotItemAggregate(): LotItemAggregate
    {
        if ($this->lotItemAggregate === null) {
            $this->lotItemAggregate = LotItemAggregate::new();
        }
        return $this->lotItemAggregate;
    }
}
