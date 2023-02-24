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

namespace Sam\View\Admin\Form\ConsignorCommissionFeeEditForm\Internal\Load;

/**
 * Trait ConsignorCommissionFeePanelLoaderCreateTrait
 * @package Sam\View\Admin\Form\ConsignorCommissionFeeEditForm\Load
 * @internal
 */
trait ConsignorCommissionFeePanelLoaderCreateTrait
{
    protected ?ConsignorCommissionFeePanelLoader $consignorCommissionFeePanelLoader = null;

    /**
     * @return ConsignorCommissionFeePanelLoader
     */
    protected function createConsignorCommissionFeePanelLoader(): ConsignorCommissionFeePanelLoader
    {
        return $this->consignorCommissionFeePanelLoader ?: ConsignorCommissionFeePanelLoader::new();
    }

    /**
     * @param ConsignorCommissionFeePanelLoader $consignorCommissionFeePanelLoader
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeePanelLoader(ConsignorCommissionFeePanelLoader $consignorCommissionFeePanelLoader): static
    {
        $this->consignorCommissionFeePanelLoader = $consignorCommissionFeePanelLoader;
        return $this;
    }
}
