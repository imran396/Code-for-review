<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\TaxDefinitionEditForm\RatePanel;

/**
 * Trait TaxDefinitionRatePanelRangeTableStateManagerAwareTrait
 * @package Sam\View\Admin\Form\TaxDefinitionEditForm\RatePanel
 */
trait TaxDefinitionRatePanelRangeTableStateManagerAwareTrait
{
    protected ?TaxDefinitionRatePanelRangeTableStateManager $taxDefinitionRatePanelRangeTableStateManager = null;

    /**
     * @return TaxDefinitionRatePanelRangeTableStateManager
     */
    protected function getTaxDefinitionRatePanelRangeTableStateManager(): TaxDefinitionRatePanelRangeTableStateManager
    {
        if ($this->taxDefinitionRatePanelRangeTableStateManager === null) {
            $this->taxDefinitionRatePanelRangeTableStateManager = TaxDefinitionRatePanelRangeTableStateManager::new();
        }
        return $this->taxDefinitionRatePanelRangeTableStateManager;
    }

    /**
     * @param TaxDefinitionRatePanelRangeTableStateManager $taxDefinitionRatePanelRangeTableStateManager
     * @return static
     * @internal
     */
    public function setTaxDefinitionRatePanelRangeTableStateManager(TaxDefinitionRatePanelRangeTableStateManager $taxDefinitionRatePanelRangeTableStateManager): static
    {
        $this->taxDefinitionRatePanelRangeTableStateManager = $taxDefinitionRatePanelRangeTableStateManager;
        return $this;
    }
}
