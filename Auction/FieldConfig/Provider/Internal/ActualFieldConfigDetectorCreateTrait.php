<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\FieldConfig\Provider\Internal;

/**
 * Trait ActualFieldConfigDetectorCreateTrait
 * @package Sam\Auction\FieldConfig\Provider\Internal
 */
trait ActualFieldConfigDetectorCreateTrait
{
    protected ?ActualFieldConfigDetector $actualFieldConfigDetector = null;

    /**
     * @return ActualFieldConfigDetector
     */
    protected function createActualFieldConfigDetector(): ActualFieldConfigDetector
    {
        return $this->actualFieldConfigDetector ?: ActualFieldConfigDetector::new();
    }

    /**
     * @param ActualFieldConfigDetector $actualFieldConfigDetector
     * @return static
     * @internal
     */
    public function setActualFieldConfigDetector(ActualFieldConfigDetector $actualFieldConfigDetector): static
    {
        $this->actualFieldConfigDetector = $actualFieldConfigDetector;
        return $this;
    }
}
