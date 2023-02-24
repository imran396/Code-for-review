<?php
/**
 * SAM-10787: Create in Admin Web the "Tax Schema List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\TaxSchemaListForm\Load;

/**
 * Trait TaxSchemaListDataLoaderCreateTrait
 * @package Sam\View\Admin\Form\TaxSchemaListForm\Load
 */
trait TaxSchemaListDataLoaderCreateTrait
{
    protected ?TaxSchemaListDataLoader $taxSchemaListDataLoader = null;

    /**
     * @return TaxSchemaListDataLoader
     */
    protected function createTaxSchemaListDataLoader(): TaxSchemaListDataLoader
    {
        return $this->taxSchemaListDataLoader ?: TaxSchemaListDataLoader::new();
    }

    /**
     * @param TaxSchemaListDataLoader $taxSchemaListDataLoader
     * @return static
     * @internal
     */
    public function setTaxSchemaListDataLoader(TaxSchemaListDataLoader $taxSchemaListDataLoader): static
    {
        $this->taxSchemaListDataLoader = $taxSchemaListDataLoader;
        return $this;
    }
}
