<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ConsignorCommissionFeeEditForm;


/**
 * Trait ConsignorCommissionFeePanelRangeTableStateManagerAwareTrait
 * @package Sam\View\Admin\Form\ConsignorCommissionFeeEditForm
 */
trait ConsignorCommissionFeePanelRangeTableStateManagerAwareTrait
{
    protected ?ConsignorCommissionFeePanelRangeTableStateManager $consignorCommissionFeePanelRangeTableStateManager = null;

    /**
     * @return ConsignorCommissionFeePanelRangeTableStateManager
     */
    protected function getConsignorCommissionFeePanelRangeTableStateManager(): ConsignorCommissionFeePanelRangeTableStateManager
    {
        if ($this->consignorCommissionFeePanelRangeTableStateManager === null) {
            $this->consignorCommissionFeePanelRangeTableStateManager = ConsignorCommissionFeePanelRangeTableStateManager::new();
        }
        return $this->consignorCommissionFeePanelRangeTableStateManager;
    }

    /**
     * @param ConsignorCommissionFeePanelRangeTableStateManager $consignorCommissionFeePanelRangeTableStateManager
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeePanelRangeTableStateManager(ConsignorCommissionFeePanelRangeTableStateManager $consignorCommissionFeePanelRangeTableStateManager): static
    {
        $this->consignorCommissionFeePanelRangeTableStateManager = $consignorCommissionFeePanelRangeTableStateManager;
        return $this;
    }
}
