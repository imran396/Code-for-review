<?php
/**
 * SAM-8005: Allow decimals in quantity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Scale;

/**
 * Trait QuantityScaleDetectorCreateTrait
 * @package Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Scale
 */
trait QuantityScaleDetectorCreateTrait
{
    /**
     * @var QuantityScaleDetector|null
     */
    protected ?QuantityScaleDetector $quantityScaleDetector = null;

    /**
     * @return QuantityScaleDetector
     */
    protected function createQuantityScaleDetector(): QuantityScaleDetector
    {
        return $this->quantityScaleDetector ?: QuantityScaleDetector::new();
    }

    /**
     * @param QuantityScaleDetector $quantityScaleDetector
     * @return static
     * @internal
     */
    public function setQuantityScaleDetector(QuantityScaleDetector $quantityScaleDetector): static
    {
        $this->quantityScaleDetector = $quantityScaleDetector;
        return $this;
    }
}
