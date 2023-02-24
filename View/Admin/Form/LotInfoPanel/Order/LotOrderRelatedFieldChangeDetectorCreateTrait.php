<?php
/**
 * SAM-8087: For manually added lots Staggered Closing Time Not Recalculated even after refreshing
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\Order;

/**
 * Trait LotOrderRelatedFieldChangeDetectorCreateTrait
 * @package Sam\View\Admin\Form\LotInfoPanel\Order
 */
trait LotOrderRelatedFieldChangeDetectorCreateTrait
{
    protected ?LotOrderRelatedFieldChangeDetector $lotOrderRelatedFieldChangeDetector = null;

    /**
     * @return LotOrderRelatedFieldChangeDetector
     */
    protected function createLotOrderRelatedFieldChangeDetector(): LotOrderRelatedFieldChangeDetector
    {
        return $this->lotOrderRelatedFieldChangeDetector ?: LotOrderRelatedFieldChangeDetector::new();
    }

    /**
     * @param LotOrderRelatedFieldChangeDetector $lotOrderRelatedFieldChangeDetector
     * @return static
     * @internal
     */
    public function setLotOrderRelatedFieldChangeDetector(LotOrderRelatedFieldChangeDetector $lotOrderRelatedFieldChangeDetector): static
    {
        $this->lotOrderRelatedFieldChangeDetector = $lotOrderRelatedFieldChangeDetector;
        return $this;
    }
}
