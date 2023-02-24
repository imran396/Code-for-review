<?php
/**
 * SAM-10782: Create in Admin Web the "Tax Definition List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\TaxDefinitionListForm\Load;

/**
 * Trait TaxDefinitionListDataLoaderCreateTrait
 * @package Sam\View\Admin\Form\TaxDefinitionListForm\Load
 */
trait TaxDefinitionListDataLoaderCreateTrait
{
    protected ?TaxDefinitionListDataLoader $taxDefinitionListDataLoader = null;

    /**
     * @return TaxDefinitionListDataLoader
     */
    protected function createTaxDefinitionListDataLoader(): TaxDefinitionListDataLoader
    {
        return $this->taxDefinitionListDataLoader ?: TaxDefinitionListDataLoader::new();
    }

    /**
     * @param TaxDefinitionListDataLoader $taxDefinitionListDataLoader
     * @return static
     * @internal
     */
    public function setTaxDefinitionListDataLoader(TaxDefinitionListDataLoader $taxDefinitionListDataLoader): static
    {
        $this->taxDefinitionListDataLoader = $taxDefinitionListDataLoader;
        return $this;
    }
}
