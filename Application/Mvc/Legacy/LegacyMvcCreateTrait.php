<?php
/**
 * SAM-10438: Wrap ZF MVC functions
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Mvc\Legacy;

/**
 * Trait LegacyMvcCreateTrait
 * @package Sam\Application\Mvc\Legacy
 */
trait LegacyMvcCreateTrait
{
    protected ?LegacyMvc $legacyMvc = null;

    /**
     * @return LegacyMvc
     */
    protected function createLegacyMvc(): LegacyMvc
    {
        return $this->legacyMvc ?: LegacyMvc::new();
    }

    /**
     * @param LegacyMvc $legacyMvc
     * @return $this
     * @internal
     */
    public function setLegacyMvc(LegacyMvc $legacyMvc): static
    {
        $this->legacyMvc = $legacyMvc;
        return $this;
    }
}
