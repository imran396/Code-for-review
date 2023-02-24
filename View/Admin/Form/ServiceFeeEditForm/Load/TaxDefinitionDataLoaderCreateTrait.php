<?php
/**
 * SAM-11239: Stacked Tax. Store invoice tax amounts per definition
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ServiceFeeEditForm\Load;

/**
 * Trait TaxDefinitionDataLoaderCreateTrait
 * @package Sam\View\Admin\Form\ServiceFeeEditForm\Load
 */
trait TaxDefinitionDataLoaderCreateTrait
{
    protected ?TaxDefinitionDataLoader $taxDefinitionDataLoader = null;

    /**
     * @return TaxDefinitionDataLoader
     */
    protected function createTaxDefinitionDataLoader(): TaxDefinitionDataLoader
    {
        return $this->taxDefinitionDataLoader ?: TaxDefinitionDataLoader::new();
    }

    /**
     * @param TaxDefinitionDataLoader $taxDefinitionDataLoader
     * @return static
     * @internal
     */
    public function setTaxDefinitionDataLoader(TaxDefinitionDataLoader $taxDefinitionDataLoader): static
    {
        $this->taxDefinitionDataLoader = $taxDefinitionDataLoader;
        return $this;
    }
}
