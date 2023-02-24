<?php
/**
 * SAM-7758: Dynamic reconfiguration of rtbd state
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Repository\Invalidate;

/**
 * Trait ConfigStateInvalidatorCreateTrait
 * @package Sam\Installation\Config\Repository\Invalidate
 */
trait ConfigStateInvalidatorCreateTrait
{
    /**
     * @var ConfigStateInvalidator|null
     */
    protected ?ConfigStateInvalidator $configStateInvalidator = null;

    /**
     * @return ConfigStateInvalidator
     */
    protected function createConfigStateInvalidator(): ConfigStateInvalidator
    {
        return $this->configStateInvalidator ?: ConfigStateInvalidator::new();
    }

    /**
     * @param ConfigStateInvalidator $configStateInvalidator
     * @return static
     * @internal
     */
    public function setConfigStateInvalidator(ConfigStateInvalidator $configStateInvalidator): static
    {
        $this->configStateInvalidator = $configStateInvalidator;
        return $this;
    }
}
