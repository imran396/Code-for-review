<?php
/**
 * SAM-8004: Refactor \Util_Storage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper;

/**
 * Trait PaginationItemsPerPageStorageCreateTrait
 * @package Sam\View\Admin\ViewHelper
 */
trait PaginationItemsPerPageStorageCreateTrait
{
    protected ?PaginationItemsPerPageStorage $paginationItemsPerPageStorage = null;

    /**
     * @return PaginationItemsPerPageStorage
     */
    protected function createPaginationItemsPerPageStorage(): PaginationItemsPerPageStorage
    {
        return $this->paginationItemsPerPageStorage ?: PaginationItemsPerPageStorage::new();
    }

    /**
     * @param PaginationItemsPerPageStorage $paginationItemsPerPageStorage
     * @return static
     * @internal
     */
    public function setPaginationItemsPerPageStorage(PaginationItemsPerPageStorage $paginationItemsPerPageStorage): static
    {
        $this->paginationItemsPerPageStorage = $paginationItemsPerPageStorage;
        return $this;
    }
}
