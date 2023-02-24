<?php
/**
 * SAM-6717: Refactor assigned sales label at Lot Item Edit page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 25, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\AssignedSales\Render\Internal\Load;


/**
 * Trait LotInfoAssignedSalesDataLoaderCreateTrait
 * @package Sam\View\Admin\Form\LotInfoPanel\AssignedSales\Render\Internal\Load
 */
trait LotInfoAssignedSalesDataLoaderCreateTrait
{
    protected ?LotInfoAssignedSalesDataLoader $dataLoader = null;

    /**
     * @return LotInfoAssignedSalesDataLoader
     */
    protected function createLotInfoAssignedSalesDataLoader(): LotInfoAssignedSalesDataLoader
    {
        return $this->dataLoader ?: LotInfoAssignedSalesDataLoader::new();
    }

    /**
     * @param LotInfoAssignedSalesDataLoader $dataLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotInfoAssignedSalesDataLoader(LotInfoAssignedSalesDataLoader $dataLoader): static
    {
        $this->dataLoader = $dataLoader;
        return $this;
    }
}
