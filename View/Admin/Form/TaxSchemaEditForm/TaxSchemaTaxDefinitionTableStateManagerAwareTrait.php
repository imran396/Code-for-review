<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\TaxSchemaEditForm;


/**
 * Trait TaxSchemaTaxDefinitionTableStateManagerAwareTrait
 * @package Sam\View\Admin\Form\TaxSchemaEditForm
 */
trait TaxSchemaTaxDefinitionTableStateManagerAwareTrait
{
    protected ?TaxSchemaTaxDefinitionTableStateManager $taxSchemaTaxDefinitionTableStateManager = null;

    /**
     * @return TaxSchemaTaxDefinitionTableStateManager
     */
    protected function getTaxSchemaTaxDefinitionTableStateManager(): TaxSchemaTaxDefinitionTableStateManager
    {
        if ($this->taxSchemaTaxDefinitionTableStateManager === null) {
            $this->taxSchemaTaxDefinitionTableStateManager = TaxSchemaTaxDefinitionTableStateManager::new();
        }
        return $this->taxSchemaTaxDefinitionTableStateManager;
    }

    /**
     * @param TaxSchemaTaxDefinitionTableStateManager $taxSchemaTaxDefinitionTableStateManager
     * @return static
     * @internal
     */
    public function setTaxSchemaTaxDefinitionTableStateManager(TaxSchemaTaxDefinitionTableStateManager $taxSchemaTaxDefinitionTableStateManager): static
    {
        $this->taxSchemaTaxDefinitionTableStateManager = $taxSchemaTaxDefinitionTableStateManager;
        return $this;
    }
}
