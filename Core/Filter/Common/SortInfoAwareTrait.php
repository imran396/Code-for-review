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

namespace Sam\Core\Filter\Common;

/**
 * Trait SortInfoAwareTrait
 * @package Sam\Core\Filter\Common
 */
trait SortInfoAwareTrait
{
    protected string $sortColumn = '';
    protected int|null $sortColumnIndex = null;
    protected bool $isAscendingOrder = true;

    /**
     * @return string
     */
    public function getSortColumn(): string
    {
        return $this->sortColumn;
    }

    /**
     * @param string|null $sortColumn
     * @return static
     */
    public function setSortColumn(?string $sortColumn): static
    {
        $this->sortColumn = (string)$sortColumn;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSortColumnIndex(): ?int
    {
        return $this->sortColumnIndex;
    }

    /**
     * @param int|null $sortColumnIndex
     * @return static
     */
    public function setSortColumnIndex(?int $sortColumnIndex): static
    {
        $this->sortColumnIndex = $sortColumnIndex; // any integer number
        return $this;
    }

    /**
     * Return value for QCodo 'dir' parameter
     * @return int
     */
    public function getSortDirection(): int
    {
        return $this->isAscendingOrder ? 0 : 1;
    }

    /**
     * Should be used for QCodo 'dir' parameter only
     * @param int $sortDirection
     * @return static
     */
    public function setSortDirection(int $sortDirection): static
    {
        $this->enableAscendingOrder($sortDirection === 0);
        return $this;
    }

    /**
     * @return bool
     */
    public function isAscendingOrder(): bool
    {
        return $this->isAscendingOrder;
    }

    /**
     * @param bool $isAscending
     * @return static
     */
    public function enableAscendingOrder(bool $isAscending): static
    {
        $this->isAscendingOrder = $isAscending;
        return $this;
    }
}
