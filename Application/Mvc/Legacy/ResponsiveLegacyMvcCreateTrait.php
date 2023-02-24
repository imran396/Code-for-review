<?php
/**
 *
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Mvc\Legacy;

/**
 * Trait ResponsiveLegacyMvcCreateTrait
 * @package Sam\Application\Mvc\Legacy
 */
trait ResponsiveLegacyMvcCreateTrait
{
    protected ?ResponsiveLegacyMvc $responsiveLegacyMvc = null;

    /**
     * @return ResponsiveLegacyMvc
     */
    protected function createResponsiveLegacyMvc(): ResponsiveLegacyMvc
    {
        return $this->responsiveLegacyMvc ?: ResponsiveLegacyMvc::new();
    }

    /**
     * @param ResponsiveLegacyMvc $responsiveLegacyMvc
     * @return $this
     * @internal
     */
    public function setResponsiveLegacyMvc(ResponsiveLegacyMvc $responsiveLegacyMvc): static
    {
        $this->responsiveLegacyMvc = $responsiveLegacyMvc;
        return $this;
    }
}
