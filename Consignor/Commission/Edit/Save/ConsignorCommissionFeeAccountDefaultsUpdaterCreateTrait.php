<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Save;

/**
 * Trait ConsignorCommissionFeeAccountDefaultsUpdaterCreateTrait
 * @package Sam\Consignor\Commission\Edit\Save
 */
trait ConsignorCommissionFeeAccountDefaultsUpdaterCreateTrait
{
    /**
     * @var ConsignorCommissionFeeAccountDefaultsUpdater|null
     */
    protected ?ConsignorCommissionFeeAccountDefaultsUpdater $consignorCommissionFeeAccountDefaultsUpdater = null;

    /**
     * @return ConsignorCommissionFeeAccountDefaultsUpdater
     */
    protected function createConsignorCommissionFeeAccountDefaultsUpdater(): ConsignorCommissionFeeAccountDefaultsUpdater
    {
        return $this->consignorCommissionFeeAccountDefaultsUpdater ?: ConsignorCommissionFeeAccountDefaultsUpdater::new();
    }

    /**
     * @param ConsignorCommissionFeeAccountDefaultsUpdater $consignorCommissionFeeAccountDefaultsUpdater
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeAccountDefaultsUpdater(ConsignorCommissionFeeAccountDefaultsUpdater $consignorCommissionFeeAccountDefaultsUpdater): static
    {
        $this->consignorCommissionFeeAccountDefaultsUpdater = $consignorCommissionFeeAccountDefaultsUpdater;
        return $this;
    }
}
