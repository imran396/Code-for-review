<?php
/**
 * Lot Item Cust Fields Data Loader Create Trait
 *
 * SAM-6237: Refactor Lot Custom Field List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 25, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotCustomFieldListForm\Load;

/**
 * Trait LotCustomFieldListDataLoaderCreateTrait
 */
trait LotCustomFieldListDataLoaderCreateTrait
{
    protected ?LotCustomFieldListDataLoader $lotCustomFieldListDataLoader = null;

    /**
     * @return LotCustomFieldListDataLoader
     */
    protected function createLotCustomFieldListDataLoader(): LotCustomFieldListDataLoader
    {
        $lotCustomFieldListDataLoader = $this->lotCustomFieldListDataLoader ?: LotCustomFieldListDataLoader::new();
        return $lotCustomFieldListDataLoader;
    }

    /**
     * @param LotCustomFieldListDataLoader $lotCustomFieldListDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setLotCustomFieldListDataLoader(LotCustomFieldListDataLoader $lotCustomFieldListDataLoader): static
    {
        $this->lotCustomFieldListDataLoader = $lotCustomFieldListDataLoader;
        return $this;
    }
}
