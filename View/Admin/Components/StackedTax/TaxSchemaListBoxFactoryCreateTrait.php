<?php
/**
 * SAM-11014: Stacked Tax. Invoice settings management. Add tax schema at account level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Components\StackedTax;

/**
 * Trait TaxSchemaListBoxFactoryCreateTrait
 * @package Sam\View\Admin\Components\StackedTax
 */
trait TaxSchemaListBoxFactoryCreateTrait
{
    protected ?TaxSchemaListBoxFactory $taxSchemaListBoxFactory = null;

    /**
     * @return TaxSchemaListBoxFactory
     */
    protected function createTaxSchemaListBoxFactory(): TaxSchemaListBoxFactory
    {
        return $this->taxSchemaListBoxFactory ?: TaxSchemaListBoxFactory::new();
    }

    /**
     * @param TaxSchemaListBoxFactory $taxSchemaListBoxFactory
     * @return static
     * @internal
     */
    public function setTaxSchemaListBoxFactory(TaxSchemaListBoxFactory $taxSchemaListBoxFactory): static
    {
        $this->taxSchemaListBoxFactory = $taxSchemaListBoxFactory;
        return $this;
    }
}
