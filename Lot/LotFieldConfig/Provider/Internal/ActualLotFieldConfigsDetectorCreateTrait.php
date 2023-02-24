<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Provider\Internal;

/**
 * Trait ActualLotFieldConfigsDetectorCreateTrait
 * @package Sam\Lot\LotFieldConfig\Provider\Internal
 */
trait ActualLotFieldConfigsDetectorCreateTrait
{
    protected ?ActualLotFieldConfigsDetector $actualLotFieldConfigsDetector = null;

    /**
     * @return ActualLotFieldConfigsDetector
     */
    protected function createActualLotFieldConfigsDetector(): ActualLotFieldConfigsDetector
    {
        return $this->actualLotFieldConfigsDetector ?: ActualLotFieldConfigsDetector::new();
    }

    /**
     * @param ActualLotFieldConfigsDetector $actualLotFieldConfigsDetector
     * @return static
     * @internal
     */
    public function setActualLotFieldConfigsDetector(ActualLotFieldConfigsDetector $actualLotFieldConfigsDetector): static
    {
        $this->actualLotFieldConfigsDetector = $actualLotFieldConfigsDetector;
        return $this;
    }
}
