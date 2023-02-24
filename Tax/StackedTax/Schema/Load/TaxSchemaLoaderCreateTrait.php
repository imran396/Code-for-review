<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 11, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Load;

/**
 * Trait TaxSchemaLoaderCreateTrait
 * @package Sam\Tax\StackedTax\Schema\Load
 */
trait TaxSchemaLoaderCreateTrait
{
    protected ?TaxSchemaLoader $taxSchemaLoader = null;

    /**
     * @return TaxSchemaLoader
     */
    protected function createTaxSchemaLoader(): TaxSchemaLoader
    {
        return $this->taxSchemaLoader ?: TaxSchemaLoader::new();
    }

    /**
     * @param TaxSchemaLoader $taxSchemaLoader
     * @return static
     * @internal
     */
    public function setTaxSchemaLoader(TaxSchemaLoader $taxSchemaLoader): static
    {
        $this->taxSchemaLoader = $taxSchemaLoader;
        return $this;
    }
}
