<?php
/**
 * This trait acts as marker for objects, that shouldn't be eliminated before form state serializing and persisting.
 *
 * SAM-4898: Form state consistency problem
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           4/2/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Longevity;

trait FormStateLongevityAwareTrait
{
    protected bool $isFormStateLongevity = true;

    /**
     * @return bool
     */
    public function isFormStateLongevity(): bool
    {
        return $this->isFormStateLongevity;
    }

    /**
     * @param bool $enabled
     * @return $this
     */
    public function enableFormStateLongevity(bool $enabled): static
    {
        $this->isFormStateLongevity = $enabled;
        return $this;
    }
}
