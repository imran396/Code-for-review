<?php
/**
 * SAM-10823: Stacked Tax. Location reference with Tax Schema (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\View\Admin\Form\LocationEditForm\TaxSchema\Load;

/**
 * Trait TaxSchemaDataLoaderCreateTrait
 * @package Sam\View\Admin\Form\LocationEditForm\TaxSchema\Load
 */
trait TaxSchemaDataLoaderCreateTrait
{
    protected ?TaxSchemaDataLoader $taxSchemaDataLoader = null;

    /**
     * @return TaxSchemaDataLoader
     */
    protected function createTaxSchemaDataLoader(): TaxSchemaDataLoader
    {
        return $this->taxSchemaDataLoader ?: TaxSchemaDataLoader::new();
    }

    /**
     * @param TaxSchemaDataLoader $taxSchemaDataLoader
     * @return $this
     * @internal
     */
    public function setTaxSchemaDataLoader(TaxSchemaDataLoader $taxSchemaDataLoader): static
    {
        $this->taxSchemaDataLoader = $taxSchemaDataLoader;
        return $this;
    }
}
