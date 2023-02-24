<?php
/**
 * SAM-3621: category filtering in advanced search results
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Tree;


/**
 * Trait LotCategoryTreeLotsQuantityManagerCreateTrait
 * @package Sam\Lot\Category\Tree
 */
trait LotCategoryTreeLotsQuantityManagerCreateTrait
{
    protected ?LotCategoryTreeLotsQuantityManager $lotCategoryTreeLotsQuantityManager = null;

    /**
     * @return LotCategoryTreeLotsQuantityManager
     */
    protected function createLotCategoryTreeLotsQuantityManager(): LotCategoryTreeLotsQuantityManager
    {
        return $this->lotCategoryTreeLotsQuantityManager ?: LotCategoryTreeLotsQuantityManager::new();
    }

    /**
     * @param LotCategoryTreeLotsQuantityManager $lotCategoryTreeLotsQuantityManager
     * @return static
     * @internal
     */
    public function setLotCategoryTreeLotsQuantityManager(LotCategoryTreeLotsQuantityManager $lotCategoryTreeLotsQuantityManager): static
    {
        $this->lotCategoryTreeLotsQuantityManager = $lotCategoryTreeLotsQuantityManager;
        return $this;
    }
}
